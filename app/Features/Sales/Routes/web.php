<?php

declare(strict_types=1);

use App\Features\Sales\EntryPoints\Http\ProposalController;
use App\Features\Sales\EntryPoints\Livewire\AssetOwnerForm;
use App\Features\Sales\EntryPoints\Livewire\CampaignPlanBuilder;
use App\Features\Sales\EntryPoints\Livewire\CampaignPlans;
use App\Features\Sales\EntryPoints\Livewire\ContactForm;
use App\Features\Sales\EntryPoints\Livewire\QuoteBuilder;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::get('/quote-builder', QuoteBuilder::class)->name('quote.builder');
    Route::get('/campaign-plans', CampaignPlans::class)->name('plans.index');
    Route::get('/campaign-plans/{instance}', CampaignPlanBuilder::class)->name('plans.show');
    Route::get('/contact', ContactForm::class)->name('contact');
    Route::get('/list-your-space', AssetOwnerForm::class)->name('assets.join');
    Route::get('/proposal/{reference}', [ProposalController::class, 'show'])->middleware('signed')->name('quote.view');
});
