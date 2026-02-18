<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    /**
     * Display the digital menu for a specific business.
     */
    public function show(string $slug)
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->with(['menus', 'businessCategory', 'images'])
            ->firstOrFail();

        $menuGroups = $business->menus
            ->groupBy('category')
            ->sortBy(function ($items, $category) {
                // Potential order: Starter, Main, Dessert, Drink, etc.
                $order = ['Starter', 'Main', 'Dessert', 'Drink', 'Beverage', 'Service'];
                $index = array_search($category, $order);
                return $index === false ? 999 : $index;
            });

        return view('public.menu.show', compact('business', 'menuGroups'));
    }
}
