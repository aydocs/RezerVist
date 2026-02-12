<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'RezerVistA POS Terminal')</title>
    <meta name="description" content="@yield('meta_description', 'RezerVistA POS - Yeni Nesil Restoran Yönetim Sistemi')">
    <meta name="keywords" content="@yield('meta_keywords', 'pos, restoran pos, adisyon sistemi')">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6200EE',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @yield('structured_data')
</head>
<body class="antialiased font-sans bg-white">
    
    <!-- Simple Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary/30">
                        R
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tighter">Rezervist</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-bold text-slate-500 hover:text-primary transition">Ana Sayfa</a>
                    <a href="/business-partner" class="text-sm font-bold text-slate-500 hover:text-primary transition">İşletme</a>
                    <a href="/login" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary transition shadow-lg shadow-slate-900/10">Giriş Yap</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} Rezervist. Tüm hakları saklıdır.
            </p>
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>
