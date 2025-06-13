<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Field;
use Illuminate\Support\Facades\Auth;

class FieldImageController extends Controller
{
    public function store(Request $request, $fieldId)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048'
        ]);

        $user = Auth::guard()->user();
        $field = Field::with('images')->findOrFail($fieldId);

        if ($field->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $existingImages = $field->images;
        $emptySlots = $existingImages->filter(function ($img) {
            return !Storage::exists("public/{$img->image_path}");
        })->values();

        $currentCount = $existingImages->filter(function ($img) {
            return Storage::exists("public/{$img->image_path}");
        })->count();

        $max = 5;
        $newImages = $request->file('images');

        if ($currentCount + count($newImages) > $max) {
            return response()->json([
                'error' => 'Slot gambar penuh. Silakan hapus gambar terlebih dahulu.',
                'current_images' => $existingImages
            ], 400);
        }

        $safeFieldName = Str::slug($field->name, '_');
        $basePath = "users/{$user->id}/lahan/{$safeFieldName}";

        foreach ($newImages as $file) {
            if ($emptySlots->isNotEmpty()) {
                $slot = $emptySlots->shift();
                $path = $slot->image_path;
                $file->storeAs(dirname($path), basename($path));
            } else {
                $slotNumber = $currentCount + 1;
                $filename = "{$safeFieldName}_{$slotNumber}." . $file->getClientOriginalExtension();
                $file->storeAs("{$basePath}", $filename);

                $field->images()->create([
                    'image_path' => "{$basePath}/{$filename}"
                ]);

                $currentCount++;
            }
        }

        return response()->json(['message' => 'Gambar berhasil ditambahkan.'], 200);
    }

    public function destroy(Request $request, $fieldId)
    {
        $request->validate([
            'image_id' => 'required|array',
            'image_id.*' => 'required|integer|exists:field_images,id',
        ]);
        dd($request->all());

        $user = Auth::guard()->user();
        $field = Field::with('images')->findOrFail($fieldId);

        if ($field->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $images = $field->images()->whereIn('id', $request->image_id)->get();

        foreach ($images as $image) {
            $filePath = "public/{$image->image_path}";
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        return response()->json(['message' => 'Gambar berhasil dihapus dari storage.'], 200);
    }

    public function index($fieldId)
    {
        $user = Auth::guard()->user();
        $field = Field::with('images')->findOrFail($fieldId);

        if ($field->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($field->images);
    }

    public function update(Request $request, $fieldId)
    {
        $request->validate([
            'image_id' => 'required|integer|exists:field_images,id',
            'image' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048'
        ]);
        // dd($request->all());
        $user = Auth::guard()->user();
        $field = Field::with('image')->findOrFail($fieldId);

        if ($field->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $image = $field->images()->where('id', $request->image_id)->first();

        if (!$image) {
            return response()->json(['error' => 'Gambar tidak ditemukan.'], 404);
        }

        $newFile = $request->file('image');
        $path = $image->image_path;

        $newFile->storeAs(dirname($path), basename($path));

        return response()->json(['message' => 'Gambar berhasil diperbarui.'], 200);
    }
}