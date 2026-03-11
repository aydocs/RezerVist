<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function upload(Request $request, $businessId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $business = Business::findOrFail($businessId);

        // Ensure user owns this business
        if ($request->user()->id !== $business->owner_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('business_images', 'public');

            $image = $business->images()->create([
                'image_path' => $path,
                'image_blob' => file_get_contents($file->getRealPath()),
            ]);

            return response()->json(['url' => $image->url, 'id' => $image->id]);
        }

        return response()->json(['message' => 'Upload failed'], 500);
    }
}
