<?php

namespace App\Providers;

use App\Article;
use App\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('welcome', function ($view) {
            $topUsers = User::getTopUserByArticles(5);
            $topArticles = Article::getArticlesByComments(5);
            $view->with('top_users', $topUsers);
            $view->with('top_articles', $topArticles);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
