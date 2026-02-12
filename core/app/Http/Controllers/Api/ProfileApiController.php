<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'sometimes|required_with:password|current_password',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        try {
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            }

            $user->update($validated);

            return response()->json([
                'message' => 'Profil başarıyla güncellendi.',
                'user' => $user->refresh(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Profil güncellenirken bir hata oluştu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096', // Max 4MB
        ]);

        $user = Auth::user();

        try {
            // Upload new photo
            $paths = FileUploadService::uploadImage($request->file('photo'), 'profile_photos', true);

            // Delete old photo if exists
            if ($user->profile_photo_path) {
                FileUploadService::delete($user->profile_photo_path);
            }

            // Update user record
            $user->update([
                'profile_photo_path' => $paths['original'],
            ]);

            return response()->json([
                'message' => 'Profil fotoğrafı güncellendi.',
                'user' => $user->refresh(),
                'photo_url' => $user->profile_photo_url, // Helper accessor
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Fotoğraf yüklenirken bir hata oluştu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user settings (like notifications).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'notifications_enabled' => 'required|boolean',
        ]);

        $user = Auth::user();
        $user->update([
            'notifications_enabled' => $request->notifications_enabled,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ayarlar güncellendi.',
            'notifications_enabled' => $user->notifications_enabled,
        ]);
    }
}
