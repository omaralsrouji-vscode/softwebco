<?php
require_once __DIR__ . '/WebDesign.php';
$design = new WebDesign();
$portfolio = require __DIR__ . '/portfolio/catalog.php';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $design->GenerateHeadTag1(); ?>
<style>
body{background:#f6f9fa;color:#0b202d}.pf-hero{padding:9rem 1.25rem 5rem;background:#071b26;position:relative;overflow:hidden}.pf-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 80% 20%,rgba(32,188,169,.25),transparent 32%),url('assets/images/brand-pattern-navy.png') center/620px repeat;opacity:.85}.pf-shell{max-width:80rem;margin:auto;position:relative;z-index:1}.pf-kicker{display:inline-flex;gap:.55rem;align-items:center;color:#6de4d3;font-weight:800;font-size:.76rem;letter-spacing:.14em;text-transform:uppercase}.pf-kicker:before{content:"";width:.5rem;height:.5rem;border-radius:50%;background:#20bca9}.pf-hero h1{color:white;font-weight:850;font-size:clamp(2.6rem,6vw,5.2rem);letter-spacing:-.05em;line-height:.98;max-width:13ch;margin-top:1.25rem}.pf-hero p{color:#c1d4da;line-height:1.8;max-width:48rem;margin-top:1.2rem}.pf-toolbar{max-width:80rem;margin:-1.5rem auto 0;padding:0 1.25rem;position:relative;z-index:2}.pf-toolbar-inner{background:white;border:1px solid #e1ecef;border-radius:1.1rem;box-shadow:0 16px 45px rgba(11,32,45,.09);padding:1rem 1.15rem;display:flex;flex-wrap:wrap;gap:.55rem;align-items:center}.filter{border:1px solid #d9e8eb;color:#50656e;border-radius:999px;padding:.5rem .85rem;font-size:.76rem;font-weight:750}.filter.active{background:#0b202d;color:white;border-color:#0b202d}.pf-grid{max-width:80rem;margin:auto;padding:3.4rem 1.25rem 6rem;display:grid;grid-template-columns:1fr;gap:1.35rem}.pf-card{background:white;border:1px solid #e0eaed;border-radius:1.45rem;overflow:hidden;box-shadow:0 12px 34px rgba(12,35,47,.055);transition:.3s}.pf-card:hover{transform:translateY(-5px);box-shadow:0 22px 55px rgba(12,35,47,.11)}.pf-media{height:16rem;background:#0d2632;position:relative;overflow:hidden}.pf-media img{width:100%;height:100%;object-fit:cover;object-position:top;transition:transform .45s}.pf-card:hover .pf-media img{transform:scale(1.04)}.pf-media:after{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(5,21,29,.58),transparent 62%)}.live-pill{position:absolute;z-index:2;top:1rem;right:1rem;background:rgba(255,255,255,.94);color:#0b202d;border-radius:999px;padding:.4rem .65rem;font-size:.68rem;font-weight:850;display:flex;align-items:center;gap:.4rem}.live-pill i{color:#13a790;font-size:.55rem}.pf-body{padding:1.35rem}.pf-category{font-size:.7rem;text-transform:uppercase;letter-spacing:.11em;color:#1a9b8c;font-weight:850}.pf-body h2{font-size:1.3rem;font-weight:850;letter-spacing:-.025em;margin-top:.35rem}.pf-body p{color:#657880;line-height:1.65;font-size:.88rem;margin-top:.55rem}.tags{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:1rem}.tag{font-size:.68rem;border-radius:999px;background:#f0f7f8;color:#526a72;padding:.35rem .6rem;font-weight:700}.pf-actions{display:flex;gap:.6rem;align-items:center;margin-top:1.2rem}.pf-live{display:inline-flex;gap:.45rem;align-items:center;background:#0b202d;color:white;border-radius:.75rem;padding:.68rem .9rem;font-size:.78rem;font-weight:800}.pf-live:hover{background:#164151}.pf-note{margin-left:auto;font-size:.7rem;color:#91a0a6;font-weight:700}.pf-footnote{max-width:80rem;margin:-3rem auto 6rem;padding:0 1.25rem}.pf-footnote>div{padding:1.5rem;border-radius:1.25rem;background:#e9f8f6;border:1px solid #ceeee9;color:#23665f;line-height:1.65;font-size:.9rem}
@media(min-width:720px){.pf-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.pf-hero,.pf-grid,.pf-toolbar,.pf-footnote{padding-left:2rem;padding-right:2rem}}@media(min-width:1100px){.pf-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
</style>
</head><body>
<div class="cursor-follower"></div><div class="loading-screen"><div class="loading-text">Loading..</div></div>
<?php $design->ShowNavbar1(); ?>
<main>
<section class="pf-hero"><div class="pf-shell"><span class="pf-kicker">Portfolio</span><h1>See the work, not just the name.</h1><p>Softwebco’s portfolio brings systems, applications and websites into one place. Use Live Display to open available projects inside a browser-style viewer.</p></div></section>
<div class="pf-toolbar"><div class="pf-toolbar-inner"><button class="filter active" data-filter="all">All work</button><button class="filter" data-filter="Business System">Business systems</button><button class="filter" data-filter="Management System">Management</button><button class="filter" data-filter="Web Application">Web apps</button><button class="filter" data-filter="Personal Portfolio">Portfolio</button></div></div>
<section class="pf-grid" id="portfolioGrid">
<?php foreach ($portfolio as $project): ?>
<article class="pf-card" data-category="<?= e($project['category']) ?>">
  <div class="pf-media"><img src="<?= e($project['image']) ?>" alt="<?= e($project['title']) ?>"><?php if (!empty($project['live'])): ?><span class="live-pill"><i class="fas fa-circle"></i> LIVE DISPLAY</span><?php endif; ?></div>
  <div class="pf-body"><div class="pf-category"><?= e($project['category']) ?></div><h2><?= e($project['title']) ?></h2><p><?= e($project['description']) ?></p><div class="tags"><?php foreach($project['tags'] as $tag): ?><span class="tag"><?= e($tag) ?></span><?php endforeach; ?></div><div class="pf-actions"><?php if (!empty($project['live'])): ?><a class="pf-live" href="<?= e($project['details_url']) ?>"><i class="fas fa-desktop"></i> Live Display</a><span class="pf-note">Responsive preview</span><?php else: ?><a class="pf-live" href="<?= e($project['details_url']) ?>"><i class="fas fa-arrow-right"></i> View Details</a><?php endif; ?></div></div>
</article>
<?php endforeach; ?>
</section>
<div class="pf-footnote"><div><strong>Portfolio source:</strong> the systems listed here are synchronized from the same Programs data file. That prevents Programs and Portfolio from drifting apart when new products are added.</div></div>
</main>
<?php $design->Showfooter(); ?>
<script src="assets/js/site.js?v=<?= rawurlencode(trim((string)@file_get_contents(__DIR__ . '/VERSION'))) ?>"></script>
<script>
document.querySelectorAll('[data-filter]').forEach(btn=>btn.addEventListener('click',()=>{document.querySelectorAll('[data-filter]').forEach(b=>b.classList.remove('active'));btn.classList.add('active');const value=btn.dataset.filter;document.querySelectorAll('.pf-card').forEach(card=>card.style.display=value==='all'||card.dataset.category===value?'':'none');}));
</script></body></html>
