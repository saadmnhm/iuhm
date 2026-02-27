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

        Route::get('/users', \App\Livewire\Admin\User\UserManagement::class)->name('users.index')->middleware('module:users');
        Route::get('/candidats', \App\Livewire\Admin\Candidat\CandidatManagement::class)->name('candidats.index')->middleware('module:candidats');
        Route::get('/candidats/{id}', \App\Livewire\Admin\Candidat\ShowCandidat::class)->name('candidats.show')->middleware('module:candidats');
        Route::get('/users/create', \App\Livewire\Admin\User\CreateUser::class)->name('users.create')->middleware('module:users');
        Route::get('/users/{id}', \App\Livewire\Admin\User\ShowUser::class)->name('users.show')->middleware('module:users');
        Route::get('/users/{id}/edit', \App\Livewire\Admin\User\EditUser::class)->name('users.edit')->middleware('module:users');
        Route::get('/addresses', \App\Livewire\Admin\Address\AddressManager::class)->name('addresses.index')->middleware('module:addresses');
        Route::get('/projects/{id}/add-registration', \App\Livewire\Admin\RegistrationId::class)->name('add.registration');
        Route::get('/activity-logs', \App\Livewire\Admin\Logs\ActivityLogs::class)->name('activity.logs')->middleware('module:activity_logs');
        Route::get('/dev-tools', \App\Livewire\Admin\Tools\DevTools::class)->name('dev.tools')->middleware('module:dev_tools');
        Route::get('/rh', \App\Livewire\Admin\Rh\RhManagement::class)->name('rh.index')->middleware('module:rh');
        Route::get('/roles', \App\Livewire\Admin\Roles\RoleManagement::class)->name('roles.index')->middleware('super_admin');
        Route::get('/my-submissions', \App\Livewire\Admin\Submissions\MyAssignedSubmissions::class)->name('my.submissions')->middleware('module:my_submissions');
        Route::get('/all-submissions', \App\Livewire\Admin\Submissions\AllSubmissions::class)->name('all.submissions')->middleware('module:all_submissions');
        Route::get('/association-parameters', \App\Livewire\Admin\Settings\AssociationParameters::class)->name('association.parameters')->middleware('module:association_parameters');
        Route::get('/blog', \App\Livewire\Admin\Blog\BlogManagement::class)->name('blog.index')->middleware('module:blog');
        Route::get('/history-audit', \App\Livewire\Admin\Submissions\HistoryAudit::class)->name('history.audit')->middleware('module:history_audit');
        Route::get('/support-tickets', \App\Livewire\Admin\Support\SupportTickets::class)->name('support.tickets')->middleware('module:support');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        
        Route::get('/programe', App\Livewire\Admin\Programe\ProgrameList::class)->name('programe')->middleware('module:programe');
        Route::get('/programe/create', App\Livewire\Admin\Programe\ProgrameCreate::class)->name('programe.create')->middleware('module:programe');
        Route::get('/programe/edit/{id}', App\Livewire\Admin\Programe\ProgrameEdit::class)->name('programe.edit')->middleware('module:programe');
        Route::get('/programe/submissions/{id}', App\Livewire\Admin\Programe\ProjectSubmissions::class)->name('project.submissions')->middleware('module:programmes');
        Route::get('/programe/candidat/{id}/submissions', \App\Livewire\Admin\Candidat\CandidatSubmissions::class)->name('candidat.submissions')->middleware('module:programmes');
        Route::get('/programe/candidat/{candidatId}/submission/{id}/export-pdf', [\App\Http\Controllers\Admin\CandidatExportController::class, 'exportSingle'])->name('candidat.submission.export')->middleware('module:candidats');
        Route::get('/programe/candidat/{id}/export-all-pdf', [\App\Http\Controllers\Admin\CandidatExportController::class, 'exportAll'])->name('candidat.export-all')->middleware('module:candidats');

        
        Route::get('/projects_view', App\Livewire\Admin\Project\ProjectView::class)->name('projects_view');
        Route::get('/form-submissions/{type}/{id}', \App\Livewire\Admin\Project\FormSubmissionView::class)->name('form-submissions.view');

        // Dynamic Form Builder (Referential)
        Route::get('/formulaires', \App\Livewire\Admin\Formulaire\FormulaireList::class)->name('formulaires.index')->middleware('module:formulaires');
        Route::get('/formulaires/create', \App\Livewire\Admin\Formulaire\FormulaireBuilder::class)->name('formulaires.create')->middleware('module:formulaires');
        Route::get('/formulaires/{id}/edit', \App\Livewire\Admin\Formulaire\FormulaireBuilder::class)->name('formulaires.edit')->middleware('module:formulaires');
        Route::get('/formulaires/submissions/{id}', \App\Livewire\Admin\Formulaire\FormulaireSubmissionDetail::class)->name('formulaires.submission.detail')->middleware('module:formulaires');

    });
});

// User Authentication Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [FrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [FrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontAuthController::class, 'register'])->name('register.post');

// Protected User Dashboard Routes
Route::middleware('candidat')->group(function () {
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
    Route::get('/blog', \App\Livewire\Front\Blog\BlogList::class)->name('blog');
    Route::get('/blog/{slug}', \App\Livewire\Front\Blog\BlogShow::class)->name('blog.show');
    
    Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');
});

});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return back();
})->name('lang.switch');

Route::middleware(['admin'])->get('/uploads/{path}', function ($path) {
    $filePath = base_path('uploads/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    return response()->file($filePath);
})->where('path', '.*')->name('uploads.show');