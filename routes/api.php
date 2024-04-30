<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SkripsiController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('me', [AuthenticationController::class, 'me']);
  Route::post('/logout', [AuthenticationController::class, 'logout']);

  Route::get('/skripsi', [SkripsiController::class, 'index']);
  Route::get('/skripsi/{id}', [SkripsiController::class, 'show']);
  Route::post('/skripsi', [SkripsiController::class, 'store'])->middleware('admin.middleware');
  Route::patch('/skripsi/{id}', [SkripsiController::class, 'update'])->middleware('admin.middleware');
  Route::delete('/skripsi/{id}', [SkripsiController::class, 'destroy'])->middleware('admin.middleware');

  Route::get('/bookmark', [BookmarkController::class, 'index']);
  Route::post('/bookmark', [BookmarkController::class, 'store']);
  Route::delete('/bookmark/{id}', [BookmarkController::class, 'destroy']);

  Route::get('/search', [SkripsiController::class, 'search']);
});

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
