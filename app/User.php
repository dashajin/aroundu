<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic', 'founder_id', 'id');
    }

    public function notices()
    {
        return $this->belongsToMany('App\Notice', 'user_notice', 'user_id', 'notice_id')->withPivot(['user_id', 'notice_id']);
    }

    public function addNotice($notice)
    {
        return $this->notices()->save($notice);
    }

    public function apiArticles()
    {
        if (is_null(request('offset'))) {
            $offset = 0;
        } else {
            $offset = request('offset');
        }
        if (is_null(request('number'))) {
            $number = 10;
        } else {
            $number = request('number');
        }
        return $this->hasMany('App\Article')->orderBy('created_at', 'desc')->offset($offset)->limit($number);
    }

    public function badges()
    {
        return $this->belongsToMany('App\Badge');
    }

    /**
     * Get all of the user's score.
     */
    public function scores()
    {
        return $this->morphMany('App\Score', 'scorable');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    //用户关注
    public function following()
    {
        return $this->belongsToMany(self::class,'follows','follower_id','followed_id')->withTimestamps();
    }

    //用户的粉丝
    public function followers()
    {
        return $this->belongsToMany(self::class,'follows','followed_id','follower_id')->withTimestamps();
    }

    //关注用户
    public function followThisUser($user)
    {
        return $this->following()->attach($user);
    }

    //取消关注用户
    public function unfollowThisUser($user)
    {
        return $this->following()->detach($user);
    }

    //是否关注了这个用户
    public function hasFollowed($user)
    {
     //dd($this->following()->where('followed_id', $user)->get());
        if ($this->following()->where('followed_id', $user)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function apiGetArticles()
    {
        $articles = $this->apiArticles;
        foreach ($articles as $article) {
            $array[$article->id]['title'] =$article->title ;
            $array[$article->id]['created_at'] =$article->created_at->diffForHumans() ;
            $array[$article->id]['content'] = str_limit(preg_replace("/<[^>]+>/", '', $article->content), 150, '...');
            $array[$article->id]['user_name'] = $article->user->name;
            $array[$article->id]['user_id'] = $article->user->id;
            $array[$article->id]['votes_count'] = $article->votes->count();
            $array[$article->id]['comments_count'] = $article->comments->count();
        }
        return $array;
    }

    public static function getTopUserByArticles($num)
    {
        $users = self::all();
        $topUsers = $users->sortByDesc(function($user) {
            return $user->articles->count();
        })->whereNotIn('id', \Auth::id())->take($num);
        return $topUsers;
    }
}
