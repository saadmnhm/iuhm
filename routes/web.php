<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublicFormWizard;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', PublicFormWizard::class);

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/projects', \App\Livewire\Admin\ProjectList::class)->name('projects');
        Route::get('/projects/{id}', \App\Livewire\Admin\ProjectDetail::class)->name('projects.show');
        Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users.index');
        Route::get('/users/create', \App\Livewire\Admin\CreateUser::class)->name('users.create');
        Route::get('/users/{id}', \App\Livewire\Admin\ShowUser::class)->name('users.show');
        Route::get('/users/{id}/edit', \App\Livewire\Admin\EditUser::class)->name('users.edit');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return back();
})->name('lang.switch');

Route::get('/uploads/{path}', function ($path) {
    $filePath = base_path('uploads/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    return response()->file($filePath);
})->where('path', '.*')->name('uploads.show');