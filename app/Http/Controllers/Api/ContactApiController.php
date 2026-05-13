<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactApiController
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $contact = Contact::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Message received.',
            'data'    => ['id' => $contact->id],
        ], 201);
    }
}
