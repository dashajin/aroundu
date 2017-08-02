<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Notifications\GotVote;
use App\Notifications\ReceivedComment;
use App\Topic;
use App\Vote;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Image;

class ArticleController extends Controller
{
    use Notifiable;
    /**
     * show all articles
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(10);
        $articles->load('user');
//        $articles = Article::apiArticleList(request('offset'), request('number'));
//        dd($articles);
//        $articles = Article::getArticlesByComments();
        $articlesByVotes = Article::getArticlesByVotes(5);
        $articleimages = array();
        $count = 0;
        foreach ($articlesByVotes as $articlesByVote) {
            if (!is_null($articlesByVote->image)) {
                $articleimages[$count]['image'] = $articlesByVote->image;
                $articleimages[$count]['link'] = '/article/'.$articlesByVote->id;
                $count++;
            }
        }
        $topics = Topic::getTopicsByArticlesNum(5);
        return view('welcome', compact('articles', 'articleimages', 'topics'));
    }

    /**
     * show article
     */
    public function show($id)
    {
        $article = Article::find($id);
        //dd(Comment::find(1));
        return view('article.show')->withArticle($article);
    }

    /**
     * create article.
     */
    public function create()
    {
        return view('article.create');
    }
    /**
     * store article.
     */
    public function store(Request $request)
    {
        $article = new Article();
        $article->title = $request->get('title');
        $article->content = $request->get('content');
        $article->location = $request->get('location');
        $article->type = $request->get('type');
        $article->user_id = $request->get('user_id');
        $article->image = $request->get('articleimage');
        $article->save();
        return redirect('article/create')->withErrors('success!');
    }

    public function edit($id)
    {
        $article = Article::find($id);
        return view('article.edit')->withArticle($article);
    }

    public function update(ArticleRequest $request, $id)
    {
//        dd($request->all());
        $article = Article::find($id);
        $article->title = $request->get('title');
        $article->content = $request->get('content');
        $article->location = $request->get('location');
        $article->type = $request->get('type');
        $article->image = $request->get('articleimage');
        $article->save();
        return back()->withErrors('update success!');
    }

    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        return redirect('/');
    }

    public function comment(CommentRequest $request)
    {
        $article = Article::find($request['article_id']);
        $comment = new Comment();
        $comment->user_id = $request['user_id'];
        $comment->upper_id = $request['upper_id'];
        $comment->content = $request['content'];
        $article->comments()->save($comment);
        $article->user->notify(new ReceivedComment($comment));
        return back();
    }

    public function vote($id)
    {
        $article = Article::find($id);
        $vote = new Vote();
        $vote->user_id = \Auth::id();
        //dd($comment->vote(\Auth::id())->exists());
        if ($article->vote(\Auth::id())->exists()){
            $param = [
                'error' => 1,
                'msg' => 'already voted!',
            ];
        } else {
            $article->votes()->save($vote);
            $article->user->notify(new GotVote($vote));
            $param = [
                'error' => 0,
                'msg' => 'vote success!',
                'count' => $article->votes->count(),
            ];
        }
        return $param;
    }

    public function unvote($id)
    {
        $article = Article::find($id);
//        dd($id);
        if (!$article->vote(\Auth::id())->exists()){
            $param = [
                'error' => 1,
                'msg' => 'unvote failure!',
            ];
            return $param;
        }
        $article->vote(\Auth::id())->delete();
        $param = [
            'error' => 0,
            'msg' => 'unvote success!',
            'count' => $article->votes->count(),
        ];
        return $param;
    }

    public function summerImageUpload(Request $request)
    {
        $path = $request->file('file')->storePublicly(md5(time()));
        return '/storage/'.$path;
    }

    public function articleImageUpload(Request $request)
    {
        $file = $request->file('file');
        $dir = '/images/'.date('Y').'/'.date('m').'/'.date('d');
        if (!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
        }
        $path = md5(time()).'.'.$file->getClientOriginalExtension();
        Storage::putFileAs($dir, $file, $path);
        return '/storage'.$dir.'/'.$path;
    }
}
