<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();

        return response()->json($categories);
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}
