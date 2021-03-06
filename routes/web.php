<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use App\Mail\CommentPostedMarkdown;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', [HomeController::class, 'home']);
//   ->name('home')
//   // ->middleware('auth')
//   ;
// Route::get('/contact', 'HomeController@contact')->name('contact');
// Route::get('/secret', 'HomeController@secret')
//   ->name('secret')
//   ->middleware('can:home.secret');
// Route::resource('posts', 'PostController');
// Route::get('/posts/tag/{tag}', 'PostTagController@index')->name('posts.tags.index');

// Route::resource('posts.comments', 'PostCommentController')->only(['store']);

// Auth::routes();






//------
// HOME
//------

// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index');

// Route::view('/', 'home.index')->name('home.index');
Route::get('/', [HomeController::class, 'home'])
  ->name('home.index');

// ----

// Route::get('/contact', function () {
//     return view('home.contact');
// })->name('home.contact');

// Route::view('/contact', 'home.contact')->name('home.contact');
Route::get('/contact', [HomeController::class, 'contact'])
  ->name('home.contact');

Route::get('/secret', [HomeController::class, 'secret'])
  ->name('home.secret')
  ->middleware('can:home.secret');
// ->middleware('can:home.secret,post');

// Route::get('/single', AboutController::class);

//------
// POSTS
// ----

// $posts = [
//   1 => [
//     'title' => 'Intro to Laravel',
//     'content' => 'This is a short intro to Laravel',
//     'is_new' => true,
//     'has_comments' => true,
//   ],
//   2 => [
//     'title' => 'Intro to PHP',
//     'content' => 'This is a short intro to PHP',
//     'is_new' => false,
//   ],
//   3 => [
//     'title' => 'Intro to Golang',
//     'content' => 'This is a short intro to Golang',
//     'is_new' => false,
//   ],
// ];

Route::resource('/posts', PostController::class);
// ->only(['index', 'show', 'create', 'store', 'edit', 'update']);

Route::get('/posts/tag/{tag}', [PostTagController::class, 'index'])->name('posts.tags.index');

Route::resource('/posts.comments', PostCommentController::class)->only(['index', 'store']);
Route::resource('users.comments', UserCommentController::class)->only(['store']);
Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);

Route::get('mailable', function () {
  $comment = Comment::find(1);
  return new CommentPostedMarkdown($comment);
});


// -----
// AUTH
//------

Auth::routes();


// Route::get('/posts', function () use ($posts) {
//   return view('posts.index', ['posts' => $posts]);
// })->name('posts.show');

// Route::get('/posts/{id}', function ($id) use ($posts) {
//   abort_if(!isset($posts[$id]), 404);

//   return view('posts.show', ['posts' => $posts[$id]]);
// });

//------
// OTHER
//------

// Route::get('/fun/responses', function () use ($posts) {
//   return response($posts, 201)->header('Content-Type', 'application/json')->cookie("CIASTO!!", "Igor Jot Jot", 3600);
// });

// Route::get('/fun/redirect', function () {
//   return redirect('/contact');
// });

// Route::get('/fun/back', function () {
//   return back();
// });

// Route::get('/fun/named-route', function () {
//   return redirect()->route('posts.show', ['id' => 1]);
// });

// Route::get('/fun/away', function () {
//   return redirect()->away('https://youtube.com');
// });

// Route::get('/fun/json', function () use ($posts) {
//   return response()->json($posts);
// });

// Route::get('/fun/download', function () use ($posts) {
//   return response()->download(public_path('backend.png'));
// });
