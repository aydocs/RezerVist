<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finansal Rapor</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { color: #4f46e5; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        .stats-container { margin-bottom: 30px; }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table td { padding: 10px; border: 1px solid #eee; width: 33.33%; }
        .stat-label { color: #888; text-transform: uppercase; font-size: 8px; margin-bottom: 5px; }
        .stat-value { font-size: 16px; font-weight: bold; color: #111; }
        .table-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #4f46e5; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f9fafb; padding: 8px; border: 1px solid #eee; text-align: left; color: #444; }
        td { padding: 8px; border: 1px solid #eee; }
        .footer { text-align: center; margin-top: 50px; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .text-right { text-align: right; }
        .status-completed { color: #059669; font-weight: bold; }
        .status-cancelled { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REZERVE ET</h1>
        <p>Finansal Performans Raporu</p>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</p>
    </div>

    <div class="stats-container">
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stat-label">Toplam İşlem Hacmi</div>
                    <div class="stat-value">₺{{ number_format($totalVolume, 2, ',', '.') }}</div>
                </td>
                <td>
                    <div class="stat-label">Net Komisyon Geliri</div>
                    <div class="stat-value">₺{{ number_format($netIncome, 2, ',', '.') }}</div>
                </td>
                <td>
                    <div class="stat-label">Toplam Rezervasyon</div>
                    <div class="stat-value">{{ $totalReservations }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stat-label">Bakiye Yüklemeleri</div>
                    <div class="stat-value">₺{{ number_format($totalTopups, 2, ',', '.') }}</div>
                </td>
                <td>
                    <div class="stat-label">Cüzdan Ödemeleri</div>
                    <div class="stat-value">₺{{ number_format($totalWalletPayments, 2, ',', '.') }}</div>
                </td>
                <td>
                    <div class="stat-label">İade Tutarı</div>
                    <div class="stat-value">₺{{ number_format($totalRefunds, 2, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="table-title">Rezervasyon Hareketleri</div>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>İşletme</th>
                <th>Müşteri</th>
                <th>Kişi</th>
                <th class="text-right">Tutar</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
            <tr>
                <td>{{ $res->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $res->business->name }}</td>
                <td>{{ $res->user->name }}</td>
                <td>{{ $res->guest_count }}</td>
                <td class="text-right">₺{{ number_format($res->total_amount, 2, ',', '.') }}</td>
                <td class="status-{{ $res->status }}">{{ strtoupper($res->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Bu rapor {{ now()->format('d.m.Y H:i:s') }} tarihinde Rezerve Et Yönetim Paneli tarafından otomatik olarak oluşturulmuştur.
    </div>
</body>
</html>
