<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\BlogPostApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\DeliverableApiController;
use App\Http\Controllers\Api\NewsletterApiController;
use App\Http\Controllers\Api\MediaApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\NavigationApiController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\HistoryTimelineApiController;
use App\Models\Article;
use App\Models\Actualite;
use App\Models\Publication;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Section;
use App\Models\NavigationItem;
use App\Models\Project;
use App\Models\HistoryTimeline;

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

    // Public read-only endpoints - rate limited
    Route::middleware('throttle:60,1')->group(function () {
        // Blog API
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/', [BlogPostApiController::class, 'index'])->name('index');
            Route::get('/trending', [BlogPostApiController::class, 'trending'])->name('trending');
            Route::get('/search', [BlogPostApiController::class, 'search'])->name('search');
            Route::get('/categories', [BlogPostApiController::class, 'categories'])->name('categories');
            Route::get('/category/{category}', [BlogPostApiController::class, 'getByCategory'])->name('category');
            Route::get('/{id}', [BlogPostApiController::class, 'getById'])->name('show');
            Route::get('/slug/{slug}', [BlogPostApiController::class, 'show'])->name('show.slug');
        });

        // News API
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [NewsApiController::class, 'index'])->name('index');
            Route::get('/latest', [NewsApiController::class, 'latest'])->name('latest');
            Route::get('/search', [NewsApiController::class, 'search'])->name('search');
            Route::get('/categories', [NewsApiController::class, 'categories'])->name('categories');
            Route::get('/category/{category}', [NewsApiController::class, 'getByCategory'])->name('category');
            Route::get('/{id}', [NewsApiController::class, 'getById'])->name('show');
            Route::get('/slug/{slug}', [NewsApiController::class, 'show'])->name('show.slug');
        });

        // Deliverables API
        Route::prefix('deliverables')->name('deliverables.')->group(function () {
            Route::get('/', [DeliverableApiController::class, 'index'])->name('index');
            Route::get('/popular', [DeliverableApiController::class, 'popular'])->name('popular');
            Route::get('/search', [DeliverableApiController::class, 'search'])->name('search');
            Route::get('/categories', [DeliverableApiController::class, 'categories'])->name('categories');
            Route::get('/statuses', [DeliverableApiController::class, 'statuses'])->name('statuses');
            Route::get('/category/{category}', [DeliverableApiController::class, 'getByCategory'])->name('category');
            Route::get('/status/{status}', [DeliverableApiController::class, 'getByStatus'])->name('status');
            Route::get('/{id}', [DeliverableApiController::class, 'getById'])->name('show');
            Route::get('/slug/{slug}', [DeliverableApiController::class, 'show'])->name('show.slug');
        });

        // Media API
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaApiController::class, 'index'])->name('index');
            Route::get('/latest', [MediaApiController::class, 'latest'])->name('latest');
            Route::get('/most-used', [MediaApiController::class, 'mostUsed'])->name('most.used');
            Route::get('/categories', [MediaApiController::class, 'categories'])->name('categories');
            Route::get('/types', [MediaApiController::class, 'types'])->name('types');
            Route::get('/stats', [MediaApiController::class, 'stats'])->name('stats');
            Route::get('/search', [MediaApiController::class, 'search'])->name('search');
            Route::get('/category/{category}', [MediaApiController::class, 'getByCategory'])->name('category');
            Route::get('/type/{type}', [MediaApiController::class, 'getByType'])->name('type');
            Route::get('/download/{id}', [MediaApiController::class, 'download'])->name('download');
            Route::get('/{id}', [MediaApiController::class, 'getById'])->name('show');
        });

        // Pages API
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [PageApiController::class, 'index'])->name('index');
            Route::get('/search', [PageApiController::class, 'search'])->name('search');
            Route::get('/{id}', [PageApiController::class, 'getById'])->name('show');
            Route::get('/slug/{slug}', [PageApiController::class, 'show'])->name('show.slug');
        });

        // Navigation API
        Route::prefix('navigation')->name('navigation.')->group(function () {
            Route::get('/', [NavigationApiController::class, 'index'])->name('index');
            Route::get('/{id}', [NavigationApiController::class, 'getById'])->name('show');
        });

        // Projects API
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [ProjectApiController::class, 'index'])->name('index');
            Route::get('/featured', [ProjectApiController::class, 'featured'])->name('featured');
            Route::get('/search', [ProjectApiController::class, 'search'])->name('search');
            Route::get('/statuses', [ProjectApiController::class, 'statuses'])->name('statuses');
            Route::get('/status/{status}', [ProjectApiController::class, 'getByStatus'])->name('status');
            Route::get('/{id}', [ProjectApiController::class, 'getById'])->name('show');
            Route::get('/slug/{slug}', [ProjectApiController::class, 'show'])->name('show.slug');
        });

        // History Timeline API
        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/', [HistoryTimelineApiController::class, 'index'])->name('index');
            Route::get('/search', [HistoryTimelineApiController::class, 'search'])->name('search');
            Route::get('/{id}', [HistoryTimelineApiController::class, 'getById'])->name('show');
        });
    });

    // Write endpoints - stricter rate limiting
    Route::middleware('throttle:10,1')->group(function () {
        // Newsletters API
        Route::prefix('newsletters')->name('newsletters.')->group(function () {
            Route::get('/', [NewsletterApiController::class, 'index'])->name('index');
            Route::get('/stats', [NewsletterApiController::class, 'stats'])->name('stats');
            Route::post('/subscribe', [NewsletterApiController::class, 'subscribe'])->name('subscribe');
            Route::post('/unsubscribe', [NewsletterApiController::class, 'unsubscribe'])->name('unsubscribe');
            Route::post('/check', [NewsletterApiController::class, 'check'])->name('check');
        });

        // Contacts / Inquiries API
        Route::post('/contacts', [ContactApiController::class, 'store'])->name('contacts.store');
    });
});