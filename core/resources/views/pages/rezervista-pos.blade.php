@extends('layouts.app')

@section('title', 'RezerVistA POS — Akıllı İşletme Terminali')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

<style>
:root{
  --p:#6200EE;--pd:#4B00B0;--pl:#7C3AED;--pl2:#A78BFA;
  --acc:#0EA5E9;--acc2:#06B6D4;--grn:#10B981;--ylw:#F59E0B;--red:#FF3D71;
  --bg:#FFFFFF;--bg2:#F8FAFC;--sf:#F1F5F9;--sf2:#E2E8F0;--sf3:#F0FDF4;
  --br:rgba(15,23,42,0.08);--brh:rgba(98,0,238,0.15);--brx:#A78BFA;
  --tx:#0F172A;--txm:#475569;--txs:#64748B;
  --sh:rgba(98,0,238,0.09);--shd:rgba(98,0,238,0.18);
  --r:20px;--r2:28px;--r3:36px;
  --ff-h:'Outfit',sans-serif;
  --ff-b:'Outfit',sans-serif;
  --ff-c:'JetBrains Mono',monospace;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.pos-page{font-family:var(--ff-b);color:var(--tx);background:var(--bg);overflow-x:hidden;-webkit-font-smoothing:antialiased;}
.pos-page a{text-decoration:none;color:inherit;}
.pos-page img{max-width:100%;display:block;}

/* ============================
   ANIMATIONS
============================ */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
@keyframes fadeDown{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}
@keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
@keyframes scaleIn{from{opacity:0;transform:scale(0.82);}to{opacity:1;transform:scale(1);}}
@keyframes scaleBig{from{opacity:0;transform:scale(0.4);}to{opacity:1;transform:scale(1);}}
@keyframes slideL{from{opacity:0;transform:translateX(-44px);}to{opacity:1;transform:translateX(0);}}
@keyframes slideR{from{opacity:0;transform:translateX(44px);}to{opacity:1;transform:translateX(0);}}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(91,33,182,0.35);}50%{box-shadow:0 0 0 18px rgba(91,33,182,0);}}
@keyframes pulseGrn{0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,0.4);}50%{box-shadow:0 0 0 14px rgba(16,185,129,0);}}
@keyframes barGrow{from{height:0;opacity:0;}}
@keyframes lineAnim{from{stroke-dashoffset:500;}to{stroke-dashoffset:0;}}
@keyframes typeAnim{from{max-width:0;}to{max-width:100%;}}
@keyframes blink{50%{border-color:transparent;}}
@keyframes rotate{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
@keyframes orb{0%,100%{transform:translate(0,0) scale(1);}50%{transform:translate(14px,-14px) scale(1.05);}}
@keyframes shine{from{left:-100%;}to{left:160%;}}
@keyframes trustScroll{0%{transform:translateX(0);}100%{transform:translateX(-50%);}}
@keyframes heroFloat{0%,100%{transform:translateY(0);}50%{transform:translateY(-7px);}}
@keyframes gradMove{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
@keyframes dotFly{0%{transform:translate(0,0) scale(1);opacity:1;}100%{transform:translate(var(--dx,20px),var(--dy,-30px)) scale(0);opacity:0;}}
@keyframes countUp{from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);}}
@keyframes notifSlide{from{opacity:0;transform:translateX(120px);}to{opacity:1;transform:translateX(0);}}
@keyframes progress{from{width:0;}to{width:100%;}}
@keyframes waveAnim{0%,100%{transform:scaleY(0.5);}50%{transform:scaleY(1.5);}}
@keyframes bgShift{0%,100%{background-position:0 0;}100%{background-position:40px 40px;}}

.a-fu{animation:fadeUp 0.65s cubic-bezier(0.19,1,0.22,1) both;}
.a-fd{animation:fadeDown 0.65s cubic-bezier(0.19,1,0.22,1) both;}
.a-f{animation:fadeIn 0.5s ease both;}
.a-sc{animation:scaleIn 0.65s cubic-bezier(0.19,1,0.22,1) both;}
.a-scb{animation:scaleBig 0.8s cubic-bezier(0.19,1,0.22,1) both;}
.a-sl{animation:slideL 0.65s cubic-bezier(0.19,1,0.22,1) both;}
.a-sr{animation:slideR 0.65s cubic-bezier(0.19,1,0.22,1) both;}
.d1{animation-delay:.1s;}.d2{animation-delay:.2s;}.d3{animation-delay:.3s;}
.d4{animation-delay:.4s;}.d5{animation-delay:.5s;}.d6{animation-delay:.6s;}
.d7{animation-delay:.7s;}.d8{animation-delay:.8s;}.d9{animation-delay:.9s;}

/* ============================
   INTRO OVERLAY
============================ */
#intro-overlay{
  position:fixed;inset:0;z-index:99999;background:#fff;
  display:flex;align-items:center;justify-content:center;
  transition:opacity 0.85s cubic-bezier(0.4,0,0.2,1),visibility 0.85s;
}
#intro-overlay.done{opacity:0;visibility:hidden;pointer-events:none;}
#intro-progress-bar{position:absolute;top:0;left:0;right:0;height:3px;background:var(--sf);}
#intro-progress-fill{height:100%;background:linear-gradient(90deg,var(--p),var(--acc));transition:width 0.12s linear;box-shadow:0 0 10px rgba(91,33,182,0.5);}
#skip-intro{
  position:absolute;bottom:32px;right:32px;
  padding:9px 20px;border-radius:50px;border:1.5px solid var(--br);
  color:var(--txm);font-family:var(--ff-b);font-size:0.72rem;font-weight:700;
  letter-spacing:0.12em;text-transform:uppercase;cursor:pointer;
  background:transparent;transition:all 0.2s;display:flex;align-items:center;gap:7px;
}
#skip-intro:hover{background:var(--p);color:white;border-color:var(--p);}
.intro-dots{position:absolute;bottom:32px;left:50%;transform:translateX(-50%);display:flex;gap:6px;}
.i-dot{width:5px;height:5px;border-radius:50%;background:var(--br);transition:all 0.3s;}
.i-dot.on{background:var(--p);transform:scale(1.5);}
[x-cloak]{display:none!important;}

.iscene{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;padding:64px 40px;}

.i-chip{
  display:inline-flex;align-items:center;gap:7px;
  padding:5px 13px;background:var(--sf);border:1.5px solid var(--br);
  border-radius:10px;font-size:0.67rem;font-weight:700;color:var(--p);
  text-transform:uppercase;letter-spacing:0.13em;font-family:var(--ff-b);
}
.i-pill{
  display:inline-flex;align-items:center;gap:6px;
  padding:4px 12px;border-radius:100px;font-size:0.68rem;font-weight:700;
  letter-spacing:0.1em;text-transform:uppercase;font-family:var(--ff-b);
}
.i-statcard{background:var(--sf);border:1.5px solid var(--br);border-radius:13px;padding:12px 16px;}
.i-sv{font-family:var(--ff-h);font-size:1.3rem;font-weight:700;color:var(--tx);letter-spacing:-0.03em;}
.i-sl{font-size:0.6rem;color:var(--txm);font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-top:3px;}
.i-type{overflow:hidden;border-right:3px solid var(--p);white-space:nowrap;display:block;animation:typeAnim 1.5s steps(14,end) both,blink 0.5s step-end infinite alternate;}

/* ============================
   LAYOUT BASE
============================ */
#main-page{opacity:0;transition:opacity 0.85s ease;}
#main-page.on{opacity:1;}
.sec{padding:120px 64px;}
.sec-sm{padding:80px 64px;}
.sec-in{max-width:1380px;margin:0 auto;width:100%;}
.sec-tag{
  display:inline-flex;align-items:center;gap:7px;
  font-size:0.7rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;
  color:var(--p);margin-bottom:14px;font-family:var(--ff-b);
}
.sec-tag::before{content:'';width:18px;height:1.5px;background:var(--p);}
.sec-h{font-family:var(--ff-h);font-size:clamp(2.2rem,4vw,3.6rem);font-weight:900;letter-spacing:-0.04em;color:var(--tx);line-height:1.0;margin-bottom:18px;}
.sec-h em{font-style:normal;color:var(--p);}
.sec-sub{font-size:1rem;color:var(--txs);font-weight:400;line-height:1.75;max-width:520px;}
.reveal{opacity:0;transform:translateY(22px);transition:opacity 0.6s cubic-bezier(0.19,1,0.22,1),transform 0.6s cubic-bezier(0.19,1,0.22,1);}
.reveal.on{opacity:1;transform:translateY(0);}

/* ============================
   BUTTONS
============================ */
.btn-p{
  display:inline-flex;align-items:center;gap:10px;padding:15px 32px;
  background:var(--p);color:white;font-weight:700;font-size:0.95rem;
  border-radius:15px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);border:none;cursor:pointer;
  letter-spacing:-0.01em;box-shadow:0 8px 24px rgba(98,0,238,0.22);
  position:relative;overflow:hidden;font-family:var(--ff-b);
}
.btn-p::after{content:'';position:absolute;top:0;left:-110%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent);animation:shine 4s ease infinite 1s;}
.btn-p:hover{background:var(--pd);transform:translateY(-3px);box-shadow:0 15px 38px rgba(98,0,238,0.3);}
.btn-o{
  display:inline-flex;align-items:center;gap:10px;padding:15px 32px;
  border:1.5px solid var(--brh);color:var(--txm);font-weight:600;font-size:0.95rem;
  border-radius:15px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);cursor:pointer;background:white;
  letter-spacing:-0.01em;font-family:var(--ff-b);
}
.btn-o:hover{border-color:var(--p);color:var(--p);background:var(--sf);transform:translateY(-3px);box-shadow:0 12px 30px var(--sh);}
.btn-w{
  display:inline-flex;align-items:center;gap:11px;padding:15px 32px;
  background:white;color:var(--p);font-weight:700;font-size:0.95rem;
  border-radius:15px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);letter-spacing:-0.01em;
  box-shadow:0 10px 30px rgba(0,0,0,0.1);font-family:var(--ff-b);
}
.btn-w:hover{transform:translateY(-4px);box-shadow:0 20px 48px rgba(0,0,0,0.16);color:var(--pd);}
.btn-gl{
  display:inline-flex;align-items:center;gap:10px;padding:15px 32px;
  border:1.5px solid rgba(255,255,255,0.22);color:white;font-weight:600;
  font-size:0.95rem;border-radius:15px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);
  background:rgba(255,255,255,0.08);font-family:var(--ff-b);backdrop-filter:blur(4px);
}
.btn-gl:hover{background:rgba(255,255,255,0.16);border-color:rgba(255,255,255,0.5);transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,0.2);}
.btn-acc{
  display:inline-flex;align-items:center;gap:9px;padding:14px 26px;
  background:linear-gradient(135deg,var(--acc),var(--acc2));color:white;font-weight:700;
  font-size:0.9375rem;border-radius:13px;transition:all 0.25s;border:none;cursor:pointer;
  box-shadow:0 4px 16px rgba(14,165,233,0.3);font-family:var(--ff-b);
}
.btn-acc:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(14,165,233,0.4);}

