<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublicFormWizard;

Route::get('/', PublicFormWizard::class);



Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return back();
})->name('lang.switch');