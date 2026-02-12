{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    
    {{-- Ana Sayfa --}}
    <url>
        <loc>{{ config('app.url') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Statik Sayfalar --}}
    <url>
        <loc>{{ config('app.url') }}/search</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/business-partner</loc>
        <lastmod>{{ now()->subDays(7)->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/help</loc>
        <lastmod>{{ now()->subDays(30)->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/contact</loc>
        <lastmod>{{ now()->subDays(30)->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/campaigns</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/privacy-policy</loc>
        <lastmod>{{ now()->subMonths(3)->toW3cString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/terms</loc>
        <lastmod>{{ now()->subMonths(3)->toW3cString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ config('app.url') }}/rezervista-pos</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Kategori Sayfaları --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ config('app.url') }}/search?category={{ urlencode($category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- İşletme Sayfaları --}}
    @foreach($businesses as $business)
    <url>
        <loc>{{ config('app.url') }}/business/{{ $business->id }}</loc>
        <lastmod>{{ $business->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

</urlset>
