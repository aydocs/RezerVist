@extends('layouts.app')

@section('title', 'Sürüm Notları — RezerVist POS')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
  :root {
    --primary: #6200EE;
    --primary-dark: #4A00B4;
    --accent: #03DAC6;
    --bg: #FFFFFF;
    --surface: #F8F7FF;
    --surface-2: #F0EDF9;
    --border: #EAE6F5;
    --text: #0D0920;
    --text-muted: #9189AC;
    --text-subtle: #5C5270;
    --shadow: rgba(98,0,238,0.08);
  }

  /* HERO */
  .ver-hero{
    padding:140px 60px 80px;position:relative;overflow:hidden;background:var(--bg);text-align:center;
  }
  .ver-hero-orb{
    position:absolute;width:700px;height:700px;border-radius:50%;pointer-events:none;
    background:radial-gradient(circle,rgba(98,0,238,0.06) 0%,transparent 65%);
    top:-200px;left:50%;transform:translateX(-50%);
  }
  .ver-hero-inner{position:relative;z-index:1;max-width:680px;margin:0 auto;}
  .ver-badge{
    display:inline-flex;align-items:center;gap:7px;
    padding:5px 14px 5px 5px;
    border:1.5px solid rgba(98,0,238,0.15);border-radius:100px;
    background:rgba(98,0,238,0.04);
    font-size:0.7rem;font-weight:700;color:var(--primary);
    letter-spacing:0.08em;text-transform:uppercase;margin-bottom:22px;
  }
  .ver-badge-dot{width:8px;height:8px;border-radius:50%;background:var(--primary);margin-right:2px;}
  .ver-title{
    font-family:'Inter',sans-serif;font-size:clamp(2.2rem,4.5vw,3.8rem);
    font-weight:800;line-height:1.05;letter-spacing:-0.04em;color:var(--text);margin-bottom:16px;
  }
  .ver-title em{font-style:normal;background:linear-gradient(135deg,var(--primary),#A855F7,#06B6D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
  .ver-sub{font-size:1.05rem;color:var(--text-subtle);font-weight:300;line-height:1.7;max-width:520px;margin:0 auto 32px;}
  .ver-back{
    display:inline-flex;align-items:center;gap:7px;font-size:0.85rem;font-weight:600;
    color:var(--primary);text-decoration:none;padding:9px 18px;border-radius:10px;
    border:1.5px solid rgba(98,0,238,0.15);background:rgba(98,0,238,0.04);transition:all 0.25s;
  }
  .ver-back:hover{background:var(--primary);color:white;border-color:var(--primary);transform:translateY(-1px);}

  /* TIMELINE */
  .timeline-section{padding:0 60px 120px;}
  .timeline-inner{max-width:760px;margin:0 auto;position:relative;}
  .timeline-line{position:absolute;left:28px;top:0;bottom:0;width:2px;background:linear-gradient(180deg,var(--primary) 0%,var(--border) 100%);border-radius:2px;}

  .ver-card{
    position:relative;padding-left:72px;margin-bottom:40px;
    opacity:0;transform:translateY(20px);transition:all 0.5s ease;
  }
  .ver-card.visible{opacity:1;transform:translateY(0);}

  .ver-dot{
    position:absolute;left:16px;top:28px;width:26px;height:26px;border-radius:50%;
    border:3px solid white;box-shadow:0 0 0 2px var(--border),0 4px 12px var(--shadow);
    z-index:2;
  }
  .ver-dot.latest{background:var(--primary);box-shadow:0 0 0 2px var(--primary),0 4px 16px rgba(98,0,238,0.35);}
  .ver-dot.update{background:var(--accent);box-shadow:0 0 0 2px var(--accent),0 4px 12px rgba(3,218,198,0.25);}
  .ver-dot.stable{background:#94A3B8;box-shadow:0 0 0 2px #94A3B8,0 4px 12px rgba(0,0,0,0.1);}

  .ver-box{
    background:white;border:1.5px solid var(--border);border-radius:20px;padding:28px 30px;
    transition:all 0.3s;box-shadow:0 2px 8px rgba(0,0,0,0.03);
  }
  .ver-box:hover{border-color:rgba(98,0,238,0.2);box-shadow:0 12px 32px var(--shadow);transform:translateY(-3px);}

  .ver-box-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:6px;flex-wrap:wrap;}
  .ver-box-ver{font-family:'Inter',sans-serif;font-size:0.72rem;font-weight:800;color:var(--primary);letter-spacing:0.06em;text-transform:uppercase;}
  .ver-box-title{font-family:'Inter',sans-serif;font-size:1.2rem;font-weight:700;color:var(--text);letter-spacing:-0.02em;margin-bottom:4px;}
  .ver-box-date{font-size:0.78rem;color:var(--text-muted);font-weight:500;margin-bottom:18px;}

  .ver-tag{
    display:inline-flex;align-items:center;gap:5px;font-size:0.65rem;font-weight:700;
    padding:4px 10px;border-radius:100px;letter-spacing:0.04em;text-transform:uppercase;
  }
  .ver-tag.new{background:rgba(16,185,129,0.08);color:#10B981;border:1px solid rgba(16,185,129,0.15);}
  .ver-tag.update{background:rgba(59,130,246,0.08);color:#3B82F6;border:1px solid rgba(59,130,246,0.15);}
  .ver-tag.fix{background:rgba(245,158,11,0.08);color:#F59E0B;border:1px solid rgba(245,158,11,0.15);}
  .ver-tag.release{background:rgba(98,0,238,0.06);color:var(--primary);border:1px solid rgba(98,0,238,0.12);}

  .ver-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;}
  .ver-item{
    display:flex;align-items:flex-start;gap:10px;font-size:0.875rem;color:var(--text-subtle);
    font-weight:400;line-height:1.55;padding:8px 12px;border-radius:10px;
    background:var(--surface);border:1px solid transparent;transition:all 0.2s;
  }
  .ver-item:hover{border-color:rgba(98,0,238,0.1);background:var(--surface-2);}
  .ver-check{
    width:18px;height:18px;border-radius:6px;flex-shrink:0;margin-top:1px;
    display:flex;align-items:center;justify-content:center;font-size:0.55rem;
  }
  .ver-check.feat{background:rgba(98,0,238,0.08);color:var(--primary);}
  .ver-check.perf{background:rgba(3,218,198,0.1);color:#0ABBA3;}
  .ver-check.bugfix{background:rgba(245,158,11,0.1);color:#F59E0B;}

  .ver-desc{font-size:0.875rem;color:var(--text-subtle);line-height:1.6;}

  /* RESPONSIVE */
  @media(max-width:768px){
    .ver-hero{padding:110px 22px 60px;}
    .timeline-section{padding:0 22px 80px;}
    .ver-card{padding-left:56px;margin-bottom:28px;}
    .timeline-line{left:20px;}
    .ver-dot{left:8px;width:22px;height:22px;}
    .ver-box{padding:22px 20px;}
    .ver-box-header{flex-direction:column;align-items:flex-start;gap:6px;}
  }
</style>

<!-- ════════════════ HERO ════════════════ -->
<section class="ver-hero">
  <div class="ver-hero-orb"></div>
  <div class="ver-hero-inner">
    <a href="{{ route('pages.pos') }}" class="ver-back">
      <i class="fa-solid fa-arrow-left" style="font-size:.7rem;"></i>
      POS Sayfasına Dön
    </a>
    <div style="margin-top:28px;">
      <div class="ver-badge">
        <div class="ver-badge-dot"></div>
        Changelog
      </div>
      <h1 class="ver-title">Sürüm<br><em>Geçmişi.</em></h1>
      <p class="ver-sub">RezerVist POS ekosistemindeki her güncelleme, yeni özellik ve iyileştirmeyi buradan takip edin.</p>
    </div>
  </div>
</section>

<!-- ════════════════ TIMELINE ════════════════ -->
<section class="timeline-section">
  <div class="timeline-inner">
    <div class="timeline-line"></div>

    <!-- v4.2.0 -->
    <div class="ver-card">
      <div class="ver-dot latest"></div>
      <div class="ver-box">
        <div class="ver-box-header">
          <div>
            <div class="ver-box-ver">v4.2.0</div>
            <div class="ver-box-title">Akıllı Terminal Motoru</div>
          </div>
          <div class="ver-tag new"><i class="fa-solid fa-sparkles" style="font-size:.5rem;"></i> En Yeni</div>
        </div>
        <div class="ver-box-date">20 Şubat 2026</div>
        <ul class="ver-list">
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            AI destekli satış tahmini motoru eklendi — geçmiş verilere göre günlük ciro öngörüsü.
          </li>
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Yeni dashboard tasarımı: Isı haritası, personel KPI ve anlık tablo görünümü.
          </li>
          <li class="ver-item">
            <div class="ver-check perf"><i class="fa-solid fa-bolt"></i></div>
            Terminal başlatma süresi %65 azaltıldı (Cold Start optimizasyonu).
          </li>
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Biyometrik giriş (parmak izi + yüz tanıma) üretim ortamında aktif.
          </li>
        </ul>
      </div>
    </div>

    <!-- v4.1.0 -->
    <div class="ver-card">
      <div class="ver-dot update"></div>
      <div class="ver-box">
        <div class="ver-box-header">
          <div>
            <div class="ver-box-ver">v4.1.0</div>
            <div class="ver-box-title">Çoklu Şube Yönetimi</div>
          </div>
          <div class="ver-tag update"><i class="fa-solid fa-arrow-up" style="font-size:.5rem;"></i> Güncelleme</div>
        </div>
        <div class="ver-box-date">5 Şubat 2026</div>
        <ul class="ver-list">
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Tek panelden 50+ şube yönetimi desteği — merkezi stok ve personel kontrolü.
          </li>
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Şube karşılaştırma raporları ve performans sıralaması eklendi.
          </li>
          <li class="ver-item">
            <div class="ver-check perf"><i class="fa-solid fa-bolt"></i></div>
            WebSocket altyapısı yeniden yazıldı — senkronizasyon gecikmesi 0.2ms'ye düşürüldü.
          </li>
        </ul>
      </div>
    </div>

    <!-- v3.0.0 -->
    <div class="ver-card">
      <div class="ver-dot update"></div>
      <div class="ver-box">
        <div class="ver-box-header">
          <div>
            <div class="ver-box-ver">v3.0.0</div>
            <div class="ver-box-title">Masa Yönetimi 2.0</div>
          </div>
          <div class="ver-tag new"><i class="fa-solid fa-sparkles" style="font-size:.5rem;"></i> Major</div>
        </div>
        <div class="ver-box-date">30 Ocak 2026</div>
        <ul class="ver-list">
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Sürükle & Bırak masa düzenleme özelliği eklendi.
          </li>
          <li class="ver-item">
            <div class="ver-check perf"><i class="fa-solid fa-bolt"></i></div>
            Canlı sipariş takibi için WebSocket optimizasyonları yapıldı.
          </li>
          <li class="ver-item">
            <div class="ver-check feat"><i class="fa-solid fa-plus"></i></div>
            Karanlık mod desteği geliştirildi.
          </li>
        </ul>
      </div>
    </div>

    <!-- v2.3.1 -->
    <div class="ver-card">
      <div class="ver-dot stable"></div>
      <div class="ver-box">
        <div class="ver-box-header">
          <div>
            <div class="ver-box-ver">v2.3.1</div>
            <div class="ver-box-title">Performans İyileştirmeleri</div>
          </div>
          <div class="ver-tag fix"><i class="fa-solid fa-wrench" style="font-size:.5rem;"></i> Düzeltme</div>
        </div>
        <div class="ver-box-date">15 Ocak 2026</div>
        <ul class="ver-list">
          <li class="ver-item">
            <div class="ver-check perf"><i class="fa-solid fa-bolt"></i></div>
            Veritabanı sorguları optimize edildi (%40 hız artışı).
          </li>
          <li class="ver-item">
            <div class="ver-check bugfix"><i class="fa-solid fa-bug"></i></div>
            Mobil uyumluluk sorunları giderildi.
          </li>
          <li class="ver-item">
            <div class="ver-check bugfix"><i class="fa-solid fa-bug"></i></div>
            Ödeme ekranında yaşanan zaman aşımı hatası düzeltildi.
          </li>
        </ul>
      </div>
    </div>

    <!-- v1.0.0 -->
    <div class="ver-card">
      <div class="ver-dot stable"></div>
      <div class="ver-box">
        <div class="ver-box-header">
          <div>
            <div class="ver-box-ver">v1.0.0</div>
            <div class="ver-box-title">İlk Sürüm</div>
          </div>
          <div class="ver-tag release"><i class="fa-solid fa-rocket" style="font-size:.5rem;"></i> Release</div>
        </div>
        <div class="ver-box-date">1 Ocak 2024</div>
        <p class="ver-desc">
          RezerVist POS sisteminin ilk kararlı sürümü yayınlandı. Temel sipariş yönetimi, masa takibi, stok kontrolü ve ödeme entegrasyonu ile işletmelerin dijital dönüşümü başladı.
        </p>
      </div>
    </div>

  </div>
</section>

<script>
  // Scroll reveal for version cards
  const vio = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.ver-card').forEach(el => vio.observe(el));
</script>
@endsection
