<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// auth routes
Route::group(['middleware' => 'auth'], function () {

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::post('/vote/feedback', [FeedbackController::class, 'voteFeedback'])->name('voteFeedback');
    Route::get('/my-feedbacks', [FeedbackController::class, 'myFeedbacks'])->name('myFeedbacks');

    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/my-comments', [CommentController::class, 'myComments'])->name('myComments');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // admin routes
    Route::group(['middleware' => 'admin'], function () {
        Route::put('/feedbacks/{feedback}', [FeedbackController::class, 'update'])->name('feedbacks.update');
        Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.delete');

        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.delete');
    });
});