/* ============================
   HERO
============================ */
.hero{
  min-height:100vh;display:flex;align-items:center;
  padding:96px 64px 80px;position:relative;overflow:hidden;background:var(--bg2);
}
.hero-noise{position:absolute;inset:0;opacity:0.025;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");pointer-events:none;}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(91,33,182,0.035) 1px,transparent 1px),linear-gradient(90deg,rgba(91,33,182,0.035) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 80% at 50% 40%,black 10%,transparent 75%);}
.hero-orb{position:absolute;border-radius:50%;pointer-events:none;}
.hero-orb-1{width:960px;height:960px;background:radial-gradient(circle,rgba(91,33,182,0.055) 0%,transparent 60%);top:-320px;left:-220px;animation:orb 11s ease-in-out infinite;}
.hero-orb-2{width:640px;height:640px;background:radial-gradient(circle,rgba(14,165,233,0.06) 0%,transparent 60%);bottom:-180px;right:-120px;animation:orb 14s ease-in-out infinite reverse;}
.hero-orb-3{width:280px;height:280px;background:radial-gradient(circle,rgba(167,139,250,0.08) 0%,transparent 70%);top:38%;left:42%;animation:orb 8s ease-in-out infinite 2s;}
.hero-in{max-width:1380px;margin:0 auto;width:100%;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;position:relative;z-index:1;}

/* Badge */
.hero-badge{display:inline-flex;align-items:center;gap:9px;padding:5px 14px 5px 5px;border:1.5px solid rgba(91,33,182,0.15);border-radius:100px;background:rgba(91,33,182,0.04);font-size:0.71rem;font-weight:700;color:var(--p);letter-spacing:0.07em;text-transform:uppercase;margin-bottom:24px;font-family:var(--ff-b);}
.badge-ring{width:22px;height:22px;border-radius:50%;background:rgba(91,33,182,0.08);display:flex;align-items:center;justify-content:center;}
.badge-dot{width:7px;height:7px;border-radius:50%;background:var(--p);animation:pulse 2.2s infinite;}
.hero-h{font-family:var(--ff-h);font-size:clamp(3.2rem,5.8vw,6rem);font-weight:900;line-height:0.9;letter-spacing:-0.05em;color:var(--tx);margin-bottom:22px;}
.hero-h .g{background:linear-gradient(135deg,var(--p) 0%,#A78BFA 50%,var(--acc) 100%);background-size:200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:gradMove 4s ease infinite;}
.hero-sub{font-size:1.05rem;font-weight:400;line-height:1.78;color:var(--txs);max-width:500px;margin-bottom:36px;}
.hero-acts{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:48px;}
.hero-trust{display:flex;align-items:center;gap:20px;font-size:0.78rem;color:var(--txm);font-weight:600;}
.hero-trust-item{display:flex;align-items:center;gap:6px;}
.hero-trust-item i{color:var(--grn);font-size:0.75rem;}
.hero-stats{display:flex;gap:40px;padding-top:48px;border-top:1.5px solid var(--br);}
.hs-num{font-family:var(--ff-h);font-size:1.9rem;font-weight:800;color:var(--tx);letter-spacing:-0.04em;line-height:1;}
.hs-num span{color:var(--p);}
.hs-lbl{font-size:0.73rem;color:var(--txm);font-weight:500;margin-top:5px;}

/* Terminal */
.terminal-wrap{position:relative;display:flex;align-items:center;justify-content:center;}
.term-glow{position:absolute;width:460px;height:460px;background:radial-gradient(circle,rgba(91,33,182,0.1) 0%,transparent 70%);border-radius:50%;animation:orb 8s ease-in-out infinite;}
.terminal{
  position:relative;z-index:1;width:400px;background:white;border-radius:24px;
  border:1.5px solid var(--br);overflow:hidden;
  box-shadow:0 32px 80px rgba(91,33,182,0.12),0 6px 20px rgba(0,0,0,0.06),inset 0 0 0 1px rgba(255,255,255,0.9);
  transform:perspective(1100px) rotateY(-6deg) rotateX(2deg);
  transition:transform 0.8s cubic-bezier(0.19,1,0.22,1);
  animation:fadeUp 0.9s cubic-bezier(0.19,1,0.22,1) 0.3s both;
}
.terminal:hover{transform:perspective(1100px) rotateY(-1deg) rotateX(0.5deg);}
.t-hdr{height:42px;background:var(--sf);border-bottom:1.5px solid var(--br);display:flex;align-items:center;justify-content:space-between;padding:0 14px;}
.t-dots{display:flex;gap:5px;}
.t-dot{width:10px;height:10px;border-radius:50%;}
.td-r{background:#FF5F57;}.td-o{background:#FFBD2E;}.td-g{background:#28C840;}
.t-lbl{font-size:0.65rem;color:var(--txm);font-weight:600;letter-spacing:0.1em;font-family:var(--ff-c);}
.t-body{padding:18px;}
.t-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.t-greet{font-size:0.66rem;color:var(--txm);font-weight:500;}
.t-name{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:var(--tx);}
.t-time{font-size:0.75rem;color:var(--p);font-weight:700;background:var(--sf);padding:5px 10px;border-radius:100px;border:1px solid var(--br);font-family:var(--ff-c);}
.t-kpis{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;}
.t-kpi{background:var(--sf);border:1.5px solid var(--br);border-radius:12px;padding:12px;}
.t-kpi.hi{background:var(--sf2);border-color:var(--br);}
.t-kl{font-size:0.58rem;color:var(--txm);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:3px;}
.t-kv{font-family:var(--ff-h);font-size:1.3rem;font-weight:700;color:var(--tx);letter-spacing:-0.03em;}
.t-kpi.hi .t-kv{color:var(--p);}
.t-kt{font-size:0.58rem;font-weight:700;margin-top:2px;}
.kt-g{color:var(--grn);}.kt-m{color:var(--txm);}
.t-chart-l{font-size:0.58rem;color:var(--txm);font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:6px;}
.t-chart{display:flex;align-items:flex-end;gap:3px;height:44px;margin-bottom:14px;}
.t-b{flex:1;border-radius:3px 3px 0 0;background:rgba(91,33,182,0.1);}
.t-b.hi{background:var(--p);}.t-b.ac{background:linear-gradient(180deg,var(--acc) 0%,rgba(14,165,233,0.15) 100%);}
.t-orders{display:flex;flex-direction:column;gap:5px;}
.t-order{display:flex;align-items:center;justify-content:space-between;padding:8px 10px;background:var(--sf);border-radius:9px;border:1.5px solid var(--br);}
.t-ol{display:flex;align-items:center;gap:7px;}
.t-ic{width:24px;height:24px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:0.6rem;}
.ic-p{background:rgba(91,33,182,0.08);color:var(--p);}
.ic-t{background:rgba(14,165,233,0.1);color:var(--acc);}
.ic-y{background:rgba(245,158,11,0.1);color:var(--ylw);}
.t-ot{font-size:0.68rem;font-weight:600;color:var(--tx);}
.t-os{font-size:0.58rem;color:var(--txm);}
.bdg{font-size:0.55rem;font-weight:700;padding:3px 7px;border-radius:100px;letter-spacing:0.05em;}
.bdg-ok{background:rgba(16,185,129,0.09);color:var(--grn);}
.bdg-go{background:rgba(245,158,11,0.1);color:var(--ylw);}
.bdg-rd{background:rgba(239,68,68,0.08);color:var(--red);}

/* Hero floats */
.hfloat{position:absolute;z-index:2;background:white;border:1.5px solid var(--br);border-radius:16px;padding:13px 17px;box-shadow:0 12px 32px rgba(91,33,182,0.1);animation:heroFloat 4s ease-in-out infinite;}
.hf1{top:-20px;left:-44px;animation-delay:0s;}
.hf2{bottom:18px;right:-36px;animation-delay:1.8s;}
.hf3{top:50%;left:-52px;transform:translateY(-50%);animation-delay:0.9s;}
.hfl{font-size:0.58rem;font-weight:700;color:var(--txm);text-transform:uppercase;letter-spacing:0.1em;}
.hfv{font-family:var(--ff-h);font-size:1.1rem;font-weight:700;color:var(--tx);margin-top:3px;}
.hft{font-size:0.63rem;font-weight:700;margin-top:2px;}
.hft-g{color:var(--grn);}.hft-a{color:var(--acc);}

/* Video modal trigger */
.video-trigger{
  display:inline-flex;align-items:center;gap:12px;
  font-size:0.95rem;font-weight:700;color:var(--txm);cursor:pointer;
  padding:0;background:none;border:none;font-family:var(--ff-b);
  transition:all 0.3s cubic-bezier(0.19,1,0.22,1);
}
.video-trigger:hover{color:var(--p);transform:translateX(5px);}
.play-ring{
  width:44px;height:44px;border-radius:50%;border:2px solid var(--br);
  display:flex;align-items:center;justify-content:center;
  transition:all 0.3s cubic-bezier(0.19,1,0.22,1);flex-shrink:0;background:white;
}
.play-ring i{font-size:0.85rem;color:var(--p);margin-left:3px;}
.video-trigger:hover .play-ring{background:var(--p);border-color:var(--p);box-shadow:0 10px 25px var(--sh);}
.video-trigger:hover .play-ring i{color:white;}

/* Video Modal */
#video-modal{position:fixed;inset:0;z-index:99998;background:rgba(0,0,0,0.85);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:all 0.35s;backdrop-filter:blur(8px);}
#video-modal.open{opacity:1;visibility:visible;}
.vmod-box{background:white;border-radius:24px;overflow:hidden;width:min(860px,94vw);box-shadow:0 40px 100px rgba(0,0,0,0.4);}
.vmod-header{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1.5px solid var(--br);}
.vmod-title{font-family:var(--ff-h);font-size:1rem;font-weight:700;color:var(--tx);}
.vmod-close{width:34px;height:34px;border-radius:8px;border:1.5px solid var(--br);display:flex;align-items:center;justify-content:center;cursor:pointer;background:var(--sf);transition:all 0.2s;font-size:0.85rem;color:var(--txm);}
.vmod-close:hover{background:var(--red);border-color:var(--red);color:white;}
.vmod-body{padding:24px;background:var(--sf);display:flex;flex-direction:column;gap:14px;}
.vmod-demo{background:white;border:1.5px solid var(--br);border-radius:18px;padding:24px;text-align:center;min-height:280px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px;}
.vmod-play-btn{width:72px;height:72px;border-radius:50%;background:var(--p);display:flex;align-items:center;justify-content:center;box-shadow:0 12px 32px rgba(91,33,182,0.35);animation:pulse 2s infinite;cursor:pointer;transition:transform 0.2s;}
.vmod-play-btn:hover{transform:scale(1.08);}
.vmod-play-btn i{font-size:1.6rem;color:white;margin-left:4px;}

/* ============================
   TRUST BAR
============================ */
.trust-bar{border-top:1.5px solid var(--br);border-bottom:1.5px solid var(--br);background:var(--sf);padding:24px 0;overflow:hidden;position:relative;}
.trust-bar::before,.trust-bar::after{content:'';position:absolute;top:0;bottom:0;width:140px;z-index:2;}
.trust-bar::before{left:0;background:linear-gradient(90deg,var(--sf),transparent);}
.trust-bar::after{right:0;background:linear-gradient(270deg,var(--sf),transparent);}
.trust-track{display:flex;gap:0;animation:trustScroll 30s linear infinite;width:max-content;}
.trust-track:hover{animation-play-state:paused;}
.trust-item{display:flex;align-items:center;gap:8px;padding:0 28px;white-space:nowrap;font-size:0.8rem;font-weight:600;color:var(--txm);transition:color 0.2s;font-family:var(--ff-b);}
.trust-item:hover{color:var(--p);}
.trust-item i{color:var(--p);opacity:0.4;}
.trust-item:hover i{opacity:1;}
.trust-dot{width:3px;height:3px;border-radius:50%;background:var(--brh);flex-shrink:0;}

/* ============================
   STATS BAR
============================ */
.stats-wrap{background:var(--bg2);padding:72px 64px;}
.stats-grid{max-width:1380px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:0;background:var(--br);border:1.5px solid var(--br);border-radius:var(--r2);overflow:hidden;}
.sblock{background:white;padding:42px 38px;position:relative;overflow:hidden;transition:background 0.25s;}
.sblock:hover{background:var(--sf);}
.sblock::before{content:'';position:absolute;top:0;left:0;right:0;height:2.5px;background:linear-gradient(90deg,var(--p),var(--acc));opacity:0;transition:opacity 0.25s;}
.sblock:hover::before{opacity:1;}
.sblock-num{font-family:var(--ff-h);font-size:3rem;font-weight:900;letter-spacing:-0.05em;color:var(--tx);line-height:1;margin-bottom:7px;}
.sblock-num .c{color:var(--p);}
.sblock-lbl{font-size:0.875rem;font-weight:600;color:var(--txm);}
.sblock-sub{font-size:0.73rem;color:var(--txm);margin-top:5px;font-weight:400;}
.sblock-icon{position:absolute;bottom:20px;right:20px;font-size:2rem;color:var(--p);opacity:0.06;}

/* ============================
   FEATURES
============================ */
.feat-sec{background:var(--sf);}
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:68px;}
.fc{background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:34px;transition:all 0.3s;position:relative;overflow:hidden;}
.fc::before{content:'';position:absolute;inset:0;border-radius:var(--r2);background:linear-gradient(135deg,rgba(91,33,182,0.025),transparent 55%);opacity:0;transition:opacity 0.3s;}
.fc:hover{border-color:rgba(91,33,182,0.25);transform:translateY(-5px);box-shadow:0 20px 48px var(--sh);}
.fc:hover::before{opacity:1;}
.fc.span2{grid-column:1/3;display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;}
.fc.dark{background:var(--p);border-color:var(--p);}
.fc.dark::before{display:none;}
.fc.dark:hover{box-shadow:0 20px 48px rgba(91,33,182,0.38);}
.fc.acc{background:linear-gradient(135deg,var(--acc),var(--acc2));border-color:var(--acc);}
.fc.acc::before{display:none;}
.fc.acc:hover{box-shadow:0 20px 48px rgba(14,165,233,0.32);}
.feat-icon{width:48px;height:48px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:22px;}
.fi-p{background:rgba(91,33,182,0.08);color:var(--p);}
.fi-t{background:rgba(14,165,233,0.1);color:var(--acc);}
.fi-g{background:rgba(16,185,129,0.1);color:var(--grn);}
.fi-w{background:rgba(255,255,255,0.15);color:white;}
.fi-s{background:var(--sf2);color:var(--txs);}
.fi-y{background:rgba(245,158,11,0.1);color:var(--ylw);}
.fi-r{background:rgba(239,68,68,0.08);color:var(--red);}
.feat-title{font-family:var(--ff-h);font-size:1.15rem;font-weight:700;color:var(--tx);margin-bottom:10px;letter-spacing:-0.02em;}
.fc.dark .feat-title,.fc.acc .feat-title{color:white;}
.feat-desc{font-size:0.875rem;color:var(--txs);line-height:1.7;}
.fc.dark .feat-desc,.fc.acc .feat-desc{color:rgba(255,255,255,0.68);}
.feat-chks{margin-top:16px;display:flex;flex-direction:column;gap:8px;}
.feat-chk{display:flex;align-items:center;gap:9px;font-size:0.78rem;font-weight:600;color:var(--txm);}
.chkd{width:16px;height:16px;border-radius:5px;flex-shrink:0;background:rgba(91,33,182,0.07);display:flex;align-items:center;justify-content:center;}
.chkd i{font-size:0.48rem;color:var(--p);}
.fc.dark .feat-chk{color:rgba(255,255,255,0.6);}
.fc.dark .chkd{background:rgba(255,255,255,0.12);}
.fc.dark .chkd i{color:white;}
.fc.acc .feat-chk{color:rgba(255,255,255,0.65);}
.fc.acc .chkd{background:rgba(255,255,255,0.15);}
.fc.acc .chkd i{color:white;}

.mini-viz{background:var(--sf);border:1.5px solid var(--br);border-radius:16px;padding:20px;}
.mv-l{font-size:0.58rem;color:var(--txm);font-weight:600;letter-spacing:0.08em;text-transform:uppercase;margin-bottom:10px;}
.mv-bars{display:flex;align-items:flex-end;gap:5px;height:68px;}
.mv-b{flex:1;border-radius:4px 4px 0 0;}
.mv-nums{display:flex;gap:12px;margin-top:12px;}
.mv-nv{font-family:var(--ff-h);font-size:1.1rem;font-weight:700;color:var(--tx);letter-spacing:-0.03em;}
.mv-nl{font-size:0.58rem;color:var(--txm);font-weight:500;margin-top:2px;}

/* Feature Pulse badge */
.feat-new{position:absolute;top:16px;right:16px;font-size:0.55rem;font-weight:800;padding:3px 8px;border-radius:100px;background:var(--grn);color:white;letter-spacing:0.1em;text-transform:uppercase;}

/* ============================
   HOW IT WORKS
============================ */
.how-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:72px;position:relative;}
.how-grid::before{content:'';position:absolute;top:24px;left:calc(100%/8);right:calc(100%/8);height:1.5px;background:linear-gradient(90deg,transparent,var(--br),var(--p),var(--br),transparent);pointer-events:none;}
.how-step{padding:36px 28px;position:relative;text-align:center;}
.how-num{
  width:48px;height:48px;border-radius:50%;background:white;
  border:2px solid var(--p);display:flex;align-items:center;justify-content:center;
  margin:0 auto 20px;font-family:var(--ff-h);font-size:0.95rem;font-weight:900;color:var(--p);
  position:relative;z-index:1;transition:all 0.3s;
}
.how-step:hover .how-num{background:var(--p);color:white;transform:scale(1.1);box-shadow:0 8px 22px rgba(91,33,182,0.3);}
.how-icon-wrap{width:60px;height:60px;border-radius:16px;background:var(--sf);border:1.5px solid var(--br);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:1.3rem;color:var(--p);transition:all 0.3s;}
.how-step:hover .how-icon-wrap{background:rgba(91,33,182,0.07);border-color:rgba(91,33,182,0.2);}
.how-title{font-family:var(--ff-h);font-size:1rem;font-weight:700;color:var(--tx);margin-bottom:8px;}
.how-desc{font-size:0.855rem;color:var(--txs);line-height:1.68;}
.how-eta{display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:0.68rem;font-weight:700;color:var(--grn);background:rgba(16,185,129,0.08);padding:4px 10px;border-radius:100px;}

/* ============================
   MOBILE APP SECTION
============================ */
.app-sec{background:var(--bg2);overflow:hidden;}
.app-wrap{display:grid;grid-template-columns:1fr 1.1fr;gap:80px;align-items:center;}
.app-phone{position:relative;display:flex;justify-content:center;}
.app-phone-glow{position:absolute;inset:-30px;background:radial-gradient(circle,rgba(91,33,182,0.1) 0%,transparent 65%);border-radius:50%;}
.phone-frame{
  width:240px;background:white;border-radius:36px;
  border:8px solid var(--tx);box-shadow:0 28px 72px rgba(0,0,0,0.2),inset 0 0 0 1px rgba(255,255,255,0.1);
  overflow:hidden;position:relative;z-index:1;
}
.phone-notch{height:30px;background:var(--tx);display:flex;align-items:center;justify-content:center;}
.phone-notch-pill{width:90px;height:10px;background:#1a1a1a;border-radius:100px;}
.phone-screen{padding:14px;background:var(--bg);}
.ph-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.ph-title{font-family:var(--ff-h);font-size:0.8rem;font-weight:700;color:var(--tx);}
.ph-notif{width:22px;height:22px;border-radius:6px;background:var(--p);display:flex;align-items:center;justify-content:center;}
.ph-notif i{font-size:0.55rem;color:white;}
.ph-stat-row{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:10px;}
.ph-stat{background:white;border:1.5px solid var(--br);border-radius:10px;padding:9px 10px;}
.ph-sv{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:var(--tx);}
.ph-sl{font-size:0.5rem;color:var(--txm);font-weight:600;text-transform:uppercase;letter-spacing:0.07em;}
.ph-chart{background:white;border:1.5px solid var(--br);border-radius:10px;padding:10px;margin-bottom:8px;}
.ph-cl{font-size:0.5rem;color:var(--txm);font-weight:600;text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;}
.ph-bars{display:flex;align-items:flex-end;gap:3px;height:36px;}
.ph-b{flex:1;border-radius:2px 2px 0 0;}
.ph-orders{display:flex;flex-direction:column;gap:4px;}
.ph-order{display:flex;align-items:center;justify-content:space-between;background:white;border:1.5px solid var(--br);border-radius:8px;padding:7px 9px;}
.ph-ot{font-size:0.6rem;font-weight:700;color:var(--tx);}
.ph-os{font-size:0.52rem;color:var(--txm);}
.ph-bdg{font-size:0.48rem;font-weight:700;padding:2px 6px;border-radius:100px;}
.ph-bdg-ok{background:rgba(16,185,129,0.1);color:var(--grn);}
.ph-bdg-go{background:rgba(245,158,11,0.1);color:var(--ylw);}
/* App floating badges */
.app-float{position:absolute;background:white;border:1.5px solid var(--br);border-radius:14px;padding:10px 14px;box-shadow:0 10px 28px rgba(91,33,182,0.1);animation:heroFloat 4.5s ease-in-out infinite;z-index:2;}
.af1{top:20px;right:-48px;animation-delay:0s;}
.af2{bottom:40px;right:-48px;animation-delay:1.5s;}
.af3{bottom:120px;left:-48px;animation-delay:0.8s;}
.afl{font-size:0.56rem;font-weight:700;color:var(--txm);text-transform:uppercase;letter-spacing:0.08em;}
.afv{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:var(--tx);margin-top:2px;}
.app-stores{display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;}
.app-store-btn{
  display:flex;align-items:center;gap:12px;padding:12px 24px;
  background:linear-gradient(180deg, #2A2D34 0%, #13151A 100%);
  color:#ffffff !important;border-radius:14px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);
  border:1px solid rgba(255,255,255,0.08);text-decoration:none;
  box-shadow:0 8px 24px rgba(0,0,0,0.12), inset 0 1px 0 rgba(255,255,255,0.05);
}
.app-store-btn:hover{
  background:linear-gradient(180deg, #32363E 0%, #1A1D24 100%);
  transform:translateY(-3px);
  box-shadow:0 14px 32px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.1);
  border-color:rgba(255,255,255,0.15);
}
.app-store-btn * { color: #ffffff !important; }
.asb-icon{font-size:1.7rem;line-height:1;}
.asb-small{font-size:0.6rem;font-weight:600;opacity:0.75;letter-spacing:0.04em;text-transform:uppercase;}
.asb-big{font-size:1rem;font-weight:800;line-height:1.1;margin-top:1px;letter-spacing:-0.01em;}
.app-features{display:flex;flex-direction:column;gap:14px;margin-top:28px;}
.app-feat{display:flex;align-items:flex-start;gap:14px;}
.app-feat-ic{width:42px;height:42px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
.app-feat-title{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:var(--tx);margin-bottom:3px;}
.app-feat-desc{font-size:0.82rem;color:var(--txs);line-height:1.6;}

/* ============================
   LIVE DEMO
============================ */
.demo-sec{background:linear-gradient(180deg,var(--bg2) 0%,var(--sf) 100%);}
.demo-wrap{display:grid;grid-template-columns:1fr 1.2fr;gap:72px;align-items:center;margin-top:64px;}
.demo-badge{display:inline-flex;align-items:center;gap:7px;padding:5px 12px;background:rgba(16,185,129,0.07);border:1.5px solid rgba(16,185,129,0.2);border-radius:100px;font-size:0.68rem;font-weight:700;color:var(--grn);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:18px;font-family:var(--ff-b);}
.demo-title{font-family:var(--ff-h);font-size:clamp(1.8rem,3vw,2.8rem);font-weight:900;letter-spacing:-0.042em;color:var(--tx);line-height:1.06;margin-bottom:14px;}
.demo-title em{font-style:normal;color:var(--p);}
.demo-sub{font-size:0.9375rem;color:var(--txs);line-height:1.72;margin-bottom:24px;}
.demo-legend{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:22px;}
.dl-item{display:flex;align-items:center;gap:6px;font-size:0.73rem;font-weight:700;color:var(--txm);}
.dl-dot{width:9px;height:9px;border-radius:3px;}
.demo-stats-row{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;}
.demo-stat{background:white;border:1.5px solid var(--br);border-radius:12px;padding:12px 16px;flex:1;min-width:76px;transition:border-color 0.2s;}
.demo-stat:hover{border-color:var(--brx);}
.demo-sv{font-family:var(--ff-h);font-size:1.4rem;font-weight:900;letter-spacing:-0.04em;line-height:1;}
.demo-sl{font-size:0.58rem;font-weight:700;color:var(--txm);text-transform:uppercase;letter-spacing:0.08em;margin-top:4px;}
.masa-grid-wrap{background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:24px;box-shadow:0 20px 56px var(--sh);}
.mgw-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:14px;border-bottom:1.5px solid var(--br);}
.mgh-title{font-family:var(--ff-h);font-size:0.85rem;font-weight:700;color:var(--tx);}
.mgh-badge{font-size:0.6rem;font-weight:700;padding:4px 9px;border-radius:100px;background:rgba(16,185,129,0.08);color:var(--grn);border:1px solid rgba(16,185,129,0.15);}
.mgh-filter{display:flex;gap:6px;}
.mgh-fb{padding:4px 10px;border-radius:100px;border:1.5px solid var(--br);font-size:0.6rem;font-weight:700;color:var(--txm);cursor:pointer;transition:all 0.2s;font-family:var(--ff-b);background:white;}
.mgh-fb.active,.mgh-fb:hover{background:var(--p);color:white;border-color:var(--p);}
.masa-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:7px;}
.masa-cell{
  aspect-ratio:1;border-radius:10px;display:flex;flex-direction:column;
  align-items:center;justify-content:center;cursor:pointer;
  transition:all 0.2s;position:relative;overflow:hidden;
  font-size:0.6rem;font-weight:700;font-family:var(--ff-b);
}
.masa-cell.empty{background:var(--sf);border:1.5px solid var(--br);color:var(--txm);}
.masa-cell.full{background:var(--p);border:1.5px solid var(--p);color:white;box-shadow:0 4px 14px rgba(91,33,182,0.25);}
.masa-cell.busy{background:rgba(239,68,68,0.05);border:1.5px solid rgba(239,68,68,0.2);color:var(--red);}
.masa-cell.reserve{background:rgba(245,158,11,0.06);border:1.5px solid rgba(245,158,11,0.25);color:var(--ylw);}
.masa-cell.cleaning{background:rgba(14,165,233,0.06);border:1.5px solid rgba(14,165,233,0.25);color:var(--acc);}
.mc-num{font-family:var(--ff-h);font-size:0.95rem;font-weight:900;line-height:1;}
.mc-lbl{font-size:0.4rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:1px;opacity:0.8;}
.masa-cell:hover{transform:scale(1.07);z-index:2;}
.masa-cell.full:hover{box-shadow:0 8px 22px rgba(91,33,182,0.36);}
.masa-cell.empty:hover{background:rgba(91,33,182,0.04);border-color:rgba(91,33,182,0.2);}
.masa-cell::after{content:attr(data-tip);position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--tx);color:white;font-size:0.57rem;font-weight:600;padding:4px 8px;border-radius:6px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.18s;letter-spacing:0.03em;font-family:var(--ff-b);}
.masa-cell:hover::after{opacity:1;}
/* Notification */
#demo-notif{position:fixed;bottom:28px;right:28px;z-index:9999;pointer-events:none;}
.notif-card{
  background:white;border:1.5px solid var(--br);border-radius:16px;
  padding:14px 18px;box-shadow:0 12px 32px rgba(91,33,182,0.15);
  display:flex;align-items:center;gap:12px;font-family:var(--ff-b);
  animation:notifSlide 0.4s cubic-bezier(0.19,1,0.22,1);
  margin-top:8px;
}
.notif-ic{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.8rem;}
.notif-ic-p{background:rgba(91,33,182,0.08);color:var(--p);}
.notif-ic-g{background:rgba(16,185,129,0.1);color:var(--grn);}
.notif-ic-y{background:rgba(245,158,11,0.1);color:var(--ylw);}
.notif-t{font-size:0.78rem;font-weight:700;color:var(--tx);}
.notif-s{font-size:0.66rem;color:var(--txm);}
.notif-prog{height:2px;background:var(--p);border-radius:100px;margin-top:8px;animation:progress 3s linear both;}

/* ============================
   PRICING
============================ */
.pricing-sec{background:var(--bg2);}
.pricing-toggle{display:flex;align-items:center;gap:14px;justify-content:center;margin:40px 0 60px;}
.pt-lbl{font-size:0.85rem;font-weight:700;color:var(--txm);}
.pt-switch{width:52px;height:28px;border-radius:100px;background:var(--p);position:relative;cursor:pointer;transition:background 0.2s;}
.pt-thumb{width:22px;height:22px;border-radius:50%;background:white;position:absolute;top:3px;left:3px;transition:left 0.25s;box-shadow:0 2px 6px rgba(0,0,0,0.15);}
.pt-switch.yearly .pt-thumb{left:27px;}
.pt-save{background:var(--grn);color:white;font-size:0.62rem;font-weight:800;padding:3px 8px;border-radius:100px;letter-spacing:0.08em;}
.pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
.pcard{background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:40px 32px;position:relative;transition:all 0.3s;overflow:hidden;}
.pcard:hover{transform:translateY(-6px);box-shadow:0 24px 56px var(--sh);}
.pcard.popular{border-color:var(--p);border-width:2px;background:linear-gradient(180deg,white,var(--sf));}
.pcard.popular::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--p),var(--acc));}
.pcard-popular-badge{position:absolute;top:20px;right:20px;background:var(--p);color:white;font-size:0.58rem;font-weight:800;padding:4px 10px;border-radius:100px;letter-spacing:0.1em;text-transform:uppercase;}
.pcard-tier{font-size:0.72rem;font-weight:800;color:var(--txm);letter-spacing:0.14em;text-transform:uppercase;margin-bottom:14px;font-family:var(--ff-b);}
.pcard.popular .pcard-tier{color:var(--p);}
.pcard-price{margin-bottom:6px;}
.price-amount{font-family:var(--ff-h);font-size:3rem;font-weight:900;color:var(--tx);letter-spacing:-0.05em;line-height:1;}
.price-cur{font-size:1.4rem;vertical-align:top;margin-top:8px;display:inline-block;color:var(--txm);}
.price-per{font-size:0.78rem;color:var(--txm);font-weight:500;margin-left:4px;}
.pcard-desc{font-size:0.85rem;color:var(--txs);margin-bottom:26px;line-height:1.6;}
.pcard-btn{width:100%;padding:13px;border-radius:12px;font-weight:700;font-size:0.875rem;cursor:pointer;transition:all 0.2s;font-family:var(--ff-b);border:none;display:block;text-align:center;margin-bottom:28px;}
.pb-outline{background:white;border:1.5px solid var(--brh);color:var(--txs);}
.pb-outline:hover{border-color:var(--p);color:var(--p);}
.pb-solid{background:var(--p);color:white;box-shadow:0 4px 16px rgba(91,33,182,0.25);}
.pb-solid:hover{background:var(--pd);transform:translateY(-1px);}
.pcard-feats{display:flex;flex-direction:column;gap:10px;}
.pf{display:flex;align-items:flex-start;gap:9px;font-size:0.82rem;color:var(--txs);}
.pf-ic{width:18px;height:18px;border-radius:5px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.5rem;margin-top:0.5px;}
.pf-ic-yes{background:rgba(16,185,129,0.1);color:var(--grn);}
.pf-ic-no{background:var(--sf);color:var(--txm);}
.pf-off{opacity:0.4;}
.pcard-note{margin-top:20px;padding-top:18px;border-top:1.5px solid var(--br);font-size:0.72rem;color:var(--txm);text-align:center;}

