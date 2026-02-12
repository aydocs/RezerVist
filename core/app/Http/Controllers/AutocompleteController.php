<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutocompleteController extends Controller
{

    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search businesses by name and address (extracting city info)
        $businesses = \App\Models\Business::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(8)
            ->get(['id', 'name', 'address']);

        $results = $businesses->map(function($business) {
            return [
                'id' => $business->id,
                'name' => $business->name,
                'type' => 'business',
                'subtitle' => $business->address
            ];
        });

        return response()->json($results);
    }
}
