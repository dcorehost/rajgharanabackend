<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Package;

class BookingController extends Controller
{
   public function index()
    {
        $bookings = Booking::all();

        return response()->json([
            'status' => true,
            'data' => $bookings
        ], 200);
    }

   public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'no_of_person' => 'required|integer|min:1',
                'adult' => 'nullable|integer|min:0',
                'child' => 'nullable|integer|min:0',
                'package_id' => 'required|integer',
                'book_camp' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        }

        // 🔹 Check if package exists
        $package = Package::find($validated['package_id']);
        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found in database.',
            ], 404);
        }

        // 🔹 Create booking
        $booking = Booking::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'no_of_person' => $validated['no_of_person'],
            'adult' => $validated['adult'] ?? 0,
            'child' => $validated['child'] ?? 0,
            'package_id' => $package->id,
            'book_camp' => $validated['book_camp'] ?? null,
        ]);

        // 🔹 Return success response
        return response()->json([
            'status' => true,
            'message' => 'Booking created successfully.',
            'data' => $booking,
        ], 201);
    }

}
