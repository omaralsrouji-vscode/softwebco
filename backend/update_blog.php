<?php
require_once '../config.php';
swc_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['post_id'])) {
    header('Location: bloglist.php');
    exit();
}

$message = '';
$messageType = 'error';
$newUpload = null;

try {
    $postId = (int)$_POST['post_id'];
    $title = trim((string)($_POST['title'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    if ($postId <= 0 || $title === '' || $content === '') {
        throw new RuntimeException('Title and content are required.');
    }

    $existingStmt = $conn->prepare('SELECT * FROM blog_posts WHERE id = ? LIMIT 1');
    $existingStmt->bind_param('i', $postId);
    $existingStmt->execute();
    $existing = $existingStmt->get_result()->fetch_assoc();
    $existingStmt->close();
    if (!$existing) {
        throw new RuntimeException('Blog post not found.');
    }

    $excerpt = trim((string)($_POST['excerpt'] ?? ''));
    $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    if ($categoryId !== null) {
        $categoryStmt = $conn->prepare("SELECT id FROM categories WHERE id = ? AND slug <> 'all' LIMIT 1");
        $categoryStmt->bind_param('i', $categoryId);
        $categoryStmt->execute();
        if ($categoryStmt->get_result()->num_rows === 0) {
            $categoryId = null;
        }
        $categoryStmt->close();
    }

    $authorName = trim((string)($_POST['author_name'] ?? '')) ?: 'Softwebco Team';
    $authorImageUrl = SWC_BLOG_AUTHOR_IMAGE;
    $readTime = trim((string)($_POST['read_time'] ?? '')) ?: '5 min read';
    $postDate = trim((string)($_POST['post_date'] ?? '')) ?: date('Y-m-d');

    // Existing legacy paths are normalized to /uploads/blogs on the next edit.
    $imageUrl = swc_blog_image_path($existing['image_url'] ?? '');
    if (isset($_FILES['image']) && (int)($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $newUpload = swc_store_blog_upload($_FILES['image']);
        $imageUrl = $newUpload;
    }

    $updateStmt = $conn->prepare(
        'UPDATE blog_posts SET title = ?, content = ?, excerpt = ?, category_id = ?, image_url = ?, author_name = ?, author_image_url = ?, read_time = ?, post_date = ? WHERE id = ?'
    );
    $updateStmt->bind_param(
        'sssisssssi',
        $title,
        $content,
        $excerpt,
        $categoryId,
        $imageUrl,
        $authorName,
        $authorImageUrl,
        $readTime,
        $postDate,
        $postId
    );

    if (!$updateStmt->execute()) {
        throw new RuntimeException('Failed to update the blog post.');
    }
    $updateStmt->close();

    if ($newUpload !== null && $newUpload !== swc_blog_image_path($existing['image_url'] ?? '')) {
        swc_delete_generated_blog_upload($existing['image_url'] ?? '');
    }

    $message = 'Blog post updated successfully.';
    $messageType = 'success';
} catch (Throwable $e) {
    if ($newUpload !== null) {
        swc_delete_generated_blog_upload($newUpload);
    }
    error_log('Update blog failed: ' . $e->getMessage());
    $message = $e instanceof InvalidArgumentException || $e instanceof RuntimeException
        ? $e->getMessage()
        : 'The blog post could not be updated.';
}

$_SESSION['blog_action_message'] = $message;
$_SESSION['blog_action_message_type'] = $messageType;
header('Location: bloglist.php');
exit();
