<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', ($globalSettings['site_name'] ?? 'Rezervist.com') . ' - ' . ($globalSettings['site_tagline'] ?? 'Türkiye\'nin Premium Rezervasyon Platformu'))</title>
    <meta name="title" content="@yield('title', ($globalSettings['site_name'] ?? 'Rezervist.com') . ' - Türkiye\'nin En İyi Rezervasyon Platformu')">
    <meta name="description" content="@yield('meta_description', $globalSettings['seo_description'] ?? 'Türkiye\'nin en kapsamlı online rezervasyon platformu. Restoran, kafe, kuaför, güzellik merkezi ve daha fazlası için hemen rezervasyon yapın. 7/24 ücretsiz hizmet.')">
    <meta name="keywords" content="@yield('meta_keywords', $globalSettings['seo_keywords'] ?? 'rezervasyon, online rezervasyon, restoran rezervasyon, masa rezervasyonu, kuaför randevu, güzellik merkezi, ankara restoran, istanbul cafe, izmir kuaför, türkiye rezervasyon')">
    <meta name="author" content="{{ $globalSettings['site_name'] ?? 'Rezervist.com' }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="language" content="Turkish">
    <meta name="revisit-after" content="1 days">
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="geo.region" content="TR">
    <meta name="geo.placename" content="Türkiye">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Alternate Languages -->
    <link rel="alternate" hreflang="tr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', ($globalSettings['site_name'] ?? 'Rezervist.com') . ' - Türkiye\'nin Premium Rezervasyon Platformu')">
    <meta property="og:description" content="@yield('meta_description', $globalSettings['seo_description'] ?? 'Türkiye\'nin en kapsamlı online rezervasyon platformu. Hemen ücretsiz rezervasyon yapın!')">
    <meta property="og:image" content="@yield('meta_image', isset($business->id) ? route('business.og-image', $business) : asset('images/og-image.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:site_name" content="{{ $globalSettings['site_name'] ?? 'Rezervist.com' }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', ($globalSettings['site_name'] ?? 'Rezervist.com'))">
    <meta name="twitter:description" content="@yield('meta_description', $globalSettings['seo_description'] ?? 'Türkiye\'nin en kapsamlı online rezervasyon platformu.')">
    <meta name="twitter:image" content="@yield('meta_image', isset($business->id) ? route('business.og-image', $business) : asset('images/og-image.jpg'))">
    @if(!empty($globalSettings['social_twitter']))
    <meta name="twitter:site" content="{{ '@' . basename($globalSettings['social_twitter']) }}">
    @endif

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#6200EE">
    <meta name="msapplication-TileColor" content="#6200EE">

    <!-- Preconnect to External Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/js/app.js'])

    <!-- JSON-LD Structured Data -->
    @php
        $socialLinks = array_values(array_filter([
            $globalSettings['social_facebook'] ?? null,
            $globalSettings['social_instagram'] ?? null,
            $globalSettings['social_twitter'] ?? null,
            $globalSettings['social_whatsapp'] ?? null,
        ]));

        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => $globalSettings['site_name'] ?? 'Rezervist.com',
            "alternateName" => "Rezervist",
            "url" => config('app.url'),
            "description" => $globalSettings['seo_description'] ?? 'Türkiye\'nin en kapsamlı online rezervasyon platformu',
            "inLanguage" => "tr-TR",
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => [
                    "@type" => "EntryPoint",
                    "urlTemplate" => config('app.url') . "/search?search={search_term_string}"
                ],
                "query-input" => "required name=search_term_string"
            ]
        ];

        $organizationSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $globalSettings['site_name'] ?? 'Rezervist.com',
            "url" => config('app.url'),
            "logo" => asset('images/logo.png'),
            "description" => "Türkiye'nin lider online rezervasyon ve işletme yönetim platformu",
            "foundingDate" => "2024",
            "founders" => [
                [
                    "@type" => "Person",
                    "name" => "Rezervist Team"
                ]
            ],
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "TR",
                "addressLocality" => "İstanbul"
            ],
            "contactPoint" => [
                [
                    "@type" => "ContactPoint",
                    "telephone" => $globalSettings['contact_phone'] ?? '',
                    "contactType" => "customer service",
                    "availableLanguage" => ["Turkish", "English"],
                    "areaServed" => "TR"
                ]
            ]
        ];

        if (!empty($socialLinks)) {
            $organizationSchema['sameAs'] = $socialLinks;
        }
    @endphp

    <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    
    @yield('structured_data')

    <!-- Google Analytics Integration with Cookie Consent -->
    @if(!empty($globalSettings['google_analytics_id']))
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // Initial state: Denied for safety until consent is confirmed
        gtag('consent', 'default', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied'
        });

        function initializeGoogleAnalytics() {
            const preferences = JSON.parse(localStorage.getItem('rezervist_cookie_preferences'));
            
            if (preferences && preferences.analytics) {
                // Update consent status
                gtag('consent', 'update', {
                    'analytics_storage': 'granted'
                });

                // Load the script if not already loaded
                if (!window.ga_initialized) {
                    const script = document.createElement('script');
                    script.async = true;
                    script.src = "https://www.googletagmanager.com/gtag/js?id={{ $globalSettings['google_analytics_id'] }}";
                    document.head.appendChild(script);
                    
                    gtag('js', new Date());
                    gtag('config', '{{ $globalSettings['google_analytics_id'] }}');
                    window.ga_initialized = true;
                }
            }

            if (preferences && preferences.marketing) {
                gtag('consent', 'update', {
                    'ad_storage': 'granted'
                });
            }
        }

        // Listen for internal consent updates
        window.addEventListener('cookie-consent-updated', initializeGoogleAnalytics);
        
        // Check on load
        document.addEventListener('DOMContentLoaded', initializeGoogleAnalytics);
    </script>
    @endif


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6200EE', // Deep Purple for Light Mode contrast
                        secondary: '#03DAC6',
                    },
                    animation: {
                        'subtle-zoom': 'subtle-zoom 20s infinite alternate',
                        'fade-in-up': 'fade-in-up 1s ease-out forwards',
                    },
                    keyframes: {
                        'subtle-zoom': {
                            '0%': { transform: 'scale(1.0)' },
                            '100%': { transform: 'scale(1.1)' },
                        },
                        'fade-in-up': {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'gradient': {
                            '0%': { backgroundPosition: '0% 50%' },
                            '100%': { backgroundPosition: '200% 50%' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F9FAFB; color: #111827; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #6200EE;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4B00B5;
        }

        /* SweetAlert2 Modal & Toast Overlays */
        .swal2-container {
            z-index: 999999 !important;
        }
        
        /* Fix for unwanted purple bar/artifacts */
        .swal2-timer-progress-bar-container,
        .swal2-timer-progress-bar,
        .swal2-progress-steps {
            display: none !important;
        }

        /* Ensure inputs don't have default styling conflicts */
        .swal2-input, .swal2-file, .swal2-textarea, .swal2-select, .swal2-radio, .swal2-checkbox {
            display: none !important;
        }

        /* Upgrade Toast Styles */
        .swal2-popup.swal2-toast {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            padding: 1rem !important;
            border-radius: 1rem !important;
        }
        /* Page Loader */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #6200EE 0%, #8e44ad 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        #page-loader.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        
        .loader-content {
            text-align: center;
        }
        
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        .loader-text {
            color: white;
            font-size: 18px;
            font-weight: 600;
            opacity: 0.9;
        }
        
        ::selection {
            background-color: #6200EE;
            color: #ffffff;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            -webkit-text-stroke: 0.5px rgba(255, 255, 255, 0.3);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Skeleton Loader Animations */
        @keyframes skeleton-loading {
            0% { background-color: #f3f4f6; }
            50% { background-color: #e5e7eb; }
            100% { background-color: #f3f4f6; }
        }
        .skeleton {
            animation: skeleton-loading 1.5s infinite linear;
            border-radius: 0.5rem;
            display: inline-block;
            height: 1em;
            width: 100%;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <script>
        // PWA Install Prompt Logic
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
            // Optionally dispatch event for custom UI
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });

        window.addEventListener('appinstalled', (e) => {
            console.log('PWA was installed');
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.log('Service Worker registration failed', err));
            });
        }
    </script>
</head>
<body class="antialiased font-sans bg-gray-50 text-gray-900">
    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">{{ $globalSettings['site_name'] ?? 'Rezervist.com' }}</div>
        </div>
    </div>
    <div class="min-h-screen flex flex-col">
        <!-- Modular Navbar -->
        <x-navbar />

        <!-- Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Detailed Footer -->
        <footer class="bg-white border-t border-gray-100 pt-20 pb-10 mt-auto">
            <div class="w-full px-6 lg:px-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-10 gap-12 mb-20">
                    
                    <!-- Brand & Identity (3 Columns) -->
                    <div class="lg:col-span-3">
                        <a href="/" class="flex items-center gap-3 mb-6 group">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary to-purple-700 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-xl shadow-primary/20 transition-transform group-hover:scale-110 duration-500">
                                {{ substr($globalSettings['site_name'] ?? 'R', 0, 1) }}
                            </div>
                            <span class="text-2xl font-black text-slate-900 tracking-tighter">{{ $globalSettings['site_name'] ?? 'Rezervist.com' }}</span>
                        </a>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8 max-w-sm">
                            {{ $globalSettings['site_tagline'] ?? __('common.footer.slogan') }}
                        </p>
                        <div class="flex items-center gap-4">
                            @php
                                $socials = [
                                    ['icon' => 'facebook', 'link' => $globalSettings['social_facebook'] ?? '#'],
                                    ['icon' => 'instagram', 'link' => $globalSettings['social_instagram'] ?? '#'],
                                    ['icon' => 'twitter', 'link' => $globalSettings['social_twitter'] ?? '#'],
                                ];
                            @endphp
                            @foreach($socials as $s)
                                @if($s['link'] !== '#')
                                <a href="{{ $s['link'] }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary hover:-translate-y-1 transition-all duration-300">
                                    <i class="fa-brands fa-{{ $s['icon'] }} text-sm"></i>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">{{ __('common.footer.explore') }}</h4>
                        <ul class="space-y-4">
                            @php
                                $exploreLinks = [
                                    ['name' => __('common.subcategories.restaurants'), 'url' => '/search?category=restoran'],
                                    ['name' => __('common.subcategories.cafes'), 'url' => '/search?category=kafe'],
                                    ['name' => __('common.categories.beauty'), 'url' => '/search?category=guzellik'],
                                    ['name' => __('common.categories.events'), 'url' => '/search?category=etkinlik'],
                                    ['name' => __('home.featured.title'), 'url' => '/search?sort=popular'],
                                ];
                            @endphp
                            @foreach($exploreLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                    {{ $link['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="lg:col-span-2">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">{{ __('common.footer.business_links') }}</h4>
                        <ul class="space-y-4">
                            @php
                                $businessLinks = [
                                    ['name' => __('common.header.business_partner'), 'url' => '/business-partner'],
                                    ['name' => __('common.menu.business_panel'), 'url' => route('vendor.dashboard')],
                                    ['name' => 'RezerVist POS', 'url' => route('pages.pos')],
                                    ['name' => __('common.footer.version_history'), 'url' => route('pages.pos.versions')],
                                    ['name' => __('common.footer.documentation'), 'url' => '/docs'],
                                ];
                            @endphp
                            @foreach($businessLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                    {{ $link['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="lg:col-span-3">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">{{ __('common.footer.support') }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <ul class="space-y-4">
                                <li>
                                    <a href="/about" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.about_us') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/help" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.help_center') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/contact" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.categories.contact') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/careers" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.careers') }}
                                    </a>
                                </li>
                            </ul>
                            <ul class="space-y-4">
                                <li>
                                    <a href="/privacy-policy" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.privacy') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/terms" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.terms') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/cookies" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.cookie_policy') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="/site-haritasi" class="text-slate-600 hover:text-primary text-[14px] font-medium transition-colors flex items-center gap-2 group">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-primary transition-colors"></span>
                                        {{ __('common.footer.sitemap') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-10 flex gap-3">
                            <a href="#" class="flex-1 bg-slate-900 rounded-2xl p-3 flex items-center gap-3 hover:bg-slate-800 transition-colors group">
                                <i class="fa-brands fa-apple text-white text-xl"></i>
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase leading-none">{{ __('common.header.app_store_pre') }}</span>
                                    <span class="text-[12px] text-white font-bold leading-tight">{{ __('common.footer.download_label') }}</span>
                                </div>
                            </a>
                            <a href="#" class="flex-1 bg-slate-900 rounded-2xl p-3 flex items-center gap-3 hover:bg-slate-800 transition-colors group">
                                <i class="fa-brands fa-google-play text-white text-lg"></i>
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase leading-none">{{ __('common.header.google_play_pre') }}</span>
                                    <span class="text-[12px] text-white font-bold leading-tight">{{ __('common.footer.download_label') }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact & Logistics Info Bar -->
                <div class="py-12 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-phone-volume text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('common.footer.customer_line') }}</span>
                            <span class="text-base font-bold text-slate-900">{{ $globalSettings['contact_phone'] ?? '+90 (212) 000 00 00' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-envelope-open-text text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('common.form.email') }}</span>
                            <span class="text-base font-bold text-slate-900">{{ $globalSettings['contact_email'] ?? 'destek@rezervist.com' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-location-dot text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('common.footer.head_office') }}</span>
                            <span class="text-base font-bold text-slate-900 leading-tight">{{ $globalSettings['contact_address'] ?? 'İstanbul, Türkiye' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Legal & Copyright -->
                <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
                        <p class="text-slate-400 text-[13px] font-medium">
                            &copy; {{ date('Y') }} <span class="text-slate-900 font-bold font-black">{{ $globalSettings['site_name'] ?? 'Rezervist.com' }}</span>. {{ __('common.footer.rights') }}
                        </p>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="flex items-center gap-8 opacity-30 hover:opacity-100 transition-opacity duration-500 grayscale hover:grayscale-0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" alt="Visa" class="h-4 w-auto">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="Mastercard" class="h-6 w-auto">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" alt="PayPal" class="h-4 w-auto">
                        <div class="h-8 w-px bg-slate-200 mx-2"></div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Ssl-logo.svg/1200px-Ssl-logo.svg.png" alt="SSL" class="h-6 w-auto">
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.VAPID_PUBLIC_KEY = "{{ config('webpush.vapid.public_key') }}";
    </script>
    <!-- Global Scripts -->
    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Global Toast Function
        function showToast(message, type = 'success') {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Global Confirmation Function
        function showConfirm(title, text, confirmButtonText = 'Evet', cancelButtonText = 'Vazgeç') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6200EE',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText
            });
        }

        // Favorites Logic
        const FAVORITE_KEY = 'guest_favorites';

        function getGuestFavorites() {
            try {
                return JSON.parse(localStorage.getItem(FAVORITE_KEY) || '[]');
            } catch (e) {
                return [];
            }
        }

        async function toggleFavorite(businessId, element) {
            const isGuest = @json(auth()->guest());
            
            if (isGuest) {
                let favorites = getGuestFavorites();
                const index = favorites.indexOf(parseInt(businessId));
                let isAdded = false;

                if (index > -1) {
                    favorites.splice(index, 1);
                } else {
                    favorites.push(parseInt(businessId));
                    isAdded = true;
                }

                localStorage.setItem(FAVORITE_KEY, JSON.stringify(favorites));
                updateHeartIcons(businessId, isAdded);
                showToast(isAdded ? "{{ __('common.messages.added_to_favorites') }}" : "{{ __('common.messages.removed_from_favorites') }}", isAdded ? 'success' : 'info');
                return;
            }

            try {
                const response = await fetch(`/favorites/toggle/${businessId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok) {
                    updateHeartIcons(businessId, data.favorited);
                    showToast(data.favorited ? "{{ __('common.messages.added_to_favorites') }}" : "{{ __('common.messages.removed_from_favorites') }}", data.favorited ? 'success' : 'info');
                } else {
                    showToast(data.message || 'Bir hata oluştu', 'error');
                }
            } catch (error) {
                console.error('Favorite toggle error:', error);
                showToast("{{ __('common.messages.connection_error') }}", 'error');
            }
        }

        function updateHeartIcons(businessId, isFavorited) {
            const allButtons = document.querySelectorAll(`button[onclick*="toggleFavorite(${businessId}"]`);
            allButtons.forEach(btn => {
                const svg = btn.querySelector('svg');
                if (!svg) return;

                if (isFavorited) {
                    svg.classList.add('fill-red-500', 'text-red-500');
                    svg.classList.remove('text-gray-400', 'text-gray-600');
                } else {
                    svg.classList.remove('fill-red-500', 'text-red-500');
                    svg.classList.add('text-gray-400', 'text-gray-600');
                }
            });
        }

        async function syncFavorites() {
            const isGuest = @json(auth()->guest());
            if (isGuest) return;

            const guestFavorites = getGuestFavorites();
            if (guestFavorites.length === 0) return;

            try {
                const response = await fetch('/favorites/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ business_ids: guestFavorites }),
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    localStorage.removeItem(FAVORITE_KEY);
                    console.log('Favorites synced successfully');
                    // Refresh view to show correct heart states if needed
                    location.reload(); 
                }
            } catch (error) {
                console.error('Sync favorites error:', error);
            }
        }

        // Initialize Favorites on Load
        document.addEventListener('DOMContentLoaded', () => {
            syncFavorites();
            
            // Apply localStorage states to icons if guest
            const isGuest = @json(auth()->guest());
            if (isGuest) {
                const favorites = getGuestFavorites();
                favorites.forEach(bid => updateHeartIcons(bid, true));
            }
        });

        // Geolocation Logic
        document.addEventListener('DOMContentLoaded', () => {
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }

            // If we don't have the location cookie, try to get it
            if (!getCookie('user_lat')) {
                // Respect user denial if we tracked it (optional, but good UX)
                // if (localStorage.getItem('user_location_denied')) return;

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Set Cookies
                            document.cookie = `user_lat=${lat}; path=/; max-age=86400`; // 24 hours
                            document.cookie = `user_lng=${lng}; path=/; max-age=86400`;
                            
                            showToast("{{ __('common.messages.location_updated') }}", 'success');

                            // Reload if on search page or home to apply immediately
                            if (window.location.pathname === '/search' || window.location.pathname === '/') {
                                const url = new URL(window.location.href);
                                url.searchParams.set('lat', lat);
                                url.searchParams.set('lng', lng);
                                if (!url.searchParams.has('sort')) {
                                    url.searchParams.set('sort', 'nearest');
                                }
                                window.location.href = url.toString();
                            }
                        },
                        (error) => {
                            console.log('Location denied or error:', error);
                            // localStorage.setItem('user_location_denied', 'true'); 
                        }
                    );
                }
            }
        });

        // Show session flash messages as toasts
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif

        // Real-time Notification Listener
        @auth
            window.addEventListener('DOMContentLoaded', () => {
                if (window.Echo) {
                    console.log('Echo initialized, joining notification channel...');
                    
                    // General Notifications (Database + Broadcast)
                    window.Echo.private(`App.Models.User.{{ Auth::id() }}`)
                        .notification((notification) => {
                            console.log('Notification received:', notification);
                            
                            // 1. Show Toast
                            showToast(notification.message || "{{ __('common.notifications.default_message') }}", 'info');
                            
                            // 2. Play subtle sound (optional, but let's keep it clean for now)
                            
                            // 3. Update Notification Icon Count (Dynamic Update)
                            const countElements = document.querySelectorAll('.notification-count');
                            countElements.forEach(el => {
                                let count = parseInt(el.innerText || '0');
                                el.innerText = count + 1;
                                el.style.display = 'flex';
                                
                                // Simple animation
                                el.classList.add('scale-125');
                                setTimeout(() => el.classList.remove('scale-125'), 300);
                            });
                        });
                        
                    // Dashboard Specific Events (For Admins)
                    @if(Auth::user()->role === 'admin')
                        window.Echo.private('admin.dashboard')
                            .listen('ReservationCreatedBroadcast', (e) => {
                                showToast(e.message, 'success');
                                // Refresh dashboard stats if on dashboard page
                                if (window.location.pathname === '/admin') {
                                    // Trigger a soft refresh or reload
                                    // window.location.reload(); 
                                }
                            });
                    @endif

                    @if(Auth::user()->role === 'business' && Auth::user()->ownedBusiness)
                        window.Echo.private('business.{{ Auth::user()->ownedBusiness->id }}')
                            .listen('ReservationCreated', (e) => {
                                console.log('Business Notification:', e);
                                showToast(e.message || 'Yeni rezervasyon!', 'success');
                                
                                // Refresh dashboard if active
                                if (window.location.pathname.includes('/dashboard')) {
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                            });
                    @endif
                }
            });
        @endauth
    </script>
    @auth
        @if(Auth::user()->role === 'business')
        <!-- Vendor Global Search Modal (Command Palette) -->
        <div x-data="{ 
                open: false, 
                query: '', 
                results: [], 
                loading: false,
                selectedIndex: -1,
                init() {
                    window.addEventListener('toggle-vendor-search', () => {
                        this.open = !this.open;
                        if(this.open) {
                            setTimeout(() => this.$refs.searchInput.focus(), 100);
                        }
                    });
                    window.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.open = true;
                            setTimeout(() => this.$refs.searchInput.focus(), 100);
                        }
                        if (e.key === 'Escape') this.open = false;
                    });
                },
                async performSearch() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.loading = true;
                    try {
                        const response = await fetch(`/vendor/search?q=${encodeURIComponent(this.query)}`);
                        this.results = await response.json();
                    } catch (e) {
                        console.error('Search failed', e);
                    } finally {
                        this.loading = false;
                        this.selectedIndex = -1;
                    }
                }
            }" 
            x-show="open" 
            x-cloak
            class="fixed inset-0 z-[1000] flex items-start justify-center pt-24 sm:pt-32 p-4 outline-none"
            @click.self="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            <!-- Modal Content -->
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <!-- Search Box -->
                <div class="relative p-6 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input x-ref="searchInput" 
                               type="text" 
                               x-model="query" 
                               @input.debounce.300ms="performSearch"
                               @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
                               @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
                               @keydown.enter.prevent="if(selectedIndex >= 0) window.location.href = results[selectedIndex].url"
                               placeholder="{{ __('common.search_modal.placeholder') }}" 
                               class="w-full text-lg font-bold text-gray-900 placeholder:text-gray-400 border-none focus:ring-0 outline-none">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">ESC</span>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div class="max-h-[60vh] overflow-y-auto p-4 custom-scrollbar">
                    <template x-if="loading">
                        <div class="flex flex-col items-center justify-center py-12 gap-4">
                            <div class="w-10 h-10 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('common.search_modal.loading') }}</p>
                        </div>
                    </template>

                    <template x-if="!loading && results.length > 0">
                        <div class="space-y-1">
                            <template x-for="(item, index) in results" :key="index">
                                <a :href="item.url" 
                                   class="flex items-center justify-between p-4 rounded-2xl transition hover:bg-gray-50 border border-transparent"
                                   :class="{'bg-primary/5 border-primary/10': selectedIndex === index}"
                                   @mouseenter="selectedIndex = index">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                                             :class="{
                                                'bg-indigo-50 text-indigo-600': item.type === 'Rezervasyon',
                                                'bg-emerald-50 text-emerald-600': item.type === 'Personel',
                                                'bg-orange-50 text-orange-600': item.type === 'Varlık / Masa'
                                             }">
                                            <i :class="'fas fa-' + item.icon + ' text-xl'"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest" x-text="item.type"></p>
                                            <h4 class="text-base font-black text-gray-900 leading-tight" x-text="item.title"></h4>
                                            <p class="text-xs text-gray-500 font-medium" x-text="item.subtitle"></p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-300 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </template>
                        </div>
                    </template>

                    <template x-if="!loading && query.length >= 2 && results.length === 0">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('common.search_modal.no_results') }}</h3>
                            <p class="text-sm text-gray-500">{!! __('common.search_modal.no_results_desc', ['query' => '<span x-text="query" class="font-bold"></span>']) !!}</p>
                        </div>
                    </template>

                    <template x-if="!loading && query.length < 2">
                        <div class="py-12 px-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-white hover:border-primary/20 transition cursor-pointer" @click="query = 'Rezervasyon'; performSearch()">
                                    <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                     <p class="text-sm font-black text-gray-900">{{ __('common.menu.reservations') }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('common.search_modal.search_by_id_name') }}</p>
                                </div>
                                <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-white hover:border-primary/20 transition cursor-pointer" @click="query = 'Personel'; performSearch()">
                                    <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <p class="text-sm font-black text-gray-900">{{ __('common.search_modal.team_members') }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('common.search_modal.search_by_name') }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-center gap-6">
                    <div class="flex items-center gap-1.5 opacity-60">
                        <span class="text-[10px] font-black text-gray-400 border border-gray-300 px-1 py-0.5 rounded leading-none">ENTER</span>
                        <span class="text-[10px] font-bold text-gray-500 tracking-tight">{{ __('common.search_modal.go') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 opacity-60">
                         <span class="text-[10px] font-black text-gray-400 border border-gray-300 px-1 py-0.5 rounded leading-none">↑↓</span>
                        <span class="text-[10px] font-bold text-gray-500 tracking-tight">{{ __('common.search_modal.navigate') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth
            
    @include('partials.cookie-consent')

    @yield('scripts')    
    <!-- Page Loader Script -->
    <script>
        // Hide loader when page is fully loaded or after timeout
        const hideLoader = () => {
            const loader = document.getElementById('page-loader');
            if (loader && !loader.classList.contains('fade-out')) {
                loader.classList.add('fade-out');
                setTimeout(() => { loader.style.display = 'none'; }, 500);
            }
        };

        window.addEventListener('load', () => setTimeout(hideLoader, 300));
        
        // Safety Fallback: Force hide after 5 seconds max
        setTimeout(hideLoader, 5000);
    </script>

    <!-- macOS style Swipe-to-Navigate System -->
    <div x-data="gestureNavigation()" x-init="init()" class="fixed inset-0 pointer-events-none z-[9999]">
        <!-- Left Indicator (Back) -->
        <div x-show="swipeX > 20" 
             class="fixed left-0 top-1/2 -translate-y-1/2 flex items-center justify-center transition-all duration-75"
             :style="`transform: translate(${(swipeX/2) - 100}%, -50%); opacity: ${Math.min(swipeX/150, 1)}`">
            <div class="w-16 h-16 bg-white/90 backdrop-blur-xl rounded-full shadow-2xl border border-gray-100 flex items-center justify-center text-gray-900 overflow-hidden relative">
                <div class="absolute inset-0 bg-indigo-600 transition-all duration-300" :style="`opacity: ${swipeX >= 150 ? 1 : 0}`"></div>
                <i class="fas fa-arrow-left text-xl relative z-10 transition-colors duration-300" :class="swipeX >= 150 ? 'text-white' : 'text-gray-900'"></i>
            </div>
        </div>

        <!-- Right Indicator (Forward) -->
        <div x-show="swipeX < -20" 
             class="fixed right-0 top-1/2 -translate-y-1/2 flex items-center justify-center transition-all duration-75"
             :style="`transform: translate(${-(swipeX/2) + 100}%, -50%); opacity: ${Math.min(-swipeX/150, 1)}`">
            <div class="w-16 h-16 bg-white/90 backdrop-blur-xl rounded-full shadow-2xl border border-gray-100 flex items-center justify-center text-gray-900 overflow-hidden relative">
                <div class="absolute inset-0 bg-indigo-600 transition-all duration-300" :style="`opacity: ${-swipeX >= 150 ? 1 : 0}`"></div>
                <i class="fas fa-arrow-right text-xl relative z-10 transition-colors duration-300" :class="-swipeX >= 150 ? 'text-white' : 'text-gray-900'"></i>
            </div>
        </div>
    </div>

    <script>
        function gestureNavigation() {
            return {
                swipeX: 0,
                startX: 0,
                startY: 0,
                isDragging: false,
                isNavigating: false,
                isWheelGesture: false,
                threshold: 150,
                wheelThreshold: 100,
                accumulatedX: 0,
                lastWheelTime: 0,
                
                init() {
                    // Mouse/Touch Dragging
                    window.addEventListener('mousedown', (e) => {
                        if (e.target.closest('button, a, input, select, textarea, [role="button"], .apexcharts-canvas')) return;
                        this.isDragging = true;
                        this.startX = e.clientX;
                        this.swipeX = 0;
                        this.isWheelGesture = false;
                    });

                    window.addEventListener('mousemove', (e) => {
                        if (!this.isDragging || this.isNavigating || this.isWheelGesture) return;
                        this.swipeX = e.clientX - this.startX;
                    });

                    window.addEventListener('mouseup', () => {
                        if (!this.isDragging || this.isWheelGesture) return;
                        this.handleEnd();
                    });

                    // Trackpad / Mouse Wheel (Modern Mac/Windows Gestures)
                    window.addEventListener('wheel', (e) => {
                        // Detect horizontal movement from wheel/trackpad
                        if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
                            // If user is swiping horizontally on a trackpad
                            this.isWheelGesture = true;
                            this.isDragging = true;
                            
                            // Accumulate deltaX
                            this.accumulatedX -= e.deltaX; // deltaX is positive for right swipe on trackpad (which is content moving left)
                            this.swipeX = this.accumulatedX;
                            
                            this.lastWheelTime = Date.now();
                            
                            // Check threshold
                            if (Math.abs(this.swipeX) >= this.threshold && !this.isNavigating) {
                                this.triggerNavigation();
                            }

                            // Auto-reset accumulatedX after a delay if no more wheel events
                            clearTimeout(this.wheelResetTimeout);
                            this.wheelResetTimeout = setTimeout(() => {
                                if (!this.isNavigating) {
                                    this.swipeX = 0;
                                    this.accumulatedX = 0;
                                    this.isDragging = false;
                                    this.isWheelGesture = false;
                                }
                            }, 200);
                        }
                    }, { passive: true });

                    // Mobile support
                    window.addEventListener('touchstart', (e) => {
                        if (e.target.closest('button, a, input, select, textarea, [role="button"], .apexcharts-canvas')) return;
                        this.startX = e.touches[0].clientX;
                        this.startY = e.touches[0].clientY;
                        this.isDragging = true;
                        this.isWheelGesture = false;
                    });

                    window.addEventListener('touchmove', (e) => {
                        if (!this.isDragging) return;
                        let deltaX = e.touches[0].clientX - this.startX;
                        let deltaY = e.touches[0].clientY - this.startY;
                        
                        // If horizontal move is clearly dominant
                        if (Math.abs(deltaX) > Math.abs(deltaY) * 1.5) {
                            this.swipeX = deltaX;
                        }
                    });

                    window.addEventListener('touchend', () => {
                        if (!this.isDragging) return;
                        this.handleEnd();
                    });
                },

                handleEnd() {
                    if (Math.abs(this.swipeX) >= this.threshold) {
                        this.triggerNavigation();
                    }
                    this.isDragging = false;
                    setTimeout(() => { if(!this.isNavigating) this.swipeX = 0; }, 50);
                },

                triggerNavigation() {
                    if (this.isNavigating) return;
                    this.isNavigating = true;
                    
                    if (this.swipeX > 0) {
                        history.back();
                    } else {
                        history.forward();
                    }
                    
                    // Reset safety
                    setTimeout(() => { 
                        this.isNavigating = false; 
                        this.swipeX = 0;
                        this.accumulatedX = 0;
                    }, 500);
                }
            }
        }
    </script>
</body>
</html>