/* ============================
   ROI CALCULATOR
============================ */
.roi-sec{background:var(--sf);}
.roi-wrap{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:start;margin-top:64px;}
.roi-card{background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:40px;box-shadow:0 8px 28px var(--sh);}
.roi-label{font-size:0.8rem;font-weight:700;color:var(--txs);margin-bottom:8px;display:block;font-family:var(--ff-b);}
.roi-input{
  width:100%;padding:12px 16px;border:1.5px solid var(--br);border-radius:12px;
  font-size:1rem;font-weight:700;color:var(--tx);background:var(--sf);
  font-family:var(--ff-b);transition:border-color 0.2s;margin-bottom:18px;outline:none;
}
.roi-input:focus{border-color:var(--p);}
.roi-slider{width:100%;margin-bottom:18px;accent-color:var(--p);height:4px;cursor:pointer;}
.roi-slider-val{display:flex;justify-content:space-between;font-size:0.7rem;color:var(--txm);font-weight:600;margin-top:-14px;margin-bottom:18px;}
.roi-result{background:linear-gradient(135deg,var(--p),var(--pl));border-radius:var(--r);padding:32px;color:white;margin-top:8px;}
.roi-r-label{font-size:0.7rem;font-weight:700;opacity:0.7;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:6px;}
.roi-r-val{font-family:var(--ff-h);font-size:2.8rem;font-weight:900;letter-spacing:-0.05em;margin-bottom:4px;}
.roi-r-sub{font-size:0.82rem;opacity:0.75;}
.roi-result-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:20px;}
.roi-mini{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:16px;}
.roi-mini-v{font-family:var(--ff-h);font-size:1.4rem;font-weight:800;color:white;}
.roi-mini-l{font-size:0.6rem;font-weight:700;opacity:0.8;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;line-height:1.3;}
.roi-disclaimer{font-size:0.75rem;color:var(--txm);margin-top:14px;text-align:center;}

/* ============================
   SECURITY SECTION
============================ */
.sec-security{background:var(--tx);position:relative;overflow:hidden;}
.sec-security::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.025) 1px,transparent 1px);background-size:52px 52px;}
.sec-security::after{content:'';position:absolute;top:-200px;right:-150px;width:600px;height:600px;background:radial-gradient(circle,rgba(91,33,182,0.18) 0%,transparent 60%);border-radius:50%;}
.sec-sec-in{position:relative;z-index:1;}
.security-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:80px;align-items:center;margin-top:64px;}
.sec-feats{display:flex;flex-direction:column;gap:22px;}
.sec-feat{display:flex;gap:18px;align-items:flex-start;padding:22px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.08);border-radius:18px;transition:all 0.3s cubic-bezier(0.19,1,0.22,1);}
.sec-feat:hover{background:rgba(255,255,255,0.05);border-color:var(--pl2);transform:translateX(5px);}
.sec-feat-ic{width:48px;height:48px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.2rem;}
.sf-ic-p{background:rgba(91,33,182,0.4);color:var(--pl2);}
.sf-ic-g{background:rgba(16,185,129,0.25);color:#6EE7B7;}
.sf-ic-a{background:rgba(14,165,233,0.25);color:#7DD3FC;}
.sec-feat-title{font-family:var(--ff-h);font-size:1.05rem;font-weight:700;color:white;margin-bottom:6px;letter-spacing:-0.01em;}
.sec-feat-desc{font-size:0.875rem;color:rgba(255,255,255,0.6);line-height:1.6;}
.sec-badge-grid{display:flex;flex-direction:column;gap:20px;}
.sec-badge-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.secbdg{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:20px;text-align:center;transition:all 0.3s;}
.secbdg:hover{background:rgba(255,255,255,0.06);border-color:var(--pl2);transform:translateY(-3px);}
.secbdg-ic{font-size:1.8rem;color:var(--pl2);margin-bottom:12px;}
.secbdg-title{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:white;margin-bottom:4px;letter-spacing:-0.01em;}
.secbdg-sub{font-size:0.75rem;color:rgba(255,255,255,0.5);line-height:1.3;}
.uptime-display{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:24px;margin-top:8px;}
.ud-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.ud-title{font-size:0.75rem;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.12em;}
.ud-val{font-family:var(--ff-h);font-size:1.4rem;font-weight:900;color:var(--grn);}
.ud-bars{display:flex;gap:3px;align-items:flex-end;height:40px;}
.ud-b{flex:1;border-radius:2px;background:rgba(16,185,129,0.2);}
.ud-b.ok{background:var(--grn);}
.ud-b.warn{background:var(--ylw);}

/* ============================
   HARDWARE SECTION
============================ */
.hw-sec{background:var(--bg2);}
.hw-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:68px;}
.hwcard{background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:32px;transition:all 0.3s;position:relative;overflow:hidden;}
.hwcard:hover{border-color:rgba(91,33,182,0.25);transform:translateY(-5px);box-shadow:0 20px 48px var(--sh);}
.hwcard.featured{border-color:var(--p);background:linear-gradient(160deg,var(--sf) 0%,white 60%);}
.hwcard.featured::before{content:'Önerilen';position:absolute;top:16px;right:16px;background:var(--p);color:white;font-size:0.56rem;font-weight:800;padding:3px 9px;border-radius:100px;letter-spacing:0.1em;text-transform:uppercase;}
.hw-icon-wrap{width:100%;height:130px;background:var(--sf);border:1.5px solid var(--br);border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:22px;font-size:3.5rem;position:relative;overflow:hidden;}
.hwcard.featured .hw-icon-wrap{background:rgba(91,33,182,0.06);border-color:rgba(91,33,182,0.15);}
.hw-icon-wrap::after{content:'';position:absolute;bottom:-20px;left:50%;transform:translateX(-50%);width:80%;height:30px;background:radial-gradient(ellipse,rgba(91,33,182,0.12),transparent);border-radius:50%;}
.hw-name{font-family:var(--ff-h);font-size:1.15rem;font-weight:700;color:var(--tx);margin-bottom:6px;}
.hw-desc{font-size:0.838rem;color:var(--txs);line-height:1.65;margin-bottom:18px;}
.hw-specs{display:flex;flex-direction:column;gap:8px;margin-bottom:20px;}
.hw-spec{display:flex;align-items:center;justify-content:space-between;font-size:0.78rem;}
.hw-spec-k{color:var(--txm);font-weight:500;}
.hw-spec-v{font-weight:700;color:var(--tx);}
.hw-price{display:flex;align-items:baseline;gap:6px;margin-bottom:14px;}
.hwp-amount{font-family:var(--ff-h);font-size:1.8rem;font-weight:900;color:var(--p);}
.hwp-per{font-size:0.72rem;color:var(--txm);}
.hwp-old{font-size:0.85rem;color:var(--txm);text-decoration:line-through;}

/* ============================
   COMPARISON
============================ */
.cmp-sec{background:var(--sf);}
.cmp-table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:10px;}
.cmp-table{margin-top:64px;border:1.5px solid var(--br);border-radius:var(--r2);overflow:hidden;background:white;box-shadow:0 8px 32px var(--sh);min-width:760px;}
.cmp-head{display:grid;grid-template-columns:1.8fr 1fr 1fr 1fr;background:white;border-bottom:1.5px solid var(--br);}
.cmp-th{padding:26px 20px;font-size:0.85rem;font-weight:800;color:var(--txm);font-family:var(--ff-b);text-align:center;}
.cmp-th.feat{text-align:left;color:var(--tx);padding-left:32px;}
.cmp-th.hero{background:var(--p);color:white;position:relative;}
.cmp-th.hero::after{content:'Önerilen';position:absolute;top:-1px;left:50%;transform:translateX(-50%);font-size:0.55rem;font-weight:800;background:var(--grn);color:white;padding:3px 10px;border-radius:0 0 8px 8px;letter-spacing:0.1em;text-transform:uppercase;}
.cmp-row{display:grid;grid-template-columns:1.8fr 1fr 1fr 1fr;border-bottom:1px solid var(--br);transition:background 0.2s;}
.cmp-row:last-child{border-bottom:none;}
.cmp-row:hover{background:var(--bg2);}
.cmp-cell{padding:18px 20px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;color:var(--txs);text-align:center;}
.cmp-cell.feat{font-weight:700;color:var(--tx);justify-content:flex-start;padding-left:32px;font-size:0.9rem;}
.cmp-cell.hero{background:rgba(91,33,182,0.03);color:var(--p);font-weight:700;}
.cy{color:var(--grn);font-size:1.1rem;}.cn{color:var(--txm);opacity:0.4;font-size:1.1rem;}.cp{color:var(--ylw);font-size:1.1rem;}

/* ============================
   INTEGRATIONS
============================ */
.int-grid{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin:52px 0 44px;}
.int-pill{
  display:flex;align-items:center;gap:8px;padding:10px 18px;
  background:white;border:1.5px solid var(--br);border-radius:100px;
  font-size:0.82rem;font-weight:600;color:var(--txs);transition:all 0.2s;
  box-shadow:0 2px 6px rgba(0,0,0,0.04);font-family:var(--ff-b);
}
.int-pill:hover{border-color:var(--p);color:var(--p);background:var(--sf);transform:translateY(-3px);box-shadow:0 8px 18px rgba(91,33,182,0.1);}
.int-pill i{font-size:0.88rem;color:var(--p);}
.api-block{
  background:white;border:1.5px solid var(--br);border-radius:var(--r2);
  padding:48px;display:flex;align-items:center;justify-content:space-between;
  gap:48px;box-shadow:0 8px 28px var(--sh);position:relative;overflow:hidden;
}
.api-block::before{content:'';position:absolute;top:-80px;right:-80px;width:320px;height:320px;background:radial-gradient(circle,rgba(14,165,233,0.07) 0%,transparent 70%);border-radius:50%;}
.api-l{position:relative;z-index:1;flex:1;}
.api-title{font-family:var(--ff-h);font-size:1.7rem;font-weight:900;color:var(--tx);letter-spacing:-0.04em;margin-bottom:10px;}
.api-sub{font-size:0.9375rem;color:var(--txs);line-height:1.68;max-width:340px;margin-bottom:22px;}
pre.api-code{
  font-family:var(--ff-c);font-size:0.7rem;background:var(--sf);
  border:1.5px solid var(--br);border-radius:14px;padding:18px 20px;
  color:var(--txs);line-height:2;white-space:pre;flex-shrink:0;
}
.cm{color:var(--p);font-weight:700;}.cs{color:#0ABBA3;}.ck{color:#D6336C;}

/* ============================
   TESTIMONIALS
============================ */
.testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:64px;}
.tcard{
  background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:32px;
  transition:all 0.3s;box-shadow:0 2px 12px rgba(0,0,0,0.04);position:relative;overflow:hidden;
}
.tcard::before{content:'\201C';position:absolute;top:18px;right:22px;font-size:5rem;color:var(--p);opacity:0.05;font-family:Georgia,serif;line-height:1;}
.tcard::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--p),var(--acc));opacity:0;transition:opacity 0.3s;}
.tcard:hover{border-color:rgba(91,33,182,0.2);transform:translateY(-4px);box-shadow:0 16px 40px var(--sh);}
.tcard:hover::after{opacity:1;}
.tcard.hl{border-color:rgba(91,33,182,0.2);background:linear-gradient(140deg,rgba(91,33,182,0.02),white);}
.tstars{font-size:0.75rem;letter-spacing:1px;margin-bottom:16px;color:var(--ylw);}
.tquote{font-size:0.9rem;color:var(--txs);line-height:1.72;margin-bottom:22px;font-style:italic;}
.tauthor{display:flex;align-items:center;gap:11px;}
.tav{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--ff-h);font-weight:800;font-size:0.95rem;color:white;flex-shrink:0;}
.tname{font-family:var(--ff-h);font-weight:700;font-size:0.85rem;color:var(--tx);}
.trole{font-size:0.7rem;color:var(--txm);font-weight:500;margin-top:2px;}
.tmetric{margin-top:16px;padding-top:16px;border-top:1.5px solid var(--br);display:flex;align-items:center;gap:8px;}
.tm-val{font-family:var(--ff-h);font-size:1.2rem;font-weight:800;color:var(--p);}
.tm-lbl{font-size:0.7rem;color:var(--txm);font-weight:500;}

/* ============================
   FAQ
============================ */
.faq-wrap{display:grid;grid-template-columns:1fr 1.5fr;gap:80px;align-items:start;margin-top:64px;}
.faq-aside-h{font-family:var(--ff-h);font-size:clamp(1.7rem,2.5vw,2.4rem);font-weight:900;letter-spacing:-0.04em;color:var(--tx);margin-bottom:12px;}
.faq-aside-sub{font-size:0.9375rem;color:var(--txs);line-height:1.72;margin-bottom:26px;}
.faq-cta{display:inline-flex;align-items:center;gap:8px;font-size:0.82rem;font-weight:700;color:var(--p);padding:10px 18px;border:1.5px solid rgba(91,33,182,0.2);border-radius:12px;transition:all 0.2s;background:rgba(91,33,182,0.03);font-family:var(--ff-b);}
.faq-cta:hover{background:var(--p);color:white;border-color:var(--p);}
.faq-contact-card{margin-top:28px;background:var(--sf);border:1.5px solid var(--br);border-radius:16px;padding:22px;}
.fcc-title{font-family:var(--ff-h);font-size:0.95rem;font-weight:700;color:var(--tx);margin-bottom:8px;}
.fcc-desc{font-size:0.8rem;color:var(--txs);margin-bottom:16px;line-height:1.6;}
.fcc-contacts{display:flex;flex-direction:column;gap:8px;}
.fcc-c{display:flex;align-items:center;gap:9px;font-size:0.78rem;font-weight:600;color:var(--txm);}
.fcc-c i{color:var(--p);width:14px;}
.faq-list{display:flex;flex-direction:column;}
.faq-item{border-bottom:1.5px solid var(--br);}
.faq-item:first-child{border-top:1.5px solid var(--br);}
.faq-q{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:20px 0;cursor:pointer;font-size:0.9375rem;font-weight:600;color:var(--tx);transition:color 0.2s;user-select:none;font-family:var(--ff-b);}
.faq-q:hover{color:var(--p);}
.faq-icon{width:26px;height:26px;border-radius:7px;background:var(--sf);border:1.5px solid var(--br);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.3s;font-size:0.68rem;color:var(--txm);}
.faq-item.open .faq-icon{background:var(--p);border-color:var(--p);color:white;transform:rotate(45deg);}
.faq-a{font-size:0.875rem;color:var(--txs);line-height:1.72;max-height:0;overflow:hidden;transition:max-height 0.4s cubic-bezier(0.19,1,0.22,1),padding 0.4s;}
.faq-item.open .faq-a{max-height:300px;padding-bottom:20px;}

/* ============================
   CTA
============================ */
.cta-sec{background:var(--p);position:relative;overflow:hidden;text-align:center;}
.cta-sec::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px);background-size:56px 56px;}
.cta-sec::after{content:'';position:absolute;top:-240px;left:50%;transform:translateX(-50%);width:720px;height:720px;background:radial-gradient(circle,rgba(255,255,255,0.06) 0%,transparent 55%);border-radius:50%;}
.cta-in{position:relative;z-index:1;}
.cta-h{font-family:var(--ff-h);font-size:clamp(2.6rem,5.5vw,5rem);font-weight:900;letter-spacing:-0.05em;color:white;line-height:0.95;margin-bottom:18px;}
.cta-sub{font-size:1rem;color:rgba(255,255,255,0.7);line-height:1.68;margin-bottom:44px;}
.cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:48px;}
.cta-trust{display:flex;align-items:center;justify-content:center;gap:32px;flex-wrap:wrap;margin-bottom:52px;}
.cta-ti{display:flex;align-items:center;gap:7px;font-size:0.78rem;font-weight:600;color:rgba(255,255,255,0.6);}
.cta-ti i{color:rgba(255,255,255,0.4);}
/* Countdown timer in CTA */
.trial-countdown{display:inline-flex;align-items:center;gap:4px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:12px 24px;margin-bottom:32px;font-family:var(--ff-c);}
.tc-block{text-align:center;min-width:48px;}
.tc-num{font-size:1.6rem;font-weight:900;color:white;line-height:1;font-family:var(--ff-h);}
.tc-lbl{font-size:0.55rem;font-weight:700;color:rgba(255,255,255,0.5);letter-spacing:0.12em;text-transform:uppercase;margin-top:2px;}
.tc-sep{font-size:1.4rem;font-weight:900;color:rgba(255,255,255,0.3);padding-bottom:14px;}

