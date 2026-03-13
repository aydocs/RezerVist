<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rezervasyon Dekontu #{{ $reservation->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #6366f1; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #6366f1; }
        .business-info { text-align: right; }
        .details { margin-bottom: 30px; }
        .details h4 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; text-align: left; border-collapse: collapse; }
        th { background: #f9fafb; padding: 10px; border-bottom: 1px solid #eee; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { margin-top: 30px; text-align: right; }
        .total-section p { margin-bottom: 5px; }
        .grand-total { font-size: 18px; font-weight: bold; color: #6366f1; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="logo">{{ $globalSettings['site_name'] ?? config('app.name') }}</div>
            <div class="business-info">
                <strong>{{ $reservation->business->name }}</strong><br>
                {{ $reservation->business->address }}<br>
                {{ $reservation->business->phone }}
            </div>
        </div>

        <div class="details">
            <h4>Müşteri Bilgileri</h4>
            <p><strong>Ad Soyad:</strong> {{ $reservation->user->name }}</p>
            <p><strong>E-posta:</strong> {{ $reservation->user->email }}</p>
            <p><strong>Telefon:</strong> {{ $reservation->user->phone }}</p>
        </div>

        <div class="details">
            <h4>Rezervasyon Detayları</h4>
            <p><strong>Tarih:</strong> {{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y H:i') }}</p>
            <p><strong>Kişi Sayısı:</strong> {{ $reservation->guest_count }}</p>
            <p><strong>Durum:</strong> {{ ucfirst($reservation->status) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Hizmet / Menü</th>
                    <th>Fiyat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation->menus as $menu)
                <tr>
                    <td>{{ $menu->name }}</td>
                    <td>{{ number_format($menu->pivot->price, 2) }} TL</td>
                </tr>
                @endforeach
                @if($reservation->menus->isEmpty())
                <tr>
                    <td>Hizmet Bedeli</td>
                    <td>{{ number_format($reservation->price, 2) }} TL</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="total-section">
            <p>Ara Toplam: {{ number_format($reservation->price, 2) }} TL</p>
            
            @if($reservation->discount_amount > 0)
            <p>Kupon İndirimi: -{{ number_format($reservation->discount_amount, 2) }} TL</p>
            @endif

            @if($reservation->loyalty_discount > 0)
            <p>Para Puan: -{{ number_format($reservation->loyalty_discount, 2) }} TL</p>
            @endif

            <p class="grand-total">Genel Toplam: {{ number_format($reservation->total_amount ?? ($reservation->price - $reservation->discount_amount - $reservation->loyalty_discount), 2) }} TL</p>
        </div>

        <div style="margin-top: 50px; text-align: center; font-size: 11px; color: #777;">
            Bu belge mali değer taşımamaktadır. Rezervasyon teyidi amacıyla düzenlenmiştir.
        </div>
    </div>
</body>
</html>
