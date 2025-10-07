<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // ✅ GET: all packages with category name
    public function index()
    {
        $packages = Package::with('category:id,name')->get();

        return response()->json([
            'status' => true,
            'data' => $packages
        ], 200);
    }

    // ✅ POST: create a new package
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_name' => 'required|string|exists:categories,name',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // find category by name
        $category = Category::where('name', $request->category_name)->first();

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $category->id,
            'price' => $request->price,
        ];

        // handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            $data['image'] = $path;
        }

        $package = Package::create($data);

        return response()->json([
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    // ✅ GET: Packages by Category Name
    public function getByCategoryName($categoryName)
    {
        // Check if category exists
        $category = \App\Models\Category::where('name', $categoryName)->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.'
            ], 404);
        }

        // Fetch all packages under that category
        $packages = \App\Models\Package::with('category:id,name')
            ->where('category_id', $category->id)
            ->get();

        if ($packages->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No packages found for this category.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'category' => $category->name,
            'data' => $packages
        ], 200);
    }


}
