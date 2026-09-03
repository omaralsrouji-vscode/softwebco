<?php
require_once '../config.php';
swc_require_admin();

$isJson = strpos(strtolower((string)($_SERVER['CONTENT_TYPE'] ?? '')), 'application/json') !== false;

function swc_delete_user_response(bool $success, string $message, int $status = 200, bool $json = false)
{
    if ($json) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit();
    }

    if ($success) {
        header('Location: users.php?deleted=1');
    } else {
        header('Location: users.php?delete_error=' . rawurlencode($message));
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    swc_delete_user_response(false, 'Invalid request method.', 405, $isJson);
}

if ($isJson) {
    $payload = json_decode((string)file_get_contents('php://input'), true);
    $payload = is_array($payload) ? $payload : [];
} else {
    $payload = $_POST;
}

$userId = (int)($payload['user_id'] ?? 0);
$currentId = (int)($_SESSION['user_id'] ?? 0);

if ($userId <= 0) {
    swc_delete_user_response(false, 'User ID required.', 400, $isJson);
}
if ($userId === $currentId) {
    swc_delete_user_response(false, 'You cannot delete the account you are currently using.', 400, $isJson);
}

$transactionStarted = false;
try {
    $lookup = $conn->prepare('SELECT profile_image FROM users WHERE id=? LIMIT 1');
    $lookup->bind_param('i', $userId);
    $lookup->execute();
    $row = $lookup->get_result()->fetch_assoc();
    $lookup->close();

    if (!$row) {
        swc_delete_user_response(false, 'User not found.', 404, $isJson);
    }

    $conn->begin_transaction();
    $transactionStarted = true;

    $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    if ($stmt->affected_rows !== 1) {
        $stmt->close();
        throw new RuntimeException('The user could not be deleted.');
    }
    $stmt->close();

    $conn->commit();
    $transactionStarted = false;

    $profile = basename(trim((string)($row['profile_image'] ?? '')));
    if ($profile !== '' && $profile !== 'default-avatar.png') {
        $path = __DIR__ . '/../storage/profile-images/' . $profile;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    swc_delete_user_response(true, 'User deleted successfully.', 200, $isJson);
} catch (Throwable $e) {
    if ($transactionStarted) {
        try {
            $conn->rollback();
        } catch (Throwable $ignore) {
        }
    }
    error_log('Delete user error: ' . $e->getMessage());
    swc_delete_user_response(false, 'Could not delete the user.', 500, $isJson);
}
