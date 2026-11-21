<?php

declare(strict_types=1);

use App\Features\Clients\EntryPoints\Http\PublicController;
use App\Features\Clients\EntryPoints\Http\WorkMediaController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/offers/{slug}', [PublicController::class, 'offerShow'])->name('offers.show');
    Route::get('/services', [PublicController::class, 'services'])->name('services');
    Route::get('/solutions', [PublicController::class, 'solutions'])->name('solutions');
    Route::get('/portfolio', [PublicController::class, 'portfolio'])->name('portfolio');
    Route::get('/work', [PublicController::class, 'work'])->name('work.index');
    Route::get('/work/media/{key}', [WorkMediaController::class, 'show'])->name('work.media');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::view('/ecosystem', 'pages.ecosystem')->name('ecosystem');
    Route::view('/privacy', 'pages.privacy')->name('privacy');
    Route::view('/terms', 'pages.terms')->name('terms');
});
