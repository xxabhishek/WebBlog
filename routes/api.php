<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/posts', [ApiController::class, 'index']);
//     Route::post('/posts', [ApiController::class, 'store']);
//     Route::get('/posts/{post}', [ApiController::class, 'show']);
//     Route::put('/posts/{post}', [ApiController::class, 'update']);
//     Route::delete('/posts/{post}', [ApiController::class, 'destroy']);
// });

Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

