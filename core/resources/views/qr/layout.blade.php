<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - {{ $business->name ?? ($globalSettings['site_name'] ?? config('app.name')) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#7c3aed',
                        secondary: '#4b5563',
                        surface: '#ffffff',
                        background: '#f9fafb',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px #7c3aed1A',
                        'glow': '0 0 15px #7c3aed4D',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(124, 58, 237, 0.1);
        }
    </style>
</head>
<body class="bg-background text-slate-900 pb-24 antialiased selection:bg-primary selection:text-white">

    <!-- Premium Navbar -->
    <nav class="fixed top-0 left-0 right-0 glass-nav z-50 transition-all duration-300">
        <div class="max-w-screen-xl mx-auto px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($business->logo)
                    <img src="{{ asset('storage/' . $business->logo) }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-primary/20 shadow-sm">
                @else
                    <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        {{ substr($business->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex flex-col">
                    <h1 class="text-sm font-black text-gray-900 leading-tight tracking-tight">{{ Str::limit($business->name, 20) }}</h1>
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        <p class="text-[10px] text-primary font-bold uppercase tracking-wider">{{ $resource->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('home') }}" class="group flex items-center gap-2 pr-3 bg-white border border-gray-100 rounded-full hover:shadow-lg hover:border-primary/20 transition-all active:scale-95 shadow-sm overflow-hidden">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-10 w-10 object-cover ring-2 ring-white group-hover:ring-primary/10 transition-all">
                        @else
                            <div class="h-10 w-10 bg-primary text-white flex items-center justify-center font-black text-xs ring-2 ring-white">
                                {{ Auth::user()->initials }}
                            </div>
                        @endif
                        <span class="text-[11px] font-black text-gray-500 group-hover:text-primary transition-colors lowercase hidden sm:block">{{ $globalSettings['site_name'] ?? config('app.name') }}</span>
                    </a>
                @endauth

                <a href="{{ route('qr.bill', ['payload' => $payload]) }}" class="relative group">
                    <div class="absolute inset-0 bg-primary rounded-xl blur opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative bg-white border border-primary/10 p-2.5 rounded-xl text-primary active:scale-95 transition-transform flex items-center gap-2 shadow-sm">
                        <span class="hidden md:block text-xs font-bold">Adisyon</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Spacer -->
    <div class="h-[76px]"></div>

    <div class="max-w-screen-xl mx-auto w-full px-5 pt-4">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                    <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-blue-500 text-lg"></i>
                    <p class="text-sm font-bold text-blue-800">{{ session('info') }}</p>
                </div>
                <button @click="show = false" class="text-blue-400 hover:text-blue-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <main class="max-w-screen-xl mx-auto w-full">
        @yield('content')
    </main>

    <!-- Floating Branding -->
    <div class="fixed bottom-6 left-0 right-0 flex justify-center z-40 pointer-events-none">
        <div class="bg-white/90 backdrop-blur-md text-gray-800 border border-primary/10 px-4 py-2 rounded-full shadow-soft flex items-center gap-2 pointer-events-auto transform hover:scale-105 transition-transform">
            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Powered by</span>
            <span class="font-black text-xs tracking-tight text-primary">{{ $globalSettings['site_name'] ?? config('app.name') }}</span>
        </div>
    </div>

</body>
</html>
