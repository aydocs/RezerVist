<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme İşleniyor...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Force redirect after a short delay to ensure session cookie is set
        // Added cache buster to prevent browser from loading cached login page
        setTimeout(function() {
            window.location.href = "{{ route('profile.wallet.index') }}?t=" + new Date().getTime();
        }, 1500);
    </script>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-purple-600 mx-auto mb-6"></div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Ödemeniz İşleniyor</h2>
        <p class="text-gray-600">Lütfen bekleyin, cüzdanınıza yönlendiriliyorsunuz...</p>
        
        @if(config('app.debug'))
            <div class="mt-8 p-4 bg-gray-100 rounded text-xs text-left font-mono">
                <p>Status: <strong>{{ request()->query('status') }}</strong></p>
                <p>Auth: <strong>{{ Auth::check() ? 'LOGGED IN (' . Auth::id() . ')' : 'GUEST' }}</strong></p>
                <p>Session: {{ session()->getId() }}</p>
            </div>
        @endif
    </div>
</body>
</html>
