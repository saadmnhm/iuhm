<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PrintController;
use App\Http\Controllers\FrontAuthController ;
use App\Livewire\Front\Dashboard\Dashboard;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::middleware('admin')->group(function () {
        Route::get('/keep-alive', function () {
            return response()->json(['ok' => true, 'time' => now()->toDateTimeString()]);
        })->name('keep-alive');

        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::post('/address/create', [\App\Http\Controllers\Admin\DashboardController::class, 'createAddress'])->name('address.create');
        Route::post('/address/delete/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'DeleteAddess'])->name('address.delete');
        Route::get('/projects/{id}', \App\Livewire\Admin\Project\ProjectDetail::class)->name('projects.show');
        


        Route::get('/users', \App\Livewire\Admin\User\UserManagement::class)->name('users.index')->middleware('module:users');
        Route::get('/candidats', \App\Livewire\Admin\Candidat\CandidatManagement::class)->name('candidats.index')->middleware('module:candidats');
        Route::get('/candidats/{id}', \App\Livewire\Admin\Candidat\ShowCandidat::class)->name('candidats.show')->middleware('module:candidats');
        Route::get('/candidats/{id}/edit', \App\Livewire\Admin\Candidat\EditCandidat::class)->name('candidats.edit')->middleware('module:candidats');
        Route::get('/candidats/{id}/print/fiche_inscription', [PrintController::class, 'fiche_inscription'])->name('candidats.print.fiche_inscription')->middleware('module:candidats');
        Route::get('/users/create', \App\Livewire\Admin\User\CreateUser::class)->name('users.create')->middleware('module:users');
        Route::get('/users/{id}', \App\Livewire\Admin\User\ShowUser::class)->name('users.show')->middleware('module:users');
        Route::get('/users/{id}/edit', \App\Livewire\Admin\User\EditUser::class)->name('users.edit')->middleware('module:users');
        Route::get('/addresses', \App\Livewire\Admin\Address\AddressManager::class)->name('addresses.index')->middleware('module:addresses');
        Route::get('/activity-logs', \App\Livewire\Admin\Logs\ActivityLogs::class)->name('activity.logs')->middleware('module:activity_logs');
        Route::get('/dev-tools', \App\Livewire\Admin\Tools\DevTools::class)->name('dev.tools')->middleware('module:dev_tools');
        Route::get('/rh', \App\Livewire\Admin\Rh\RhManagement::class)->name('rh.index')->middleware('module:rh');
        Route::get('/rh/create', \App\Livewire\Admin\Rh\RhCreate::class)->name('rh.create')->middleware('module:rh');
        Route::get('/rh/{id}/edit', \App\Livewire\Admin\Rh\RhEdit::class)->name('rh.edit')->middleware('module:rh');
        Route::get('/rh/{id}', \App\Livewire\Admin\Rh\RhShow::class)->name('rh.show')->middleware('module:rh');
        Route::get('/rh/{id}/print/attestation', [PrintController::class, 'rhAttestation'])->name('rh.print.attestation')->middleware('module:rh');
        Route::get('/rh/{id}/print/fiche', [PrintController::class, 'rhFiche'])->name('rh.print.fiche')->middleware('module:rh');
        Route::get('/rh/print/list', [PrintController::class, 'rhList'])->name('rh.print.list')->middleware('module:rh');

        // Finance Module
        Route::get('/finance', \App\Livewire\Admin\Finance\FinanceDashboard::class)->name('finance.index')->middleware('module:finance');
        Route::get('/finance/transaction/create', \App\Livewire\Admin\Finance\TransactionCreate::class)->name('finance.create')->middleware('module:finance');
        Route::get('/finance/transaction/{id}/edit', \App\Livewire\Admin\Finance\TransactionEdit::class)->name('finance.edit')->middleware('module:finance');
        Route::get('/finance/transaction/{id}', \App\Livewire\Admin\Finance\TransactionShow::class)->name('finance.show')->middleware('module:finance');
        Route::get('/finance/charge/create', \App\Livewire\Admin\Finance\ChargeCreate::class)->name('finance.charges.create')->middleware('module:finance');
        Route::get('/finance/transaction/{id}/print', [PrintController::class, 'financeTransaction'])->name('finance.print')->middleware('module:finance');
        Route::get('/finance/report', [PrintController::class, 'financeReport'])->name('finance.report')->middleware('module:finance');

        // Material Module
        Route::get('/material', \App\Livewire\Admin\Material\MaterialDashboard::class)->name('material.index')->middleware('module:material');
        Route::get('/material/create', \App\Livewire\Admin\Material\MaterialCreate::class)->name('material.create')->middleware('module:material');
        Route::get('/material/{id}/edit', \App\Livewire\Admin\Material\MaterialEdit::class)->name('material.edit')->middleware('module:material');
        Route::get('/material/{id}', \App\Livewire\Admin\Material\MaterialShow::class)->name('material.show')->middleware('module:material');
        Route::get('/material/{id}/print/fiche', [PrintController::class, 'materialFiche'])->name('material.print.fiche')->middleware('module:material');
        Route::get('/material/print/inventory', [PrintController::class, 'materialInventory'])->name('material.print.inventory')->middleware('module:material');
        Route::get('/roles', \App\Livewire\Admin\Roles\RoleManagement::class)->name('roles.index')->middleware('module:gestion_roles');
        Route::get('/my-submissions', \App\Livewire\Admin\Submissions\MyAssignedSubmissions::class)->name('my.submissions')->middleware('module:my_submissions');
        Route::get('/all-submissions', \App\Livewire\Admin\Submissions\AllSubmissions::class)->name('all.submissions')->middleware('module:all_submissions');
        Route::get('/association-parameters', \App\Livewire\Admin\Settings\AssociationParameters::class)->name('association.parameters')->middleware('module:association_parameters');
        Route::get('/blog', \App\Livewire\Admin\Blog\BlogManagement::class)->name('blog.index')->middleware('module:blog');
        Route::get('/history-audit', \App\Livewire\Admin\Submissions\HistoryAudit::class)->name('history.audit')->middleware('module:history_audit');
        Route::get('/support-tickets', \App\Livewire\Admin\Support\SupportTickets::class)->name('support.tickets')->middleware('module:support');

        // Chat
        Route::get('/chat', \App\Livewire\Admin\Chat\ChatList::class)->name('chat.list')->middleware('module:chat');
        Route::get('/chat/{candidatId}', \App\Livewire\Admin\Chat\ChatConversation::class)->name('chat.conversation')->middleware('module:chat');
        Route::get('/broadcast', \App\Livewire\Admin\Chat\BroadcastMessage::class)->name('chat.broadcast')->middleware('module:chat');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        
        Route::get('/programe', App\Livewire\Admin\Programe\ProgrameList::class)->name('programe')->middleware('module:programe');
        Route::get('/programe/create', App\Livewire\Admin\Programe\ProgrameCreate::class)->name('programe.create')->middleware('module:programe');
        Route::get('/programe/edit/{id}', App\Livewire\Admin\Programe\ProgrameEdit::class)->name('programe.edit')->middleware('module:programe');
        Route::get('/programe/submissions/{id}', App\Livewire\Admin\Programe\ProjectSubmissions::class)->name('project.submissions')->middleware('module:programmes');
        Route::get('/programe/candidat/{id}/submissions/{projectId?}', \App\Livewire\Admin\Candidat\CandidatSubmissions::class)->name('candidat.submissions')->middleware('module:programmes');
        Route::get('/programe/candidat/{candidatId}/submission/{id}/export-pdf', [\App\Http\Controllers\Admin\CandidatExportController::class, 'exportSingle'])->name('candidat.submission.export')->middleware('module:candidats');
        Route::get('/programe/candidat/{id}/export-all-pdf', [\App\Http\Controllers\Admin\CandidatExportController::class, 'exportAll'])->name('candidat.export-all')->middleware('module:candidats');

        
        Route::get('/projects_view', App\Livewire\Admin\Project\ProjectView::class)->name('projects_view');

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

    // Password Reset
    Route::get('/password/forgot',          [FrontAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/password/forgot',         [FrontAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}',   [FrontAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/password/reset',          [FrontAuthController::class, 'resetPassword'])->name('password.update');

    Route::post('/logout', [FrontAuthController::class, 'logout'])->name('logout');

    // Protected User Dashboard Routes
    Route::middleware('candidat')->group(function () {
        Route::get('/keep-alive', function () {
            return response()->json(['ok' => true, 'time' => now()->toDateTimeString()]);
        })->name('keep-alive');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/settings', \App\Livewire\Front\Dashboard\Settings::class)->name('settings');
        Route::get('/support', \App\Livewire\Front\Dashboard\Support::class)->name('support');
        Route::get('/chat', \App\Livewire\Front\Dashboard\Chat::class)->name('chat');
        Route::get('/f/{slug}', \App\Livewire\Front\DynamicFormWizard::class)->name('dynamic_form');
        // Project Routes
        Route::get('/projects', \App\Livewire\Front\Programe\ProjectList::class)->name('projects.list');
        Route::get('/projects/{id}/conditions', \App\Livewire\Front\Programe\ProjectConditionAgreement::class)->name('project.conditions');
        Route::get('/projects/{id}', \App\Livewire\Front\Programe\ProjectDetail::class)->name('project.detail');
        Route::get('/projects/{projectId}/formulaire/{formulaireSlug}/{order}', \App\Livewire\Front\Programe\ProjectFormulaireView::class)->name('project.formulaire');
        Route::get('/blog', \App\Livewire\Front\Blog\BlogList::class)->name('blog');
        Route::get('/blog/{slug}', \App\Livewire\Front\Blog\BlogShow::class)->name('blog.show');
            Route::get('/broadcasts', \App\Livewire\Front\Dashboard\BroadcastHistory::class)->name('broadcasts.history');
    });


    
    Route::get('/email/verify', function () {
        if (!Auth::guard('candidat')->check()) {
            return redirect()->route('user.login');
        }
        if (Auth::guard('candidat')->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }
        return view('livewire.front.auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $candidat = \App\Models\Candidat::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($candidat->getEmailForVerification()))) {
            abort(403);
        }

        if (!$candidat->hasVerifiedEmail()) {
            $candidat->markEmailAsVerified();
        }

        if (!Auth::guard('candidat')->check()) {
            Auth::guard('candidat')->login($candidat);
        }

        return redirect()->route('user.dashboard')->with('verified', true);
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if (!Auth::guard('candidat')->check()) {
            return redirect()->route('user.login');
        }
        try {
            Auth::guard('candidat')->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    })->middleware('throttle:6,1')->name('verification.send');

});





Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return back();
})->name('lang.switch');

Route::get('/uploads/{path}', function ($path) {
    if (!Auth::guard('web')->check() && !Auth::guard('candidat')->check()) {
        abort(403);
    }

    $filePath = base_path('uploads/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    return response()->file($filePath);
})->where('path', '.*')->name('uploads.show');