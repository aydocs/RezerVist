<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Finansal Rapor - {{ $business->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #4F46E5; }
        .meta { color: #666; font-size: 12px; margin-top: 5px; }
        .cards { margin-bottom: 30px; width: 100%; }
        .card { display: inline-block; width: 30%; background: #f8fafc; padding: 15px; border-radius: 8px; margin-right: 2%; text-align: center; }
        .card:last-child { margin-right: 0; }
        .card h3 { margin: 0; color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .card .value { font-size: 20px; font-weight: bold; color: #0f172a; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 10px; background: #f1f5f9; color: #475569; font-size: 10px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .text-right { text-align: right; }
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .bg-green { background: #dcfce7; color: #166534; }
        .bg-indigo { background: #e0e7ff; color: #3730a3; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ $globalSettings['site_name'] ?? config('app.name') }} <span style="font-size: 14px; color: #94a3b8; font-weight: normal;">| Finansal Rapor</span></div>
        <div class="meta">
            {{ $business->name }} • {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Brüt Hasılat</h3>
            <div class="value">₺{{ number_format($totalRevenue, 2, ',', '.') }}</div>
        </div>
        <div class="card">
            <h3>Platform Komisyonu</h3>
            <div class="value" style="color: #ef4444;">-₺{{ number_format($totalCommission, 2, ',', '.') }}</div>
        </div>
        <div class="card" style="background: #ecfdf5; border: 1px solid #d1fae5;">
            <h3 style="color: #059669;">Net Kazanç</h3>
            <div class="value" style="color: #059669;">₺{{ number_format($totalNet, 2, ',', '.') }}</div>
        </div>
    </div>

    <h3>İşlem Detayları</h3>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Müşteri</th>
                <th>Tutar</th>
                <th>Komisyon</th>
                <th>Net</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
                @php 
                    $comm = $res->total_amount * ($business->commission_rate / 100);
                    $net = $res->total_amount - $comm;
                @endphp
                <tr>
                    <td>{{ $res->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ $res->user->name }}</td>
                    <td class="text-right">₺{{ number_format($res->total_amount, 2, ',', '.') }}</td>
                    <td class="text-right text-red">-₺{{ number_format($comm, 2, ',', '.') }}</td>
                    <td class="text-right text-green">+₺{{ number_format($net, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $res->status == 'completed' ? 'bg-green' : 'bg-indigo' }}">
                            {{ $res->status == 'completed' ? 'Tamamlandı' : 'Onaylı' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
