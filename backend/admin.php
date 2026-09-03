<?php
require_once '../config.php';
swc_require_admin();
$current_user = swc_current_admin_user($conn);
require_once '../WebDesign.php';
$design = new WebDesign();

$stats = ['users'=>0,'active'=>0,'blogs'=>0,'this_month'=>0,'categories'=>0];
$recentUsers = [];
$recentPosts = [];
try {
    $r = $conn->query("SELECT COUNT(*) c, SUM(account_status='active' AND is_locked=0) a FROM users");
    if ($r) { $x=$r->fetch_assoc(); $stats['users']=(int)$x['c']; $stats['active']=(int)$x['a']; }
    $r = $conn->query("SELECT COUNT(*) c, SUM(post_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')) m FROM blog_posts");
    if ($r) { $x=$r->fetch_assoc(); $stats['blogs']=(int)$x['c']; $stats['this_month']=(int)$x['m']; }
    $r = $conn->query("SELECT COUNT(*) c FROM categories");
    if ($r) $stats['categories']=(int)$r->fetch_assoc()['c'];
    $r = $conn->query("SELECT id,username,email,display_name,profile_image,account_status,created_at FROM users ORDER BY created_at DESC LIMIT 5");
    if ($r) $recentUsers=$r->fetch_all(MYSQLI_ASSOC);
    $r = $conn->query("SELECT p.id,p.title,p.excerpt,p.image_url,p.post_date,p.created_at,c.name category_name FROM blog_posts p LEFT JOIN categories c ON c.id=p.category_id ORDER BY COALESCE(p.post_date,DATE(p.created_at)) DESC,p.id DESC LIMIT 5");
    if ($r) $recentPosts=$r->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $e) { error_log('Dashboard: '.$e->getMessage()); }
$displayName = trim((string)($current_user['display_name'] ?? '')) ?: 'Admin';
?>
<!doctype html>
<html lang="en">
<head><?php $design->GenerateHeadTag2(); ?></head>
<body>
<?php $design->ShowNavbar2('admin', $current_user); ?>

<section class="swc-welcome-card">
    <div class="swc-welcome-copy">
        <span><i class="bi bi-stars"></i> Content workspace</span>
        <h2>Welcome back, <?php echo htmlspecialchars($displayName); ?>.</h2>
        <p>Everything you need is in four places: users, blogs, categories and this dashboard. No extra modules, no clutter.</p>
    </div>
    <div class="swc-welcome-actions">
        <a class="swc-btn swc-btn-primary" href="bloglist.php?view=new"><i class="bi bi-plus-lg"></i> New blog</a>
        <a class="swc-btn swc-btn-ghost" href="categories.php"><i class="bi bi-tags"></i> Categories</a>
    </div>
</section>

<div class="swc-page-grid swc-page-grid-4 swc-section-gap">
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Blog posts</span><span class="swc-stat-icon"><i class="bi bi-journal-richtext"></i></span></div><strong class="swc-stat-value"><?php echo $stats['blogs']; ?></strong><div class="swc-stat-foot"><?php echo $stats['this_month']; ?> dated this month</div></article>
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Categories</span><span class="swc-stat-icon"><i class="bi bi-tags-fill"></i></span></div><strong class="swc-stat-value"><?php echo $stats['categories']; ?></strong><div class="swc-stat-foot">Public blog filters</div></article>
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Users</span><span class="swc-stat-icon"><i class="bi bi-people-fill"></i></span></div><strong class="swc-stat-value"><?php echo $stats['users']; ?></strong><div class="swc-stat-foot"><?php echo $stats['active']; ?> active administrator<?php echo $stats['active']===1?'':'s'; ?></div></article>
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Website</span><span class="swc-stat-icon"><i class="bi bi-globe2"></i></span></div><strong class="swc-stat-value" style="font-size:22px">Live</strong><div class="swc-stat-foot">Open the public site anytime</div></article>
</div>

<div class="swc-page-grid swc-page-grid-2 swc-section-gap">
    <section class="swc-panel">
        <div class="swc-panel-head"><div class="swc-panel-title"><h2>Recent blogs</h2><p>Your latest public articles.</p></div><a class="swc-btn swc-btn-ghost swc-btn-sm" href="bloglist.php">View all <i class="bi bi-arrow-right"></i></a></div>
        <div class="swc-panel-body" style="padding-top:6px;padding-bottom:8px">
            <?php if (!$recentPosts): ?>
                <div class="swc-empty"><div class="swc-empty-icon"><i class="bi bi-journal"></i></div><h3>No blogs yet</h3><p>Create your first article and it will appear here.</p><a class="swc-btn swc-btn-primary swc-btn-sm" href="bloglist.php?view=new"><i class="bi bi-plus-lg"></i> New blog</a></div>
            <?php else: foreach ($recentPosts as $p): ?>
                <a class="swc-list-row" href="bloglist.php?view=edit&id=<?php echo (int)$p['id']; ?>">
                    <img class="swc-thumb" src="<?php echo htmlspecialchars(swc_blog_admin_image_url($p['image_url'])); ?>" alt="">
                    <span class="swc-list-main"><strong><?php echo htmlspecialchars($p['title']); ?></strong><small><?php echo htmlspecialchars($p['category_name'] ?: 'Uncategorized'); ?> · <?php echo htmlspecialchars(date('M j, Y', strtotime($p['post_date'] ?: $p['created_at']))); ?></small></span>
                    <i class="bi bi-chevron-right swc-list-arrow"></i>
                </a>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <section class="swc-panel">
        <div class="swc-panel-head"><div class="swc-panel-title"><h2>Admin users</h2><p>People who can sign in to this workspace.</p></div><a class="swc-btn swc-btn-ghost swc-btn-sm" href="users.php">Manage <i class="bi bi-arrow-right"></i></a></div>
        <div class="swc-panel-body" style="padding-top:6px;padding-bottom:8px">
            <?php if (!$recentUsers): ?>
                <div class="swc-empty"><div class="swc-empty-icon"><i class="bi bi-people"></i></div><h3>No users found</h3></div>
            <?php else: foreach ($recentUsers as $u): $nm=trim((string)$u['display_name']) ?: $u['username']; ?>
                <div class="swc-list-row">
                    <span class="swc-person-photo"><img src="<?php echo htmlspecialchars(swc_admin_profile_url($u['profile_image'])); ?>" alt=""></span>
                    <span class="swc-list-main"><strong><?php echo htmlspecialchars($nm); ?></strong><small><?php echo htmlspecialchars($u['email']); ?></small></span>
                    <span class="swc-status <?php echo htmlspecialchars($u['account_status']); ?>"><?php echo htmlspecialchars($u['account_status']); ?></span>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </section>
</div>

<?php $design->CloseAdminLayout(); ?>
</body>
</html>