/* ============================
   FOOTER
============================ */
.footer{background:var(--tx);padding:80px 64px 40px;position:relative;overflow:hidden;}
.footer::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:radial-gradient(circle,rgba(91,33,182,0.1) 0%,transparent 65%);border-radius:50%;pointer-events:none;}
.footer-grid{max-width:1380px;margin:0 auto;}
.footer-top{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:64px;margin-bottom:72px;}
.footer-brand-logo{display:flex;align-items:center;gap:10px;margin-bottom:18px;}
.fbl-mark{width:38px;height:38px;background:var(--p);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:var(--ff-h);font-size:1.2rem;font-weight:900;color:white;}
.fbl-name{font-family:var(--ff-h);font-size:1.15rem;font-weight:700;color:white;}
.footer-brand-desc{font-size:0.855rem;color:rgba(255,255,255,0.45);line-height:1.72;max-width:280px;margin-bottom:26px;}
.footer-socials{display:flex;gap:8px;}
.fsoc{width:36px;height:36px;border-radius:9px;border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:0.85rem;color:rgba(255,255,255,0.5);transition:all 0.2s;cursor:pointer;}
.fsoc:hover{background:var(--p);border-color:var(--p);color:white;}
.footer-col-title{font-family:var(--ff-h);font-size:0.85rem;font-weight:700;color:white;margin-bottom:18px;letter-spacing:-0.01em;}
.footer-links{display:flex;flex-direction:column;gap:10px;}
.footer-link{font-size:0.825rem;color:rgba(255,255,255,0.45);transition:color 0.2s;font-weight:400;}
.footer-link:hover{color:white;}
.footer-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(91,33,182,0.25);border:1px solid rgba(91,33,182,0.4);border-radius:8px;padding:5px 10px;font-size:0.63rem;font-weight:700;color:var(--pl2);margin-bottom:10px;letter-spacing:0.08em;text-transform:uppercase;}
.footer-mid{display:grid;grid-template-columns:1fr 1fr;gap:24px;padding:32px 0;border-top:1px solid rgba(255,255,255,0.06);border-bottom:1px solid rgba(255,255,255,0.06);margin-bottom:32px;}
.footer-newsletter{display:flex;gap:10px;}
.fn-input{flex:1;padding:11px 16px;border:1px solid rgba(255,255,255,0.12);border-radius:10px;background:rgba(255,255,255,0.05);color:white;font-family:var(--ff-b);font-size:0.838rem;outline:none;transition:border-color 0.2s;}
.fn-input::placeholder{color:rgba(255,255,255,0.35);}
.fn-input:focus{border-color:rgba(91,33,182,0.5);}
.fn-btn{padding:11px 20px;background:var(--p);color:white;border:none;border-radius:10px;font-weight:700;font-size:0.838rem;cursor:pointer;font-family:var(--ff-b);transition:all 0.2s;white-space:nowrap;}
.fn-btn:hover{background:var(--pd);}
.footer-cert{display:flex;align-items:center;gap:16px;justify-content:flex-end;flex-wrap:wrap;}
.fcert{display:flex;align-items:center;gap:7px;font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.4);}
.fcert i{color:rgba(91,33,182,0.7);}
.footer-bottom{max-width:1380px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
.fb-copy{font-size:0.78rem;color:rgba(255,255,255,0.3);}
.fb-links{display:flex;gap:20px;}
.fb-link{font-size:0.78rem;color:rgba(255,255,255,0.3);transition:color 0.2s;}
.fb-link:hover{color:rgba(255,255,255,0.7);}

/* ============================
   KVKK BANNER
============================ */
#kvkk-banner{
  position:fixed;bottom:0;left:0;right:0;z-index:9997;
  background:white;border-top:1.5px solid var(--br);
  padding:18px 32px;display:flex;align-items:center;gap:20px;
  box-shadow:0 -8px 32px rgba(0,0,0,0.08);
  transform:translateY(100%);transition:transform 0.4s cubic-bezier(0.19,1,0.22,1);
}
#kvkk-banner.show{transform:translateY(0);}
.kvkk-text{flex:1;font-size:0.8rem;color:var(--txs);line-height:1.6;}
.kvkk-text a{color:var(--p);font-weight:700;}
.kvkk-actions{display:flex;gap:8px;flex-shrink:0;}
.kvkk-btn-ok{padding:9px 20px;background:var(--p);color:white;border:none;border-radius:10px;font-weight:700;font-size:0.78rem;cursor:pointer;font-family:var(--ff-b);}
.kvkk-btn-ok:hover{background:var(--pd);}
.kvkk-btn-no{padding:9px 14px;background:var(--sf);color:var(--txm);border:1.5px solid var(--br);border-radius:10px;font-weight:600;font-size:0.78rem;cursor:pointer;font-family:var(--ff-b);}

/* ============================
   LIVE CHAT BUBBLE
============================ */
#chat-bubble{
  position:fixed;bottom:32px;left:32px;z-index:9996;
  width:52px;height:52px;border-radius:50%;background:var(--grn);
  display:flex;align-items:center;justify-content:center;cursor:pointer;
  box-shadow:0 8px 24px rgba(16,185,129,0.4);transition:all 0.25s;
  animation:pulseGrn 2.5s infinite;
}
#chat-bubble:hover{transform:scale(1.1);}
#chat-bubble i{font-size:1.2rem;color:white;}
#chat-bubble .cbadge{
  position:absolute;top:-4px;right:-4px;width:18px;height:18px;
  background:var(--red);border-radius:50%;display:flex;align-items:center;
  justify-content:center;font-size:0.6rem;font-weight:800;color:white;border:2px solid white;
}
.chat-tooltip{
  position:absolute;left:62px;bottom:0;
  background:var(--tx);color:white;font-size:0.72rem;font-weight:700;
  padding:8px 14px;border-radius:10px;white-space:nowrap;font-family:var(--ff-b);
  opacity:0;pointer-events:none;transition:opacity 0.2s;
}
.chat-tooltip::before{content:'';position:absolute;right:100%;top:50%;transform:translateY(-50%);border:5px solid transparent;border-right-color:var(--tx);}
#chat-bubble:hover .chat-tooltip{opacity:1;}

/* ============================
   NAVBAR
============================ */
.pos-nav{
  position:fixed;top:24px;left:50%;transform:translateX(-50%);
  width:min(1380px,94vw);height:68px;background:rgba(255,255,255,0.8);
  backdrop-filter:blur(16px);border:1.5px solid rgba(255,255,255,0.4);
  border-radius:20px;z-index:9000;display:flex;align-items:center;
  justify-content:space-between;padding:0 24px;
  box-shadow:0 8px 32px rgba(0,0,0,0.06);transition:all 0.4s cubic-bezier(0.19,1,0.22,1);
  opacity:0;visibility:hidden;
}
.pos-nav.on{opacity:1;visibility:visible;}
.pos-nav.scrolled{top:12px;height:60px;background:rgba(255,255,255,0.9);box-shadow:0 12px 48px rgba(0,0,0,0.12);border-radius:15px;}
.nav-logo{display:flex;align-items:center;gap:10px;font-family:var(--ff-h);font-size:1.1rem;font-weight:800;color:var(--tx);}
.nav-logo-mark{width:32px;height:32px;background:var(--p);border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;font-weight:900;}
.nav-links{display:flex;align-items:center;gap:32px;}
.nav-link{font-size:0.875rem;font-weight:600;color:var(--txm);transition:color 0.2s;}
.nav-link:hover{color:var(--p);}
.nav-acts{display:flex;align-items:center;gap:12px;}
.btn-nav-p{padding:10px 20px;background:var(--p);color:white;border-radius:12px;font-size:0.85rem;font-weight:700;box-shadow:0 4px 12px rgba(91,33,182,0.2);transition:all 0.2s;}
.btn-nav-p:hover{background:var(--pd);transform:translateY(-1px);}
.btn-nav-o{padding:9px 18px;border:1.5px solid var(--br);border-radius:12px;font-size:0.85rem;font-weight:700;color:var(--txm);transition:all 0.2s;}
.btn-nav-o:hover{border-color:var(--p);color:var(--p);}

/* ============================
   RESPONSIVE
============================ */
@media(max-width:1100px){
  .pos-nav{top:0;left:0;right:0;width:100%;border-radius:0;transform:none;height:64px;}
  .nav-links{display:none;}
  .sec,.sec-sm,.footer{padding:80px 24px;}
  .stats-wrap{padding:56px 24px;}
  .hero{padding:120px 24px 64px;}
  .hero-in{grid-template-columns:1fr;gap:52px;text-align:center;}
  .hero-stats{justify-content:center;}
  .hero-sub,.sec-sub{margin-left:auto;margin-right:auto;}
  .hero-acts{justify-content:center;}
  .terminal{width:320px;transform:none!important;}
  .hfloat{display:none;}
  .stats-grid{grid-template-columns:1fr 1fr;}
  .feat-grid,.testi-grid{grid-template-columns:1fr;}
  .fc.span2{grid-column:1;display:block;}
  .fc.span2 .mini-viz{margin-top:22px;}
  .how-grid{grid-template-columns:1fr 1fr;}
  .how-grid::before{display:none;}
  .app-wrap,.demo-wrap,.roi-wrap,.security-grid,.faq-wrap{grid-template-columns:1fr;}
  .pricing-grid,.hw-grid{grid-template-columns:1fr;}
  .cmp-head,.cmp-row{grid-template-columns:2fr 1.5fr;}
  .cmp-cell:nth-child(n+3),.cmp-th:nth-child(n+3){display:none;}
  .api-block{flex-direction:column;padding:28px 22px;}
  .footer-top{grid-template-columns:1fr 1fr;}
  .footer-mid{grid-template-columns:1fr;}
  .footer-bottom{flex-direction:column;gap:12px;text-align:center;}
  #kvkk-banner{flex-direction:column;align-items:flex-start;}
  #chat-bubble{bottom:24px;left:24px;}
}
@media(max-width:640px){
  .stats-grid{grid-template-columns:1fr;}
  .masa-grid{grid-template-columns:repeat(4,1fr);}
  .pricing-toggle{flex-wrap:wrap;justify-content:center;}
  .roi-result-grid{grid-template-columns:1fr;}
  .sec-badge-row{grid-template-columns:1fr;}
  .footer-top{grid-template-columns:1fr;}
  .cta-trust{gap:16px;}
  #skip-intro{bottom:18px;right:18px;}
  .intro-dots{bottom:18px;}
  .nav-acts .btn-nav-o{display:none;}
}
</style>

<!-- =====================
     INTRO OVERLAY
===================== -->
<div class="pos-page" x-data="posIntro()" x-init="init()">

