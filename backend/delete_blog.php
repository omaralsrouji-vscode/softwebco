<?php
require_once '../config.php';
swc_require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['post_id'])) {
    header('Location: bloglist.php');
    exit();
}

$message = '';
$messageType = 'error';

try {
    $postId = (int)$_POST['post_id'];
    if ($postId <= 0) {
        throw new RuntimeException('Invalid blog post.');
    }

    $checkStmt = $conn->prepare('SELECT title, image_url FROM blog_posts WHERE id = ? LIMIT 1');
    $checkStmt->bind_param('i', $postId);
    $checkStmt->execute();
    $post = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();
    if (!$post) {
        throw new RuntimeException('Blog post not found.');
    }

    $deleteStmt = $conn->prepare('DELETE FROM blog_posts WHERE id = ?');
    $deleteStmt->bind_param('i', $postId);
    if (!$deleteStmt->execute()) {
        throw new RuntimeException('Failed to delete the blog post.');
    }
    $deleteStmt->close();

    // Only generated uploads are deleted. Shared seed/fallback images are never removed.
    swc_delete_generated_blog_upload($post['image_url'] ?? '');

    $message = "Blog post '{$post['title']}' deleted successfully.";
    $messageType = 'success';
} catch (Throwable $e) {
    error_log('Delete blog failed: ' . $e->getMessage());
    $message = $e instanceof RuntimeException ? $e->getMessage() : 'The blog post could not be deleted.';
}

$_SESSION['blog_action_message'] = $message;
$_SESSION['blog_action_message_type'] = $messageType;
header('Location: bloglist.php');
exit();
