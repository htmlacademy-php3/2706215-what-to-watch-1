<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::prefix('/user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('show');
    Route::patch('/', [UserController::class, 'update'])->name('update');
});

Route::get('/films', [FilmController::class, 'index'])->name('films.index');
Route::post('/films', [FilmController::class, 'store'])->name('films.store');
Route::get('/films/{id}', [FilmController::class, 'show'])->name('films.show');
Route::patch('/films/{id}', [FilmController::class, 'update'])->name('films.update');
Route::get('/films/{id}/similar', [FilmController::class, 'similar'])->name('films.similar');

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::patch('/genres/{genre}', [GenreController::class, 'update'])->name('genres.update');

Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/films/{id}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
Route::delete('/films/{id}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

Route::get('/comments/{id}', [CommentController::class, 'index'])->name('comments.index');
Route::post('/comments/{id}', [CommentController::class, 'store'])->name('comments.store');
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
Route::post('/promo/{id}', [PromoController::class, 'store'])->name('promo.store');
