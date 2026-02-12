<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        // Ensure user is derived from a business owner or part of a business
        $user = auth()->user();

        // Use owned business ID or the business_id they belong to
        $businessId = $user->ownedBusiness ? $user->ownedBusiness->id : $user->business_id;

        if (! $businessId) {
            return response()->json(['message' => 'Business ID not found for user'], 404);
        }

        return User::where('business_id', $businessId)->orWhere('id', $user->id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = auth()->user();
        $businessId = $user->ownedBusiness ? $user->ownedBusiness->id : $user->business_id;

        if (! $businessId) {
            return response()->json(['message' => 'No business associated'], 400);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'business', // or 'staff' if we differentiate
            'business_id' => $businessId,
            'email_verified_at' => now(),
        ]);

        return response()->json($newUser, 201);
    }

    public function destroy($id)
    {
        $currentUser = auth()->user();
        $businessId = $currentUser->ownedBusiness ? $currentUser->ownedBusiness->id : $currentUser->business_id;

        $userToDelete = User::where('id', $id)->where('business_id', $businessId)->first();

        if (! $userToDelete) {
            return response()->json(['message' => 'User not found or authorized'], 404);
        }

        $userToDelete->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
