<?php

declare(strict_types=1);

use App\Features\Advisor\EntryPoints\Livewire\MediaAdvisor;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/media-advisor', MediaAdvisor::class)->name('advisor');
    Route::get('/media-advisor/{token}', MediaAdvisor::class)->name('advisor.show');
});
