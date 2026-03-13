<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Business::query();

        // 1. Location / Name Search
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%")
                    ->orWhereHas('businessCategory', function ($cq) use ($term) {
                        $cq->where('name', 'like', "%{$term}%");
                    });
            });
        }
        // ...


        // 2. Category Filter (handle both string and array)
        // 2. Category Filter (handle both string and array, IDs or slugs)
        if ($request->filled('category')) {
            $input = is_array($request->category) ? $request->category : [$request->category];
            $ids = [];
            $slugs = [];

            foreach ($input as $item) {
                if (is_numeric($item)) {
                    $ids[] = $item;
                } else {
                    $slugs[] = $item;
                }
            }

            if (! empty($slugs)) {
                $slugIds = \App\Models\Category::whereIn('slug', $slugs)->pluck('id')->toArray();
                $ids = array_merge($ids, $slugIds);
            }

            if (! empty($ids)) {
                $query->whereIn('category_id', $ids);
            }
        }

        // 3. Rating Filter
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // 4. Price Filter
        if ($request->filled('price_min')) {
            $query->where('price_per_person', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price_per_person', '<=', $request->price_max);
        }

        // 5. Features Filter (Tags)
        if ($request->filled('features')) {
            $tagSlugs = is_array($request->features) ? $request->features : [$request->features];
            foreach ($tagSlugs as $slug) {
                $query->whereHas('tags', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                });
            }
        }

        // 6. Open Now Filter
        if ($request->filled('open_now')) {
            $now = now();
            $dayOfWeek = $now->dayOfWeek; // 0 (Sunday) to 6 (Saturday) in Carbon usually, check DB
            // Database day_of_week: 0=Pzt, 1=Sal... 6=Paz. Carbon 0=Sun.
            // Normalized: Carbon(0-6 Sun-Sat) -> App(0-6 Mon-Sun?)
            // In VendorController: $dayNum is passed.
            $appDayNum = ($dayOfWeek + 6) % 7; // Mon=0, Tue=1... Sun=6

            $currentTime = $now->format('H:i:s');

            $query->whereHas('hours', function ($h) use ($appDayNum, $currentTime) {
                $h->where('day_of_week', $appDayNum)
                    ->where('is_closed', false)
                    ->where('open_time', '<=', $currentTime)
                    ->where('close_time', '>=', $currentTime);
            });
        }

        // 7. Sorting
        $sort = $request->get('sort');

        // Location-based Sorting (Haversine Formula)
        // Check Request params OR Cookies
        $lat = $request->get('lat') ?? $request->cookie('user_lat');
        $lng = $request->get('lng') ?? $request->cookie('user_lng');

        if ($lat && $lng) {
            $query->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$lat, $lng, $lat]);

            // Distance Radius Filter
            if ($request->filled('distance_max')) {
                $query->having('distance', '<=', $request->distance_max);
            }

            // If no explicit sort is chosen and we have location, default to nearest
            if (! $sort) {
                $sort = 'nearest';
            }
        } else {
            // Default to recommended if no location and no sort
            if (! $sort) {
                $sort = 'recommended';
            }
        }

        switch ($sort) {
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price_per_person', 'asc');
                break;
            case 'nearest':
                // Only if we have distance column
                if ($lat && $lng) {
                    $query->orderBy('distance', 'asc');
                } else {
                    $query->orderBy('id', 'desc');
                }
                break;
            case 'recommended':
            default:
                $query->orderBy('rating', 'desc')->orderBy('id', 'desc');
                break;
        }

        $businesses = $query->with(['businessCategory', 'approvedReviews'])->where('is_active', true)->paginate(10)->withQueryString();
        $categories = \App\Models\Category::all();
        $searchQuery = $request->get('q');

        if ($request->ajax()) {
            return view('search.partials.list', compact('businesses'))->render();
        }

        return view('search.index', compact('businesses', 'categories', 'searchQuery'));
    }
}
