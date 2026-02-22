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
        <!-- Top Info Bar -->
        <div class="bg-gradient-to-r from-primary to-purple-700 text-white py-2">
            <div class="w-full px-6 lg:px-12">
                <div class="flex flex-wrap items-center justify-between text-sm">
                    <div class="flex items-center space-x-6">
                        <a href="tel:{{ str_replace(' ', '', $globalSettings['contact_phone']) }}" class="flex items-center hover:text-secondary transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $globalSettings['contact_phone'] }}
                        </a>
                        <a href="mailto:{{ $globalSettings['contact_email'] }}" class="hidden sm:flex items-center hover:text-secondary transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $globalSettings['contact_email'] }}
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium tracking-tight">{{ __('common.header.support') }}</span>
                    </div>
                        <div class="flex items-center space-x-2">
                            @if(!empty($globalSettings['social_facebook']))
                            <a href="{{ $globalSettings['social_facebook'] }}" target="_blank" aria-label="Facebook" class="hover:text-secondary transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif
                            @if(!empty($globalSettings['social_twitter']))
                            <a href="{{ $globalSettings['social_twitter'] }}" target="_blank" aria-label="Twitter" class="hover:text-secondary transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            @endif
                            @if(!empty($globalSettings['social_instagram']))
                            <a href="{{ $globalSettings['social_instagram'] }}" target="_blank" aria-label="Instagram" class="hover:text-secondary transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                            </a>
                            @endif
                            @if(!empty($globalSettings['social_whatsapp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $globalSettings['social_whatsapp']) }}" target="_blank" aria-label="WhatsApp" class="hover:text-secondary transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.067 2.875 1.215 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 7.454c-1.679 0-3.325-.43-4.788-1.241l-.344-.194-3.561.934.951-3.472-.213-.339C3.856 16.037 3.037 14.056 3.037 12c0-4.941 4.019-8.96 8.962-8.96 4.941 0 8.96 4.019 8.96 8.96 0 4.944-4.019 8.962-8.96 8.962m12.177-12.199C24.828 4.349 20.4 0 12 0 3.6 0 0 3.6 0 12c0 2.686.726 5.304 2.102 7.575L0 24l4.525-1.187C6.737 24.211 9.333 25 12 25c8.4 0 12.828-4.349 12.828-12.199 0-3.21-1.249-6.228-3.516-8.496"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navbar -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-[200] transition-all duration-300" x-data="{ mobileMenu: false, searchOpen: false, notificationsOpen: false }">
            <div class="w-full px-6 lg:px-12">
                <div class="flex justify-between lg:grid lg:grid-cols-[1fr_auto_1fr] h-20 items-center gap-4">

                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center gap-2 group">
                             <div class="w-10 h-10 bg-gradient-to-br from-primary to-purple-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary/30 transition duration-300">{{ substr($globalSettings['site_name'], 0, 1) }}</div>
                             <span class="text-2xl font-bold text-gray-900 tracking-tight group-hover:text-primary transition duration-300">{{ $globalSettings['site_name'] }}</span>
                        </a>
                    </div>

                    <!-- Desktop Menu (Center) -->
                    <div class="hidden lg:flex items-center justify-center gap-8 h-full">
                        <!-- Keşfet Linki -->
                        <div class="h-full flex items-center">
                            <a href="/search" class="h-10 px-4 text-gray-700 hover:text-primary transition font-bold flex items-center justify-center text-sm outline-none tracking-wide text-center {{ request()->is('search*') ? 'text-primary' : '' }}">
                                Keşfet
                            </a>
                        </div>
                        
                        <!-- Ürünlerimiz Linki (POS) -->
                        <div class="h-full flex items-center">
                            <a href="{{ route('pages.pos') }}" class="h-10 px-4 text-gray-700 hover:text-primary transition font-bold flex items-center justify-center text-sm outline-none tracking-wide text-center">
                                Ürünlerimiz
                            </a>
                        </div>
                        <!-- Blog Link -->
                        <div class="h-full flex items-center">
                            <a href="{{ route('blog.index') }}" class="h-10 px-4 text-gray-700 hover:text-primary transition font-bold flex items-center justify-center text-sm outline-none tracking-wide text-center {{ request()->routeIs('blog.*') ? 'text-primary' : '' }}">
                                {{ __('common.menu.blog') }}
                            </a>
                        </div>
                        <!-- Hakkımızda Link -->
                        <div class="h-full flex items-center">
                            <a href="{{ route('pages.about') }}" class="h-10 px-4 text-gray-700 hover:text-primary transition font-bold flex items-center justify-center text-sm outline-none tracking-wide text-center {{ request()->routeIs('pages.about') ? 'text-primary' : '' }}">
                                {{ __('common.footer.about_us') ?? 'Hakkımızda' }}
                            </a>
                        </div>
                        <!-- İletişim Link -->
                        <div class="h-full flex items-center">
                            <a href="{{ route('pages.contact') }}" class="h-10 px-4 text-gray-700 hover:text-primary transition font-bold flex items-center justify-center text-sm outline-none tracking-wide text-center {{ request()->routeIs('pages.contact') ? 'text-primary' : '' }}">
                                İletişim
                            </a>
                        </div>
                    </div>

                <!-- Auth Buttons + Mobile Menu (Right Side) -->
                <div class="flex items-center justify-end gap-5 justify-self-end">
                        <!-- Desktop Auth Buttons -->
                        <div class="hidden lg:flex items-center gap-6">


                            @auth
                            <!-- Vendor Search Trigger -->
                            @if(Auth::user()->role === 'business')
                            <button @click="window.dispatchEvent(new CustomEvent('toggle-vendor-search'))" class="text-gray-400 hover:text-primary transition p-2 bg-gray-50 rounded-xl hidden xl:flex items-center gap-3 border border-gray-100 group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <span class="text-xs font-bold text-gray-500 group-hover:text-primary transition">Ara...</span>
                                <span class="text-[10px] font-black text-gray-300 border border-gray-200 px-1.5 py-0.5 rounded-lg group-hover:border-primary/20 transition whitespace-nowrap">Ctrl K</span>
                            </button>
                            @endif

                            <!-- Notification Bell -->
                            <div class="relative">
                                <button @click="notificationsOpen = !notificationsOpen; mobileMenu = false; searchOpen = false" class="text-gray-400 hover:text-primary transition rounded-full relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    @php
                                        $unreadCount = Auth::user()->unreadNotifications->count();
                                    @endphp
                                    @if($unreadCount > 0)
                            <span class="notification-count absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white">{{ $unreadCount }}</span>
                        @else
                            <span class="notification-count absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center border-2 border-white" style="display: none;">0</span>
                        @endif
                                </button>

                                <!-- Desktop Notification Dropdown -->
                                <div x-show="notificationsOpen" @click.away="notificationsOpen = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="hidden lg:block absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[250] origin-top-right overflow-hidden ring-1 ring-black ring-opacity-5 max-h-[80vh] overflow-y-auto custom-scrollbar">
                                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                        <h3 class="font-bold text-gray-900 text-sm">{{ __('common.notifications.title') }}</h3>
                                        <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $unreadCount }} {{ __('common.notifications.new') }}</span>
                                    </div>
                                    <div class="max-h-[400px] overflow-y-auto">
                                        @forelse(Auth::user()->notifications->take(5) as $notification)
                                            <a href="{{ route('notifications.markRead', $notification->id) }}" class="block px-4 py-4 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : '' }}">
                                                <div class="flex gap-3">
                                                    @if(isset($notification->data['type']) && $notification->data['type'] === 'business_application')
                                                        <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-primary/10' }} flex items-center justify-center flex-shrink-0">
                                                            @if(($notification->data['icon'] ?? '') === 'check-circle')
                                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @elseif(($notification->data['icon'] ?? '') === 'x-circle')
                                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @elseif(($notification->data['icon'] ?? '') === 'clock')
                                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-primary/10' }} flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-5 h-5 {{ $notification->read_at ? 'text-gray-400' : 'text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm text-gray-900 leading-snug">{!! $notification->data['message'] ?? 'Yeni bildiriminiz var' !!}</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                                </div>
                                                <p class="text-sm text-gray-500 font-medium">{{ __('common.notifications.empty') }}.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 flex items-center justify-between border-t border-gray-100">
                                        <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-gray-500 hover:text-primary transition">{{ __('common.menu.view_all') }}</a>
                                        @if($unreadCount > 0)
                                            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs font-bold text-primary hover:underline">{{ __('common.notifications.mark_all_read') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endauth

                        @auth
                            <div class="relative hidden lg:block" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2.5 pl-1.5 pr-4 py-1.5 border border-gray-100 rounded-full hover:shadow-lg hover:border-gray-200 transition bg-white group">
                                    @if(Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-9 h-9 rounded-full object-cover border-2 border-primary/10">
                                    @else
                                        <div class="w-9 h-9 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs ring-2 ring-white shadow-inner">
                                            {{ Auth::user()->initials }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-600 text-[13px] hidden lg:block group-hover:text-primary transition lowercase">{{ \Illuminate\Support\Str::limit(Auth::user()->name, 15) }}</span>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-primary transition" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-[250] origin-top-right ring-1 ring-black ring-opacity-5 focus:outline-none max-h-[80vh] overflow-y-auto custom-scrollbar">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">{{ __('common.menu.logged_in_as') }}</p>
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-primary uppercase font-bold tracking-wider mt-0.5">{{ Auth::user()->role === 'business' ? __('common.roles.business') : (Auth::user()->role === 'admin' ? __('common.roles.admin') : __('common.roles.customer')) }}</p>
                                    </div>

                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ __('common.menu.profile_info') }}
                                    </a>

                                    <a href="{{ route('profile.reservations') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                        {{ __('common.menu.my_reservations') }}
                                    </a>

                                    <a href="{{ route('profile.favorites') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        {{ __('common.menu.my_favorites') }}
                                    </a>

                                    <a href="{{ route('profile.wallet.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ __('common.menu.wallet') }}
                                    </a>

                                    <a href="{{ route('messages.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                            {{ __('common.menu.messages') }}
                                        </div>
                                        @if(Auth::user()->unread_messages_count > 0)
                                            <span class="bg-rose-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ Auth::user()->unread_messages_count }}</span>
                                        @endif
                                    </a>

                                    <a href="{{ route('profile.support') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ __('common.menu.support') }}
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <div class="px-4 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('common.roles.admin') }}</div>
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            {{ __('common.menu.admin_panel') }}
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            {{ __('common.menu.user_management') }}
                                        </a>
                                        <a href="{{ route('admin.applications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            {{ __('common.menu.business_applications') }}
                                        </a>
                                        <a href="{{ route('admin.contact-messages.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ __('common.admin.dashboard.contact_messages') }}
                                        </a>
                                        <a href="{{ route('admin.platform-activity.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('common.admin.dashboard.platform_activity') }}
                                        </a>
                                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ __('common.menu.settings') }}
                                        </a>
                                    @elseif(Auth::user()->role === 'business')
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <div class="px-4 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('common.menu.business_management') }}</div>
                                        <a href="{{ route('vendor.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            {{ __('common.menu.business_panel') }}
                                        </a>
                                        <a href="{{ route('vendor.business.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            {{ __('common.menu.business_info') }}
                                        </a>
                                        <a href="{{ route('vendor.reservations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('common.menu.reservations') }}
                                        </a>
                                        <a href="{{ route('vendor.menus.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                                            {{ __('common.menu.menus_services') }}
                                        </a>
                                        <a href="{{ route('vendor.staff.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            {{ __('common.menu.staff') }}
                                        </a>
                                        <a href="{{ route('vendor.resources.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            {{ __('common.menu.resources') }}
                                        </a>
                                        <a href="{{ route('vendor.finance.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            {{ __('common.menu.financial_reports') }}
                                        </a>
                                        <a href="{{ route('vendor.billing.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ __('common.menu.plans') }}
                                        </a>
                                    @endif

                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            {{ __('common.menu.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Auth Buttons -->
                            <div class="hidden lg:flex items-center gap-6">
                                <!-- Business Application Button - Outline Style -->
                                <a href="/business-partner" class="flex items-center px-4 py-2 border-2 border-primary text-primary rounded-lg font-semibold text-sm hover:bg-primary hover:text-white transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ __('common.header.business_partner') }}
                                </a>
                                
                                <div class="flex items-center gap-3">
                                    <a href="/login" class="px-5 py-2.5 border-2 border-blue-600 text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition text-sm">
                                        {{ __('common.menu.login') }}
                                    </a>
                                    <a href="/register" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 text-sm">
                                        {{ __('common.menu.register') }}
                                    </a>
                                </div>
                            </div>
                        @endauth
                        </div>

                        <!-- Mobile Action Buttons (Integrated) -->
                        <div class="lg:hidden flex items-center gap-2">
                            <!-- Language Switcher (Mobile) -->

                            @auth
                            <!-- Mobile Notification Button -->
                            <button 
                                @click="notificationsOpen = !notificationsOpen; mobileMenu = false" 
                                class="relative p-2.5 bg-gray-50 rounded-xl border border-gray-200 hover:bg-white hover:border-primary/30 hover:shadow-md transition-all group"
                                aria-label="Toggle notifications"
                            >
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm border-2 border-white">{{ $unreadCount }}</span>
                                @endif
                            </button>
                            @endauth
                            
                            <!-- Mobile Menu Button -->
                            <button 
                                @click="mobileMenu = !mobileMenu; notificationsOpen = false" 
                                class="p-2.5 bg-gray-50 rounded-xl border border-gray-200 hover:bg-white hover:border-primary/30 hover:shadow-md transition-all group"
                                aria-label="Toggle mobile menu"
                            >
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenu">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenu" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
                

            <!-- Mobile Notification Panel -->
            @auth
            <div x-show="notificationsOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 @click.away="notificationsOpen = false"
                 class="lg:hidden bg-white border-t border-gray-100 absolute top-full left-0 w-full z-[200] shadow-lg h-[calc(100vh-80px)] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-base">{{ __('common.notifications.title') }}</h3>
                    <span class="text-[11px] font-bold text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-wider">{{ $unreadCount }} {{ __('common.notifications.new') }}</span>
                </div>
                <div class="max-h-[80vh] min-h-[400px] overflow-y-auto">
                    @forelse(Auth::user()->notifications->take(5) as $notification)
                        <a href="{{ route('notifications.markRead', $notification->id) }}" class="block px-4 py-4 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-primary/10' }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 {{ $notification->read_at ? 'text-gray-400' : 'text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900 leading-snug">{!! $notification->data['message'] ?? __('common.notifications.default_message') !!}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">{{ __('common.notifications.empty') }}.</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-4 py-3 bg-gray-50 flex items-center justify-between border-t border-gray-100 mb-6">
                    <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-gray-500 hover:text-primary transition">{{ __('common.menu.view_all') }}</a>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-primary hover:underline">{{ __('common.notifications.mark_all_read') }}</button>
                        </form>
                    @endif
                </div>
            </div>

            @endauth

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="lg:hidden bg-white border-t border-gray-100 absolute top-full left-0 w-full z-[200] shadow-lg h-[calc(100vh-80px)] overflow-y-auto">
                <div class="px-4 py-4 space-y-1">
                     <!-- Mobile Search -->
                    <div class="mb-4">
                        <form action="/search" method="GET" class="relative">
                            <input type="text" name="search" placeholder="{{ __('common.search_placeholder') }}" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </form>
                    </div>
 
                    <a href="/" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->is('/') ? 'bg-primary/5 text-primary' : '' }}">
                        {{ __('common.menu.home') }}
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </a>
 
                    <!-- Keşfet Mobile Link -->
                    <a href="/search" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->is('search*') ? 'bg-primary/5 text-primary' : '' }}">
                        Keşfet
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </a>

                    <!-- Ürünlerimiz Mobile Link -->
                    <a href="{{ route('pages.pos') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group">
                        Ürünlerimiz
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </a>
                    <a href="/business-partner" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->is('business-partner') ? 'bg-primary/5 text-primary' : '' }}">
                        {{ __('common.header.business_partner') }}
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </a>

                    <a href="{{ route('blog.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->routeIs('blog.*') ? 'bg-primary/5 text-primary' : '' }}">
                        {{ __('Blog') }}
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5"></path></svg>
                    </a>
                    
                    <a href="{{ route('pages.about') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->routeIs('pages.about') ? 'bg-primary/5 text-primary' : '' }}">
                        {{ __('common.footer.about_us') ?? 'Hakkımızda' }}
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                    
                    <a href="{{ route('pages.contact') }}" class="block px-4 py-3 rounded-xl text-base font-bold text-gray-900 hover:bg-gray-50 flex items-center justify-between group {{ request()->routeIs('pages.contact') ? 'bg-primary/5 text-primary' : '' }}">
                        İletişim
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </a>

                    @auth
                        <div class="pt-4 mt-4 border-t border-gray-100">
                            <!-- Profile Header with Avatar -->
                            <div class="mx-4 mb-4 p-4 bg-gradient-to-br from-primary/10 to-purple-600/10 rounded-2xl border border-primary/20">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-purple-700 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 mb-0.5">{{ __('home.hero.welcome') }},</p>
                                        <p class="text-base font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Profile Menu Items -->
                            <div class="px-4 space-y-1">
                                <a href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.profile_info') }}</span>
                                </a>
                                <a href="{{ route('profile.reservations') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.my_reservations') }}</span>
                                </a>
                                <a href="{{ route('profile.favorites') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.my_favorites') }}</span>
                                </a>
                                <a href="{{ route('profile.wallet.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.wallet') }}</span>
                                </a>
                                <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.messages') }}</span>
                                </a>
                                <a href="{{ route('profile.support') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <span>{{ __('common.menu.support') }}</span>
                                </a>
                            </div>

                            @if(Auth::user()->role === 'admin')
                                <!-- Admin Section -->
                                <div class="px-8 mt-6 mb-2">
                                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3">{{ __('common.roles.admin') }}</p>
                                </div>
                                <div class="px-4 space-y-1">
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.admin_panel') }}</span>
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.user_management') }}</span>
                                    </a>
                                    <a href="{{ route('admin.applications.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.business_applications') }}</span>
                                    </a>
                                    <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span>{{ __('common.admin.dashboard.contact_messages') }}</span>
                                    </a>
                                    <a href="{{ route('admin.platform-activity.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span>{{ __('common.admin.dashboard.platform_activity') }}</span>
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.settings') }}</span>
                                    </a>
                                </div>
                            @elseif(Auth::user()->role === 'business')
                                <!-- Business Section -->
                                <div class="px-8 mt-6 mb-2">
                                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3">{{ __('common.menu.business_management') }}</p>
                                </div>
                                <div class="px-4 space-y-1">
                                    <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.business_panel') }}</span>
                                    </a>
                                    <a href="{{ route('vendor.business.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.business_info') }}</span>
                                    </a>
                                    <a href="{{ route('vendor.reservations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.reservations') }}</span>
                                    </a>
                                    <a href="{{ route('vendor.menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.menus_services') }}</span>
                                    </a>
                                    <a href="{{ route('vendor.finance.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group font-semibold">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.financial_reports') }}</span>
                                    </a>
                                    <a href="{{ route('vendor.billing.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-primary/5 hover:text-primary transition-all group font-semibold">
                                        <div class="w-9 h-9 bg-gray-100 group-hover:bg-primary/10 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span>{{ __('common.menu.plans') }}</span>
                                    </a>
                                </div>
                            @endif

                            <form method="POST" action="/logout" class="block pt-2">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-red-600 font-bold hover:bg-red-50 rounded-xl transition flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('common.menu.logout') }}
                                </button>
                            </form>

                            <!-- PWA Install Button (Hidden by default) -->
                            <div x-data="{ showInstall: false }" 
                                 x-init="window.addEventListener('pwa-installable', () => showInstall = true)"
                                 x-show="showInstall"
                                 class="mt-4 px-4 pb-4">
                                <button @click="if(window.deferredPrompt) { window.deferredPrompt.prompt(); window.deferredPrompt.userChoice.then((choiceResult) => { if(choiceResult.outcome === 'accepted') { showInstall = false; } window.deferredPrompt = null; }); }"
                                        class="w-full py-4 bg-gradient-to-r from-primary to-purple-700 text-white rounded-2xl font-bold shadow-lg shadow-primary/30 flex items-center justify-center gap-3 animate-pulse">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    {{ __('common.header.add_to_home_screen') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="pt-6 mt-4 border-t border-gray-100 flex flex-col gap-3">
                            <a href="/login" class="w-full text-center py-3 text-gray-900 font-bold border border-gray-200 rounded-xl hover:bg-gray-50">{{ __('common.menu.login') }}</a>
                            <a href="/register" class="w-full text-center py-3 bg-primary text-white rounded-xl font-bold shadow-lg shadow-primary/30">{{ __('common.menu.register') }}</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

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
                            <span class="text-base font-bold text-slate-900">{{ $globalSettings['contact_email'] ?? 'iletisim@rezervist.com' }}</span>
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