<div id="intro-overlay" :class="{ 'done': done }">
  <div id="intro-progress-bar"><div id="intro-progress-fill" :style="'width:' + progress + '%'"></div></div>
  <button id="skip-intro" @click="skip()">Geç <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
  <div class="intro-dots"><template x-for="i in total" :key="i"><div class="i-dot" :class="{ 'on': scene===i-1 }"></div></template></div>

  <!-- SCENE 0 — Logo splash -->
  <div class="iscene" x-show="scene===0" x-cloak>
    <div class="a-scb" style="display:flex;flex-direction:column;align-items:center;gap:28px;">
      <div style="position:relative;">
        <div style="position:absolute;inset:-18px;border-radius:50%;border:1.5px solid rgba(91,33,182,0.15);animation:rotate 8s linear infinite;"></div>
        <div style="position:absolute;inset:-34px;border-radius:50%;border:1.5px dashed rgba(91,33,182,0.07);animation:rotate 14s linear infinite reverse;"></div>
        <div style="width:112px;height:112px;background:var(--p);border-radius:28px;display:flex;align-items:center;justify-content:center;box-shadow:0 24px 64px rgba(91,33,182,0.4);animation:pulse 2.5s infinite 1s;position:relative;z-index:1;">
          <span style="color:white;font-weight:900;font-size:3rem;font-family:var(--ff-h);">R</span>
        </div>
      </div>
      <div class="a-f d5" style="text-align:center;">
        <p style="color:var(--txm);font-size:0.75rem;font-weight:700;letter-spacing:0.4em;text-transform:uppercase;font-family:var(--ff-b);">RezerVist Systems</p>
        <div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:8px;">
          <div style="width:5px;height:5px;border-radius:50%;background:var(--grn);animation:pulseGrn 1.8s infinite;"></div>
          <span style="font-size:0.62rem;font-weight:700;color:var(--txm);letter-spacing:0.18em;text-transform:uppercase;font-family:var(--ff-b);">Tüm Sistemler Aktif</span>
        </div>
      </div>
    </div>
  </div>

  <!-- SCENE 1 — Product name + typewriter -->
  <div class="iscene" x-show="scene===1" x-cloak>
    <div style="text-align:center;max-width:620px;">
      <div class="a-fd" style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:18px;">
        <div style="width:36px;height:36px;background:var(--p);border-radius:10px;display:flex;align-items:center;justify-content:center;"><span style="color:white;font-family:var(--ff-h);font-weight:900;font-size:1.1rem;">R</span></div>
        <span style="font-size:0.68rem;font-weight:800;color:var(--txm);letter-spacing:0.22em;text-transform:uppercase;font-family:var(--ff-b);">Satış Noktası Çözümü</span>
      </div>
      <h1 class="i-type" style="font-family:var(--ff-h);font-size:clamp(2.4rem,6vw,4.5rem);font-weight:900;color:var(--tx);letter-spacing:-0.05em;margin:0 auto;">RezerVistA POS</h1>
      <div class="a-f d9" style="display:flex;align-items:center;justify-content:center;gap:12px;margin-top:28px;">
        <div class="i-statcard" style="text-align:center;"><div class="i-sv" style="color:var(--p);">v1.0</div><div class="i-sl">Sürüm</div></div>
        <div class="i-statcard" style="text-align:center;"><div class="i-sv" style="color:var(--grn);">Aktif</div><div class="i-sl">Durum</div></div>
        <div class="i-statcard" style="text-align:center;"><div class="i-sv">{{ number_format($activeBusinessesCount, 0, ',', '.') }}{{ $activeBusinessesCount >= 1000 ? '+' : '' }}</div><div class="i-sl">İşletme</div></div>
        <div class="i-statcard" style="text-align:center;"><div class="i-sv">{{ max(1, $cityCount) }}</div><div class="i-sl">İl</div></div>
      </div>
    </div>
  </div>

  <!-- SCENE 2 — Speed -->
  <div class="iscene" x-show="scene===2" x-cloak>
    <div style="max-width:620px;width:100%;display:flex;align-items:center;gap:60px;flex-wrap:wrap;justify-content:center;">
      <div class="a-sl" style="flex:1;min-width:240px;">
        <div style="width:56px;height:56px;background:var(--sf);border-radius:16px;border:1.5px solid var(--br);display:flex;align-items:center;justify-content:center;margin-bottom:18px;"><i class="fa-solid fa-bolt" style="color:var(--p);font-size:1.5rem;"></i></div>
        <div style="font-family:var(--ff-h);font-size:clamp(2.8rem,7vw,4.5rem);font-weight:900;color:var(--tx);letter-spacing:-0.05em;line-height:1;">15<span style="color:var(--p);">ms</span></div>
        <p style="color:var(--txs);font-size:0.95rem;margin-top:10px;line-height:1.68;max-width:280px;">Maksimum yanıt süresi. İnternet olmaksızın yerel ağda tam güçte çalışır.</p>
        <div style="display:flex;gap:8px;margin-top:18px;">
          <div class="i-chip"><i class="fa-solid fa-check" style="font-size:.55rem;"></i> Offline Mode</div>
          <div class="i-chip"><i class="fa-solid fa-check" style="font-size:.55rem;"></i> LAN Sync</div>
        </div>
      </div>
      <div class="a-sr" style="flex-shrink:0;">
        <div style="width:150px;height:150px;border-radius:50%;position:relative;display:flex;align-items:center;justify-content:center;animation:pulse 2.2s infinite;">
          <div style="text-align:center;position:relative;z-index:1;">
            <div style="font-family:var(--ff-h);font-size:2rem;font-weight:900;color:var(--p);line-height:1;">~15</div>
            <div style="font-size:0.58rem;font-weight:800;color:var(--txm);text-transform:uppercase;letter-spacing:0.15em;margin-top:2px;font-family:var(--ff-b);">ms avg</div>
          </div>
          <svg style="position:absolute;inset:0;width:100%;height:100%;" viewBox="0 0 150 150">
            <circle cx="75" cy="75" r="68" fill="none" stroke="var(--br)" stroke-width="5"/>
            <circle cx="75" cy="75" r="68" fill="none" stroke="var(--p)" stroke-width="5" stroke-dasharray="427" stroke-dashoffset="42" stroke-linecap="round" transform="rotate(-90 75 75)" style="animation:lineAnim 1.5s ease both;"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- SCENE 3 — Table grid -->
  <div class="iscene" x-show="scene===3" x-cloak>
    <div style="max-width:540px;width:100%;">
      <div class="a-fd" style="text-align:center;margin-bottom:24px;">
        <div class="i-chip" style="margin-bottom:12px;"><i class="fa-solid fa-table-cells" style="font-size:.58rem;"></i> Masa Yönetimi</div>
        <h2 style="font-family:var(--ff-h);font-size:clamp(1.8rem,4.5vw,3.2rem);font-weight:900;color:var(--tx);letter-spacing:-0.045em;">Tüm Masalar <span style="color:var(--p);">Tek Ekranda</span></h2>
      </div>
      <div class="a-sc d2" style="display:grid;grid-template-columns:repeat(6,1fr);gap:6px;">
        @php $tbs2=[['t'=>'empty','n'=>'01'],['t'=>'full','n'=>'02'],['t'=>'busy','n'=>'03'],['t'=>'empty','n'=>'04'],['t'=>'full','n'=>'05'],['t'=>'reserve','n'=>'06'],['t'=>'empty','n'=>'07'],['t'=>'full','n'=>'08'],['t'=>'busy','n'=>'09'],['t'=>'empty','n'=>'10'],['t'=>'cleaning','n'=>'11'],['t'=>'full','n'=>'12'],['t'=>'empty','n'=>'13'],['t'=>'busy','n'=>'14'],['t'=>'full','n'=>'15'],['t'=>'reserve','n'=>'16'],['t'=>'empty','n'=>'17'],['t'=>'full','n'=>'18']]; @endphp
        @foreach($tbs2 as $t)
          @php $lmap2=['empty'=>'Boş','full'=>'Dolu','busy'=>'Meşgul','reserve'=>'Rezerv','cleaning'=>'Temiz']; @endphp
          <div style="aspect-ratio:1;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:var(--ff-b);font-weight:700;{{ $t['t']==='full'?'background:var(--p);color:white;box-shadow:0 4px 14px rgba(91,33,182,0.28);':($t['t']==='busy'?'background:rgba(239,68,68,0.06);border:1.5px solid rgba(239,68,68,0.2);color:var(--red);':($t['t']==='reserve'?'background:rgba(245,158,11,0.07);border:1.5px solid rgba(245,158,11,0.22);color:var(--ylw);':($t['t']==='cleaning'?'background:rgba(14,165,233,0.07);border:1.5px solid rgba(14,165,233,0.22);color:var(--acc);':'background:var(--sf);border:1.5px solid var(--br);color:var(--txm);'))) }}">
            <div style="font-size:0.42rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:1px;opacity:0.8;">{{ $lmap2[$t['t']] }}</div>
            <div style="font-family:var(--ff-h);font-size:0.9rem;font-weight:900;">{{ $t['n'] }}</div>
          </div>
        @endforeach
      </div>
      <div class="a-f d7" style="display:flex;justify-content:center;gap:14px;margin-top:14px;flex-wrap:wrap;">
        @foreach(['full'=>['var(--p)','Dolu'],'busy'=>['rgba(239,68,68,0.6)','Meşgul'],'reserve'=>['rgba(245,158,11,0.6)','Rezerv'],'cleaning'=>['rgba(14,165,233,0.6)','Temizleniyor'],'empty'=>['var(--br)','Boş']] as $s=>$info)
        <div style="display:flex;align-items:center;gap:5px;"><div style="width:8px;height:8px;border-radius:3px;background:{{$info[0]}};"></div><span style="font-size:0.68rem;font-weight:700;color:var(--txm);font-family:var(--ff-b);">{{$info[1]}}</span></div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- SCENE 4 — Analytics -->
  <div class="iscene" x-show="scene===4" x-cloak>
    <div style="max-width:620px;width:100%;display:flex;align-items:center;gap:56px;flex-wrap:wrap;justify-content:center;">
      <div class="a-sl" style="flex:1;min-width:240px;">
        <div style="width:56px;height:56px;background:var(--sf);border-radius:16px;border:1.5px solid var(--br);display:flex;align-items:center;justify-content:center;margin-bottom:18px;"><i class="fa-solid fa-chart-column" style="color:var(--p);font-size:1.5rem;"></i></div>
        <div style="font-family:var(--ff-h);font-size:clamp(1.8rem,4vw,3.2rem);font-weight:900;color:var(--tx);letter-spacing:-0.045em;line-height:1.05;">Anlık<br><span style="color:var(--p);">Analitik</span></div>
        <p style="color:var(--txs);font-size:0.9rem;margin-top:10px;line-height:1.68;">Günlük · Haftalık · Aylık satış trendleri. AI destekli öngörüler. PDF & Excel export.</p>
        <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
          <div class="i-statcard"><div class="i-sv" style="color:var(--p);">₺124K</div><div class="i-sl">Bu Ay</div></div>
          <div class="i-statcard" style="background:rgba(16,185,129,0.06);border-color:rgba(16,185,129,0.15);"><div class="i-sv" style="color:var(--grn);">+32%</div><div class="i-sl">Büyüme</div></div>
        </div>
      </div>
      <div class="a-sr" style="flex-shrink:0;display:flex;align-items:flex-end;gap:7px;height:130px;">
        @foreach([50,72,38,90,58,100,68,45,80] as $idx=>$h)
          <div style="width:26px;border-radius:5px 5px 0 0;background:{{ $h>=90?'var(--grn)':($h>=70?'var(--p)':'rgba(91,33,182,0.2)') }};height:{{ $h }}%;animation:barGrow 0.8s cubic-bezier(0.19,1,0.22,1) {{ $idx*0.08 }}s both;align-self:flex-end;"></div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- SCENE 6 — Cloud hybrid -->
  <div class="iscene" x-show="scene===6" x-cloak>
    <div style="max-width:540px;width:100%;text-align:center;">
      <div class="a-fd" style="margin-bottom:22px;">
        <div class="i-chip" style="margin-bottom:12px;"><i class="fa-solid fa-cloud" style="font-size:.58rem;"></i> Hibrit Altyapı</div>
        <h2 style="font-family:var(--ff-h);font-size:clamp(1.8rem,4.5vw,3rem);font-weight:900;color:var(--tx);letter-spacing:-0.045em;">Bulut <span style="color:var(--p);">&amp;</span> Yerel Ağ</h2>
        <p style="color:var(--txs);font-size:0.9rem;margin-top:8px;">İnternet kesilse de veriler kaybolmaz. Her terminal birbirini görür.</p>
      </div>
      <div class="a-sc d3">
        <svg width="440" height="120" viewBox="0 0 440 120" fill="none" style="max-width:100%;overflow:visible;">
          <circle cx="65" cy="60" r="38" fill="var(--sf)" stroke="var(--br)" stroke-width="1.5"/>
          <circle cx="65" cy="60" r="38" fill="none" stroke="var(--p)" stroke-width="1.5" stroke-dasharray="4 4"/>
          <text x="65" y="58" text-anchor="middle" fill="var(--p)" font-size="17" font-family="Font Awesome 6 Free" font-weight="900">&#xf0c2;</text>
          <text x="65" y="76" text-anchor="middle" fill="var(--p)" font-size="7" font-family="Space Grotesk" font-weight="800" letter-spacing="1.5">CLOUD</text>
          <line x1="105" y1="60" x2="335" y2="60" stroke="var(--br)" stroke-width="2"/>
          <line x1="105" y1="60" x2="335" y2="60" stroke="var(--p)" stroke-width="2" stroke-dasharray="10 8" style="animation:lineAnim 2s ease both;"/>
          <circle cx="220" cy="60" r="9" fill="rgba(91,33,182,0.1)" style="animation:pulse 1.8s infinite;"/>
          <circle cx="220" cy="60" r="3.5" fill="var(--p)"/>
          <circle cx="375" cy="60" r="38" fill="var(--sf)" stroke="var(--br)" stroke-width="1.5"/>
          <circle cx="375" cy="60" r="38" fill="none" stroke="var(--p)" stroke-width="1.5" stroke-dasharray="4 4" style="animation:rotate 10s linear infinite;"/>
          <text x="375" y="58" text-anchor="middle" fill="var(--p)" font-size="17" font-family="Font Awesome 6 Free" font-weight="900">&#xf108;</text>
          <text x="375" y="76" text-anchor="middle" fill="var(--p)" font-size="7" font-family="Space Grotesk" font-weight="800" letter-spacing="1.5">LOCAL</text>
        </svg>
      </div>
      <div class="a-f d7" style="display:flex;justify-content:center;gap:20px;margin-top:14px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:6px;"><div style="width:7px;height:7px;border-radius:50%;background:var(--grn);"></div><span style="font-size:0.7rem;font-weight:700;color:var(--txm);letter-spacing:0.08em;text-transform:uppercase;font-family:var(--ff-b);">%100 Senkron</span></div>
        <div style="display:flex;align-items:center;gap:6px;"><div style="width:7px;height:7px;border-radius:50%;background:var(--p);"></div><span style="font-size:0.7rem;font-weight:700;color:var(--txm);letter-spacing:0.08em;text-transform:uppercase;font-family:var(--ff-b);">Güvenli Bağlantı</span></div>
        <div style="display:flex;align-items:center;gap:6px;"><div style="width:7px;height:7px;border-radius:50%;background:var(--acc);"></div><span style="font-size:0.7rem;font-weight:700;color:var(--txm);letter-spacing:0.08em;text-transform:uppercase;font-family:var(--ff-b);">Otomatik Yedek</span></div>
      </div>
    </div>
  </div>

  <!-- SCENE 7 — All ready -->
  <div class="iscene" x-show="scene===7" x-cloak>
    <div style="text-align:center;max-width:700px;width:100%;">
      <div class="a-fd">
        <h2 style="font-family:var(--ff-h);font-size:clamp(2.2rem,5vw,4rem);font-weight:900;color:var(--tx);letter-spacing:-0.05em;margin-bottom:8px;">Her Şey <span style="background:linear-gradient(135deg,var(--p),#A78BFA,var(--acc));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Hazır.</span></h2>
        <p style="color:var(--txm);font-size:0.9rem;">Saniyeler içinde kurulum. Yıllarca kesintisiz çalışma.</p>
      </div>
      <div class="a-sc d2" style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin:24px 0;">
        @foreach([['fa-bolt','Ultra Hız','15ms'],['fa-table-cells','Masa Grid','Gerçek Zamanlı'],['fa-chart-column','Analitik','AI Destekli'],['fa-cloud','Hibrit Ağ','%99.9 Uptime'],['fa-shield-halved','Güvenlik','AES-256'],['fa-qrcode','QR Menü','Dijital'],['fa-boxes-stacked','Stok','Otomasyon'],['fa-mobile-screen','Mobil','iOS & Android']] as $f)
        <div style="background:var(--sf);border:1.5px solid var(--br);border-radius:14px;padding:18px 12px;text-align:center;transition:all 0.3s;" onmouseover="this.style.borderColor='rgba(91,33,182,0.3)';this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='var(--br)';this.style.transform='translateY(0)';">
          <i class="fa-solid {{ $f[0] }}" style="color:var(--p);font-size:1.3rem;display:block;margin-bottom:8px;"></i>
          <div style="font-family:var(--ff-h);font-size:0.8rem;font-weight:700;color:var(--tx);">{{ $f[1] }}</div>
          <div style="font-size:0.62rem;color:var(--txm);margin-top:3px;font-family:var(--ff-c);">{{ $f[2] }}</div>
        </div>
        @endforeach
      </div>
      <div class="a-f d7">
        <button onclick="document.querySelector('[x-data]').__x.$data.skip()" style="padding:14px 36px;background:var(--p);color:white;border:none;border-radius:14px;font-weight:700;font-size:0.875rem;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;box-shadow:0 10px 30px rgba(91,33,182,0.3);font-family:var(--ff-b);display:inline-flex;align-items:center;gap:10px;transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
          POS Sistemini İncele <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
    </div>
  </div>

</div><!-- /intro-overlay -->

<!-- =====================
     MAIN PAGE
===================== -->
<div id="main-page" :class="{ 'on': done }">

<!-- =====================
     HERO
===================== -->
<section class="hero">
  <div class="hero-noise"></div>
  <div class="hero-grid"></div>
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-orb hero-orb-3"></div>
  <div class="hero-in">
    <div>
      <div class="hero-badge">
        <div class="badge-ring"><div class="badge-dot"></div></div>
        <span>Yeni Nesil POS · v1.0</span>
        <div style="width:1px;height:12px;background:rgba(91,33,182,0.15);"></div>
        <a href="{{ route('pages.pos.versions') }}" style="color:var(--p);font-size:0.63rem;font-weight:800;letter-spacing:0.07em;">SÜRÜM NOTLARI →</a>
      </div>
      <h1 class="hero-h">İşletmenizin<br><span class="g">Sinir Sistemi.</span></h1>
      <p class="hero-sub">Sadece bir POS değil — rezervasyon, mutfak, stok, analitik ve çok şube yönetimini sıfır gecikmeyle tek bir ekosistemde buluşturan akıllı terminal.</p>
      <div class="hero-acts">
        <a href="#features" class="btn-p" style="color:#fff;">
          <i class="fa-solid fa-arrow-down" style="font-size:.72rem;color:#fff;"></i>
          Özellikleri Keşfet
        </a>
        <button class="video-trigger" onclick="document.getElementById('video-modal').classList.add('open')">
          <div class="play-ring"><i class="fa-solid fa-play"></i></div> Demo İzle
        </button>
        <a href="{{ route('pages.contact') }}" class="btn-o">Teklif Al <i class="fa-solid fa-arrow-right" style="font-size:.7rem;"></i></a>
      </div>
      <div class="hero-trust">
        <div class="hero-trust-item"><i class="fa-solid fa-check"></i> Kurulum dahil</div>
        <div class="hero-trust-item"><i class="fa-solid fa-check"></i> 14 gün ücretsiz</div>
        <div class="hero-trust-item"><i class="fa-solid fa-check"></i> Kart gerekmez</div>
      </div>
      <div class="hero-stats">
        <div><div class="hs-num">{{ $formattedBusinessesCount }}<span>{{ $businessSuffix }}</span></div><div class="hs-lbl">Aktif İşletme</div></div>
        <div><div class="hs-num">{{ $latency }}<span>ms</span></div><div class="hs-lbl">Senkronizasyon</div></div>
        <div><div class="hs-num">{{ $uptime }}<span>%</span></div><div class="hs-lbl">Uptime SLA</div></div>
        <div><div class="hs-num">₺{{ $formattedVolume }}<span>{{ $volumeSuffix }}</span></div><div class="hs-lbl">Toplam Hacim</div></div>
      </div>
    </div>
    <div class="terminal-wrap">
      <div class="term-glow"></div>
      <div class="hfloat hf1">
        <div class="hfl">Günlük Ciro</div>
        <div class="hfv">₺18.4K</div>
        <div class="hft hft-g">↑ %23 bu hafta</div>
      </div>
      <div class="hfloat hf2">
        <div class="hfl">Aktif Masa</div>
        <div class="hfv">12 / 24</div>
        <div class="hft hft-a">4 rezervasyon</div>
      </div>
      <div class="hfloat hf3">
        <div class="hfl">Mutfak</div>
        <div class="hfv">8 Sipariş</div>
        <div class="hft hft-g">↓ avg 4dk</div>
      </div>
      <div class="terminal">
        <div class="t-hdr">
          <div class="t-dots"><div class="t-dot td-r"></div><div class="t-dot td-o"></div><div class="t-dot td-g"></div></div>
          <span class="t-lbl">RezerVistA POS v1.0</span>
          <div style="width:32px;"></div>
        </div>
        <div class="t-body">
          <div class="t-topbar">
            <div><div class="t-greet">Merhaba, Ayberk 👋</div><div class="t-name">Günlük Özet</div></div>
            <div class="t-time" id="live-time">--:--</div>
          </div>
          <div class="t-kpis">
            <div class="t-kpi hi"><div class="t-kl">Bugünkü Ciro</div><div class="t-kv">₺18.4K</div><div class="t-kt kt-g">↑ %23</div></div>
            <div class="t-kpi"><div class="t-kl">Siparişler</div><div class="t-kv">142</div><div class="t-kt kt-m">12 aktif</div></div>
          </div>
          <div class="t-kpis" style="margin-top:-6px;">
            <div class="t-kpi"><div class="t-kl">Ortalama Süre</div><div class="t-kv" style="font-size:1rem;">24 dk</div><div class="t-kt kt-g">↓ 3dk iyi</div></div>
            <div class="t-kpi"><div class="t-kl">Masa Doluluk</div><div class="t-kv" style="font-size:1rem;">%79</div><div class="t-kt kt-g">↑ %12</div></div>
          </div>
          <div class="t-chart-l">Saatlik Satış Trendi</div>
          <div class="t-chart">
            <div class="t-b" style="height:26%;"></div><div class="t-b" style="height:42%;"></div>
            <div class="t-b" style="height:58%;"></div><div class="t-b" style="height:34%;"></div>
            <div class="t-b hi" style="height:84%;"></div><div class="t-b ac" style="height:68%;"></div>
            <div class="t-b" style="height:46%;"></div><div class="t-b" style="height:54%;"></div>
          </div>
          <div class="t-orders">
            <div class="t-order"><div class="t-ol"><div class="t-ic ic-p"><i class="fa-solid fa-utensils"></i></div><div><div class="t-ot">Masa 7 — 4 Kişi</div><div class="t-os">Ana yemek · 3 içecek</div></div></div><span class="bdg bdg-ok">Hazır</span></div>
            <div class="t-order"><div class="t-ol"><div class="t-ic ic-t"><i class="fa-solid fa-motorcycle"></i></div><div><div class="t-ot">Kurye #KY-041</div><div class="t-os">Yolda · ~12 dk</div></div></div><span class="bdg bdg-go">Yolda</span></div>
            <div class="t-order"><div class="t-ol"><div class="t-ic ic-y"><i class="fa-solid fa-clock"></i></div><div><div class="t-ot">Masa 3 — Rezervasyon</div><div class="t-os">19:30 · 6 kişi</div></div></div><span class="bdg bdg-rd">Bekliyor</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Video Modal -->
<div id="video-modal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="vmod-box">
    <div class="vmod-header">
<div class="vmod-title">
  <i class="fa-solid fa-video" style="margin-right:8px;color:var(--p);"></i>
  RezerVistA POS — Demo Videosu
