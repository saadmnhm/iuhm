<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\NewsletterResource;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterApiController extends BaseApiController
{
    /**
     * Get all active subscribers (admin only in practice)
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $subscribers = Newsletter::where('is_active', true)
            ->latest('subscribed_at')
            ->paginate($perPage);

        return $this->paginated($subscribers);
    }

    /**
     * Subscribe an email address
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        $subscriber = Newsletter::create([
            'email'         => $validated['email'],
            'is_active'     => true,
            'subscribed_at' => now(),
        ]);

        return $this->success(
            new NewsletterResource($subscriber),
            'Subscribed successfully.',
            201
        );
    }

    /**
     * Unsubscribe an email address
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Newsletter::where('email', $validated['email'])->first();

        if (! $subscriber) {
            return $this->error('Email not found.', 404);
        }

        $subscriber->update(['is_active' => false]);

        return $this->success(null, 'Unsubscribed successfully.');
    }

    /**
     * Check subscription status
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Newsletter::where('email', $validated['email'])->first();

        return $this->success([
            'subscribed' => $subscriber ? (bool) $subscriber->is_active : false,
        ]);
    }

    /**
     * Get newsletter statistics
     */
    public function stats(): JsonResponse
    {
        return $this->success([
            'total'    => Newsletter::count(),
            'active'   => Newsletter::where('is_active', true)->count(),
            'inactive' => Newsletter::where('is_active', false)->count(),
        ]);
    }
}