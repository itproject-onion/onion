<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\onion;

Route::get('/onion', [RecommendationController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

use Illuminate\Http\Request;

Route::post('/save-outfit', function (Request $request) {
    return response()->json([
        'message' => 'Outfit-IDs empfangen!',
        'daten' => $request->all()
    ]);
})->name('outfit.save');