<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/onion', [RecommendationController::class, 'index']);

Route::get('/', [RecommendationController::class, 'index']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('items', ItemController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class)->except(['show'])->middleware('auth');

Route::post('/save-outfit', function (Request $request) {
    return response()->json([
        'message' => 'Outfit-IDs empfangen!',
        'daten' => $request->all(),
    ]);
})->name('outfit.save');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth'])->name('settings');
