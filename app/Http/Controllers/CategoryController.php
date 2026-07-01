<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = auth()->user()->categories()->withCount('accounts')->get();
        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $category = auth()->user()->categories()->create([
            'name' => $request->name,
            'color' => $request->color ?? '#6750A4',
        ]);

        return response()->json($category->loadCount('accounts'), 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($request->only('name', 'color'));

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->user_id !== auth()->id()) abort(403);

        // Detach accounts from this category before deleting
        $category->accounts()->update(['category_id' => null]);
        $category->delete();

        return response()->json(['ok' => true]);
    }
}
