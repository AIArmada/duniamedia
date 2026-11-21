<?php

declare(strict_types=1);

use App\Features\Content\EntryPoints\Http\AcademyController;
use App\Features\Content\EntryPoints\Http\ArticleController;
use App\Features\Content\EntryPoints\Http\InsightsController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/insights', InsightsController::class)->name('insights');
    Route::get('/insights/{slug}', ArticleController::class)->name('insights.show');
    Route::get('/academy', AcademyController::class)->name('academy');
});