</div>
      <div class="vmod-close" onclick="document.getElementById('video-modal').classList.remove('open')"><i class="fa-solid fa-xmark"></i></div>
    </div>
    <div class="vmod-body">
      <div class="vmod-demo">
        <div class="vmod-play-btn" onclick="alert('Demo video yükleniyor...')">
          <i class="fa-solid fa-play"></i>
        </div>
        <p style="font-family:var(--ff-h);font-size:1.1rem;font-weight:700;color:var(--tx);">3 Dakikada Tüm Özellikler</p>
        <p style="font-size:0.82rem;color:var(--txs);">Kurulumdan ilk siparişe — RezerVistA POS'u canlı görün.</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-top:4px;">
          <div class="i-chip"><i class="fa-solid fa-clock" style="font-size:.55rem;"></i> 3:24 dk</div>
          <div class="i-chip"><i class="fa-solid fa-cc" style="font-size:.55rem;"></i> Türkçe Altyazı</div>
          <div class="i-chip"><i class="fa-solid fa-4k" style="font-size:.55rem;"></i> HD Kalite</div>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        @foreach(['Masa Yönetimi — 0:32','Sipariş Akışı — 1:14','Raporlama — 2:08'] as $ts)
        <div style="background:white;border:1.5px solid var(--br);border-radius:12px;padding:14px;text-align:center;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--p)';" onmouseout="this.style.borderColor='var(--br)';">
          <i class="fa-solid fa-circle-play" style="color:var(--p);font-size:1.3rem;display:block;margin-bottom:6px;"></i>
          <div style="font-size:0.72rem;font-weight:700;color:var(--tx);font-family:var(--ff-b);">{{ $ts }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- =====================
     TRUST BAR
===================== Düzenlenecek
<div class="trust-bar">
  <div class="trust-track">
    @php
      $brands = [
        ['fa-file-invoice','e-Fatura GİB'],
        ['fa-store','Getir Yemek'],
        ['fa-motorcycle','Trendyol Yemek'],
        ['fa-credit-card','İyzico Ödeme'],
        ['fa-building-columns','Garanti BBVA'],
        ['fa-qrcode','QR Menü Pro'],
        ['fa-cash-register','Parasut ERP'],
        ['fa-truck','Mikro ERP'],
        ['fa-cloud','Microsoft Azure'],
        ['fa-database','PostgreSQL'],
        ['fa-brands fa-whatsapp','WhatsApp Business'],
        ['fa-chart-bar','Power BI'],
        ['fa-file-invoice','e-Fatura GİB'],
        ['fa-store','Getir Yemek'],
        ['fa-motorcycle','Trendyol Yemek'],
        ['fa-credit-card','İyzico Ödeme'],
        ['fa-building-columns','Garanti BBVA'],
        ['fa-qrcode','QR Menü Pro'],
        ['fa-cash-register','Parasut ERP'],
        ['fa-truck','Mikro ERP'],
        ['fa-cloud','Microsoft Azure'],
        ['fa-database','PostgreSQL'],
        ['fa-brands fa-whatsapp','WhatsApp Business'],
        ['fa-chart-bar','Power BI']
      ];
    @endphp

    @foreach($brands as $b)
      <div class="trust-item">
        <i class="{{ $b[0] }}"></i> {{ $b[1] }}
      </div>
      <div class="trust-dot"></div>
    @endforeach
  </div>
</div>
-->

<!-- =====================
     STATS
===================== -->
<div class="stats-wrap">
  <div class="stats-grid">
    <div class="sblock reveal"><div class="sblock-num">{{ $formattedBusinessesCount }}<span class="c">{{ $businessSuffix }}</span></div><div class="sblock-lbl">Aktif İşletme</div><div class="sblock-sub">{{ max(1, $cityCount) }} ilde Türkiye genelinde hizmet</div><i class="fa-solid fa-store sblock-icon"></i></div>
    <div class="sblock reveal" style="transition-delay:.08s;"><div class="sblock-num">{{ $uptime }}<span class="c">%</span></div><div class="sblock-lbl">Uptime Garantisi</div><div class="sblock-sub">Son 12 ayda yalnızca 52 dk kesinti</div><i class="fa-solid fa-server sblock-icon"></i></div>
    <div class="sblock reveal" style="transition-delay:.16s;"><div class="sblock-num">{{ $latency }}<span class="c">ms</span></div><div class="sblock-lbl">Ortalama Gecikme</div><div class="sblock-sub">Yerel ağda anlık senkronizasyon</div><i class="fa-solid fa-bolt sblock-icon"></i></div>
    <div class="sblock reveal" style="transition-delay:.24s;"><div class="sblock-num">₺{{ $formattedVolume }}<span class="c">{{ $volumeSuffix }}</span></div><div class="sblock-lbl">İşlem Hacmi</div><div class="sblock-sub">{{ date('Y') }} yılında işlenen toplam ciro</div><i class="fa-solid fa-chart-line sblock-icon"></i></div>
  </div>
</div>

<!-- =====================
     FEATURES
===================== -->
<section id="features" class="sec feat-sec">
  <div class="sec-in">
    <div class="reveal"><span class="sec-tag">Temel Özellikler</span><h2 class="sec-h">Mikro Detaylar,<br><em>Makro Performans.</em></h2><p class="sec-sub">Donanım ve yazılımın mükemmel uyumuyla işletmenizi bir adım öteye taşıyın.</p></div>
    <div class="feat-grid">

      <!-- Span 2 — Sync -->
      <div class="fc span2 reveal">
        <div>
          <div class="feat-icon fi-t"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="feat-title">Anlık Senkronizasyon</h3>
          <p class="feat-desc">Mutfak, kasa ve garson terminalleri arasında 0.2ms gecikmeyle veri aktarımı. Siparişler asla kaybolmaz, müşteriler asla beklemez. WebSocket tabanlı çift yönlü bağlantı ile tüm cihazlar gerçek zamanlı senkronize kalır.</p>
          <div class="feat-chks">
            <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Real-time WebSocket DB Sync</div>
            <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Cloud + Yerel Anlık Yedekleme</div>
            <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Çoklu Terminal Desteği (Sınırsız)</div>
            <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Otomatik Çakışma Çözümü</div>
          </div>
        </div>
        <div class="mini-viz">
          <div class="mv-l">Gerçek Zamanlı Ağ Monitörü</div>
          <div class="mv-bars">
            @foreach([36,52,26,70,16,42,30,62,22,50,80,18] as $h)
            <div class="mv-b" style="height:{{ $h }}%;background:{{ $h>=70?'var(--p)':($h<=20?'rgba(14,165,233,0.5)':'rgba(91,33,182,0.15)') }};border-radius:4px 4px 0 0;"></div>
            @endforeach
          </div>
          <div class="mv-nums">
            <div class="mv-n"><div class="mv-nv" style="color:var(--p);">0.2ms</div><div class="mv-nl">Ort. Gecikme</div></div>
            <div class="mv-n"><div class="mv-nv">99.9%</div><div class="mv-nl">Uptime</div></div>
            <div class="mv-n"><div class="mv-nv" style="color:var(--acc);">3.2K+</div><div class="mv-nl">Aktif Cihaz</div></div>
            <div class="mv-n"><div class="mv-nv" style="color:var(--grn);">0</div><div class="mv-nl">Veri Kaybı</div></div>
          </div>
        </div>
      </div>

      <!-- Dark — Security -->
      <div class="fc dark reveal" style="transition-delay:.08s;">
        <div class="feat-icon fi-w"><i class="fa-solid fa-shield-halved"></i></div>
        <h3 class="feat-title">Askeri Seviye Güvenlik</h3>
        <p class="feat-desc">256-bit AES şifreleme ve PCI-DSS uyumlu altyapı. Biyometrik giriş ile yalnızca yetkili personel erişimi.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> E2EE Uçtan Uca Şifreleme</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Biyometrik Kimlik Doğrulama</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Rol Tabanlı Erişim Kontrolü</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Oturum Yönetimi & Audit Log</div>
        </div>
      </div>

      <!-- AI Analytics -->
      <div class="fc reveal" style="transition-delay:.13s;">
        <div class="feat-new">YENİ</div>
        <div class="feat-icon fi-p"><i class="fa-solid fa-brain"></i></div>
        <h3 class="feat-title">AI Destekli Analitik</h3>
        <p class="feat-desc">Yapay zeka ile satış trendlerini öngörün. En karlı masaları, personel performansını ve menü optimizasyonunu anlık izleyin.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Tahminsel Satış Motoru</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Isı Haritası Raporları</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Anomali Tespiti</div>
        </div>
      </div>

      <!-- QR Menu -->
      <div class="fc reveal" style="transition-delay:.18s;">
        <div class="feat-icon fi-t"><i class="fa-solid fa-qrcode"></i></div>
        <h3 class="feat-title">QR & Dijital Menü</h3>
        <p class="feat-desc">Masadaki müşteriler kodu okutarak menüye anında ulaşır. Güncellemeler saniyeler içinde tüm masalara yayınlanır.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Dinamik Fiyat & Stok Güncellemesi</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> 15+ Dil Desteği</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Masadan Sipariş & Ödeme</div>
        </div>
      </div>

      <!-- Stock -->
      <div class="fc reveal" style="transition-delay:.23s;">
        <div class="feat-icon fi-s"><i class="fa-solid fa-boxes-stacked"></i></div>
        <h3 class="feat-title">Akıllı Stok Yönetimi</h3>
        <p class="feat-desc">Kritik eşiğin altına düşen malzemeleri tespit edin, tedarikçiye otomatik sipariş gönderin. FIFO/LIFO yöntemleri destekli.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Otomatik Sipariş Tetikleyici</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Tedarikçi Entegrasyonu</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Barkod / QR Stok Sayımı</div>
        </div>
      </div>

      <!-- KDS -->
      <div class="fc reveal" style="transition-delay:.28s;">
        <div class="feat-icon fi-y"><i class="fa-solid fa-kitchen-set"></i></div>
        <h3 class="feat-title">Mutfak Ekranı (KDS)</h3>
        <p class="feat-desc">Siparişler girer girmez mutfak ekranına düşer. Öncelik sıralaması, gecikme uyarıları ve hazırlık süresi takibi.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Gerçek Zamanlı Sipariş Akışı</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Hazırlık Süresi Takibi</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Sesli & Görsel Uyarılar</div>
        </div>
      </div>

      <!-- Reporting -->
      <div class="fc reveal" style="transition-delay:.33s;">
        <div class="feat-icon fi-r"><i class="fa-solid fa-file-chart-column"></i></div>
        <h3 class="feat-title">Gelişmiş Raporlama</h3>
        <p class="feat-desc">Günlük, haftalık, aylık ciro raporları. Personel performansı, masa devir hızı ve ürün bazlı karlılık analizleri.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Otomatik Rapor Zamanlaması</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> WhatsApp & E-posta Gönderimi</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> PDF, Excel, CSV Export</div>
        </div>
      </div>

      <!-- Multi branch -->
      <div class="fc reveal" style="transition-delay:.38s;">
        <div class="feat-icon fi-p"><i class="fa-solid fa-code-branch"></i></div>
        <h3 class="feat-title">Çok Şube Yönetimi</h3>
        <p class="feat-desc">Tüm şubelerinizi tek panelden yönetin. Şubeler arası stok transferi, merkezi menü güncellemesi ve toplu raporlama.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Merkezi Dashboard</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Şube Karşılaştırma Raporu</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Hiyerarşik Yetki Sistemi</div>
        </div>
      </div>

      <!-- Loyalty — Accent -->
      <div class="fc acc reveal" style="transition-delay:.43s;">
        <div class="feat-new" style="background:rgba(255,255,255,0.3);color:white;">YENİ</div>
        <div class="feat-icon fi-w"><i class="fa-solid fa-star"></i></div>
        <h3 class="feat-title">Müşteri Sadakat Sistemi</h3>
        <p class="feat-desc">Puan bazlı ödül sistemi, doğum günü kampanyaları ve segmente özel indirimlerle müşteri bağlılığını artırın.</p>
        <div class="feat-chks">
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> Puan Kazanma & Harcama</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> SMS & Push Kampanyaları</div>
          <div class="feat-chk"><div class="chkd"><i class="fa-solid fa-check"></i></div> CRM Entegrasyonu</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- =====================
     HOW IT WORKS
===================== -->
<section class="sec" style="background:var(--bg2);">
  <div class="sec-in">
    <div class="reveal" style="text-align:center;">
      <span class="sec-tag">Nasıl Çalışır?</span>
      <h2 class="sec-h" style="max-width:540px;margin:0 auto 16px;">Dakikalar İçinde<br><em>Kurulum ve Yayın.</em></h2>
      <p class="sec-sub" style="margin:0 auto;text-align:center;">Teknik bilgi gerekmez. Destek ekibimiz her adımda yanınızda.</p>
    </div>
    <div class="how-grid">
      <div class="how-step reveal"><div class="how-num">1</div><div class="how-icon-wrap"><i class="fa-solid fa-box-open"></i></div><h3 class="how-title">Cihazı Bağlayın</h3><p class="how-desc">Terminali prize takın, Wi-Fi veya Ethernet'e bağlayın. Sistem otomatik yapılandırma sihirbazını başlatır.</p><div class="how-eta"><i class="fa-solid fa-clock" style="font-size:.65rem;"></i> ~3 dk</div></div>
      <div class="how-step reveal" style="transition-delay:.1s;"><div class="how-num">2</div><div class="how-icon-wrap"><i class="fa-solid fa-sliders"></i></div><h3 class="how-title">Menünüzü Aktarın</h3><p class="how-desc">Ürünlerinizi girin veya Excel ile toplu import yapın. Masa planınızı sürükle-bırak ile oluşturun.</p><div class="how-eta"><i class="fa-solid fa-clock" style="font-size:.65rem;"></i> ~3 dk</div></div>
      <div class="how-step reveal" style="transition-delay:.2s;"><div class="how-num">3</div><div class="how-icon-wrap"><i class="fa-solid fa-users"></i></div><h3 class="how-title">Ekibi Tanımlayın</h3><p class="how-desc">Personel rollerini ve yetkilerini atayın. Garson, kasiyer, mutfak, yönetici profillerini 2 dakikada oluşturun.</p><div class="how-eta"><i class="fa-solid fa-clock" style="font-size:.65rem;"></i> ~2 dk</div></div>
      <div class="how-step reveal" style="transition-delay:.3s;"><div class="how-num">4</div><div class="how-icon-wrap"><i class="fa-solid fa-rocket"></i></div><h3 class="how-title">Hizmete Başlayın</h3><p class="how-desc">İlk siparişinizi alın. Mutfak ekranı, garson tableti ve kasa terminali anında senkronize olur.</p><div class="how-eta"><i class="fa-solid fa-check" style="font-size:.65rem;"></i> Hemen</div></div>
    </div>
    <!-- Timeline bar -->
    <div class="reveal" style="transition-delay:.4s;margin-top:72px;background:white;border:1.5px solid var(--br);border-radius:var(--r2);padding:42px 48px;display:flex;align-items:center;justify-content:space-between;gap:40px;flex-wrap:wrap;box-shadow:0 24px 56px var(--sh);">
      <div style="flex:1;min-width:320px;">
        <div style="font-family:var(--ff-h);font-size:1.8rem;font-weight:900;color:var(--tx);letter-spacing:-0.04em;line-height:1.1;">⏱ Ortalama <span style="background:linear-gradient(135deg,var(--p),var(--acc));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">8 dakikada</span><br>kurulum tamamlanır.</div>
        <div style="font-size:0.95rem;color:var(--txm);margin-top:10px;line-height:1.6;font-weight:500;">Hiçbir teknik bilgi gerekmez. Uzman ekibimiz ilk kurulumdan ürün girişine kadar her adımda size destek olur.</div>
      </div>
        <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <a href="{{ route('register') }}" class="btn-p btn-white">
            <i class="fa-solid fa-rocket"></i> Hemen Başla
        </a>
        <a href="{{ route('pages.contact') }}" class="btn-o btn-white">
            <i class="fa-solid fa-circle-play"></i> Ücretsiz Demo Al
        </a>
        </div>
    </div>
  </div>
</section>

<!-- =====================
     MOBILE APP
===================== -->
<section class="sec app-sec">
  <div class="sec-in">
    <div class="app-wrap">
      <div class="reveal">
        <span class="sec-tag">Mobil Uygulama</span>
        <h2 class="sec-h">İşletmeniz<br><em>Cebinizde.</em></h2>
        <p class="sec-sub" style="margin-bottom:28px;">iOS ve Android için yönetim uygulaması ile işletmenizi dilediğiniz yerden izleyin ve yönetin. Anlık satış bildirimleri, canlı masa görünümü ve personel takibi.</p>
        <div class="app-features">
          <div class="app-feat">
            <div class="app-feat-ic fi-p" style="background:rgba(91,33,182,0.08);"><i class="fa-solid fa-bell" style="color:var(--p);"></i></div>
            <div><div class="app-feat-title">Anlık Bildirimler</div><div class="app-feat-desc">Yeni sipariş, ödeme alındı, stok kritik uyarıları anında telefonunuza gelir.</div></div>
          </div>
          <div class="app-feat">
            <div class="app-feat-ic" style="background:rgba(16,185,129,0.08);"><i class="fa-solid fa-chart-simple" style="color:var(--grn);"></i></div>
            <div><div class="app-feat-title">Canlı Dashboard</div><div class="app-feat-desc">Günlük ciro, aktif masalar ve siparişleri gerçek zamanlı takip edin.</div></div>
          </div>
          <div class="app-feat">
            <div class="app-feat-ic" style="background:rgba(14,165,233,0.08);"><i class="fa-solid fa-users" style="color:var(--acc);"></i></div>
            <div><div class="app-feat-title">Personel Yönetimi</div><div class="app-feat-desc">Çalışan performanslarını görüntüleyin, vardiya planı oluşturun, prim hesaplayın.</div></div>
          </div>
          <div class="app-feat">
            <div class="app-feat-ic" style="background:rgba(245,158,11,0.08);"><i class="fa-solid fa-file-invoice" style="color:var(--ylw);"></i></div>
            <div><div class="app-feat-title">Rapor & Fatura</div><div class="app-feat-desc">Tüm raporlara erişin, fatura onaylayın, haftalık özeti WhatsApp ile paylaşın.</div></div>
          </div>
        </div>
        <div class="app-stores">
          <a href="{{ $globalSettings['social_appstore'] ?? '#' }}" class="app-store-btn">
            <div class="asb-icon"><i class="fa-brands fa-apple"></i></div>
            <div><div class="asb-small">App Store'dan İndir</div><div class="asb-big">App Store</div></div>
          </a>
          <a href="{{ $globalSettings['social_googleplay'] ?? '#' }}" class="app-store-btn">
            <div class="asb-icon"><i class="fa-brands fa-google-play"></i></div>
            <div><div class="asb-small">Google Play'den İndir</div><div class="asb-big">Google Play</div></div>
          </a>
        </div>
        <div style="margin-top:16px;font-size:0.78rem;color:var(--txm);">iOS 15+ · Android 8+ gerektirir · Ücretsiz</div>
      </div>
      <div class="app-phone reveal" style="transition-delay:.12s;">
        <div class="app-phone-glow"></div>
        <div class="app-float af1">
          <div class="afl">Satış Artışı</div>
          <div class="afv" style="color:var(--grn);">+%31</div>
        </div>
        <div class="app-float af2">
          <div class="afl">Aktif Sipariş</div>
          <div class="afv">14 Adet</div>
        </div>
        <div class="app-float af3">
          <div class="afl">Bildirim</div>
          <div class="afv">Stok Kritik</div>
        </div>
        <div class="phone-frame">
          <div class="phone-notch"><div class="phone-notch-pill"></div></div>
          <div class="phone-screen">
            <div class="ph-hdr">
              <div><div style="font-size:0.52rem;color:var(--txm);font-weight:500;">Merhaba, Ahmet 👋</div><div class="ph-title">Dashboard</div></div>
              <div class="ph-notif"><i class="fa-solid fa-bell"></i></div>
            </div>
            <div class="ph-stat-row">
              <div class="ph-stat" style="background:var(--sf2);border-color:var(--br);"><div class="ph-sv" style="color:var(--p);">₺18.4K</div><div class="ph-sl">Bugünkü Ciro</div></div>
              <div class="ph-stat"><div class="ph-sv">142</div><div class="ph-sl">Sipariş</div></div>
            </div>
            <div class="ph-stat-row">
              <div class="ph-stat"><div class="ph-sv" style="color:var(--grn);">%79</div><div class="ph-sl">Doluluk</div></div>
              <div class="ph-stat"><div class="ph-sv" style="color:var(--ylw);">3</div><div class="ph-sl">Uyarı</div></div>
            </div>
            <div class="ph-chart">
              <div class="ph-cl">Saatlik Satış</div>
              <div class="ph-bars">
                @foreach([30,55,42,70,28,85,62,45] as $h)
                <div class="ph-b" style="height:{{ $h }}%;background:{{ $h>=70?'var(--p)':'rgba(91,33,182,0.15)' }};border-radius:2px 2px 0 0;"></div>
                @endforeach
              </div>
            </div>
            <div class="ph-orders">
              <div class="ph-order"><div><div class="ph-ot">Masa 7 · 4 kişi</div><div class="ph-os">Ana yemek · 3 içecek</div></div><div class="ph-bdg ph-bdg-ok">Hazır</div></div>
              <div class="ph-order"><div><div class="ph-ot">Kurye #KY-041</div><div class="ph-os">Yolda · ~12 dk</div></div><div class="ph-bdg ph-bdg-go">Yolda</div></div>
              <div class="ph-order"><div><div class="ph-ot">Masa 3 · Rezerv</div><div class="ph-os">19:30 · 6 kişi</div></div><div class="ph-bdg" style="background:rgba(91,33,182,0.08);color:var(--p);">Bekleme</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =====================
     LIVE DEMO
===================== -->
<section class="sec demo-sec" id="demo">
  <div class="sec-in">
    <div class="demo-wrap">
      <div class="demo-info reveal">
        <div class="demo-badge"><i class="fa-solid fa-circle" style="font-size:.48rem;animation:pulseGrn 1.5s infinite;"></i> Etkileşimli Demo</div>
        <h2 class="demo-title">Masa Yönetimi<br><em>Anlık Görünüm</em></h2>
        <p class="demo-sub">Her masanın durumu gerçek zamanlı güncellenir. Masalara tıklayarak durumlarını döngüsel olarak değiştirebilirsiniz. 5 farklı durum desteklenmektedir.</p>
        <div class="demo-legend">
          <div class="dl-item"><div class="dl-dot" style="background:var(--p);"></div> Dolu</div>
          <div class="dl-item"><div class="dl-dot" style="background:var(--red);opacity:0.5;"></div> Meşgul</div>
          <div class="dl-item"><div class="dl-dot" style="background:var(--ylw);opacity:0.65;"></div> Rezervasyon</div>
          <div class="dl-item"><div class="dl-dot" style="background:var(--acc);opacity:0.6;"></div> Temizleniyor</div>
          <div class="dl-item"><div class="dl-dot" style="background:var(--br);"></div> Boş</div>
        </div>
        <div class="demo-stats-row">
          <div class="demo-stat"><div class="demo-sv" style="color:var(--p);" id="full-count">9</div><div class="demo-sl">Dolu</div></div>
          <div class="demo-stat"><div class="demo-sv" style="color:var(--red);" id="busy-count">4</div><div class="demo-sl">Meşgul</div></div>
          <div class="demo-stat"><div class="demo-sv" style="color:var(--ylw);" id="res-count">3</div><div class="demo-sl">Rezerv</div></div>
          <div class="demo-stat"><div class="demo-sv" style="color:var(--acc);" id="clean-count">2</div><div class="demo-sl">Temiz</div></div>
          <div class="demo-stat"><div class="demo-sv" id="empty-count">6</div><div class="demo-sl">Boş</div></div>
        </div>
      </div>
      <div class="reveal" style="transition-delay:.1s;">
        <div class="masa-grid-wrap">
          <div class="mgw-header">
            <div class="mgh-title">🍽 Zemin Kat · 24 Masa</div>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="mgh-badge"><i class="fa-solid fa-circle" style="font-size:.46rem;"></i> Canlı</div>
            </div>
          </div>
          <div class="mgh-filter" style="margin-bottom:14px;display:flex;gap:6px;flex-wrap:wrap;">
            <button class="mgh-fb active" onclick="filterMasa('all',this)">Tümü</button>
            <button class="mgh-fb" onclick="filterMasa('full',this)">Dolu</button>
            <button class="mgh-fb" onclick="filterMasa('empty',this)">Boş</button>
            <button class="mgh-fb" onclick="filterMasa('reserve',this)">Rezerv</button>
          </div>
          <div class="masa-grid" id="masa-grid">
            @php
            $masalar=[
              ['s'=>'full','t'=>'Masa 1 · 4 kişi · ₺280'],['s'=>'full','t'=>'Masa 2 · 2 kişi · ₺140'],
              ['s'=>'busy','t'=>'Masa 3 · Sipariş bekleniyor'],['s'=>'empty','t'=>'Masa 4 · Boş'],
              ['s'=>'full','t'=>'Masa 5 · 6 kişi · ₺520'],['s'=>'reserve','t'=>'Masa 6 · 19:30 Rezervasyon'],
              ['s'=>'empty','t'=>'Masa 7 · Boş'],['s'=>'full','t'=>'Masa 8 · 3 kişi · ₺210'],
              ['s'=>'busy','t'=>'Masa 9 · Hesap istedi'],['s'=>'full','t'=>'Masa 10 · 5 kişi · ₺410'],
              ['s'=>'cleaning','t'=>'Masa 11 · Temizleniyor'],['s'=>'reserve','t'=>'Masa 12 · 20:00 Rezervasyon'],
              ['s'=>'full','t'=>'Masa 13 · 2 kişi · ₺175'],['s'=>'empty','t'=>'Masa 14 · Boş'],
              ['s'=>'busy','t'=>'Masa 15 · Mutfakta bekliyor'],['s'=>'full','t'=>'Masa 16 · 4 kişi · ₺340'],
              ['s'=>'reserve','t'=>'Masa 17 · 20:30 Rezervasyon'],['s'=>'cleaning','t'=>'Masa 18 · Temizleniyor'],
              ['s'=>'full','t'=>'Masa 19 · 8 kişi · ₺780'],['s'=>'empty','t'=>'Masa 20 · Boş'],
              ['s'=>'busy','t'=>'Masa 21 · Sipariş bekleniyor'],['s'=>'full','t'=>'Masa 22 · 3 kişi · ₺260'],
              ['s'=>'empty','t'=>'Masa 23 · Boş'],['s'=>'full','t'=>'Masa 24 · 4 kişi · ₺310']
            ];
            $slabels=['full'=>'Dolu','busy'=>'Meşgul','empty'=>'Boş','reserve'=>'Rezerv','cleaning'=>'Temiz'];
            @endphp
            @foreach($masalar as $i=>$m)
              <div class="masa-cell {{ $m['s'] }}" data-tip="{{ $m['t'] }}" onclick="toggleMasa(this)" data-status="{{ $m['s'] }}">
                <div class="mc-lbl">{{ $slabels[$m['s']] }}</div>
                <div class="mc-num">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Demo Notification container -->
<div id="demo-notif"></div>

<!-- =====================
     SECURITY
===================== -->
<section class="sec sec-security" id="security">
  <div class="sec-in sec-sec-in">
    <div class="reveal" style="text-align:center;">
      <span class="sec-tag" style="color:var(--pl2);">Güvenlik & Uyumluluk</span>
      <h2 class="sec-h" style="color:white;">Bankacılık Düzeyinde<br><em style="color:var(--pl2);">Güvenlik.</em></h2>
      <p class="sec-sub" style="color:rgba(255,255,255,0.5);margin:0 auto;">Müşteri verileriniz ve ödeme bilgileriniz her an korunma altında.</p>
    </div>
    <div class="security-grid">
      <div class="sec-feats reveal" style="transition-delay:.08s;">
        @php $secfeats=[['fa-lock','sf-ic-p','AES-256 Uçtan Uca Şifreleme','Tüm veriler, aktarım sırasında ve depolamada 256-bit AES ile şifrelenir. Kimse, Anthropic dahil, verilerinizi okuyamaz.'],['fa-fingerprint','sf-ic-g','Biyometrik Kimlik Doğrulama','Parmak izi veya yüz tanıma ile güvenli personel girişi. Her işlem bir kimlikle ilişkilendirilir ve loglanır.'],['fa-shield-check','sf-ic-a','PCI-DSS Level 1 Uyumu','Ödeme kartı endüstrisinin en üst güvenlik standardına uygun altyapı. Kart verileri asla sunucularımızda saklanmaz.'],['fa-eye-slash','sf-ic-p','KVKK & GDPR Uyumluluğu','Türkiye KVKK ve Avrupa GDPR standartlarına tam uyumluluk. Veri sahibi hakları otomatik yönetilir.']]; @endphp
        @foreach($secfeats as $f)
        <div class="sec-feat">
          <div class="sec-feat-ic {{ $f[1] }}"><i class="fa-solid {{ $f[0] }}"></i></div>
          <div><div class="sec-feat-title">{{ $f[2] }}</div><div class="sec-feat-desc">{{ $f[3] }}</div></div>
        </div>
        @endforeach
      </div>
      <div class="sec-badge-grid reveal" style="transition-delay:.16s;">
        <div class="sec-badge-row">
          @foreach([['fa-certificate','PCI-DSS','Level 1 Certified'],['fa-shield-halved','ISO 27001','Bilgi Güvenliği'],['fa-check-double','KVKK','Tam Uyumlu'],['fa-lock','SSL/TLS','256-bit Encrypted']] as $b)
          <div class="secbdg"><div class="secbdg-ic"><i class="fa-solid {{ $b[0] }}"></i></div><div class="secbdg-title">{{ $b[1] }}</div><div class="secbdg-sub">{{ $b[2] }}</div></div>
          @endforeach
        </div>
        <div class="uptime-display">
          <div class="ud-header">
            <div class="ud-title">Son 90 Gün Uptime</div>
            <div class="ud-val">99.94%</div>
          </div>
          <div class="ud-bars">
            @for($i=0;$i<60;$i++)
            <div class="ud-b {{ $i===12||$i===37?'warn':'ok' }}" style="height:{{ $i===12||$i===37?'50':'100' }}%;"></div>
            @endfor
          </div>
          <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:0.6rem;color:rgba(255,255,255,0.35);font-family:var(--ff-b);">
            <span>90 gün önce</span><span>Bugün</span>
          </div>
        </div>
        <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);border-radius:12px;padding:18px;display:flex;align-items:center;gap:14px;">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;animation:pulseGrn 2s infinite;flex-shrink:0;"><i class="fa-solid fa-check" style="color:#6EE7B7;"></i></div>
          <div><div style="font-size:0.85rem;font-weight:700;color:white;">Tüm Sistemler Çalışıyor</div><div style="font-size:0.72rem;color:rgba(255,255,255,0.45);margin-top:2px;">Son kontrol: {{ date('H:i') }} — status.rezervist.com</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =====================
     INTEGRATIONS
