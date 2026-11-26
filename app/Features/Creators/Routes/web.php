<?php

declare(strict_types=1);

use App\Features\Creators\EntryPoints\Http\CreatorProfileController;
use App\Features\Creators\EntryPoints\Livewire\CreatorExplorer;
use App\Features\Creators\EntryPoints\Livewire\CreatorJoin;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/creators', CreatorExplorer::class)->name('creators.index');
    Route::get('/creators/join', CreatorJoin::class)->name('creators.join');
    Route::get('/creators/{slug}', CreatorProfileController::class)->where('slug', '[a-z0-9-]+')->name('creators.show');
});
