<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    // Admin Register
  // Register
public function register(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:10|max:15',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors()
        ], 422);
    }

    // Create admin user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'password' => Hash::make($validated['password']),
        'is_admin' => false,
    ]);

    // Store user ID in session
    session(['user_id' => $user->id]);

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
    ], 201);
}

// Login
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $admin = User::where('email', $request->email)
                 ->where('is_admin', true)
                 ->first();

    if (! $admin || ! Hash::check($request->password, $admin->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect or not an admin.'],
        ]);
    }

    // Store user ID in session
    session(['user_id' => $admin->id]);

    return response()->json([
        'message' => 'Login successful',
        'admin' => $admin,
    ]);
}

// Logout
public function logout(Request $request)
{
    // Remove user ID from session
    session()->forget('user_id');

    return response()->json(['message' => 'Logged out successfully']);
}

}
