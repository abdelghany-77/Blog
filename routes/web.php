<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\LoginController;

// home view
Route::get('/', [PostController::class, 'home'])->name('home');

// login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/show/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/category/{id}', [PostController::class, 'postsByCategory'])->name('posts.category');

//admin route auth
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::get('/posts', [PostController::class, 'index'])->name('admin.posts.index');
  Route::get('/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
  Route::post('/posts/store', [PostController::class, 'store'])->name('admin.posts.store');
  Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
  Route::put('/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
  Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
  //categories routes
  Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
  Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
  Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
  Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
  Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
  Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
});
