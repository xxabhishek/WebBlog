<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
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
    return view('layouts.app');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//all posts
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

Route::group(['middleware' => ['role:admin']], function() {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

Route::resource('posts', PostController::class)->only(['index', 'show']);

Route::post('/posts/{id}/comments', [PostController::class, 'storeComment'])->name('comments.store');
// Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('comments.destroy');
Route::delete('/comments/{id}', [PostController::class, 'destroyComment'])->name('comments.destroy');

//store comment
Route::post('/comments/{id}', [PostController::class, 'storeComment'])->name('comments.store');

Route::post('/comments/{id}/like', [PostController::class, 'likeComment'])->name('comments.like');
Route::post('/comments/{id}/share', [PostController::class, 'shareComment'])->name('comments.share');

//search blog
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');




//New Routes

// Public access routes for viewing posts

Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Protected routes for authenticated users with the 'admin' role
Route::middleware('role:admin')->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// Comments routes
Route::post('/posts/{id}/comments', [PostController::class, 'storeComment'])->name('comments.store');
Route::delete('/comments/{id}', [PostController::class, 'destroyComment'])->name('comments.destroy');

// Like and share comments routes
Route::post('/comments/{id}/like', [PostController::class, 'likeComment'])->name('comments.like');
Route::post('/comments/{id}/share', [PostController::class, 'shareComment'])->name('comments.share');

// Search posts route
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

