<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublicFormWizard;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\FrontAuthController ;
use App\Livewire\Front\Dashboard\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/projects', \App\Livewire\Admin\ProjectList::class)->name('projects');
        Route::get('/projects/{id}', \App\Livewire\Admin\ProjectDetail::class)->name('projects.show');
        Route::get('/projects/{id}/export-pdf', [\App\Http\Controllers\Admin\ProjectExportController::class, 'exportPdf'])->name('projects.export.pdf');
        Route::get('/projects/{id}/preview-pdf', [\App\Http\Controllers\Admin\ProjectExportController::class, 'previewPdf'])->name('projects.preview.pdf');

        Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users.index');
        Route::get('/users/create', \App\Livewire\Admin\CreateUser::class)->name('users.create');
        Route::get('/users/{id}', \App\Livewire\Admin\ShowUser::class)->name('users.show');
        Route::get('/users/{id}/edit', \App\Livewire\Admin\EditUser::class)->name('users.edit');
        Route::get('/projects/{id}/add-registration', \App\Livewire\Admin\RegistrationId::class)->name('add.registration');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// User Authentication Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [FrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [FrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontAuthController::class, 'register'])->name('register.post');
});

// Protected User Dashboard Routes
Route::prefix('form')->name('form.')->middleware('user')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');
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