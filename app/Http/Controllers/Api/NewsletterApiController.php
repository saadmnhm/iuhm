<?php

namespace App\Http\Controllers\Api;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterApiController
{
    /**
     * Get all active subscribers
     */
    public function index(Request $request): JsonResponse
    {
        $perPage     = (int) $request->get('per_page', 15);
        $subscribers = Newsletter::where('is_active', true)
            ->latest('subscribed_at')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $subscribers->items(),
            'pagination' => [
                'total'        => $subscribers->total(),
                'per_page'     => $subscribers->perPage(),
                'current_page' => $subscribers->currentPage(),
                'last_page'    => $subscribers->lastPage(),
            ],
        ]);
    }

    /**
     * Subscribe an email address
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        $subscriber = Newsletter::create([
            'email'         => $request->email,
            'is_active'     => true,
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Subscribed successfully.',
            'data'    => $subscriber,
        ], 201);
    }

    /**
     * Unsubscribe an email address
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Newsletter::where('email', $request->email)->first();

        if (! $subscriber) {
            return response()->json(['status' => 'error', 'message' => 'Email not found.'], 404);
        }

        $subscriber->update(['is_active' => false]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Unsubscribed successfully.',
        ]);
    }

    /**
     * Check subscription status
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $subscriber = Newsletter::where('email', $request->email)->first();

        return response()->json([
            'status'    => 'success',
            'subscribed' => $subscriber ? (bool) $subscriber->is_active : false,
        ]);
    }

    /**
     * Get newsletter statistics
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'total'    => Newsletter::count(),
                'active'   => Newsletter::where('is_active', true)->count(),
                'inactive' => Newsletter::where('is_active', false)->count(),
            ],
        ]);
    }
}
