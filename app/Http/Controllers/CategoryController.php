<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->ordered()->get();

        return response()->json($categories);
    }

    public function show(Category $category)
    {
        $games  = $category->games()->where('is_active', true)->ordered()->get();

        return view('categories.show', compact('category', 'games'));
    }
}
