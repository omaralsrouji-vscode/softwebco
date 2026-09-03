<?php
// config.php
require_once __DIR__ . '/includes/environment.php';

// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Beirut');
require_once __DIR__ . '/includes/blog-media.php';

// Database configuration. Values may come from the server environment or a
// local, uncommitted .env file. Defaults match a typical local XAMPP setup.
define('DB_HOST', swc_env('SWC_DB_HOST', 'localhost'));
define('DB_PORT', (int)swc_env('SWC_DB_PORT', '3306'));
define('DB_USER', swc_env('SWC_DB_USER', 'root'));
define('DB_PASSWORD', swc_env('SWC_DB_PASSWORD', ''));
define('DB_NAME', swc_env('SWC_DB_NAME', 'softwebco'));

// Error reporting: log errors in production; set SWC_DEBUG=1 locally to display them.
error_reporting(E_ALL);
$swcDebug = getenv('SWC_DEBUG') === '1';
ini_set('display_errors', $swcDebug ? '1' : '0');

// Create PDO connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Don't show the actual error to users in production
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Create mysqli connection (if you need it for legacy code)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Check mysqli connection
if ($conn->connect_error) {
    error_log("MySQLi connection failed: " . $conn->connect_error);
    // Don't die here as PDO is the primary connection
}

// Set mysqli charset
if (!$conn->connect_error) {
    $conn->set_charset("utf8mb4");
}

// Shared password helpers: support legacy plain-text rows while safely storing all new passwords.
function swc_verify_password(string $plainPassword, string $storedPassword): bool
{
    if (password_get_info($storedPassword)['algo'] !== null) {
        return password_verify($plainPassword, $storedPassword);
    }

    return hash_equals($storedPassword, $plainPassword);
}

function swc_password_needs_upgrade(string $storedPassword): bool
{
    return password_get_info($storedPassword)['algo'] === null || password_needs_rehash($storedPassword, PASSWORD_DEFAULT);
}


// Resolve project media correctly when it is rendered from /backend.
// Site content paths are stored relative to the project root.
// Blog media lives under uploads/blogs; user profile images live under storage/profile-images.
function swc_is_external_media_url(?string $path): bool
{
    $path = trim((string)$path);
    return $path !== '' && (bool)preg_match('~^(?:https?:)?//|^(?:data|blob):~i', $path);
}

function swc_admin_content_url(?string $path, string $fallback = '../assets/images/favicon.png'): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return $fallback;
    }
    if (swc_is_external_media_url($path) || strpos($path, '../') === 0 || strpos($path, '/') === 0) {
        return $path;
    }
    return '../' . ltrim($path, './');
}

function swc_admin_profile_url(?string $filename): string
{
    $filename = trim((string)$filename);
    if ($filename === '') {
        $filename = 'default-avatar.png';
    }
    if (swc_is_external_media_url($filename) || strpos($filename, '../') === 0 || strpos($filename, '/') === 0) {
        return $filename;
    }
    if (strpos($filename, '/') !== false) {
        return swc_admin_content_url($filename, '../storage/profile-images/default-avatar.png');
    }
    $safe = basename($filename);
    $file = __DIR__ . '/storage/profile-images/' . $safe;
    if (!is_file($file)) {
        $safe = 'default-avatar.png';
    }
    return '../storage/profile-images/' . rawurlencode($safe);
}

function swc_public_profile_path(?string $filename): string
{
    $filename = trim((string)$filename);
    if ($filename === '') {
        return SWC_BLOG_AUTHOR_IMAGE;
    }
    if (swc_is_external_media_url($filename) || strpos($filename, 'assets/') === 0 || strpos($filename, 'storage/') === 0 || strpos($filename, 'uploads/') === 0 || strpos($filename, '/') === 0) {
        return $filename;
    }
    return 'storage/profile-images/' . rawurlencode(basename($filename));
}

// Blog and portfolio content are installed only by database/install.sql.
// Runtime requests never seed or mutate editorial content.



// Detect Arabic editorial text so public Blog pages can apply the correct language direction and typography.
function swc_text_is_arabic(?string $text): bool
{
    $text = trim(strip_tags((string)$text));
    if ($text === '') {
        return false;
    }

    return (bool)preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $text);
}

function swc_text_language_attributes(?string $text): string
{
    return swc_text_is_arabic($text) ? ' lang="ar" dir="rtl"' : ' lang="en" dir="ltr"';
}

// Backend helpers. The admin workspace is intentionally limited to Dashboard, Users, Blogs and Categories.
function swc_require_admin(): void
{
    if (empty($_SESSION['logged_in'])) {
        header('Location: login.php');
        exit();
    }
}

function swc_current_admin_user(mysqli $connection): array
{
    $id = (int)($_SESSION['user_id'] ?? 0);
    $fallback = [
        'id' => $id,
        'username' => $_SESSION['username'] ?? 'admin',
        'email' => '',
        'display_name' => $_SESSION['username'] ?? 'Softwebco Admin',
        'profile_image' => 'default-avatar.png',
        'bio' => '',
        'account_status' => 'active',
        'created_at' => null,
        'updated_at' => null,
    ];
    if ($id <= 0 || $connection->connect_error) return $fallback;
    try {
        $stmt = $connection->prepare('SELECT id, username, email, display_name, profile_image, bio, account_status, created_at, updated_at FROM users WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) return $fallback;
        $row['display_name'] = trim((string)($row['display_name'] ?? '')) ?: (string)$row['username'];
        $row['profile_image'] = trim((string)($row['profile_image'] ?? '')) ?: 'default-avatar.png';
        return array_merge($fallback, $row);
    } catch (Throwable $e) {
        error_log('Could not load admin user: '.$e->getMessage());
        return $fallback;
    }
}

function swc_admin_initials(string $name): string
{
    $parts = preg_split('/\\s+/', trim($name)) ?: [];
    $out = '';
    foreach ($parts as $part) {
        if ($part === '') continue;
        $out .= strtoupper(function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1));
        if (strlen($out) >= 2) break;
    }
    return $out ?: 'SW';
}

?>
