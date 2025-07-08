<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {

    Route::get('posts/data', [PostController::class, 'getPosts'])->name('posts.data');
    Route::resource('posts', PostController::class);

    Route::resource('comments', CommentController::class)->except([
        'show', 'edit'
    ]);

    Route::get('tags/data', [TagController::class, 'getTags'])->name('tags.data');

     Route::get('/dashboard', function () {
        return redirect()->route('posts.index');
    })->name('dashboard');
});
