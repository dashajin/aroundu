<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ArticleController@index');

Route::get('/register', 'RegisterController@index');
Route::post('/register', 'RegisterController@register');
Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');

Route::get('/user/me/setting', 'UserController@setting');
Route::post('/user/me/uploadAvatar', 'UserController@uploadAvatar');
Route::post('/user/me/setting', 'UserController@settingStore');
Route::get('/user/{id}', 'UserController@show');
Route::get('/user/{id}/follow', 'UserController@follow');
Route::get('/user/{id}/unfollow', 'UserController@unfollow');

Route::post('/comment/store', 'ArticleController@comment');
Route::get('/comment/{id}/vote', 'CommentController@vote');
Route::get('/comment/{id}/unvote', 'CommentController@unvote');


Route::get('/home', 'HomeController@index')->name('home');
Route::post('/uploadImage', 'ImageController@imageUpload');

Route::resource('article', 'ArticleController');
Route::post('article/summerImageUpload', 'ArticleController@summerImageUpload');
Route::post('article/uploadImage', 'ArticleController@articleImageUpload');
Route::get('/article/{id}/vote', 'ArticleController@vote');
Route::get('/article/{id}/unvote', 'ArticleController@unvote');
Route::get('/article/{id}/edit', 'ArticleController@edit');

Route::Post('/topic', 'TopicController@store');
Route::get('/topic/{topic}', 'TopicController@show');
Route::get('/topic/{topic}/submit', 'TopicController@submit');

Route::get('/notice', 'NoticeController@index');
Route::get('/notice/follow', 'NoticeController@follow');
Route::get('/notice/like', 'NoticeController@like');
Route::get('/notice/comment', 'NoticeController@comment');



include_once('admin.php');
