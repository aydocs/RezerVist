@extends('layouts.app')

@section('title', 'Sürüm Notları — RezerVist POS')

@section('content')
<style>
  .vp-hero{padding:140px 24px 80px;background:linear-gradient(180deg,#F5F3FF 0%,#fff 100%) !important;text-align:center;}
  .vp-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:50px;background:#fff !important;border:1px solid #E9E5F5;font-size:12px;font-weight:700;color:#6200EE !important;letter-spacing:0.05em;margin-bottom:20px;box-shadow:0 2px 8px rgba(98,0,238,0.06);}
  .vp-badge i{font-size:10px;color:#6200EE !important;}
  .vp-title{font-family:'Outfit',sans-serif;font-size:48px;font-weight:800;color:#0F172A !important;letter-spacing:-0.03em;line-height:1.1;margin-bottom:14px;background:none !important;-webkit-text-fill-color:#0F172A !important;}
  .vp-title span{color:#6200EE !important;-webkit-text-fill-color:#6200EE !important;}
  .vp-desc{font-size:16px;color:#64748B !important;max-width:460px;margin:0 auto 28px;line-height:1.7;background:none !important;}
  .vp-back{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:12px;background:#6200EE !important;color:#fff !important;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.25s;box-shadow:0 4px 14px rgba(98,0,238,0.25);}
  .vp-back:hover{background:#4A00B4 !important;transform:translateY(-2px);box-shadow:0 8px 24px rgba(98,0,238,0.3);}
  .vp-back i{font-size:11px;color:#fff !important;}

  .vp-grid{max-width:880px;margin:0 auto;padding:0 24px 100px;display:flex;flex-direction:column;gap:24px;}

  .vp-release{background:#fff !important;border-radius:20px;border:1px solid #E9E5F5;overflow:hidden;transition:all 0.3s;box-shadow:0 1px 4px rgba(0,0,0,0.04);}
  .vp-release:hover{border-color:#D4C9EE;box-shadow:0 12px 40px rgba(98,0,238,0.08);transform:translateY(-3px);}

  .vp-release-head{padding:24px 28px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;background:transparent !important;}
  .vp-release-left{display:flex;align-items:center;gap:16px;}
  .vp-ver-icon{width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
  .vp-ver-icon.latest{background:linear-gradient(135deg,#6200EE,#A855F7) !important;color:#fff !important;box-shadow:0 4px 16px rgba(98,0,238,0.3);}
  .vp-ver-icon.update{background:linear-gradient(135deg,#06B6D4,#22D3EE) !important;color:#fff !important;box-shadow:0 4px 16px rgba(6,182,212,0.3);}
  .vp-ver-icon.major{background:linear-gradient(135deg,#F59E0B,#FBBF24) !important;color:#fff !important;box-shadow:0 4px 16px rgba(245,158,11,0.3);}
  .vp-ver-icon.fix{background:linear-gradient(135deg,#EF4444,#F87171) !important;color:#fff !important;box-shadow:0 4px 16px rgba(239,68,68,0.2);}
  .vp-ver-icon.stable{background:#F1F5F9 !important;color:#64748B !important;}
  .vp-ver-icon i{color:inherit !important;}

  .vp-ver-num{font-size:12px;font-weight:800;color:#6200EE !important;letter-spacing:0.08em;text-transform:uppercase;background:none !important;}
  .vp-ver-name{font-family:'Outfit',sans-serif;font-size:20px;font-weight:700;color:#0F172A !important;letter-spacing:-0.02em;margin-top:2px;background:none !important;}
  .vp-ver-date{font-size:12px;color:#94A3B8 !important;font-weight:500;margin-top:3px;background:none !important;}

  .vp-tag{padding:5px 12px;border-radius:50px;font-size:11px;font-weight:700;letter-spacing:0.04em;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;}
  .vp-tag i{font-size:9px;}
  .vp-tag.new{background:#ECFDF5 !important;color:#059669 !important;border:1px solid #A7F3D0;}
  .vp-tag.update{background:#EFF6FF !important;color:#2563EB !important;border:1px solid #BFDBFE;}
  .vp-tag.major{background:#FFF7ED !important;color:#EA580C !important;border:1px solid #FED7AA;}
  .vp-tag.fix{background:#FEF2F2 !important;color:#DC2626 !important;border:1px solid #FECACA;}
  .vp-tag.release{background:#F5F3FF !important;color:#6200EE !important;border:1px solid #DDD6FE;}
  .vp-tag.new i{color:#059669 !important;}.vp-tag.update i{color:#2563EB !important;}.vp-tag.major i{color:#EA580C !important;}.vp-tag.fix i{color:#DC2626 !important;}.vp-tag.release i{color:#6200EE !important;}

  .vp-changes{padding:0 28px 24px;background:transparent !important;}
  .vp-changes-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;}
  .vp-change{display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-radius:12px;background:#FAFAFE !important;font-size:14px;color:#475569 !important;line-height:1.6;transition:background 0.2s;}
  .vp-change:hover{background:#F1F0FA !important;}

  .vp-change-icon{width:24px;height:24px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;margin-top:2px;}
  .vp-change-icon.feat{background:#EDE9FE !important;color:#7C3AED !important;}
  .vp-change-icon.perf{background:#CCFBF1 !important;color:#0D9488 !important;}
  .vp-change-icon.bugfix{background:#FEF3C7 !important;color:#D97706 !important;}
  .vp-change-icon i{color:inherit !important;}

  .vp-release-text{padding:0 28px 24px;font-size:14px;color:#64748B !important;line-height:1.7;background:none !important;}

  @media(max-width:640px){
    .vp-hero{padding:110px 20px 60px;}
    .vp-title{font-size:32px;}
    .vp-release-head{padding:20px;flex-direction:column;gap:10px;}
    .vp-changes{padding:0 20px 20px;}
    .vp-change{padding:10px 12px;font-size:13px;}
    .vp-grid{padding:0 16px 60px;gap:18px;}
  }
</style>

<!-- ═══════ HERO ═══════ -->
<section class="vp-hero">
  <div>
    <a href="{{ route('pages.pos') }}" class="vp-back">
      <i class="fa-solid fa-arrow-left"></i>
      POS Sayfasına Dön
    </a>
    <div style="margin-top:32px;">
      <div class="vp-badge">
        <i class="fa-solid fa-code-branch"></i>
        CHANGELOG
      </div>
      <h1 class="vp-title">Sürüm <span>Notları</span></h1>
      <p class="vp-desc">RezerVist POS ekosistemindeki tüm güncellemeler, yeni özellikler ve iyileştirmeler.</p>
    </div>
  </div>
</section>

<!-- ═══════ RELEASES ═══════ -->
<div class="vp-grid">

  <!-- v4.2.0 -->
  <div class="vp-release">
    <div class="vp-release-head">
      <div class="vp-release-left">
        <div class="vp-ver-icon latest"><i class="fa-solid fa-rocket"></i></div>
        <div class="vp-ver-info">
          <div class="vp-ver-num">v1.0.5</div>
          <div class="vp-ver-name">Akıllı Terminal Motoru</div>
          <div class="vp-ver-date">23 Şubat 2026</div>
        </div>
      </div>
      <div class="vp-tag new"><i class="fa-solid fa-circle-check"></i> En Yeni</div>
    </div>
    <div class="vp-changes">
      <ul class="vp-changes-list">
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          AI destekli satış tahmini motoru eklendi — geçmiş verilere göre günlük ciro öngörüsü.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Yeni dashboard tasarımı: Isı haritası, personel KPI ve anlık tablo görünümü.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon perf"><i class="fa-solid fa-bolt"></i></div>
          Terminal başlatma süresi %65 azaltıldı (Cold Start optimizasyonu).
        </li>
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Biyometrik giriş (parmak izi + yüz tanıma) üretim ortamında aktif.
        </li>
      </ul>
    </div>
  </div>

  <!-- v4.1.0 -->
  <div class="vp-release">
    <div class="vp-release-head">
      <div class="vp-release-left">
        <div class="vp-ver-icon update"><i class="fa-solid fa-arrow-up"></i></div>
        <div class="vp-ver-info">
          <div class="vp-ver-num">v1.0.2</div>
          <div class="vp-ver-name">Çoklu Şube Yönetimi</div>
          <div class="vp-ver-date">10 Şubat 2026</div>
        </div>
      </div>
      <div class="vp-tag update"><i class="fa-solid fa-arrow-trend-up"></i> Güncelleme</div>
    </div>
    <div class="vp-changes">
      <ul class="vp-changes-list">
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Tek panelden 50+ şube yönetimi desteği — merkezi stok ve personel kontrolü.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Şube karşılaştırma raporları ve performans sıralaması eklendi.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon perf"><i class="fa-solid fa-bolt"></i></div>
          WebSocket altyapısı yeniden yazıldı — senkronizasyon gecikmesi 0.2ms'ye düşürüldü.
        </li>
      </ul>
    </div>
  </div>

  <!-- v3.0.0 -->
  <div class="vp-release">
    <div class="vp-release-head">
      <div class="vp-release-left">
        <div class="vp-ver-icon major"><i class="fa-solid fa-fire"></i></div>
        <div class="vp-ver-info">
          <div class="vp-ver-num">v1.0.0</div>
          <div class="vp-ver-name">Masa Yönetimi 2.0</div>
          <div class="vp-ver-date">1 Şubat 2026</div>
        </div>
      </div>
      <div class="vp-tag major"><i class="fa-solid fa-fire"></i> Major</div>
    </div>
    <div class="vp-changes">
      <ul class="vp-changes-list">
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Sürükle &amp; Bırak masa düzenleme özelliği eklendi.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon perf"><i class="fa-solid fa-bolt"></i></div>
          Canlı sipariş takibi için WebSocket optimizasyonları yapıldı.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon feat"><i class="fa-solid fa-plus"></i></div>
          Karanlık mod desteği geliştirildi.
        </li>
      </ul>
    </div>
  </div>

  <!-- v2.3.1 -->
  <div class="vp-release">
    <div class="vp-release-head">
      <div class="vp-release-left">
        <div class="vp-ver-icon fix"><i class="fa-solid fa-bug"></i></div>
        <div class="vp-ver-info">
          <div class="vp-ver-num">v0.9.8</div>
          <div class="vp-ver-name">Performans İyileştirmeleri</div>
          <div class="vp-ver-date">15 Ocak 2026</div>
        </div>
      </div>
      <div class="vp-tag fix"><i class="fa-solid fa-bug"></i> Düzeltme</div>
    </div>
    <div class="vp-changes">
      <ul class="vp-changes-list">
        <li class="vp-change">
          <div class="vp-change-icon perf"><i class="fa-solid fa-bolt"></i></div>
          Veritabanı sorguları optimize edildi (%40 hız artışı).
        </li>
        <li class="vp-change">
          <div class="vp-change-icon bugfix"><i class="fa-solid fa-triangle-exclamation"></i></div>
          Mobil uyumluluk sorunları giderildi.
        </li>
        <li class="vp-change">
          <div class="vp-change-icon bugfix"><i class="fa-solid fa-triangle-exclamation"></i></div>
          Ödeme ekranında yaşanan zaman aşımı hatası düzeltildi.
        </li>
      </ul>
    </div>
  </div>

  <!-- v1.0.0 -->
  <div class="vp-release">
    <div class="vp-release-head">
      <div class="vp-release-left">
        <div class="vp-ver-icon stable"><i class="fa-solid fa-flag-checkered"></i></div>
        <div class="vp-ver-info">
          <div class="vp-ver-num">v0.9.0</div>
          <div class="vp-ver-name">Beta Sürüm</div>
          <div class="vp-ver-date">1 Ocak 2026</div>
        </div>
      </div>
      <div class="vp-tag release"><i class="fa-solid fa-flag"></i> Release</div>
    </div>
    <div class="vp-release-text">
      RezerVist POS sisteminin ilk kararlı sürümü yayınlandı. Temel sipariş yönetimi, masa takibi, stok kontrolü ve ödeme entegrasyonu ile işletmelerin dijital dönüşümü başladı.
    </div>
  </div>

</div>
@endsection
