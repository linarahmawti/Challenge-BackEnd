<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Menyimpan mobil baru
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'color' => 'required|string|max:100',
        'status' => 'required|in:available,unavailable',
        'seat' => 'required|integer|min:1',
        'cc' => 'required|integer|min:1',
        'top_speed' => 'required|integer|min:1',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'imageUrl' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'category_id' => 'required|exists:categories,id'
    ]);

    // Upload gambar utama
    if ($request->hasFile('imageUrl')) {
        $validated['imageUrl'] = $request->file('imageUrl')->store('cars', 'public');
    }

    $car = Car::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Mobil berhasil ditambahkan',
        'data' => $car->load('category')
    ], 201);
}

    /**
     * Memperbarui mobil
     */
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'color' => 'sometimes|string',
            'status' => 'sometimes|in:tersedia,tidak tersedia',
            'seat' => 'sometimes|integer|min:1',
            'cc' => 'sometimes|integer|min:1',
            'top_speed' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string',
            'imageUrl' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        // Update gambar jika ada
        if ($request->hasFile('imageUrl')) {
            // Hapus gambar lama
            if ($car->imageUrl) {
                Storage::disk('public')->delete($car->imageUrl);
            }
            $validated['imageUrl'] = $request->file('imageUrl')->store('cars', 'public');
        }

        $car->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mobil berhasil diperbarui',
            'data' => $car->fresh('category')
        ]);
    }

    /**
     * Menghapus mobil
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        // Hapus gambar
        if ($car->imageUrl) {
            Storage::disk('public')->delete($car->imageUrl);
        }

        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mobil berhasil dihapus'
        ]);
    }
}