===================== -->
<!-- Düzenlenecek
<section class="sec" id="integrations" style="background:var(--bg2);">
  <div class="sec-in">
    <div class="reveal" style="text-align:center;">
      <span class="sec-tag">Entegrasyonlar</span>
      <h2 class="sec-h">Sınırsız<br><em>Ekosistem.</em></h2>
      <p class="sec-sub" style="margin:0 auto;text-align:center;">
        Tüm araçlarınızla konuşur. Hepsini tek noktadan yönetirsiniz. 40+ hazır entegrasyon.
      </p>
    </div>

    <div class="int-grid reveal" style="transition-delay:.08s;">
      @foreach([
        ['fa-file-invoice-dollar','e-Fatura (GİB)'],
        ['fa-motorcycle','Trendyol Yemek'],
        ['fa-store','Getir Yemek'],
        ['fa-truck','Yemeksepeti'],
        ['fa-credit-card','İyzico'],
        ['fa-credit-card','PayTR'],
        ['fa-building-columns','Garanti BBVA'],
        ['fa-building-columns','Ziraat Bankası'],
        ['fa-qrcode','QR Menü Pro'],
        ['fa-wifi','NFC / Temassız'],
        ['fa-boxes-stacked','Mikro ERP'],
        ['fa-cash-register','Parasut'],
        ['fa-brands fa-whatsapp','WhatsApp Business'],
        ['fa-envelope','E-posta SMTP'],
        ['fa-chart-bar','Power BI'],
        ['fa-brands fa-google','Google Analytics'],
        ['fa-truck-fast','Kurye API'],
        ['fa-barcode','Barkod / QR Okuyucu'],
        ['fa-print','Fişyazıcı / Termal'],
        ['fa-scale-balanced','Kasa Çekmecesi']
      ] as $p)
        <div class="int-pill">
          <i class="{{ $p[0] }}"></i> {{ $p[1] }}
        </div>
      @endforeach
    </div>

    <div class="api-block reveal" style="transition-delay:.16s;">
      <div class="api-l">
        <div class="sec-tag" style="margin-bottom:10px;">Geliştirici API'si</div>
        <div class="api-title">Kendi Yazılımınızı<br>Bağlayın.</div>
        <p class="api-sub">
          RESTful API ve WebSocket desteğiyle mevcut sistemlerinizi dakikalar içinde entegre edin.
          200+ endpoint, tam Swagger dokümantasyonu.
        </p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
          <a href="/docs" class="btn-p">
            <i class="fa-solid fa-book" style="font-size:.72rem;"></i> API Dökümanları
          </a>
          <a href="#" class="btn-o">
            <i class="fa-brands fa-github" style="font-size:.72rem;"></i> GitHub SDK
          </a>
        </div>
      </div>

      <pre class="api-code"><span class="cm">POST</span> /api/v1/orders
<span class="ck">Authorization:</span> <span class="cs">Bearer {api_key}</span>
<span class="ck">Content-Type:</span> application/json

{
  <span class="ck">"table_id":</span>  <span class="cs">"T-07"</span>,
  <span class="ck">"items":</span>    [
    { <span class="cs">"id"</span>: 42, <span class="cs">"qty"</span>: 2 }
  ],
  <span class="ck">"status":</span>   <span class="cs">"confirmed"</span>,
  <span class="ck">"note":</span>     <span class="cs">"Az pişmiş"</span>
}

<span class="cm">→ 200 OK</span> { "order_id": "ORD-2847" }</pre>
    </div>
  </div>
</section>
-->
<!-- =====================
     TESTIMONIALS
===================== -->
<section id="testimonials" class="sec" style="background:var(--sf);">
  <div class="sec-in">
    <div class="reveal" style="text-align:center;">
      <span class="sec-tag">Müşteri Görüşleri</span>
      <h2 class="sec-h">{{ $formattedBusinessesCount }}{{ $businessSuffix }}+ İşletme<br><em>Konuşuyor.</em></h2>
    </div>
    <div class="testi-grid">
      @php $testis=[
        ['hl'=>true,'stars'=>5,'q'=>'"Kurulumdan üç ay sonra sipariş kayıplarımız sıfıra indi. Mutfak ile kasa artık aynı dili konuşuyor. Personel memnuniyeti de inanılmaz arttı."','name'=>'Mustafa Yılmaz','role'=>'Lezzet Lokantası, İstanbul','av'=>'M','grad'=>'var(--p),#A78BFA','metric'=>'+%34','metriclbl'=>'Sipariş Verimliliği'],
        ['hl'=>false,'stars'=>5,'q'=>'"Analitik paneli inanılmaz. Menüyü yeniden düzenledik, fiyatları optimize ettik. Aylık ciro %31 arttı."','name'=>'Selin Kaya','role'=>'Café Aura, Ankara','av'=>'S','grad'=>'#0ABBA3,#06B6D4','metric'=>'+₺48K','metriclbl'=>'Aylık Ek Ciro'],
        ['hl'=>false,'stars'=>5,'q'=>'"7 şubemizi tek panelden yönetiyorum. Stok uyarıları, personel raporları, anlık satış — hepsi elimde."','name'=>'Emre Demir','role'=>'Burger Zinciri, 7 Şube','av'=>'E','grad'=>'#F59E0B,#EF4444','metric'=>'7 Şube','metriclbl'=>'Tek Panelden'],
        ['hl'=>false,'stars'=>5,'q'=>'"Kurulum ekibi 6 saat içinde tüm sistemi hazırladı. QR menü özelliği müşterilerimize profesyonel deneyim sunuyor."','name'=>'Ahmet Çelik','role'=>'Fıstık Cafe, Bursa','av'=>'A','grad'=>'var(--p),var(--pl)','metric'=>'6 Saat','metriclbl'=>'Tam Kurulum'],
        ['hl'=>false,'stars'=>5,'q'=>'"Mutfak ekranı hayatımızı değiştirdi. Garsonlar artık mutfağa gidip sipariş sormak zorunda kalmıyor. Masa devir hızı %22 arttı."','name'=>'Fatma Arslan','role'=>'Deniz Restaurant, İzmir','av'=>'F','grad'=>'#10B981,#06B6D4','metric'=>'+%22','metriclbl'=>'Masa Devir Hızı'],
        ['hl'=>false,'stars'=>5,'q'=>'"Haftalık raporlar artık otomatik geliyor. Vergi dönemleri artık kabus değil, muhasebeci ile entegrasyon mükemmel çalışıyor."','name'=>'Kemal Doğan','role'=>'Kebapçı Doğan, Gaziantep','av'=>'K','grad'=>'#F43F5E,#FB923C','metric'=>'%100','metriclbl'=>'Otomatik Rapor'],
        ['hl'=>false,'stars'=>5,'q'=>'"Sadakat puanı sistemi sayesinde müşteri tekrar ziyaret oranımız %40 arttı. Doğum günü kampanyaları çok sevildi."','name'=>'Zeynep Aktaş','role'=>'Patisserie Zey, Eskişehir','av'=>'Z','grad'=>'var(--acc),var(--acc2)','metric'=>'+%40','metriclbl'=>'Tekrar Ziyaret'],
        ['hl'=>false,'stars'=>5,'q'=>'"Stok yönetimi özelliği tedarikçilerimizle entegre oldu. Malzeme israfını %28 azalttık, bu büyük bir maliyet avantajı."','name'=>'Hasan Öztürk','role'=>'Mezes Restaurant, Antalya','av'=>'H','grad'=>'#059669,#10B981','metric'=>'-₺12K','metriclbl'=>'Aylık İsraf Tasarrufu'],
        ['hl'=>false,'stars'=>5,'q'=>'"RezerVist mobil uygulaması ile tatildeyken bile işletmemi canlı takip edebiliyorum. Rahat tatil yapabiliyorum artık."','name'=>'Canan Şahin','role'=>'Şahin Kafeterya, Bodrum','av'=>'C','grad'=>'#7C3AED,#5B21B6','metric'=>'7/24','metriclbl'=>'Uzaktan Yönetim'],
      ]; @endphp
      @foreach($testis as $idx=>$t)
      <div class="tcard {{ $t['hl']?'hl':'' }} reveal" style="transition-delay:{{ $idx*0.06 }}s;">
        <div class="tstars">{{ str_repeat('★',$t['stars']) }}</div>
        <p class="tquote">{{ $t['q'] }}</p>
        <div class="tauthor">
          <div class="tav" style="background:linear-gradient(135deg,{{ $t['grad'] }});">{{ $t['av'] }}</div>
          <div><div class="tname">{{ $t['name'] }}</div><div class="trole">{{ $t['role'] }}</div></div>
        </div>
        <div class="tmetric"><div class="tm-val">{{ $t['metric'] }}</div><div class="tm-lbl">{{ $t['metriclbl'] }}</div></div>
      </div>
      @endforeach
    </div>
    <!-- Trust summary bar -->
    <div class="reveal" style="transition-delay:.3s;margin-top:56px;background:var(--bg2);border:1.5px solid var(--br);border-radius:var(--r);padding:32px 40px;display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center;">
      @foreach([[$averageRating,'Ortalama Puan','★★★★★'],[$formattedBusinessesCount.$businessSuffix.'+','Aktif Müşteri','Türkiye geneli'],[$customerSatisfaction,'Müşteri Memnuniyeti','Net Promoter Score'],[$supportResponseTime,'Ortalama Destek','Yanıt süresi']] as $s)
      <div><div style="font-family:var(--ff-h);font-size:1.8rem;font-weight:900;color:var(--p);letter-spacing:-0.04em;">{{ $s[0] }}</div><div style="font-size:0.85rem;font-weight:600;color:var(--tx);margin-top:3px;">{{ $s[1] }}</div><div style="font-size:0.72rem;color:var(--txm);margin-top:2px;">{{ $s[2] }}</div></div>
      @endforeach
    </div>
  </div>
</section>

<!-- =====================
     FAQ
