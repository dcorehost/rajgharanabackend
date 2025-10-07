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
        try{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'no_of_person' => 'required|integer|min:1',
            'adult' => 'nullable|integer|min:0',
            'child' => 'nullable|integer|min:0',
            'package_id' => 'required|integer', // only integer for now
        ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors()
        ], 422);
    }

        // Check if package exists
        $package = \App\Models\Package::find($request->package_id);
        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found in database.'
            ], 404);
        }

        $booking = \App\Models\Booking::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'no_of_person' => $request->no_of_person,
            'adult' => $request->adult ?? 0,
            'child' => $request->child ?? 0,
            'package_id' => $package->id,
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }
}
