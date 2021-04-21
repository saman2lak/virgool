<?php

use App\Http\Controllers\AllUserDraftController;
use App\Http\Controllers\AllUserPostController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadPostImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum')->name("profile.update");

Route::post('/uplaod-post-image', UploadPostImageController::class)->middleware('auth:sanctum')->name('upload-post-image');

Route::post('/posts/create', [DraftController::class, "store"])->middleware('auth:sanctum')->name('draft.store');

Route::get('/drafts/{draft}', [DraftController::class, "show"])->middleware('auth:sanctum')->name('draft.show');
Route::patch('/drafts/{draft}', [DraftController::class, "update"])->middleware('auth:sanctum')->name('draft.update');
Route::delete('/drafts/{draft}', [DraftController::class, "destroy"])->middleware('auth:sanctum')->name('draft.destroy');

Route::post('/posts', [PostController::class, "store"])->middleware('auth:sanctum')->name('post.store');
Route::get('/posts/all-posts', AllUserPostController::class)->middleware('auth:sanctum')->name('all.post');
Route::get('/posts/all-drafts', AllUserDraftController::class)->middleware('auth:sanctum')->name('all.draft');
Route::get('/posts/{post:slug}/edit', [PostController::class, "show"])->middleware('auth:sanctum')->name('post.show');
Route::patch('/posts/{post:slug}/edit', [PostController::class, "edit"])->middleware('auth:sanctum')->name('post.edit');
Route::patch('/posts/{post:slug}', [PostController::class, "update"])->middleware('auth:sanctum')->name('post.update');
Route::delete('/posts/{post:slug}', [PostController::class, "destroy"])->middleware('auth:sanctum')->name('post.destroy');
Route::get('/posts/{post:slug}', [PostController::class, "showPost"])->name('show.post');

Route::post('/comments/{post:slug}', [CommentController::class, "store"])->middleware('auth:sanctum')->name('comment.store');
Route::post('/replies/{post:slug}', [CommentController::class, "replay"])->middleware('auth:sanctum')->name('comment.replay');
Route::delete('/comments/{comment}', [CommentController::class, "destroy"])->middleware('auth:sanctum')->name('comment.destroy');
Route::patch('/comments/{comment}', [CommentController::class, "update"])->middleware('auth:sanctum')->name('comment.update');

Route::post('/bookmarks/{post:slug}', [BookmarkController::class, "store"])->middleware('auth:sanctum')->name('bookmarks.store');
Route::delete('/bookmarks/{post:slug}', [BookmarkController::class, "destroy"])->middleware('auth:sanctum')->name('unbookmarks.destroy');

Route::post('/likes/{post:slug}', [LikeController::class, "store"])->middleware('auth:sanctum')->name('likes.store');
Route::delete('/likes/{post:slug}', [LikeController::class, "destroy"])->middleware('auth:sanctum')->name('likes.destroy');

Route::post('/follows/{user:username}', FollowController::class)->middleware('auth:sanctum')->name('follows.store');

Route::get('/notification', [NotificationController::class, "index"])->name('notification.index');
Route::patch('/notification/{notification}', [NotificationController::class, "update"])->name('notification.read');

Route::get('/posts/category/{category:slug}', [CategoryController::class, "index"])->name('category.index');
Route::get('/navbar-category', [CategoryController::class, "navbar"])->name('category.navbar');

Route::get('/user-post/{user:username}', [PostController::class, "userpost"])->name('user.post');

Route::get('/liked-post', [PostController::class, "likedPost"])->name('liked.post');
Route::get('/home-post', [PostController::class, "homePost"])->name('home.post');


//