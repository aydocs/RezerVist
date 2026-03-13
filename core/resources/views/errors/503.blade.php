<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bakım Modu - RezerVist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen px-4">
    <div class="text-center max-w-lg">
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl transform rotate-3">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Kısa Bir Mola Veriyoruz</h1>
        <p class="text-gray-600 text-lg mb-8">RezerVist şu anda planlı bakım çalışması nedeniyle hizmet verememektedir. Size daha iyi hizmet sunabilmek için sistemlerimizi güncelliyoruz. Lütfen kısa bir süre sonra tekrar deneyin.</p>
        
        <div class="inline-flex items-center text-sm text-gray-400">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tahmini Dönüş Süresi: 30 Dakika
        </div>
        
        <div class="mt-12 text-sm text-gray-400">
            &copy; {{ date('Y') }} RezerVist. Tüm hakları saklıdır.
        </div>
    </div>
</body>
</html>
