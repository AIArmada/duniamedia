<?php

declare(strict_types=1);

use App\Features\Catalog\EntryPoints\Http\MediaDirectoryController;
use App\Features\Catalog\EntryPoints\Http\SearchController;
use App\Features\Catalog\EntryPoints\Livewire\MediaExplorer;
use App\Features\Catalog\EntryPoints\Livewire\RateFinder;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/media', MediaExplorer::class)->name('media.index');
    Route::get('/rates', RateFinder::class)->name('rates.index');
    Route::get('/media/owners', [MediaDirectoryController::class, 'index'])->name('media.owners');
    Route::get('/media/owners/{owner}', [MediaDirectoryController::class, 'owner'])->name('media.owner');
    Route::get('/media/owners/{owner}/{channel}', [MediaDirectoryController::class, 'channel'])->name('media.channel');
    Route::get('/media/channels/{channel}', [MediaDirectoryController::class, 'showChannel'])->name('media.channel.show');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});
