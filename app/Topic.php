<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function articles()
    {
        return $this->belongsToMany('App\Article');
    }

    public function founder()
    {
        return $this->belongsTo('App\User', 'founder_id', 'id');
    }

    public function getTopicArticlesByVote($id, $pageNum)
    {
//        return self::find($id)->articles->sortByDesc(function ($articles) {
//            return $articles->votes->count();
//        })->take($pageNum);
        $articles = Topic::find($id)->articles;
        $sorted = $articles->sortByDesc(function ($article) {
            return $article->votes->count();
        });
//        dd($sorted);
        return $sorted;
    }

    public static function getTopicsByArticlesNum($num)
    {
        $topics = Topic::all();
        return $topics->sortByDesc(function ($topic) {
            return $topic->articles->count();
        })->take($num);
    }
}
