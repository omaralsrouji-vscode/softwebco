<?php
require_once __DIR__ . '/WebDesign.php';
$design = new WebDesign();
$programs = require __DIR__ . '/programs/catalog.php';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $design->GenerateHeadTag1(); ?>
<style>
  body{background:#f7fafb;color:#0b202d}
  .programs-hero{padding:9rem 1.25rem 4rem;background:linear-gradient(145deg,#071c28 0%,#0b2a39 62%,#0f3f47 100%);position:relative;overflow:hidden}
  .programs-hero:after{content:"";position:absolute;inset:0;background:url('<?= e($design->PublicUrl('assets/images/brand-pattern-teal.png')) ?>') center/720px repeat;opacity:.055;pointer-events:none}
  .programs-shell{max-width:80rem;margin:auto;position:relative;z-index:1}
  .eyebrow{display:inline-flex;align-items:center;gap:.5rem;color:#73e5d5;font-size:.78rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase}
  .eyebrow:before{content:"";width:.5rem;height:.5rem;border-radius:50%;background:#20bca9;box-shadow:0 0 0 .35rem rgba(32,188,169,.12)}
  .programs-hero h1{font-size:clamp(2.4rem,6vw,5rem);line-height:.96;color:white;font-weight:800;letter-spacing:-.045em;max-width:14ch;margin-top:1.35rem}
  .programs-hero p{max-width:46rem;margin-top:1.25rem;color:#c5d6dc;font-size:1.05rem;line-height:1.8}
  .programs-grid{max-width:80rem;margin:auto;padding:4rem 1.25rem 6rem;display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:1.4rem}
  .program-card{background:white;border:1px solid #e6eef0;border-radius:1.5rem;overflow:hidden;box-shadow:0 14px 40px rgba(11,32,45,.06);transition:.3s ease;display:flex;flex-direction:column}
  .program-card:hover{transform:translateY(-6px);box-shadow:0 24px 60px rgba(11,32,45,.12);border-color:#cfe9e5}
  .program-media{height:14rem;overflow:hidden;background:#dfe9ec;position:relative}
  .program-media img{width:100%;height:100%;object-fit:cover;object-position:top;transition:transform .45s ease}
  .program-card:hover .program-media img{transform:scale(1.045)}
  .program-media:after{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(5,25,34,.64),transparent 62%)}
  .program-badge{position:absolute;left:1rem;bottom:1rem;z-index:2;background:#20bca9;color:#fff;border-radius:999px;padding:.45rem .8rem;font-size:.7rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
  .program-body{padding:1.4rem;display:flex;flex-direction:column;flex:1}
  .program-body h2{font-size:1.25rem;font-weight:800;letter-spacing:-.02em;color:#102c39}
  .program-body p{margin-top:.65rem;color:#60727a;line-height:1.65;font-size:.9rem}
  .tag-row{display:flex;flex-wrap:wrap;gap:.45rem;margin-top:1rem}.tag{font-size:.7rem;background:#eff8f7;color:#197f73;border:1px solid #d9efeb;border-radius:999px;padding:.35rem .65rem;font-weight:700}
  .program-actions{display:flex;gap:.65rem;flex-wrap:wrap;margin-top:auto;padding-top:1.3rem}.btn-primary,.btn-secondary{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;border-radius:.8rem;padding:.7rem 1rem;font-size:.82rem;font-weight:800;transition:.25s}
  .btn-primary{background:#0b202d;color:white}.btn-primary:hover{background:#143849}.btn-secondary{background:#e9f8f6;color:#118a7d}.btn-secondary:hover{background:#d9f1ed}
  @media(min-width:700px){.programs-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.programs-hero{padding-inline:2rem}.programs-grid{padding-left:2rem;padding-right:2rem}}
  @media(min-width:1080px){.programs-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
</style>
</head>
<body>
<div class="cursor-follower"></div><div class="loading-screen"><div class="loading-text">Loading..</div></div>
<?php $design->ShowNavbar1(); ?>
<main>
<section class="programs-hero">
  <div class="programs-shell">
    <span class="eyebrow">Softwebco Programs</span>
    <h1>Systems built for real work.</h1>
    <p>Explore Softwebco applications, management systems and web products. Each item has a dedicated details page, while the Portfolio program includes its own live interactive preview.</p>
  </div>
</section>
<section class="programs-grid">
<?php foreach ($programs as $slug => $program): ?>
  <article class="program-card">
    <div class="program-media">
      <img src="<?= e($design->PublicUrl($program['image'])) ?>" alt="<?= e($program['title']) ?>">
      <span class="program-badge"><?= e($program['category']) ?></span>
    </div>
    <div class="program-body">
      <h2><?= e($program['title']) ?></h2>
      <p><?= e($program['description']) ?></p>
      <div class="tag-row"><?php foreach ($program['tags'] as $tag): ?><span class="tag"><?= e($tag) ?></span><?php endforeach; ?></div>
      <div class="program-actions">
        <a class="btn-primary" href="<?= e($design->PublicUrl($program['route'])) ?>">Details <i class="fas fa-arrow-right"></i></a>
        <?php if (!empty($program['live_display_url'])): ?><a class="btn-secondary" href="<?= e($design->PublicUrl($program['live_display_url'])) ?>">Live Display <i class="fas fa-desktop"></i></a><?php endif; ?>
      </div>
    </div>
  </article>
<?php endforeach; ?>
</section>
</main>
<?php $design->Showfooter(); ?>
<script src="<?= e($design->PublicUrl('assets/js/site.js')) ?>?v=<?= rawurlencode(trim((string)@file_get_contents(__DIR__ . '/VERSION'))) ?>"></script>
</body></html>
