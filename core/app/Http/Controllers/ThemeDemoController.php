<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Resource;

class ThemeDemoController extends Controller
{
    /**
     * Show a demo of the digital menu with mock data.
     */
    public function show(Request $request, $theme)
    {
        // Allowed themes validation
        $allowedThemes = ['modern', 'classic', 'dark', 'grid', 'minimal'];
        if (!in_array($theme, $allowedThemes)) {
            abort(404);
        }

        // Mock Business Object
        $business = new \stdClass();
        $business->name = "Lezzet Köşesi (Demo)";
        $business->description = "İşletmenizin harika yemekleri burada görünecek. Modern ve şık bir deneyim sunun.";
        $business->phone = "+90 212 555 1234";
        $business->address = "Rezervist Bulvarı, Teknoloji Sitesi No:1";
        $business->menu_theme = $theme;
        $business->menu_color = $request->get('color', '#7c3aed'); // Default to purple
        $business->logo = null;
        $business->cover_image = null;

        // Mock Resource Object
        $resource = new \stdClass();
        $resource->name = "Masa 1";

        // Mock Menu Items
        $items = collect([
            (object)[
                'id' => 1,
                'name' => 'Fırın Sütlaç',
                'description' => 'Geleneksel yöntemlerle taş fırında pişirilmiş, üzeri nar gibi kızarmış.',
                'price' => 85.00,
                'category' => 'Tatlılar',
                'image' => 'https://images.unsplash.com/photo-1590089415225-401ed6f9db11?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ],
            (object)[
                'id' => 2,
                'name' => 'Kuzu Tandır',
                'description' => '12 saat boyunca ağır ateşte pişmiş, kemikten ayrılan kuzu eti. İç pilav ile servis edilir.',
                'price' => 450.00,
                'category' => 'Ana Yemekler',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ],
            (object)[
                'id' => 3,
                'name' => 'Gavurdağı Salatası',
                'description' => 'Taze ceviz, domates, salatalık ve nar ekşili özel sosu ile.',
                'price' => 120.00,
                'category' => 'Başlangıçlar',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ],
            (object)[
                'id' => 4,
                'name' => 'Mercimek Çorbası',
                'description' => 'Tereyağlı ve naneli kurutulmuş ekmek ile.',
                'price' => 65.00,
                'category' => 'Başlangıçlar',
                'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ],
            (object)[
                'id' => 5,
                'name' => 'Adana Kebap',
                'description' => 'Zırh kıyması, közlenmiş biber ve domates ile.',
                'price' => 320.00,
                'category' => 'Ana Yemekler',
                'image' => 'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ],
            (object)[
                'id' => 6,
                'name' => 'Ev Yapımı Limonata',
                'description' => 'Taze nane yaprakları ile buzlu servis edilir.',
                'price' => 55.00,
                'category' => 'İçecekler',
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&q=80&w=400',
                'is_available' => true
            ]
        ]);

        $groupedMenus = $items->groupBy('category');
        $payload = "DEMO_PAYLOAD";

        return view('qr.menu', compact('business', 'resource', 'groupedMenus', 'payload'));
    }
}
