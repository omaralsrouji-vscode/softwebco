<?php
// auth.php - Secure login authentication
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: login.php?error=empty');
    exit();
}

$stmt = $conn->prepare(
    "SELECT id, username, password, display_name, profile_image, account_status, is_locked
     FROM users
     WHERE username = ?
     LIMIT 1"
);
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$passwordValid = $user && swc_verify_password($password, (string)$user['password']);
$accountAllowed = $user
    && ($user['account_status'] ?? 'active') === 'active'
    && (int)($user['is_locked'] ?? 0) === 0;

if (!$passwordValid || !$accountAllowed) {
    header('Location: login.php?error=invalid');
    exit();
}

// Transparently upgrade legacy plain-text/old hashes after a successful login.
if (swc_password_needs_upgrade((string)$user['password'])) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $upgrade = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    $upgrade->bind_param('si', $newHash, $user['id']);
    $upgrade->execute();
    $upgrade->close();
}

session_regenerate_id(true);
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['login_time'] = time();
$_SESSION['current_user'] = [
    'display_name' => !empty($user['display_name']) ? $user['display_name'] : $user['username'],
    'profile_image' => !empty($user['profile_image']) ? $user['profile_image'] : 'default-avatar.png'
];

header('Location: admin.php');
exit();
