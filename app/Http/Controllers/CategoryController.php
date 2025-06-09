<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil disimpan',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json(['message' => 'Kategori berhasil diperbarui', 'data' => $category]);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
