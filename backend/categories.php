<?php
require_once '../config.php';
swc_require_admin();
$current_user = swc_current_admin_user($conn);
require_once '../WebDesign.php';
$design = new WebDesign();

function swc_category_slug(string $value): string
{
    $value = trim(strtolower($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    return trim($value, '-');
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'create') {
            $name = trim((string)($_POST['name'] ?? ''));
            $slug = swc_category_slug((string)($_POST['slug'] ?? ''));
            if ($slug === '') $slug = swc_category_slug($name);
            if ($name === '' || strlen($name) > 50) throw new Exception('Category name is required and must be 50 characters or less.');
            if ($slug === '' || strlen($slug) > 50) throw new Exception('Enter a valid category slug.');
            if ($slug === 'all') throw new Exception('The slug “all” is reserved for the protected All Blogs filter.');
            $check = $conn->prepare('SELECT id FROM categories WHERE slug = ? LIMIT 1');
            $check->bind_param('s', $slug); $check->execute();
            if ($check->get_result()->num_rows) throw new Exception('That category slug already exists.');
            $check->close();
            $stmt = $conn->prepare('INSERT INTO categories(name, slug) VALUES(?, ?)');
            $stmt->bind_param('ss', $name, $slug); $stmt->execute(); $stmt->close();
            $_SESSION['category_notice'] = ['success', 'Category created successfully.'];
            header('Location: categories.php'); exit();
        }

        if ($action === 'update') {
            $id = (int)($_POST['category_id'] ?? 0);
            if ($id <= 0) throw new Exception('Invalid category.');
            $protectStmt = $conn->prepare('SELECT slug FROM categories WHERE id = ? LIMIT 1');
            $protectStmt->bind_param('i', $id); $protectStmt->execute(); $currentCategory = $protectStmt->get_result()->fetch_assoc(); $protectStmt->close();
            if (!$currentCategory) throw new Exception('Category not found.');
            if ($id === 1 || (string)$currentCategory['slug'] === 'all') throw new Exception('All Blogs is protected because the public blog filter depends on it.');
            $name = trim((string)($_POST['name'] ?? ''));
            $slug = swc_category_slug((string)($_POST['slug'] ?? ''));
            if ($slug === '') $slug = swc_category_slug($name);
            if ($name === '' || strlen($name) > 50) throw new Exception('Category name is required and must be 50 characters or less.');
            if ($slug === '' || strlen($slug) > 50 || $slug === 'all') throw new Exception('Enter a valid unique category slug.');
            $check = $conn->prepare('SELECT id FROM categories WHERE slug = ? AND id <> ? LIMIT 1');
            $check->bind_param('si', $slug, $id); $check->execute();
            if ($check->get_result()->num_rows) throw new Exception('That category slug already exists.');
            $check->close();
            $stmt = $conn->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
            $stmt->bind_param('ssi', $name, $slug, $id); $stmt->execute(); $stmt->close();
            $_SESSION['category_notice'] = ['success', 'Category updated successfully.'];
            header('Location: categories.php'); exit();
        }

        if ($action === 'delete') {
            $id = (int)($_POST['category_id'] ?? 0);
            if ($id <= 0) throw new Exception('Invalid category.');
            $stmt = $conn->prepare('SELECT name,slug FROM categories WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $id); $stmt->execute(); $category = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if (!$category) throw new Exception('Category not found.');
            if ($id === 1 || (string)$category['slug'] === 'all') throw new Exception('All Blogs cannot be deleted because the public blog filter depends on it.');
            $countStmt = $conn->prepare('SELECT COUNT(*) c FROM blog_posts WHERE category_id = ?');
            $countStmt->bind_param('i', $id); $countStmt->execute(); $affected = (int)$countStmt->get_result()->fetch_assoc()['c']; $countStmt->close();
            // blog_posts.category_id uses ON DELETE SET NULL, keeping articles safe.
            $stmt = $conn->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->bind_param('i', $id); $stmt->execute(); $stmt->close();
            $suffix = $affected ? " {$affected} blog post" . ($affected === 1 ? ' is' : 's are') . ' now uncategorized.' : '';
            $_SESSION['category_notice'] = ['success', 'Category deleted.' . $suffix];
            header('Location: categories.php'); exit();
        }
    } catch (Throwable $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

if (isset($_SESSION['category_notice']) && is_array($_SESSION['category_notice'])) {
    [$messageType, $message] = $_SESSION['category_notice'];
    unset($_SESSION['category_notice']);
}

$editId = max(0, (int)($_GET['edit'] ?? 0));
$editCategory = null;
if ($editId > 0) {
    try {
        $stmt = $conn->prepare('SELECT id,name,slug FROM categories WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $editId); $stmt->execute(); $editCategory = $stmt->get_result()->fetch_assoc(); $stmt->close();
    } catch (Throwable $e) { error_log('Category edit load: '.$e->getMessage()); }
}

$categories = [];
$totalPosts = 0;
try {
    $result = $conn->query('SELECT c.id,c.name,c.slug,c.created_at,COUNT(p.id) post_count FROM categories c LEFT JOIN blog_posts p ON p.category_id=c.id GROUP BY c.id,c.name,c.slug,c.created_at ORDER BY c.id ASC');
    if ($result) $categories = $result->fetch_all(MYSQLI_ASSOC);
    $result = $conn->query('SELECT COUNT(*) c FROM blog_posts');
    if ($result) $totalPosts = (int)$result->fetch_assoc()['c'];
} catch (Throwable $e) { error_log('Categories: '.$e->getMessage()); }
?>
<!doctype html>
<html lang="en">
<head><?php $design->GenerateHeadTag2(); ?></head>
<body>
<?php $design->ShowNavbar2('categories', $current_user); ?>

<?php if ($message): ?>
<div class="swc-alert <?php echo $messageType === 'success' ? 'swc-alert-success' : 'swc-alert-error'; ?>">
    <i class="bi <?php echo $messageType === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'; ?>"></i>
    <span><?php echo htmlspecialchars($message); ?></span>
</div>
<?php endif; ?>

<div class="swc-page-grid swc-page-grid-3" style="margin-bottom:18px">
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Categories</span><span class="swc-stat-icon"><i class="bi bi-tags-fill"></i></span></div><strong class="swc-stat-value"><?php echo count($categories); ?></strong><div class="swc-stat-foot">Public blog filters</div></article>
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Blog posts</span><span class="swc-stat-icon"><i class="bi bi-journal-richtext"></i></span></div><strong class="swc-stat-value"><?php echo $totalPosts; ?></strong><div class="swc-stat-foot">Across every category</div></article>
    <article class="swc-stat-card"><div class="swc-stat-head"><span class="swc-stat-label">Protected filter</span><span class="swc-stat-icon"><i class="bi bi-shield-check"></i></span></div><strong class="swc-stat-value" style="font-size:22px">All Blogs</strong><div class="swc-stat-foot">Cannot be renamed or deleted</div></article>
</div>

<div class="swc-category-layout">
    <section class="swc-panel">
        <div class="swc-panel-head">
            <div class="swc-panel-title"><h2>Blog categories</h2><p>These categories appear in the public Blogs filter.</p></div>
            <a class="swc-btn swc-btn-ghost swc-btn-sm" href="../blogs.php" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Preview blogs</a>
        </div>
        <div class="swc-category-tip"><i class="bi bi-info-circle-fill"></i><span>Deleting a category never deletes its articles. Any linked posts become uncategorized automatically.</span></div>
        <?php if (!$categories): ?>
            <div class="swc-empty"><div class="swc-empty-icon"><i class="bi bi-tags"></i></div><h3>No categories found</h3><p>Create a category to organize your blog content.</p></div>
        <?php else: ?>
        <div class="swc-table-wrap">
            <table class="swc-table">
                <thead><tr><th>Category</th><th>Slug</th><th>Posts</th><th>Created</th><th style="text-align:right">Actions</th></tr></thead>
                <tbody>
                <?php foreach ($categories as $c): $protected = (int)$c['id'] === 1 || $c['slug'] === 'all'; ?>
                    <tr>
                        <td><div class="swc-category-row-name"><span class="swc-category-mark"><i class="bi <?php echo $protected ? 'bi-shield-check' : 'bi-tag'; ?>"></i></span><span><strong><?php echo htmlspecialchars($c['name']); ?></strong><small><?php echo $protected ? 'Protected frontend filter' : 'Blog category'; ?></small></span></div></td>
                        <td><span class="swc-slug"><?php echo htmlspecialchars($c['slug']); ?></span></td>
                        <td><span class="swc-category <?php echo $protected ? 'is-protected' : ''; ?>"><?php echo (int)$c['post_count']; ?> post<?php echo (int)$c['post_count'] === 1 ? '' : 's'; ?></span></td>
                        <td><?php echo htmlspecialchars(date('M j, Y', strtotime($c['created_at']))); ?></td>
                        <td><div class="swc-row-actions">
                            <?php if ($protected): ?>
                                <span class="swc-category is-protected"><i class="bi bi-lock-fill"></i> Protected</span>
                            <?php else: ?>
                                <a class="swc-btn swc-btn-ghost swc-btn-sm" href="categories.php?edit=<?php echo (int)$c['id']; ?>"><i class="bi bi-pencil"></i> Edit</a>
                                <form method="post" class="swc-danger-form" onsubmit="return confirm('Delete this category? Blog posts will stay safe and become uncategorized.')">
                                    <input type="hidden" name="action" value="delete"><input type="hidden" name="category_id" value="<?php echo (int)$c['id']; ?>">
                                    <button class="swc-btn swc-btn-danger swc-btn-icon swc-btn-sm" type="submit" title="Delete"><i class="bi bi-trash3"></i></button>
                                </form>
                            <?php endif; ?>
                        </div></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="swc-mobile-cards">
            <?php foreach ($categories as $c): $protected = (int)$c['id'] === 1 || $c['slug'] === 'all'; ?>
            <article class="swc-mobile-card">
                <div class="swc-mobile-card-head"><div class="swc-mobile-card-meta"><span class="swc-category-mark"><i class="bi <?php echo $protected ? 'bi-shield-check' : 'bi-tag'; ?>"></i></span><div style="min-width:0"><h3><?php echo htmlspecialchars($c['name']); ?></h3><p><?php echo htmlspecialchars($c['slug']); ?> · <?php echo (int)$c['post_count']; ?> posts</p></div></div><?php if ($protected): ?><span class="swc-category is-protected">Protected</span><?php endif; ?></div>
                <?php if (!$protected): ?><div class="swc-mobile-card-actions"><a class="swc-btn swc-btn-ghost swc-btn-sm" href="categories.php?edit=<?php echo (int)$c['id']; ?>"><i class="bi bi-pencil"></i> Edit</a><form method="post" class="swc-danger-form" onsubmit="return confirm('Delete this category?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="category_id" value="<?php echo (int)$c['id']; ?>"><button class="swc-btn swc-btn-danger swc-btn-sm" type="submit"><i class="bi bi-trash3"></i> Delete</button></form></div><?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <aside class="swc-category-editor">
        <div class="swc-form-head">
            <h2><?php echo $editCategory && (int)$editCategory['id'] !== 1 ? 'Edit category' : 'Add category'; ?></h2>
            <p><?php echo $editCategory && (int)$editCategory['id'] !== 1 ? 'Update the name shown on your public blog.' : 'Create a new filter for the public Blogs page.'; ?></p>
        </div>
        <?php if ($editCategory && (int)$editCategory['id'] === 1): ?>
            <div class="swc-form-body"><div class="swc-alert swc-alert-info" style="margin:0"><i class="bi bi-shield-check"></i><span>All Blogs is protected because the frontend uses its <strong>all</strong> slug to show every article.</span></div></div>
            <div class="swc-form-actions"><a class="swc-btn swc-btn-ghost" href="categories.php">Close</a></div>
        <?php else: ?>
        <form method="post">
            <input type="hidden" name="action" value="<?php echo $editCategory ? 'update' : 'create'; ?>">
            <?php if ($editCategory): ?><input type="hidden" name="category_id" value="<?php echo (int)$editCategory['id']; ?>"><?php endif; ?>
            <div class="swc-form-body">
                <div class="swc-field-grid" style="grid-template-columns:1fr">
                    <label class="swc-field"><span class="swc-label">Category name <b class="swc-required">*</b></span><input class="swc-input" name="name" maxlength="50" required value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>" placeholder="Example: Business Tips"></label>
                    <label class="swc-field"><span class="swc-label">Slug</span><input class="swc-input" name="slug" maxlength="50" value="<?php echo htmlspecialchars($editCategory['slug'] ?? ''); ?>" placeholder="business-tips"><span class="swc-hint">Leave blank and it will be generated from the name.</span></label>
                </div>
            </div>
            <div class="swc-form-actions">
                <?php if ($editCategory): ?><a class="swc-btn swc-btn-ghost" href="categories.php">Cancel</a><?php endif; ?>
                <button class="swc-btn swc-btn-primary" type="submit"><i class="bi <?php echo $editCategory ? 'bi-check2' : 'bi-plus-lg'; ?>"></i> <?php echo $editCategory ? 'Save category' : 'Add category'; ?></button>
            </div>
        </form>
        <?php endif; ?>
    </aside>
</div>

<?php $design->CloseAdminLayout(); ?>
</body>
</html>
