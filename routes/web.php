<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookController;

Route::get('register', function () {
    return view('auth.register');
});

Route::post('register', [RegisterController::class, 'register']);

Route::get('login', function () {
    return view('auth.login');
});

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');



// Home route
Route::middleware(['auth'])->group(function () {
    Route::get('home', function () {
        return view('home'); // Ensure home.blade.php exists in resources/views
    })->name('home');
});

//books routes

Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::post('/books/{id}/comments', [BookController::class, 'storeComment'])->name('books.comments.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/searchbook', [BookController::class, 'search'])->name('searchbook');
Route::get('/showbook', [BookController::class, 'index'])->name('showbook');

Route::post('/comments', [BookController::class, 'storecomment'])->name('comments.store');


Route::post('/ratings', [BookController::class, 'storerating'])->name('comment.rating');


});