===================== -->
<section class="sec" id="faq" style="position:relative;background:var(--bg);overflow:hidden;padding:120px 64px;">
  <div style="position:absolute;top:-200px;right:-200px;width:600px;height:600px;background:radial-gradient(circle,rgba(91,33,182,0.06) 0%,transparent 70%);border-radius:50%;pointer-events:none;"></div>
  
  <div class="sec-in" style="position:relative;z-index:2;max-width:1100px;">
    <div class="reveal" style="text-align:center;margin-bottom:60px;">
      <span class="sec-tag">SSS</span>
      <h2 class="sec-h" style="font-size:3.2rem;margin-top:16px;">Aklınızdaki<br><em>Sorular.</em></h2>
      <p class="sec-sub" style="margin:0 auto;max-width:500px;">Merak ettiklerinizi derledik. Bulamadığınız bir soru varsa, 7/24 destek ekibimiz yanınızda.</p>
    </div>
    
    <div class="faq-list reveal" style="transition-delay:.1s;display:flex;flex-direction:column;gap:12px;">
      @php $faqs=[
        ['İnternet kesilince ne olur?','RezerVist hibrit altyapısı sayesinde yerel ağ üzerinden çalışmaya devam eder. Tüm siparişler ve işlemler lokal olarak kaydedilir, internet bağlantısı yeniden kurulduğunda otomatik olarak bulutla senkronize edilir. Hiçbir veri kaybolmaz.'],
        ['Mevcut menümü nasıl aktarabilirim?','Excel veya CSV formatında hazırladığınız ürün listenizi sisteme toplu olarak import edebilirsiniz. Ekibimiz kurulum sürecinde menü aktarımı konusunda birebir destek sağlar. Fotoğraflı menüler için de QR menü şablonları mevcuttur.'],
        ['Kaç terminal kullanabilirim?','Lisans tipinize göre; Lite\'da 1, Pro\'da 3, Elite\'ta sınırsız terminal. Tüm terminaller birbirleriyle anlık senkronize çalışır. Ek terminal her zaman eklenebilir.'],
        ['Kurulum ne kadar sürer?','Ortalama 8 dakikada temel kurulum tamamlanır. Destek ekibimiz menü girişi, masa planı ve personel eğitimi dahil tam kurulumu genellikle aynı gün tamamlar.'],
        ['Ödeme sistemleriyle entegrasyon var mı?','Evet. İyzico, Stripe, PayTR, Garanti BBVA ve diğer önde gelen ödeme altyapılarıyla entegrasyon mevcuttur. NFC/temassız ödeme ve QR kod ödeme de desteklenmektedir.'],
        ['Verilerimi nasıl yedekliyor?','Tüm veriler her 15 dakikada bir buluta yedeklenir. Ek olarak yerel NVMe depolama üzerinde anlık snapshot tutulur. Son 90 günlük veri her zaman erişilebilir durumda.'],
        ['KVKK kapsamında verilerimi nasıl yönetiyorsunuz?','Türkiye Kişisel Verilerin Korunması Kanunu (KVKK) kapsamında tüm işlemler kayıt altında tutulur. Veri işleme sözleşmesi, silme/dışa aktarma talepleri ve denetim logları dahildir. GDPR uyumluluğu da sağlanmaktadır.'],
        ['Çok şube için özel fiyatlandırma var mı?','Elite plan ile sınırsız şube yönetimi standart dahildir. 10\'dan fazla şube için özel kurumsal paket sunarız. Satış ekibimizle iletişime geçerek özel teklif alabilirsiniz.'],
        ['Hangi donanımla uyumludur?','Windows 10+, macOS 12+, iPad (iPadOS 15+), Android 8+ tablet ve telefonlarda çalışır. Termal yazıcı, barkod okuyucu, kasa çekmecesi gibi çevre birimleriyle de entegre olur.'],
        ['AI özellikler nasıl çalışıyor?','Makine öğrenimi modelleri işletmenizin geçmiş verilerini analiz ederek satış öngörüsü, stok tahmini ve personel optimizasyonu yapar. Minimum 30 günlük veri gerektirir; veriler sisteminizde şifreli olarak saklanır, üçüncü taraflarla paylaşılmaz.'],
      ]; @endphp
      @foreach($faqs as $i=>$f)
      <div class="faq-item new-faq-item{{ $i===0?' open':'' }}" style="background:var(--sf);border:1px solid var(--br);border-radius:16px;overflow:hidden;transition:all 0.35s ease;">
        <div class="faq-q" onclick="toggleFaq(this)" style="padding:22px 30px;font-family:var(--ff-h);font-size:1.1rem;font-weight:700;color:var(--tx);cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:all 0.3s;">
          {{ $f[0] }}
          <div class="faq-icon" style="width:36px;height:36px;border-radius:50%;background:rgba(91,33,182,0.08);color:var(--p);display:flex;align-items:center;justify-content:center;transition:transform 0.4s cubic-bezier(0.4,0,0.2,1);flex-shrink:0;">
            <i class="fa-solid fa-plus"></i>
          </div>
        </div>
        <div class="faq-a" style="padding:0 30px;color:var(--txm);font-size:0.95rem;line-height:1.7;max-height:0;opacity:0;transition:all 0.4s cubic-bezier(0.4,0,0.2,1);overflow:hidden;">
           <div style="padding-bottom:24px;">{{ $f[1] }}</div>
        </div>
      </div>
      @endforeach
    </div>
    
    <div class="reveal" style="transition-delay:.2s;margin-top:40px;text-align:center;">
      <div style="display:inline-flex;align-items:center;gap:16px;background:var(--sf);border:1px solid var(--br);padding:14px 28px;border-radius:100px;">
        <div style="display:flex;align-items:center;">
           <div style="width:12px;height:12px;background:var(--grn);border-radius:50%;margin-right:8px;box-shadow:0 0 10px var(--grn);animation:pulseGrn 2s infinite;"></div>
           <span style="font-size:0.85rem;font-weight:600;color:var(--tx);font-family:var(--ff-b);">Destek Ekibi Çevrimiçi</span>
        </div>
        <div style="width:1px;height:24px;background:var(--br);"></div>
        <a href="{{ route('pages.contact') }}" style="font-size:0.85rem;font-weight:700;color:var(--p);text-decoration:none;display:flex;align-items:center;gap:6px;">Bize Ulaşın <i class="fa-solid fa-arrow-right" style="font-size:.7rem;"></i></a>
      </div>
    </div>
  </div>
</section>

<style>
.new-faq-item:hover { border-color:var(--p); box-shadow:0 12px 24px rgba(91,33,182,0.06); transform:translateY(-2px); }
.new-faq-item.open .faq-icon { transform:rotate(45deg); background:var(--p); color:white; }
.new-faq-item.open .faq-a { max-height:400px !important; opacity:1 !important; }
.new-faq-item.open .faq-q { color:var(--p); }
</style>

<!-- =====================
     CTA
===================== -->
<section class="sec" id="contact" style="padding:140px 64px;">
  <div class="sec-in" style="max-width:1200px;">
    <div class="cta-premium-box reveal" style="position:relative;background:linear-gradient(145deg, #1e0b3b 0%, #0a041a 100%);border-radius:32px;padding:80px 60px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);box-shadow:0 60px 120px rgba(10,4,26,0.6), inset 0 1px 0 rgba(255,255,255,0.15);">
      
      <!-- Background glows -->
      <div style="position:absolute;top:-20%;left:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(167,139,250,0.2) 0%,transparent 70%);border-radius:50%;pointer-events:none;"></div>
      <div style="position:absolute;bottom:-30%;right:-10%;width:700px;height:700px;background:radial-gradient(circle,rgba(6,182,212,0.15) 0%,transparent 70%);border-radius:50%;pointer-events:none;"></div>
      
      <div style="position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;text-align:center;">
        
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);padding:8px 16px;border-radius:100px;color:white;font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:24px;backdrop-filter:blur(10px);">
          <i class="fa-solid fa-bolt" style="color:#F59E0B;"></i> Hazır Mısınız?
        </div>
        
        <h2 style="font-family:var(--ff-h);font-size:clamp(2.8rem,5vw,4.5rem);font-weight:900;color:white;line-height:1.05;letter-spacing:-0.03em;margin-bottom:24px;">Hemen Bugün<br><span style="background:linear-gradient(135deg,#A78BFA 0%,#6EE7B7 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Dijitalleşin.</span></h2>
        
        <p style="font-size:1.1rem;color:rgba(255,255,255,0.6);max-width:600px;margin-bottom:40px;line-height:1.6;">14 gün boyunca tüm özellikleri ücretsiz deneyin. Kredi kartı gerekmez. Kurulum ekibimiz aynı gün sisteminizi hazır hale getirsin.</p>
        
        <!-- Premium Countdown -->
        <div style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:24px 32px;display:inline-flex;flex-direction:column;align-items:center;margin-bottom:48px;backdrop-filter:blur(12px);">
          <div style="font-size:0.7rem;font-weight:800;color:rgba(255,255,255,0.4);letter-spacing:0.15em;text-transform:uppercase;margin-bottom:16px;font-family:var(--ff-b);">Lansmana Özel İndirim Bitiyor</div>
          <div class="trial-countdown" style="display:flex;align-items:center;gap:16px;">
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:60px;height:60px;background:rgba(255,255,255,0.05);border-radius:12px;border:1px solid rgba(255,255,255,0.1);"><div style="font-family:var(--ff-h);font-size:1.5rem;font-weight:800;color:white;" id="ct-d">03</div><div style="font-size:0.6rem;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;margin-top:2px;">Gün</div></div>
            <div style="color:rgba(255,255,255,0.2);font-size:1.5rem;font-weight:800;margin-top:-16px;">:</div>
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:60px;height:60px;background:rgba(255,255,255,0.05);border-radius:12px;border:1px solid rgba(255,255,255,0.1);"><div style="font-family:var(--ff-h);font-size:1.5rem;font-weight:800;color:white;" id="ct-h">14</div><div style="font-size:0.6rem;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;margin-top:2px;">Saat</div></div>
            <div style="color:rgba(255,255,255,0.2);font-size:1.5rem;font-weight:800;margin-top:-16px;">:</div>
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:60px;height:60px;background:rgba(255,255,255,0.05);border-radius:12px;border:1px solid rgba(255,255,255,0.1);"><div style="font-family:var(--ff-h);font-size:1.5rem;font-weight:800;color:white;" id="ct-m">37</div><div style="font-size:0.6rem;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;margin-top:2px;">Dak</div></div>
            <div style="color:rgba(255,255,255,0.2);font-size:1.5rem;font-weight:800;margin-top:-16px;">:</div>
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:60px;height:60px;background:rgba(255,255,255,0.05);border-radius:12px;border:1px solid rgba(255,255,255,0.1);"><div style="font-family:var(--ff-h);font-size:1.5rem;font-weight:800;color:white;" id="ct-s">22</div><div style="font-size:0.6rem;color:rgba(255,255,255,0.4);text-transform:uppercase;font-weight:700;margin-top:2px;">Sn</div></div>
          </div>
        </div>
        
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:center;margin-bottom:32px;">
          <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:10px;padding:18px 36px;background:white;color:var(--p);font-weight:800;font-size:1rem;border-radius:100px;text-decoration:none;box-shadow:0 15px 35px rgba(255,255,255,0.15);transition:all 0.3s;position:relative;overflow:hidden;" class="premium-btn">
            <span style="position:relative;z-index:2;"><i class="fa-solid fa-rocket"></i> Ücretsiz Başlat</span>
          </a>
          <button onclick="document.getElementById('video-modal').classList.add('open')" style="display:inline-flex;align-items:center;gap:10px;padding:18px 36px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.2);color:white;font-weight:700;font-size:1rem;border-radius:100px;cursor:pointer;backdrop-filter:blur(10px);transition:all 0.3s;" class="premium-btn-o">
            <i class="fa-solid fa-play"></i> Demo İzle
          </button>
        </div>
        
        <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:center;">
          <div style="display:flex;align-items:center;gap:8px;font-size:0.75rem;color:rgba(255,255,255,0.45);font-weight:600;"><i class="fa-solid fa-shield-check"></i> PCI-DSS Uyumlu</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:0.75rem;color:rgba(255,255,255,0.45);font-weight:600;"><i class="fa-solid fa-headset"></i> 7/24 Destek</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:0.75rem;color:rgba(255,255,255,0.45);font-weight:600;"><i class="fa-solid fa-rotate-left"></i> Anında İptal</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:0.75rem;color:rgba(255,255,255,0.45);font-weight:600;"><i class="fa-solid fa-users"></i> {{ $formattedBusinessesCount }}{{ $businessSuffix }}+ İşletme</div>
        </div>
        
        <!-- Mini testimonial -->
        <div style="display:inline-flex;align-items:center;gap:14px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:16px 24px;margin-top:24px;">
          <div style="display:flex;margin-right:4px;">
            @foreach(['var(--p),#A78BFA','#0ABBA3,#06B6D4','#F59E0B,#EF4444'] as $g)
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,{{ $g }});border:2px solid rgba(255,255,255,0.2);margin-left:-8px;display:flex;align-items:center;justify-content:center;font-family:var(--ff-h);font-weight:900;font-size:0.75rem;color:white;">{{ ['M','S','E'][$loop->index] }}</div>
            @endforeach
          </div>
          <div style="text-align:left;">
            <div style="font-size:0.78rem;font-weight:700;color:white;">"İlk haftada fark ettik. Harika!"</div>
            <div style="font-size:0.65rem;color:rgba(255,255,255,0.45);margin-top:2px;">{{ $formattedBusinessesCount }}{{ $businessSuffix }}+ memnun işletme katıldı</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<style>
.premium-btn:hover { transform:translateY(-3px); box-shadow:0 20px 45px rgba(255,255,255,0.25); background:#f8f9fa; }
.premium-btn::after { content:''; position:absolute; top:0; left:-100%; width:50%; height:100%; background:linear-gradient(90deg,transparent,rgba(91,33,182,0.1),transparent); animation:shine 3s infinite 1s; }
.premium-btn-o:hover { background:rgba(255,255,255,0.15); border-color:rgba(255,255,255,0.3); transform:translateY(-3px); }
</style>

</div><!-- /main-page -->
</div><!-- /x-data -->

<script>
// ============================
// LIVE CLOCK
// ============================
function tick(){const el=document.getElementById('live-time');if(el)el.textContent=new Date().toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});}
tick();setInterval(tick,1000);

// ============================
// REVEAL ON SCROLL
// ============================
const revIO=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('on');});},{threshold:0.05});
document.querySelectorAll('.reveal').forEach(el=>revIO.observe(el));

// ============================
// FAQ TOGGLE
// ============================
function toggleFaq(el){const item=el.parentElement;const isOpen=item.classList.contains('open');document.querySelectorAll('.faq-item').forEach(i=>i.classList.remove('open'));if(!isOpen)item.classList.add('open');}

// ============================
// DEMO: MASA TOGGLE
// ============================
const statusCycle={empty:'full',full:'busy',busy:'reserve',reserve:'cleaning',cleaning:'empty'};
const statusLabels={empty:'Boş',full:'Dolu',busy:'Meşgul',reserve:'Rezerv',cleaning:'Temiz'};
const statusNotifs={
  full:{t:'Masa Doldu',s:'Sipariş alındı, mutfağa iletildi.',ic:'fa-utensils',cls:'notif-ic-p'},
  busy:{t:'Masa Meşgul',s:'Hesap talep edildi.',ic:'fa-receipt',cls:'notif-ic-g'},
  reserve:{t:'Rezervasyon Eklendi',s:'Masaya rezervasyon oluşturuldu.',ic:'fa-calendar-check',cls:'notif-ic-g'},
  cleaning:{t:'Temizlik Modu',s:'Masa temizleniyor, yakında hazır.',ic:'fa-broom',cls:'notif-ic-y'},
  empty:{t:'Masa Boşaldı',s:'Masa müsait duruma geçti.',ic:'fa-chair',cls:'notif-ic-p'},
};
let notifTimer=null;
function toggleMasa(el){
  const cur=el.dataset.status;const next=statusCycle[cur];
  el.classList.remove(cur);el.classList.add(next);
  el.dataset.status=next;
  el.querySelector('.mc-lbl').textContent=statusLabels[next];
  updateCounts();showNotif(statusNotifs[next],el);
}
function updateCounts(){
  const cells=document.querySelectorAll('.masa-cell');
  const c={full:0,busy:0,reserve:0,cleaning:0,empty:0};
  cells.forEach(x=>c[x.dataset.status]=(c[x.dataset.status]||0)+1);
  document.getElementById('full-count').textContent=c.full||0;
  document.getElementById('busy-count').textContent=c.busy||0;
  document.getElementById('res-count').textContent=c.reserve||0;
  const cleanEl=document.getElementById('clean-count');if(cleanEl)cleanEl.textContent=c.cleaning||0;
  document.getElementById('empty-count').textContent=c.empty||0;
}
function showNotif(n,tableEl){
  const container=document.getElementById('demo-notif');
  const div=document.createElement('div');div.className='notif-card';
  div.innerHTML=`<div class="notif-ic ${n.cls}"><i class="fa-solid ${n.ic}"></i></div><div><div class="notif-t">${n.t}</div><div class="notif-s">${n.s}</div><div class="notif-prog"></div></div>`;
  container.appendChild(div);
  setTimeout(()=>{div.style.opacity='0';div.style.transform='translateX(120px)';div.style.transition='all 0.4s';setTimeout(()=>div.remove(),400);},3200);
}

// Demo filter
function filterMasa(status,btn){
  document.querySelectorAll('.mgh-fb').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.masa-cell').forEach(c=>{
    c.style.opacity=status==='all'||c.dataset.status===status?'1':'0.2';
    c.style.pointerEvents=status==='all'||c.dataset.status===status?'auto':'none';
  });
}

// ============================
// PRICING TOGGLE
// ============================
let isYearly=false;
const prices={lite:[990,792],pro:[1990,1592],elite:[3490,2792]};
function toggleBilling(){
  isYearly=!isYearly;
  const sw=document.getElementById('billing-toggle');
  sw.classList.toggle('yearly',isYearly);
  document.getElementById('price-lite').textContent=prices.lite[isYearly?1:0].toLocaleString('tr-TR');
  document.getElementById('price-pro').textContent=prices.pro[isYearly?1:0].toLocaleString('tr-TR');
  document.getElementById('price-elite').textContent=prices.elite[isYearly?1:0].toLocaleString('tr-TR');
}

// ============================
// ROI CALCULATOR
// ============================
function calcROI(){
  const rev=parseInt(document.getElementById('roi-rev').value)||150000;
  const tables=parseInt(document.getElementById('roi-tables').value)||56;
  const turn=parseInt(document.getElementById('roi-turn').value)||20;
  const staff=parseInt(document.getElementById('roi-staff').value)||2;
  
  const efficiency=Math.min(45,Math.round(15+tables*0.16071));
  const waste=Math.round(rev*0.055);
  const extraIncome=Math.round((rev*(efficiency/100))+waste+(staff*50)+((150-turn)*62.046));
  const payback=(57657/Math.max(1,extraIncome)).toFixed(1);
  
  document.getElementById('roi-income').textContent='₺'+extraIncome.toLocaleString('tr-TR');
  document.getElementById('roi-efficiency').textContent='+%'+efficiency;
  document.getElementById('roi-waste').textContent='-₺'+waste.toLocaleString('tr-TR');
  document.getElementById('roi-payback').textContent=payback+' ay';
  document.getElementById('roi-annual').textContent='₺'+(extraIncome*12).toLocaleString('tr-TR');
}
calcROI();

// ============================
// COUNTDOWN TIMER
// ============================
(function(){
  let end=new Date();end.setDate(end.getDate()+3);end.setHours(end.getHours()+14);end.setMinutes(end.getMinutes()+37);end.setSeconds(end.getSeconds()+22);
  function updateCountdown(){
    const diff=end-new Date();if(diff<=0)return;
    const d=Math.floor(diff/86400000);const h=Math.floor((diff%86400000)/3600000);
    const m=Math.floor((diff%3600000)/60000);const s=Math.floor((diff%60000)/1000);
    ['d','h','m','s'].forEach((k,i)=>{const el=document.getElementById('ct-'+k);if(el)el.textContent=String([d,h,m,s][i]).padStart(2,'0');});
  }
  updateCountdown();setInterval(updateCountdown,1000);
})();

// ============================
// KVKK
// ============================
if(!localStorage.getItem('kvkk_ok')){
  setTimeout(()=>document.getElementById('kvkk-banner').classList.add('show'),1800);
}
document.querySelectorAll('.kvkk-btn-ok,.kvkk-btn-no').forEach(b=>{
  b.addEventListener('click',()=>{localStorage.setItem('kvkk_ok','1');document.getElementById('kvkk-banner').classList.remove('show');});
});

// ============================
// INTRO ALPINE
// ============================
function posIntro(){
  return{
    scene:0,done:false,progress:0,total:8,timer:null,progTimer:null,scrolled:false,
    durations:[1800,2200,2600,2800,2800,2600,2600,3600],
    init(){
      this.runScene(0);
      window.addEventListener('scroll', () => {
        this.scrolled = window.scrollY > 50;
      });
    },
    runScene(idx){
      if(idx>=this.total){this.finish();return;}
      this.scene=idx;const dur=this.durations[idx];
      const pS=(idx/this.total)*100;const pE=((idx+1)/this.total)*100;
      clearInterval(this.progTimer);
      const steps=60;const st=dur/steps;const ss=(pE-pS)/steps;
      let s=0;this.progress=pS;
      this.progTimer=setInterval(()=>{s++;this.progress=pS+ss*s;if(s>=steps)clearInterval(this.progTimer);},st);
      clearTimeout(this.timer);this.timer=setTimeout(()=>this.runScene(idx+1),dur);
    },
    skip(){clearTimeout(this.timer);clearInterval(this.progTimer);this.progress=100;setTimeout(()=>this.finish(),300);},
    finish(){clearTimeout(this.timer);clearInterval(this.progTimer);this.done=true;window.scrollTo(0,0);}
  }
}
</script>
@endsection