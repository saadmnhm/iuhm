<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\BlogPostApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\DeliverableApiController;
use App\Http\Controllers\Api\NewsletterApiController;
use App\Http\Controllers\Api\MediaApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Models\Article;
use App\Models\Actualite;
use App\Models\Publication;
use App\Models\Newsletter;

Route::prefix('api/v1')->name('api.v1.')->group(function () {

    // Health check – used by site_iuhm to verify ERP connectivity
    Route::get('/health', function () {
        try {
            DB::connection()->getPdo();
            $dbOk = true;
        } catch (\Throwable) {
            $dbOk = false;
        }

        $stats = [];
        if ($dbOk) {
            try {
                $stats = [
                    'blog_posts'   => Article::count(),
                    'news'         => Actualite::count(),
                    'deliverables' => Publication::count(),
                    'newsletters'  => Newsletter::count(),
                ];
            } catch (\Throwable) {}
        }

        return response()->json([
            'status'    => $dbOk ? 'ok' : 'degraded',
            'service'   => 'iuhm-erp-api',
            'version'   => '1.0',
            'timestamp' => now()->toISOString(),
            'database'  => $dbOk ? 'connected' : 'error',
            'stats'     => $stats,
        ], $dbOk ? 200 : 503);
    })->name('health');


    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogPostApiController::class, 'index'])->name('index');
        Route::get('/trending', [BlogPostApiController::class, 'trending'])->name('trending');
        Route::get('/search', [BlogPostApiController::class, 'search'])->name('search');
        Route::get('/category/{category}', [BlogPostApiController::class, 'getByCategory'])->name('category');
        Route::get('/{id}', [BlogPostApiController::class, 'getById'])->name('show');
        Route::get('/slug/{slug}', [BlogPostApiController::class, 'show'])->name('show.slug');
    });

    // News API
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsApiController::class, 'index'])->name('index');
        Route::get('/latest', [NewsApiController::class, 'latest'])->name('latest');
        Route::get('/search', [NewsApiController::class, 'search'])->name('search');
        Route::get('/category/{category}', [NewsApiController::class, 'getByCategory'])->name('category');
        Route::get('/{id}', [NewsApiController::class, 'getById'])->name('show');
        Route::get('/slug/{slug}', [NewsApiController::class, 'show'])->name('show.slug');
    });

    // Deliverables API
    Route::prefix('deliverables')->name('deliverables.')->group(function () {
        Route::get('/', [DeliverableApiController::class, 'index'])->name('index');
        Route::get('/popular', [DeliverableApiController::class, 'popular'])->name('popular');
        Route::get('/search', [DeliverableApiController::class, 'search'])->name('search');
        Route::get('/category/{category}', [DeliverableApiController::class, 'getByCategory'])->name('category');
        Route::get('/status/{status}', [DeliverableApiController::class, 'getByStatus'])->name('status');
        Route::get('/{id}', [DeliverableApiController::class, 'getById'])->name('show');
        Route::get('/slug/{slug}', [DeliverableApiController::class, 'show'])->name('show.slug');
    });

    // Newsletters API
    Route::prefix('newsletters')->name('newsletters.')->group(function () {
        Route::get('/', [NewsletterApiController::class, 'index'])->name('index');
        Route::get('/latest', [NewsletterApiController::class, 'latest'])->name('latest');
        Route::get('/sent', [NewsletterApiController::class, 'sent'])->name('sent');
        Route::get('/search', [NewsletterApiController::class, 'search'])->name('search');
        Route::get('/stats', [NewsletterApiController::class, 'stats'])->name('stats');
        Route::get('/issue/{issue}', [NewsletterApiController::class, 'getByIssue'])->name('issue');
        Route::get('/slug/{slug}', [NewsletterApiController::class, 'show'])->name('show.slug');
        Route::get('/{id}', [NewsletterApiController::class, 'getById'])->name('show');
        Route::post('/subscribe', [NewsletterApiController::class, 'subscribe'])->name('subscribe');
        Route::post('/unsubscribe', [NewsletterApiController::class, 'unsubscribe'])->name('unsubscribe');
    });

    // Contacts / Inquiries API
    Route::post('/contacts', [ContactApiController::class, 'store'])->name('contacts.store');

    // Media API
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaApiController::class, 'index'])->name('index');
        Route::get('/latest', [MediaApiController::class, 'latest'])->name('latest');
        Route::get('/most-used', [MediaApiController::class, 'mostUsed'])->name('most.used');
        Route::get('/categories', [MediaApiController::class, 'categories'])->name('categories');
        Route::get('/stats', [MediaApiController::class, 'stats'])->name('stats');
        Route::get('/search', [MediaApiController::class, 'search'])->name('search');
        Route::get('/category/{category}', [MediaApiController::class, 'getByCategory'])->name('category');
        Route::get('/type/{type}', [MediaApiController::class, 'getByType'])->name('type');
        Route::get('/download/{id}', [MediaApiController::class, 'download'])->name('download');
        Route::get('/{id}', [MediaApiController::class, 'getById'])->name('show');
    });
});

