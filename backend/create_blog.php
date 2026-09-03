<?php
require_once '../config.php';
swc_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: bloglist.php');
    exit();
}

$message = '';
$messageType = 'error';
$newUpload = null;

try {
    $title = trim((string)($_POST['title'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    if ($title === '' || $content === '') {
        throw new RuntimeException('Title and content are required.');
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

    $imageUrl = SWC_BLOG_DEFAULT_IMAGE;
    if (isset($_FILES['image']) && (int)($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $newUpload = swc_store_blog_upload($_FILES['image']);
        $imageUrl = $newUpload;
    }

    $stmt = $conn->prepare(
        'INSERT INTO blog_posts (title, content, excerpt, category_id, image_url, author_name, author_image_url, read_time, post_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'sssisssss',
        $title,
        $content,
        $excerpt,
        $categoryId,
        $imageUrl,
        $authorName,
        $authorImageUrl,
        $readTime,
        $postDate
    );

    if (!$stmt->execute()) {
        throw new RuntimeException('Failed to create the blog post.');
    }
    $stmt->close();

    $message = 'Blog post created successfully.';
    $messageType = 'success';
} catch (Throwable $e) {
    if ($newUpload !== null) {
        swc_delete_generated_blog_upload($newUpload);
    }
    error_log('Create blog failed: ' . $e->getMessage());
    $message = $e instanceof InvalidArgumentException || $e instanceof RuntimeException
        ? $e->getMessage()
        : 'The blog post could not be created.';
}

$_SESSION['blog_action_message'] = $message;
$_SESSION['blog_action_message_type'] = $messageType;
header('Location: bloglist.php');
exit();
