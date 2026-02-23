@extends('layouts.app')

@section('title', 'RezerVist POS — Akıllı İşletme Terminali')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
  :root {
    --primary: #6200EE;
    --primary-dark: #4A00B4;
    --primary-light: #8B3FFF;
    --accent: #03DAC6;
    --bg: #FFFFFF;
    --surface: #F8F7FF;
    --surface-2: #F0EDF9;
    --border: #EAE6F5;
    --border-strong: #D6CEF0;
    --text: #0D0920;
    --text-muted: #9189AC;
    --text-subtle: #5C5270;
    --shadow: rgba(98,0,238,0.08);
  }

  /* HERO */
  .hero{min-height:100vh;display:flex;align-items:center;padding:100px 60px 80px;position:relative;overflow:hidden;background:var(--bg);}
  .hero-orb{position:absolute;border-radius:50%;pointer-events:none;}
  .hero-orb-1{width:880px;height:880px;background:radial-gradient(circle,rgba(98,0,238,0.055) 0%,transparent 65%);top:-280px;left:-180px;animation:orbf 10s ease-in-out infinite;}
  .hero-orb-2{width:580px;height:580px;background:radial-gradient(circle,rgba(3,218,198,0.07) 0%,transparent 65%);bottom:-130px;right:-80px;animation:orbf 13s ease-in-out infinite reverse;}
  @keyframes orbf{0%,100%{transform:translate(0,0);}50%{transform:translate(22px,-22px);}}
  .hero-grid{
    position:absolute;inset:0;
    background-image:linear-gradient(rgba(98,0,238,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(98,0,238,0.04) 1px,transparent 1px);
    background-size:72px 72px;
    mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,black 25%,transparent 80%);
  }
  .hero-inner{max-width:1360px;margin:0 auto;width:100%;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;position:relative;z-index:1;}

  .hero-badge{
    display:inline-flex;align-items:center;gap:8px;
    padding:5px 14px 5px 5px;
    border:1.5px solid rgba(98,0,238,0.15);border-radius:100px;
    background:rgba(98,0,238,0.04);
    font-size:0.71rem;font-weight:700;color:var(--primary);
    letter-spacing:0.07em;text-transform:uppercase;margin-bottom:26px;
  }
  .badge-ping{width:22px;height:22px;border-radius:50%;background:rgba(98,0,238,0.08);display:flex;align-items:center;justify-content:center;}
  .badge-dot{width:7px;height:7px;border-radius:50%;background:var(--primary);animation:ping 2s infinite;}
  @keyframes ping{0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.5;transform:scale(0.75);}}

  .hero-title{
    font-family:'Inter',sans-serif;
    font-size:clamp(3rem,5.5vw,5.5rem);font-weight:800;line-height:0.93;letter-spacing:-0.045em;
    color:var(--text);margin-bottom:22px;
  }
  .hero-title .grad{
    background:linear-gradient(135deg,var(--primary) 0%,#A855F7 50%,#06B6D4 100%);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  }
  .hero-sub{font-size:1.075rem;font-weight:300;line-height:1.75;color:var(--text-subtle);max-width:490px;margin-bottom:38px;}
  .hero-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap;}

  .btn-primary{
    display:inline-flex;align-items:center;gap:9px;padding:14px 26px;
    background:var(--primary);color:white;font-weight:700;font-size:0.9375rem;
    border-radius:13px;text-decoration:none;transition:all 0.3s;border:none;cursor:pointer;
    letter-spacing:-0.01em;box-shadow:0 4px 16px rgba(98,0,238,0.2);
  }
  .btn-primary:hover{background:var(--primary-dark);transform:translateY(-2px);box-shadow:0 12px 32px rgba(98,0,238,0.3);}
  .btn-outline{
    display:inline-flex;align-items:center;gap:9px;padding:14px 26px;
    border:1.5px solid var(--border-strong);color:var(--text-subtle);font-weight:600;font-size:0.9375rem;
    border-radius:13px;text-decoration:none;transition:all 0.25s;cursor:pointer;background:white;letter-spacing:-0.01em;
  }
  .btn-outline:hover{border-color:var(--primary);color:var(--primary);background:rgba(98,0,238,0.03);}

  .hero-stats{display:flex;gap:34px;margin-top:46px;padding-top:46px;border-top:1.5px solid var(--border);}
  .stat-num{font-family:'Inter',sans-serif;font-size:1.7rem;font-weight:800;color:var(--text);letter-spacing:-0.04em;line-height:1;}
  .stat-num span{color:var(--primary);}
  .stat-label{font-size:0.76rem;color:var(--text-muted);font-weight:500;margin-top:5px;}

  /* TERMINAL */
  .terminal-wrap{position:relative;display:flex;align-items:center;justify-content:center;}
  .term-glow{position:absolute;width:460px;height:460px;background:radial-gradient(circle,rgba(98,0,238,0.11) 0%,transparent 70%);border-radius:50%;animation:orbf 7s ease-in-out infinite;}
  .terminal{
    position:relative;z-index:1;width:390px;
    background:white;border-radius:22px;border:1.5px solid var(--border);overflow:hidden;
    box-shadow:0 28px 72px rgba(98,0,238,0.1),0 6px 20px rgba(0,0,0,0.05);
    transform:perspective(1100px) rotateY(-6deg) rotateX(2deg);
    transition:transform 0.8s ease;animation:tEntry 1s ease both;
  }
  .terminal:hover{transform:perspective(1100px) rotateY(-1deg) rotateX(0deg);}
  @keyframes tEntry{from{opacity:0;transform:perspective(1100px) rotateY(-18deg) rotateX(8deg) translateY(40px);}to{opacity:1;transform:perspective(1100px) rotateY(-6deg) rotateX(2deg);}}
  .t-header{height:44px;background:var(--surface);border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 16px;}
  .t-dots{display:flex;gap:5px;}
  .t-dot{width:10px;height:10px;border-radius:50%;}
  .t-dot-r{background:#FF5F57;} .t-dot-o{background:#FFBD2E;} .t-dot-g{background:#28C840;}
  .t-label{font-size:0.67rem;color:var(--text-muted);font-weight:600;letter-spacing:0.1em;}
  .t-body{padding:20px;}
  .t-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
  .t-greet{font-size:0.68rem;color:var(--text-muted);font-weight:500;}
  .t-name{font-family:'Inter',sans-serif;font-size:0.95rem;font-weight:700;color:var(--text);}
  .t-time{font-size:0.78rem;color:var(--primary);font-weight:700;background:rgba(98,0,238,0.06);padding:5px 11px;border-radius:100px;border:1px solid rgba(98,0,238,0.12);}
  .t-kpis{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-bottom:16px;}
  .t-kpi{background:var(--surface);border:1.5px solid var(--border);border-radius:13px;padding:13px;}
  .t-kpi.f{background:rgba(98,0,238,0.05);border-color:rgba(98,0,238,0.18);}
  .t-kpi-l{font-size:0.6rem;color:var(--text-muted);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:4px;}
  .t-kpi-v{font-family:'Inter',sans-serif;font-size:1.35rem;font-weight:800;color:var(--text);letter-spacing:-0.03em;}
  .t-kpi.f .t-kpi-v{color:var(--primary);}
  .t-kpi-t{font-size:0.6rem;font-weight:700;margin-top:3px;}
  .t-kpi-t.g{color:#10B981;} .t-kpi-t.m{color:var(--text-muted);}
  .t-chart-l{font-size:0.6rem;color:var(--text-muted);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:7px;}
  .t-chart{display:flex;align-items:flex-end;gap:4px;height:48px;margin-bottom:16px;}
  .t-b{flex:1;border-radius:4px 4px 0 0;background:rgba(98,0,238,0.1);}
  .t-b.hi{background:var(--primary);}
  .t-b.ac{background:linear-gradient(180deg,var(--accent) 0%,rgba(3,218,198,0.2) 100%);}
  .t-orders{display:flex;flex-direction:column;gap:6px;}
  .t-order{display:flex;align-items:center;justify-content:space-between;padding:8px 11px;background:var(--surface);border-radius:10px;border:1.5px solid var(--border);}
  .t-ol{display:flex;align-items:center;gap:8px;}
  .t-ic{width:26px;height:26px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:0.62rem;}
  .ic-p{background:rgba(98,0,238,0.08);color:var(--primary);}
  .ic-t{background:rgba(3,218,198,0.1);color:#0ABBA3;}
  .t-ot{font-size:0.7rem;font-weight:600;color:var(--text);}
  .t-os{font-size:0.6rem;color:var(--text-muted);}
  .bdg{font-size:0.56rem;font-weight:700;padding:3px 7px;border-radius:100px;letter-spacing:0.05em;}
  .bdg-ok{background:rgba(16,185,129,0.08);color:#10B981;}
  .bdg-go{background:rgba(245,158,11,0.1);color:#F59E0B;}

  /* SECTIONS */
  .section{padding:112px 60px;}
  .section-inner{max-width:1360px;margin:0 auto;}
  .section-tag{display:inline-block;font-size:0.7rem;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:var(--primary);margin-bottom:16px;}
  .section-title{font-family:'Inter',sans-serif;font-size:clamp(2rem,3.8vw,3.2rem);font-weight:800;letter-spacing:-0.04em;color:var(--text);line-height:1.05;margin-bottom:18px;}
  .section-title em{font-style:normal;color:var(--primary);}
  .section-sub{font-size:1rem;color:var(--text-subtle);font-weight:300;line-height:1.7;max-width:520px;}

  /* FEATURES */
  .feat-bg{background:var(--surface);}
  .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:68px;}
  .feat-card{
    background:white;border:1.5px solid var(--border);border-radius:22px;padding:34px;
    transition:all 0.35s;position:relative;overflow:hidden;
  }
  .feat-card:hover{border-color:rgba(98,0,238,0.25);transform:translateY(-5px);box-shadow:0 18px 44px var(--shadow);}
  .feat-card.span2{grid-column:1/3;display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;}
  .feat-card.dark{background:var(--primary);border-color:var(--primary);}
  .feat-card.dark:hover{box-shadow:0 18px 44px rgba(98,0,238,0.35);}
  .feat-icon{width:48px;height:48px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:22px;}
  .fi-p{background:rgba(98,0,238,0.08);color:var(--primary);}
  .fi-t{background:rgba(3,218,198,0.1);color:#0ABBA3;}
  .fi-w{background:rgba(255,255,255,0.15);color:white;}
  .fi-s{background:var(--surface-2);color:var(--text-subtle);}
  .feat-title{font-family:'Inter',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:11px;letter-spacing:-0.02em;}
  .feat-card.dark .feat-title{color:white;}
  .feat-desc{font-size:0.875rem;color:var(--text-subtle);line-height:1.65;font-weight:400;}
  .feat-card.dark .feat-desc{color:rgba(255,255,255,0.7);}
  .feat-chks{margin-top:16px;display:flex;flex-direction:column;gap:8px;}
  .feat-chk{display:flex;align-items:center;gap:8px;font-size:0.78rem;font-weight:600;color:var(--text-muted);}
  .chk-dot{width:16px;height:16px;border-radius:5px;flex-shrink:0;background:rgba(98,0,238,0.07);display:flex;align-items:center;justify-content:center;}
  .chk-dot i{font-size:0.5rem;color:var(--primary);}
  .feat-card.dark .feat-chk{color:rgba(255,255,255,0.6);}
  .feat-card.dark .chk-dot{background:rgba(255,255,255,0.12);}
  .feat-card.dark .chk-dot i{color:white;}

  /* mini viz */
  .mini-viz{background:var(--surface);border:1.5px solid var(--border);border-radius:17px;padding:20px;}
  .mv-l{font-size:0.6rem;color:var(--text-muted);font-weight:600;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:12px;}
  .mv-bars{display:flex;align-items:flex-end;gap:5px;height:66px;}
  .mv-b{flex:1;border-radius:4px 4px 0 0;}
  .mv-nums{display:flex;gap:12px;margin-top:12px;}
  .mv-n{flex:1;}
  .mv-nv{font-family:'Inter',sans-serif;font-size:1.1rem;font-weight:800;color:var(--text);letter-spacing:-0.03em;}
  .mv-nl{font-size:0.6rem;color:var(--text-muted);font-weight:500;margin-top:1px;}


  /* TESTIMONIALS */
  .testi-bg{background:var(--surface);}
  .testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:60px;}
  .testi-card{
    background:white;border:1.5px solid var(--border);border-radius:22px;padding:32px;
    transition:all 0.3s;box-shadow:0 2px 10px rgba(0,0,0,0.04);
  }
  .testi-card:hover{border-color:rgba(98,0,238,0.2);transform:translateY(-4px);box-shadow:0 14px 36px var(--shadow);}
  .testi-stars{font-size:0.68rem;letter-spacing:2px;margin-bottom:16px;color:#F59E0B;}
  .testi-q{font-size:0.9rem;color:var(--text-subtle);font-weight:400;line-height:1.7;margin-bottom:22px;font-style:italic;}
  .testi-author{display:flex;align-items:center;gap:11px;}
  .testi-av{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#A855F7);display:flex;align-items:center;justify-content:center;font-family:'Inter',sans-serif;font-weight:800;font-size:0.9rem;color:white;flex-shrink:0;}
  .testi-name{font-weight:700;font-size:0.85rem;color:var(--text);}
  .testi-role{font-size:0.73rem;color:var(--text-muted);font-weight:500;margin-top:1px;}

  /* CTA */
  .cta-section{background:var(--primary);position:relative;overflow:hidden;text-align:center;}
  .cta-section::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.05) 1px,transparent 1px);background-size:60px 60px;}
  .cta-section::after{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:700px;height:700px;background:radial-gradient(circle,rgba(255,255,255,0.07) 0%,transparent 60%);border-radius:50%;}
  .cta-inner{position:relative;z-index:1;}
  .cta-title{font-family:'Inter',sans-serif;font-size:clamp(2.4rem,5vw,4.5rem);font-weight:800;letter-spacing:-0.045em;color:white;line-height:1.0;margin-bottom:18px;}
  .cta-sub{font-size:1rem;color:rgba(255,255,255,0.7);font-weight:300;line-height:1.65;margin-bottom:42px;}
  .cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
  .btn-white{display:inline-flex;align-items:center;gap:9px;padding:15px 30px;background:white;color:var(--primary);font-weight:700;font-size:0.9375rem;border-radius:13px;text-decoration:none;transition:all 0.3s;letter-spacing:-0.01em;box-shadow:0 8px 22px rgba(0,0,0,0.15);}
  .btn-white:hover{transform:translateY(-2px) scale(1.02);box-shadow:0 14px 36px rgba(0,0,0,0.2);}
  .btn-ghost-w{display:inline-flex;align-items:center;gap:9px;padding:15px 30px;border:1.5px solid rgba(255,255,255,0.3);color:white;font-weight:600;font-size:0.9375rem;border-radius:13px;text-decoration:none;transition:all 0.25s;letter-spacing:-0.01em;background:rgba(255,255,255,0.08);}
  .btn-ghost-w:hover{background:rgba(255,255,255,0.16);border-color:rgba(255,255,255,0.5);}

  /* REVEAL */
  .reveal{opacity:0;transform:translateY(26px);transition:opacity 0.6s ease,transform 0.6s ease;}
  .reveal.visible{opacity:1;transform:translateY(0);}

  /* ─── INTRO OVERLAY ─── */
  #intro-overlay{
    position:fixed;inset:0;z-index:99999;
    background:#fff;
    display:flex;align-items:center;justify-content:center;
    transition:opacity 0.8s ease,visibility 0.8s ease;
  }
  #intro-overlay.hidden-overlay{opacity:0;visibility:hidden;pointer-events:none;}
  #intro-progress{position:absolute;top:0;left:0;height:3px;background:var(--primary);transition:width 0.15s linear;}
  #skip-btn{
    position:absolute;top:24px;right:28px;
    padding:10px 22px;border-radius:50px;
    border:1.5px solid var(--primary);color:var(--primary);
    font-size:11px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;
    cursor:pointer;background:transparent;
    transition:background 0.2s,color 0.2s;
    font-family:'Inter',sans-serif;
  }
  #skip-btn:hover{background:var(--primary);color:#fff;}
  .intro-scene{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;padding:40px;}
  [x-cloak]{display:none!important;}

  @keyframes introFadeSlideUp{from{opacity:0;transform:translateY(40px);}to{opacity:1;transform:translateY(0);}}
  @keyframes introFadeSlideDown{from{opacity:0;transform:translateY(-30px);}to{opacity:1;transform:translateY(0);}}
  @keyframes introFadeIn{from{opacity:0;}to{opacity:1;}}
  @keyframes introScaleIn{from{opacity:0;transform:scale(0.7);}to{opacity:1;transform:scale(1);}}
  @keyframes introScaleInBig{from{opacity:0;transform:scale(0.3);}to{opacity:1;transform:scale(1);}}
  @keyframes introSlideInLeft{from{opacity:0;transform:translateX(-60px);}to{opacity:1;transform:translateX(0);}}
  @keyframes introSlideInRight{from{opacity:0;transform:translateX(60px);}to{opacity:1;transform:translateX(0);}}
  @keyframes introPulseBrand{0%,100%{box-shadow:0 0 0 0 rgba(98,0,238,0.3);}50%{box-shadow:0 0 0 18px rgba(98,0,238,0);}}
  @keyframes introBarGrow{from{height:0;}}
  @keyframes introDrawLine{from{stroke-dashoffset:300;}to{stroke-dashoffset:0;}}
  @keyframes introTyping{from{max-width:0;}to{max-width:100%;}}
  @keyframes introBlink{50%{border-color:transparent;}}

  .ia-fadeSlideUp{animation:introFadeSlideUp 0.7s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-fadeSlideDown{animation:introFadeSlideDown 0.7s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-fadeIn{animation:introFadeIn 0.6s ease both;}
  .ia-scaleIn{animation:introScaleIn 0.7s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-scaleInBig{animation:introScaleInBig 0.9s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-slideInLeft{animation:introSlideInLeft 0.7s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-slideInRight{animation:introSlideInRight 0.7s cubic-bezier(0.19,1,0.22,1) both;}
  .ia-d100{animation-delay:0.1s;}.ia-d200{animation-delay:0.2s;}.ia-d300{animation-delay:0.3s;}.ia-d500{animation-delay:0.5s;}.ia-d700{animation-delay:0.7s;}
  .intro-typewriter{overflow:hidden;border-right:3px solid var(--primary);white-space:nowrap;animation:introTyping 1.8s steps(16,end) both,introBlink 0.6s step-end infinite alternate;}
  .intro-bar{width:28px;border-radius:6px 6px 0 0;background:var(--primary);align-self:flex-end;}
  .intro-net-line{stroke-dasharray:300;animation:introDrawLine 1.5s ease both;}
  #main-page{opacity:0;transition:opacity 0.8s ease;}
  #main-page.visible{opacity:1;}

  /* RESPONSIVE */
  @media(max-width:1024px){
    .hero{padding:96px 22px 68px;}
    .hero-inner{grid-template-columns:1fr;gap:52px;text-align:center;}
    .hero-stats{justify-content:center;}
    .hero-sub{margin:0 auto 38px;}
    .hero-actions{justify-content:center;}
    .terminal{width:310px;transform:none!important;}
    .section{padding:76px 22px;}
    .features-grid{grid-template-columns:1fr;}
    .feat-card.span2{grid-column:1;display:block;}
    .feat-card.span2 .mini-viz{margin-top:24px;}
    .testi-grid{grid-template-columns:1fr;}
  }
</style>

<!-- ════════════════ INTRO OVERLAY ════════════════ -->
<div x-data="posIntro()" x-init="start()">

<div id="intro-overlay" :class="{ 'hidden-overlay': done }">
  <div id="intro-progress" :style="'width:' + progress + '%'"></div>
  <button id="skip-btn" @click="skip()">Tanıtımı Geç &rarr;</button>

  <!-- Scene 0: Logo Reveal -->
  <div class="intro-scene" x-show="scene === 0" x-cloak>
    <div class="ia-scaleInBig" style="display:flex;flex-direction:column;align-items:center;gap:24px;">
      <div style="width:128px;height:128px;background:var(--primary);border-radius:2rem;display:flex;align-items:center;justify-content:center;box-shadow:0 25px 50px rgba(98,0,238,0.3);animation:introPulseBrand 2s infinite 0.9s;">
        <span style="color:white;font-weight:900;font-size:3.5rem;font-family:'Inter',sans-serif;">R</span>
      </div>
      <p class="ia-fadeIn ia-d500" style="color:var(--text-muted);font-size:0.85rem;font-weight:600;letter-spacing:0.4em;text-transform:uppercase;">RezerVist Systems</p>
    </div>
  </div>

  <!-- Scene 1: Name Typewriter -->
  <div class="intro-scene" x-show="scene === 1" x-cloak>
    <div style="text-align:center;">
      <div class="ia-fadeSlideDown" style="display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:16px;">
        <div style="width:40px;height:40px;background:var(--primary);border-radius:12px;display:flex;align-items:center;justify-content:center;">
          <span style="color:white;font-weight:900;font-size:1.2rem;font-family:'Inter',sans-serif;">R</span>
        </div>
      </div>
      <h1 class="intro-typewriter" style="font-size:clamp(2.5rem,6vw,4.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;">RezerVistA POS</h1>
      <p class="ia-fadeIn ia-d700" style="color:var(--text-muted);font-size:1rem;font-weight:500;margin-top:20px;letter-spacing:0.2em;text-transform:uppercase;">Satış Noktası Çözümü</p>
      <div class="ia-fadeIn ia-d700" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:28px;">
        <div style="width:8px;height:8px;border-radius:50%;background:#10B981;"></div>
        <span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);letter-spacing:0.15em;text-transform:uppercase;">v4.5 — Hazır</span>
      </div>
    </div>
  </div>

  <!-- Scene 2: Speed -->
  <div class="intro-scene" x-show="scene === 2" x-cloak>
    <div style="max-width:640px;width:100%;display:flex;align-items:center;gap:60px;flex-wrap:wrap;justify-content:center;">
      <div class="ia-slideInLeft" style="flex:1;min-width:260px;">
        <div style="width:72px;height:72px;background:var(--surface);border-radius:20px;display:flex;align-items:center;justify-content:center;margin-bottom:22px;border:1.5px solid var(--border);">
          <i class="fa-solid fa-bolt" style="color:var(--primary);font-size:1.8rem;"></i>
        </div>
        <div style="font-size:clamp(3rem,6vw,4.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;line-height:1;">15<span style="color:var(--primary);">ms</span></div>
        <p style="color:var(--text-subtle);font-size:1.1rem;font-weight:400;margin-top:12px;line-height:1.6;">Maksimum yanıt süresi. İnternet olmadan bile yerel ağda tam hızda çalışır.</p>
      </div>
      <div class="ia-slideInRight" style="flex-shrink:0;">
        <div style="width:180px;height:180px;border-radius:50%;border:6px solid var(--border);display:flex;align-items:center;justify-content:center;position:relative;animation:introPulseBrand 2s infinite;">
          <div style="text-align:center;"><div style="font-size:2.5rem;font-weight:900;color:var(--primary);font-family:'Inter',sans-serif;">~15</div><div style="font-size:0.65rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.15em;">ms avg</div></div>
          <svg style="position:absolute;inset:0;" viewBox="0 0 192 192"><circle cx="96" cy="96" r="86" fill="none" stroke="var(--border)" stroke-width="6"/><circle cx="96" cy="96" r="86" fill="none" stroke="var(--primary)" stroke-width="6" stroke-dasharray="540" stroke-dashoffset="54" stroke-linecap="round" transform="rotate(-90 96 96)" class="intro-net-line"/></svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Scene 3: Table Management -->
  <div class="intro-scene" x-show="scene === 3" x-cloak>
    <div style="max-width:580px;width:100%;">
      <div class="ia-fadeSlideDown" style="text-align:center;margin-bottom:32px;">
        <div style="display:inline-flex;align-items:center;gap:10px;padding:6px 16px;background:var(--surface);border:1.5px solid var(--border);border-radius:12px;margin-bottom:12px;">
          <i class="fa-solid fa-table-cells" style="color:var(--primary);font-size:0.8rem;"></i>
          <span style="font-size:0.7rem;font-weight:800;color:var(--primary);text-transform:uppercase;letter-spacing:0.15em;">Masa Yönetimi</span>
        </div>
        <h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;">Tüm Masaları <span style="color:var(--primary);">Tek Ekranda</span></h2>
      </div>
      <div class="ia-scaleIn ia-d300" style="display:grid;grid-template-columns:repeat(6,1fr);gap:8px;">
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">01</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--primary);font-size:0.7rem;background:var(--primary);color:white;box-shadow:0 4px 16px rgba(98,0,238,0.3);transform:scale(1.05);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Dolu</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">02</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid rgba(244,63,94,0.15);font-size:0.7rem;background:rgba(244,63,94,0.05);color:#F43F5E;"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Meşgul</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">03</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">04</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--primary);font-size:0.7rem;background:var(--primary);color:white;box-shadow:0 4px 16px rgba(98,0,238,0.3);transform:scale(1.05);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Dolu</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">05</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid rgba(244,63,94,0.15);font-size:0.7rem;background:rgba(244,63,94,0.05);color:#F43F5E;"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Meşgul</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">06</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">07</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">08</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--primary);font-size:0.7rem;background:var(--primary);color:white;box-shadow:0 4px 16px rgba(98,0,238,0.3);transform:scale(1.05);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Dolu</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">09</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">10</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">11</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid rgba(244,63,94,0.15);font-size:0.7rem;background:rgba(244,63,94,0.05);color:#F43F5E;"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Meşgul</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">12</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">13</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--primary);font-size:0.7rem;background:var(--primary);color:white;box-shadow:0 4px 16px rgba(98,0,238,0.3);transform:scale(1.05);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Dolu</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">14</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid rgba(244,63,94,0.15);font-size:0.7rem;background:rgba(244,63,94,0.05);color:#F43F5E;"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Meşgul</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">15</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">16</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid var(--border);font-size:0.7rem;background:white;color:var(--text-muted);"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Boş</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">17</span></div>
        <div style="aspect-ratio:1;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:900;border:1.5px solid rgba(244,63,94,0.15);font-size:0.7rem;background:rgba(244,63,94,0.05);color:#F43F5E;"><span style="font-size:0.5rem;letter-spacing:0.12em;text-transform:uppercase;">Meşgul</span><span style="font-size:1rem;font-family:'Inter',sans-serif;">18</span></div>
      </div>
    </div>
  </div>

  <!-- Scene 4: Reporting -->
  <div class="intro-scene" x-show="scene === 4" x-cloak>
    <div style="max-width:640px;width:100%;display:flex;align-items:center;gap:60px;flex-wrap:wrap;justify-content:center;">
      <div class="ia-slideInLeft" style="flex:1;min-width:260px;">
        <div style="width:72px;height:72px;background:var(--surface);border-radius:20px;display:flex;align-items:center;justify-content:center;margin-bottom:22px;border:1.5px solid var(--border);">
          <i class="fa-solid fa-chart-column" style="color:var(--primary);font-size:1.8rem;"></i>
        </div>
        <div style="font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;line-height:1.05;">Anlık<br><span style="color:var(--primary);">Raporlama</span></div>
        <p style="color:var(--text-subtle);font-size:1rem;font-weight:400;margin-top:12px;line-height:1.6;">Günlük, haftalık, aylık satış grafikleri. Personel performansı. PDF ihracat.</p>
        <div style="display:flex;gap:14px;margin-top:20px;">
          <div style="padding:8px 16px;background:var(--surface);border-radius:12px;border:1.5px solid var(--border);">
            <div style="font-size:1.2rem;font-weight:900;color:var(--primary);font-family:'Inter',sans-serif;">₺124K</div>
            <div style="font-size:0.6rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Bu Ay</div>
          </div>
          <div style="padding:8px 16px;background:rgba(16,185,129,0.05);border-radius:12px;border:1.5px solid rgba(16,185,129,0.15);">
            <div style="font-size:1.2rem;font-weight:900;color:#10B981;font-family:'Inter',sans-serif;">+32%</div>
            <div style="font-size:0.6rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Büyüme</div>
          </div>
        </div>
      </div>
      <div class="ia-slideInRight" style="flex-shrink:0;display:flex;align-items:flex-end;gap:10px;height:160px;">
        <div class="intro-bar" style="height:50%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.1s both;"></div>
        <div class="intro-bar" style="height:75%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.2s both;"></div>
        <div class="intro-bar" style="height:40%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.3s both;"></div>
        <div class="intro-bar" style="height:90%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.4s both;background:#A855F7;"></div>
        <div class="intro-bar" style="height:60%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.5s both;"></div>
        <div class="intro-bar" style="height:100%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.6s both;"></div>
        <div class="intro-bar" style="height:70%;animation:introBarGrow 0.8s cubic-bezier(0.19,1,0.22,1) 0.7s both;background:#A855F7;"></div>
      </div>
    </div>
  </div>

  <!-- Scene 5: Cloud + Local Network -->
  <div class="intro-scene" x-show="scene === 5" x-cloak>
    <div style="max-width:580px;width:100%;text-align:center;">
      <div class="ia-fadeSlideDown" style="margin-bottom:28px;">
        <div style="display:inline-flex;align-items:center;gap:10px;padding:6px 16px;background:var(--surface);border:1.5px solid var(--border);border-radius:12px;margin-bottom:12px;">
          <i class="fa-solid fa-cloud" style="color:var(--primary);font-size:0.8rem;"></i>
          <span style="font-size:0.7rem;font-weight:800;color:var(--primary);text-transform:uppercase;letter-spacing:0.15em;">Hibrit Altyapı</span>
        </div>
        <h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;">Bulut <span style="color:var(--primary);">&</span> Yerel Ağ</h2>
        <p style="color:var(--text-subtle);font-size:1rem;font-weight:400;margin-top:10px;">İnternet kesilse de veriler kaybolmaz. Tüm terminaller birbirini görür.</p>
      </div>
      <div class="ia-scaleIn ia-d300" style="display:flex;align-items:center;justify-content:center;">
        <svg width="460" height="120" viewBox="0 0 460 120" fill="none" style="max-width:100%;">
          <circle cx="70" cy="60" r="36" fill="var(--surface)" stroke="var(--primary)" stroke-width="2"/>
          <text x="70" y="56" text-anchor="middle" fill="var(--primary)" font-size="16" font-family="Font Awesome 6 Free" font-weight="900">&#xf0c2;</text>
          <text x="70" y="74" text-anchor="middle" fill="var(--primary)" font-size="8" font-family="Inter" font-weight="800" letter-spacing="1">CLOUD</text>
          <line x1="106" y1="60" x2="354" y2="60" stroke="var(--primary)" stroke-width="2" stroke-dasharray="8 6" class="intro-net-line"/>
          <circle cx="230" cy="60" r="6" fill="var(--primary)" opacity="0.3" style="animation:introPulseBrand 1.5s infinite"/>
          <circle cx="230" cy="60" r="3" fill="var(--primary)"/>
          <circle cx="390" cy="60" r="36" fill="var(--surface)" stroke="var(--primary)" stroke-width="2"/>
          <text x="390" y="56" text-anchor="middle" fill="var(--primary)" font-size="16" font-family="Font Awesome 6 Free" font-weight="900">&#xf108;</text>
          <text x="390" y="74" text-anchor="middle" fill="var(--primary)" font-size="8" font-family="Inter" font-weight="800" letter-spacing="1">LOCAL</text>
        </svg>
      </div>
      <div class="ia-fadeIn ia-d700" style="display:flex;justify-content:center;gap:20px;margin-top:20px;">
        <div style="display:flex;align-items:center;gap:6px;"><div style="width:8px;height:8px;border-radius:50%;background:#10B981;"></div><span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.1em;">Senkron: %100</span></div>
        <div style="display:flex;align-items:center;gap:6px;"><div style="width:8px;height:8px;border-radius:50%;background:var(--primary);"></div><span style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.1em;">Bağlı</span></div>
      </div>
    </div>
  </div>

  <!-- Scene 6: Feature Burst -->
  <div class="intro-scene" x-show="scene === 6" x-cloak>
    <div style="text-align:center;margin-bottom:32px;" class="ia-fadeSlideDown">
      <h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--text);font-family:'Inter',sans-serif;letter-spacing:-0.04em;">Her Şey <span style="color:var(--primary);">Hazır.</span></h2>
      <p style="color:var(--text-muted);font-size:1rem;font-weight:400;margin-top:10px;">Saniyeler içinde kurulum. Yıllarca kararlı çalışma.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;max-width:640px;width:100%;" class="ia-scaleIn ia-d200">
      <div style="background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:22px 14px;text-align:center;">
        <i class="fa-solid fa-bolt" style="color:var(--primary);font-size:1.5rem;margin-bottom:10px;display:block;"></i>
        <div style="font-size:0.85rem;font-weight:800;color:var(--text);">Ultra Hız</div>
        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:4px;">15ms latency</div>
      </div>
      <div style="background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:22px 14px;text-align:center;">
        <i class="fa-solid fa-table-cells" style="color:var(--primary);font-size:1.5rem;margin-bottom:10px;display:block;"></i>
        <div style="font-size:0.85rem;font-weight:800;color:var(--text);">Masa Grid</div>
        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:4px;">Sürükle & bırak</div>
      </div>
      <div style="background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:22px 14px;text-align:center;">
        <i class="fa-solid fa-chart-column" style="color:var(--primary);font-size:1.5rem;margin-bottom:10px;display:block;"></i>
        <div style="font-size:0.85rem;font-weight:800;color:var(--text);">Raporlama</div>
        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:4px;">PDF & Excel</div>
      </div>
      <div style="background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:22px 14px;text-align:center;">
        <i class="fa-solid fa-cloud" style="color:var(--primary);font-size:1.5rem;margin-bottom:10px;display:block;"></i>
        <div style="font-size:0.85rem;font-weight:800;color:var(--text);">Hibrit Ağ</div>
        <div style="font-size:0.7rem;color:var(--text-muted);margin-top:4px;">%99.9 uptime</div>
      </div>
    </div>
    <div class="ia-fadeIn ia-d700" style="margin-top:36px;">
      <button onclick="document.querySelector('[x-data]').__x.$data.skip()" style="padding:14px 32px;background:var(--primary);color:white;border:none;border-radius:14px;font-weight:800;font-size:0.875rem;letter-spacing:0.1em;text-transform:uppercase;cursor:pointer;box-shadow:0 8px 24px rgba(98,0,238,0.3);transition:all 0.3s;font-family:'Inter',sans-serif;">
        POS Sistemini İncele &rarr;
      </button>
    </div>
  </div>

</div><!-- /intro-overlay -->

<!-- ════════════════ MAIN PAGE ════════════════ -->
<div id="main-page" :class="{ 'visible': done }">

<!-- ════════════════ HERO ════════════════ -->
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-grid"></div>

  <div class="hero-inner">
    <div>
      <div class="hero-badge">
        <div class="badge-ping"><div class="badge-dot"></div></div>
        Yeni Nesil Donanım · v1
      </div>
      <h1 class="hero-title">
        İşletmenizin<br>
        <span class="grad">Sinir Sistemi.</span>
      </h1>
      <p class="hero-sub">
        Sadece bir POS değil — rezervasyon, mutfak, stok ve analitik yönetimini
        sıfır gecikmeyle tek bir ekosistemde buluşturan akıllı terminal.
      </p>
      <div class="hero-actions">
        <a href="#features" class="btn-primary">
          <i class="fa-solid fa-arrow-down" style="font-size:.75rem;"></i>
          Özellikleri Keşfet
        </a>
        <a href="{{ route('pages.contact') }}" class="btn-outline">
          Teklif Al
          <i class="fa-solid fa-arrow-right" style="font-size:.72rem;"></i>
        </a>
      </div>
      <div class="hero-stats">
        <div><div class="stat-num">3<span>K+</span></div><div class="stat-label">Aktif İşletme</div></div>
        <div><div class="stat-num">0.2<span>ms</span></div><div class="stat-label">Senkronizasyon</div></div>
        <div><div class="stat-num">99.9<span>%</span></div><div class="stat-label">Uptime Garantisi</div></div>
      </div>
    </div>

    <!-- Terminal -->
    <div class="terminal-wrap">
      <div class="term-glow"></div>
      <div class="terminal">
        <div class="t-header">
          <div class="t-dots">
            <div class="t-dot t-dot-r"></div>
            <div class="t-dot t-dot-o"></div>
            <div class="t-dot t-dot-g"></div>
          </div>
          <span class="t-label">RezerVistA Pos v1</span>
          <div style="width:36px;"></div>
        </div>
        <div class="t-body">
          <div class="t-topbar">
            <div>
              <div class="t-greet">Merhaba, Ayberk 👋</div>
              <div class="t-name">Günlük Özet</div>
            </div>
            <div class="t-time" id="live-time">--:--</div>
          </div>
          <div class="t-kpis">
            <div class="t-kpi f">
              <div class="t-kpi-l">Bugünkü Ciro</div>
              <div class="t-kpi-v">₺18.4K</div>
              <div class="t-kpi-t g">↑ %23 bu hafta</div>
            </div>
            <div class="t-kpi">
              <div class="t-kpi-l">Siparişler</div>
              <div class="t-kpi-v">142</div>
              <div class="t-kpi-t m">12 aktif</div>
            </div>
          </div>
          <div class="t-chart-l">Saatlik Satış</div>
          <div class="t-chart">
            <div class="t-b" style="height:28%;"></div>
            <div class="t-b" style="height:46%;"></div>
            <div class="t-b" style="height:60%;"></div>
            <div class="t-b" style="height:36%;"></div>
            <div class="t-b hi" style="height:86%;"></div>
            <div class="t-b ac" style="height:70%;"></div>
            <div class="t-b" style="height:48%;"></div>
            <div class="t-b" style="height:56%;"></div>
          </div>
          <div class="t-orders">
            <div class="t-order">
              <div class="t-ol">
                <div class="t-ic ic-p"><i class="fa-solid fa-utensils"></i></div>
                <div>
                  <div class="t-ot">Masa 7 — 4 Kişi</div>
                  <div class="t-os">Ana yemek · 3 içecek</div>
                </div>
              </div>
              <span class="bdg bdg-ok">Hazır</span>
            </div>
            <div class="t-order">
              <div class="t-ol">
                <div class="t-ic ic-t"><i class="fa-solid fa-motorcycle"></i></div>
                <div>
                  <div class="t-ot">Kurye #KY-041</div>
                  <div class="t-os">Yolda · ~12 dk</div>
                </div>
              </div>
              <span class="bdg bdg-go">Yolda</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════ FEATURES ════════════════ -->
<section id="features" class="section feat-bg">
  <div class="section-inner">
    <div class="reveal">
      <span class="section-tag">Temel Özellikler</span>
      <h2 class="section-title">Mikro Detaylar,<br><em>Makro Performans.</em></h2>
      <p class="section-sub">Donanım ve yazılımın mükemmel uyumuyla işletmenizi bir adım öteye taşıyın.</p>
    </div>

    <div class="features-grid">
      <div class="feat-card span2 reveal">
        <div>
          <div class="feat-icon fi-t"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="feat-title">Anlık Senkronizasyon</h3>
          <p class="feat-desc">Mutfak, kasa ve garson terminalleri arasında 0.2ms gecikmeyle veri aktarımı. Siparişler asla kaybolmaz, müşteriler asla beklemez.</p>
          <div class="feat-chks">
            <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Real-time WebSocket DB Sync</div>
            <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Cloud + Yerel Anlık Yedek</div>
            <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Çoklu Terminal Desteği</div>
          </div>
        </div>
        <div class="mini-viz">
          <div class="mv-l">Ağ Gecikmesi Monitörü</div>
          <div class="mv-bars">
            <div class="mv-b" style="height:36%;background:rgba(98,0,238,0.12);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:52%;background:rgba(98,0,238,0.18);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:26%;background:rgba(98,0,238,0.12);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:70%;background:var(--primary);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:16%;background:rgba(3,218,198,0.5);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:42%;background:rgba(98,0,238,0.14);border-radius:4px 4px 0 0;"></div>
            <div class="mv-b" style="height:30%;background:rgba(98,0,238,0.10);border-radius:4px 4px 0 0;"></div>
          </div>
          <div class="mv-nums">
            <div class="mv-n"><div class="mv-nv" style="color:var(--primary);">0.2ms</div><div class="mv-nl">Ort. Gecikme</div></div>
            <div class="mv-n"><div class="mv-nv">99.9%</div><div class="mv-nl">Uptime</div></div>
            <div class="mv-n"><div class="mv-nv" style="color:#0ABBA3;">3K+</div><div class="mv-nl">Aktif Cihaz</div></div>
          </div>
        </div>
      </div>

      <div class="feat-card dark reveal" style="transition-delay:.08s;">
        <div class="feat-icon fi-w"><i class="fa-solid fa-shield-halved"></i></div>
        <h3 class="feat-title">Askeri Seviye Güvenlik</h3>
        <p class="feat-desc">256-bit AES şifreleme ve PCI-DSS uyumlu altyapı. Biyometrik giriş ile yalnızca yetkili personel erişim sağlar.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> E2EE Uçtan Uca Şifreleme</div>
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Biyometrik Kimlik Doğrulama</div>
        </div>
      </div>

      <div class="feat-card reveal" style="transition-delay:.13s;">
        <div class="feat-icon fi-p"><i class="fa-solid fa-chart-line"></i></div>
        <h3 class="feat-title">AI Destekli Analitik</h3>
        <p class="feat-desc">Yapay zeka ile satış trendlerini öngörün. En karlı masaları ve personel performansını anlık izleyin.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Tahminsel Satış Motoru</div>
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Isı Haritası Raporları</div>
        </div>
      </div>

      <div class="feat-card reveal" style="transition-delay:.18s;">
        <div class="feat-icon fi-t"><i class="fa-solid fa-qrcode"></i></div>
        <h3 class="feat-title">QR & Dijital Menü</h3>
        <p class="feat-desc">Masadaki müşteriler kodu okutarak menüye anında ulaşır, sipariş verir. Güncellemeler saniyeler içinde yayınlanır.</p>
      </div>

      <div class="feat-card reveal" style="transition-delay:.23s;">
        <div class="feat-icon fi-s"><i class="fa-solid fa-boxes-stacked"></i></div>
        <h3 class="feat-title">Akıllı Stok Yönetimi</h3>
        <p class="feat-desc">Kritik eşiğin altına düşen malzemeleri tespit edin, tedarikçiye otomatik sipariş gönderin.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Otomatik Sipariş Tetikleyici</div>
          <div class="feat-chk"><div class="chk-dot"><i class="fa-solid fa-check"></i></div> Tedarikçi Entegrasyonu</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════ TESTIMONIALS ════════════════ -->
<section id="testimonials" class="section testi-bg">
  <div class="section-inner">
    <div class="reveal" style="text-align:center;">
      <span class="section-tag">Müşteri Görüşleri</span>
      <h2 class="section-title">İşletmeler<br><em>Konuşuyor.</em></h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card reveal">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-q">"Kurulumdan üç ay sonra sipariş kayıplarımız sıfıra indi. Mutfak ile kasa artık aynı dili konuşuyor. Bunu daha önce keşfetmediğime üzüldüm."</p>
        <div class="testi-author">
          <div class="testi-av">M</div>
          <div><div class="testi-name">Mustafa Yılmaz</div><div class="testi-role">Lezzet Lokantası, İstanbul</div></div>
        </div>
      </div>
      <div class="testi-card reveal" style="transition-delay:.1s;">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-q">"Analitik paneli inanılmaz. Hangi ürünün hangi saatte en çok satıldığını görünce menüyü ve çizelgeyi düzenledik. Ciro %31 arttı."</p>
        <div class="testi-author">
          <div class="testi-av" style="background:linear-gradient(135deg,#0ABBA3,#06B6D4);">S</div>
          <div><div class="testi-name">Selin Kaya</div><div class="testi-role">Café Aura, Ankara</div></div>
        </div>
      </div>
      <div class="testi-card reveal" style="transition-delay:.2s;">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-q">"7 şubemizi tek panelden yönetiyorum. Stok uyarıları, personel raporları, anlık satış — hepsi elimde. RezerVist olmadan nasıl çalıştım bilmiyorum."</p>
        <div class="testi-author">
          <div class="testi-av" style="background:linear-gradient(135deg,#F59E0B,#EF4444);">E</div>
          <div><div class="testi-name">Emre Demir</div><div class="testi-role">Burger Zinciri, 7 Şube</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════ CTA ════════════════ -->
<section class="section cta-section" id="contact">
  <div class="section-inner cta-inner">
    <span class="section-tag" style="color:rgba(255,255,255,0.55);">Hazır Mısınız?</span>
    <h2 class="cta-title">İşletmenizi Bugün<br>Dijitalleştirin.</h2>
    <p class="cta-sub">14 gün ücretsiz deneyin. Kredi kartı gerekmez, kurulum desteği dahil.</p>
    <div class="cta-actions">
      <a href="{{ route('register') }}" class="btn-white"><i class="fa-solid fa-rocket" style="font-size:.8rem;"></i> Ücretsiz Başlat</a>
      <a href="{{ route('pages.contact') }}" class="btn-ghost-w">Demo Talep Et</a>
    </div>
  </div>
</section>

</div><!-- /main-page -->
</div><!-- /x-data -->

<script>
  // Canlı saat
  function tick() {
    const el = document.getElementById('live-time');
    if (el) el.textContent = new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
  }
  tick(); setInterval(tick, 1000);

  // Scroll reveal
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.08 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  // ─── POS Intro Animation ───
  function posIntro() {
    return {
      scene: 0,
      done: false,
      progress: 0,
      totalScenes: 7,
      timer: null,
      progressTimer: null,
      durations: [2000, 2200, 2500, 2800, 2800, 2800, 3500],

      start() {
        this.runScene(0);
      },

      runScene(index) {
        if (index >= this.totalScenes) { this.finish(); return; }
        this.scene = index;
        const duration = this.durations[index];
        const startProgress = (index / this.totalScenes) * 100;
        const endProgress = ((index + 1) / this.totalScenes) * 100;

        clearInterval(this.progressTimer);
        const steps = 60;
        const stepTime = duration / steps;
        const stepSize = (endProgress - startProgress) / steps;
        let step = 0;
        this.progress = startProgress;
        this.progressTimer = setInterval(() => {
          step++;
          this.progress = startProgress + (stepSize * step);
          if (step >= steps) clearInterval(this.progressTimer);
        }, stepTime);

        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.runScene(index + 1), duration);
      },

      skip() {
        clearTimeout(this.timer);
        clearInterval(this.progressTimer);
        this.progress = 100;
        setTimeout(() => this.finish(), 300);
      },

      finish() {
        clearTimeout(this.timer);
        clearInterval(this.progressTimer);
        this.done = true;
        window.scrollTo(0, 0);
      }
    }
  }
</script>
@endsection