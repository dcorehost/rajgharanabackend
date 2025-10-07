<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    /**
     * GET: All contacts
     */
    public function index()
    {
        try {
            $contacts = Contact::latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Contacts retrieved successfully',
                'data' => $contacts,
                'count' => $contacts->count()
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve contacts',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST: Create new contact
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:15',
                'comment' => 'nullable|string'
            ]);

            $contact = Contact::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Contact created successfully',
                'data' => $contact
            ], Response::HTTP_CREATED);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create contact',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}