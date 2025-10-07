<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ✅ GET: List all categories
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    // ✅ POST: Create a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = ['name' => $request->name];

        $category = Category::create($data);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }
}
