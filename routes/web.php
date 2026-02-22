<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Formulairestatic\BusinessPlan;
use App\Livewire\Formulairestatic\EvaluationIdee;
use App\Livewire\Formulairestatic\BilanCompetences;
use App\Livewire\Formulairestatic\Bmc;
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
        Route::post('/address/create', [\App\Http\Controllers\Admin\DashboardController::class, 'createAddress'])->name('address.create');
        Route::post('/address/delete/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'DeleteAddess'])->name('address.delete');
        Route::get('/projects', \App\Livewire\Admin\Project\ProjectList::class)->name('projects');
        Route::get('/projects/{id}', \App\Livewire\Admin\Project\ProjectDetail::class)->name('projects.show');
        Route::get('/projects/{id}/export-pdf', [\App\Http\Controllers\Admin\ProjectExportController::class, 'exportPdf'])->name('projects.export.pdf');
        Route::get('/projects/{id}/preview-pdf', [\App\Http\Controllers\Admin\ProjectExportController::class, 'previewPdf'])->name('projects.preview.pdf');
        
        // Form Detail Pages
        Route::get('/etude-marche/{id}', \App\Livewire\Admin\Formold\EtudeMarcheDetail::class)->name('etude-marche.show');
        Route::get('/evaluation-idee/{id}', \App\Livewire\Admin\Formold\EvaluationIdeeDetail::class)->name('evaluation-idee.show');
        Route::get('/bmc/{id}', \App\Livewire\Admin\Formold\BmcDetail::class)->name('bmc.show');
        Route::get('/bilan-competence/{id}', \App\Livewire\Admin\Formold\BilanCompetenceDetail::class)->name('bilan-competence.show');
        
        // Form PDF Exports
        Route::get('/etude-marche/{id}/export-pdf', [\App\Http\Controllers\FormExportController::class, 'exportEtudeMarche'])->name('etude-marche.export-pdf');
        Route::get('/evaluation-idee/{id}/export-pdf', [\App\Http\Controllers\FormExportController::class, 'exportEvaluationIdee'])->name('evaluation-idee.export-pdf');
        Route::get('/bmc/{id}/export-pdf', [\App\Http\Controllers\FormExportController::class, 'exportBmc'])->name('bmc.export-pdf');
        Route::get('/bilan-competence/{id}/export-pdf', [\App\Http\Controllers\FormExportController::class, 'exportBilanCompetence'])->name('bilan-competence.export-pdf');

        Route::get('/users', \App\Livewire\Admin\User\UserManagement::class)->name('users.index');
        Route::get('/candidats', \App\Livewire\Admin\Candidat\CandidatManagement::class)->name('candidats.index');
        Route::get('/candidats/{id}', \App\Livewire\Admin\Candidat\ShowCandidat::class)->name('candidats.show');
        Route::get('/candidat/{id}/submissions', \App\Livewire\Admin\Candidat\CandidatSubmissions::class)->name('candidat.submissions');
        Route::get('/users/create', \App\Livewire\Admin\User\CreateUser::class)->name('users.create');
        Route::get('/users/{id}', \App\Livewire\Admin\User\ShowUser::class)->name('users.show');
        Route::get('/users/{id}/edit', \App\Livewire\Admin\User\EditUser::class)->name('users.edit');
        Route::get('/addresses', \App\Livewire\Admin\Address\AddressManager::class)->name('addresses.index');
        Route::get('/projects/{id}/add-registration', \App\Livewire\Admin\RegistrationId::class)->name('add.registration');
        Route::get('/activity-logs', \App\Livewire\Admin\Logs\ActivityLogs::class)->name('activity.logs');
        Route::get('/support-tickets', \App\Livewire\Admin\Support\SupportTickets::class)->name('support.tickets');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        
        Route::get  ('/programe_zettat', App\Livewire\Admin\Programe\ProgrameList::class)->name('programe_zettat');
        Route::get('/programe_zettat/create', App\Livewire\Admin\Programe\ProgrameCreate::class)->name('programe_zettat.create');
        Route::get('/programe_zettat/{id}/edit', App\Livewire\Admin\Programe\ProgrameEdit::class)->name('programe_zettat.edit');
        Route::get('/programe_zettat/{id}/submissions', App\Livewire\Admin\Programe\ProjectSubmissions::class)->name('project.submissions');
        
        Route::get('/projects_view', App\Livewire\Admin\Project\ProjectView::class)->name('projects_view');
        Route::get('/form-submissions/{type}/{id}', \App\Livewire\Admin\Project\FormSubmissionView::class)->name('form-submissions.view');
        Route::get('/form-submissions', \App\Livewire\Admin\Project\FormSubmissions::class)->name('form-submissions');

        // Dynamic Form Builder (Referential)
        Route::get('/formulaires', \App\Livewire\Admin\Formulaire\FormulaireList::class)->name('formulaires.index');
        Route::get('/formulaires/create', \App\Livewire\Admin\Formulaire\FormulaireBuilder::class)->name('formulaires.create');
        Route::get('/formulaires/{id}/edit', \App\Livewire\Admin\Formulaire\FormulaireBuilder::class)->name('formulaires.edit');
        Route::get('/formulaires/{formId}/submissions', \App\Livewire\Admin\Formulaire\FormulaireSubmissions::class)->name('formulaires.submissions');
        Route::get('/formulaires/submissions/{id}', \App\Livewire\Admin\Formulaire\FormulaireSubmissionDetail::class)->name('formulaires.submission.detail');

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
Route::prefix('form')->name('form.')->middleware('candidat')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/business-plan', BusinessPlan::class)->name('business_plan');
    Route::get('/bilan-competences', BilanCompetences::class)->name('bilan_competences');
    Route::get('/bmc', Bmc::class)->name('bmc');
    Route::get('/etude-marche', \App\Livewire\Formulairestatic\EtudeMarche::class)->name('etude_marche');
    Route::get('/evaluation-idee', EvaluationIdee::class)->name('evaluation_idee');
    Route::get('/settings', \App\Livewire\Front\Dashboard\Settings::class)->name('settings');
    Route::get('/support', \App\Livewire\Front\Dashboard\Support::class)->name('support');
    Route::get('/f/{slug}', \App\Livewire\Front\DynamicFormWizard::class)->name('dynamic_form');
    // Project Routes
    Route::get('/projects', \App\Livewire\Front\Programe\ProjectList::class)->name('projects.list');
    Route::get('/projects/{id}', \App\Livewire\Front\Programe\ProjectDetail::class)->name('project.detail');
    Route::get('/projects/{projectId}/formulaire/{formulaireSlug}/{order}', \App\Livewire\Front\Programe\ProjectFormulaireView::class)->name('project.formulaire');
    
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