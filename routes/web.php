<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/posts', 'PostController@index')->name('post.index');
	Route::get('/posts/create', 'PostController@create')->name('post.create');
	Route::post('/posts', 'PostController@store')->name('post.store');
	Route::get('/posts/{postId}/edit', 'PostController@edit');
	Route::patch('/posts/{postId}', 'PostController@update')->name('post.update');
	Route::post('/posts/delete', 'PostController@destroy')->name('post.delete');
	Route::post('/blog/like', 'HomeController@BlogLike')->name('blog.like');
	Route::post('/blog/comment', 'HomeController@BlogComment')->name('blog.comment');
});