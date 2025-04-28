<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiPostController;


Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');


Route::get('/posts', [ApiPostController::class, 'index']);
Route::get('/posts/{id}', [ApiPostController::class, 'show']);
Route::post('/posts/store', [ApiPostController::class, 'store']);
Route::put('/posts/update/{id}', [ApiPostController::class, 'update']);
Route::delete('/posts/delete/{id}', [ApiPostController::class, 'destroy']);
