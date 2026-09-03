<?php
/**
 * Blog media storage.
 *
 * Application/design assets belong in /assets.
 * User-generated blog media belongs in /uploads/blogs.
 */

const SWC_BLOG_UPLOAD_WEB_DIR = 'uploads/blogs';
const SWC_BLOG_DEFAULT_IMAGE = 'uploads/blogs/default-blog.png';
const SWC_BLOG_AUTHOR_IMAGE = 'uploads/blogs/softwebco-profile.png';
const SWC_BLOG_MAX_UPLOAD_BYTES = 5 * 1024 * 1024;

function swc_blog_upload_fs_dir(): string
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'blogs';
}

function swc_blog_legacy_image_map(): array
{
    return [
        // Original uploaded Blog media from pre-v2.1.8 releases.
        'storage/uploads/blogs/blog_1766597181_694c223d5da93.png' => 'uploads/blogs/launch-your-website.png',
        'storage/uploads/blogs/blog_1766670367_694d401fbe7c8.png' => 'uploads/blogs/lebanese-independence-day.png',
        'storage/uploads/blogs/blog_1766671874_694d4602d6362.png' => 'uploads/blogs/website-closed-window.png',
        'uploads/blogs/blog_1766597181_694c223d5da93.png' => 'uploads/blogs/launch-your-website.png',
        'uploads/blogs/blog_1766670367_694d401fbe7c8.png' => 'uploads/blogs/lebanese-independence-day.png',
        'uploads/blogs/blog_1766671874_694d4602d6362.png' => 'uploads/blogs/website-closed-window.png',
        'assets/images/programs/erp-system.png' => 'uploads/blogs/erp-system.png',
        'assets/images/programs/car-rental-system.png' => 'uploads/blogs/car-rental-system.png',
        'assets/images/cards/Shopping-application.png' => 'uploads/blogs/ecommerce-system.png',
        'assets/images/cards/ams-card.png' => 'uploads/blogs/agent-management-system.png',
        'assets/images/cards/pms-card.png' => 'uploads/blogs/project-management-system.png',
        'assets/images/cards/srs-card.png' => 'uploads/blogs/sports-reservation-system.png',
        'assets/images/cards/ocr-card.png' => 'uploads/blogs/ocr.png',
        'assets/images/brand-pattern-navy.png' => 'uploads/blogs/softwebco-website.png',
        'assets/images/site/businessman.webp' => 'uploads/blogs/client-service-portal.webp',
        'assets/images/profile/softwebco-blog-profile.png' => SWC_BLOG_AUTHOR_IMAGE,
    ];
}

function swc_blog_image_path(?string $path): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return SWC_BLOG_DEFAULT_IMAGE;
    }

    // Keep legacy remote values readable, but new admin uploads never create them.
    if (preg_match('~^(?:https?:)?//|^(?:data|blob):~i', $path)) {
        return $path;
    }

    $path = ltrim(str_replace('\\', '/', $path), '/');
    if ($path === '' || str_contains($path, '..') || !preg_match('~^[A-Za-z0-9_./%+\-]+$~', $path)) {
        return SWC_BLOG_DEFAULT_IMAGE;
    }

    $legacy = swc_blog_legacy_image_map();
    if (isset($legacy[$path])) {
        return $legacy[$path];
    }

    if (str_starts_with($path, SWC_BLOG_UPLOAD_WEB_DIR . '/')) {
        return SWC_BLOG_UPLOAD_WEB_DIR . '/' . basename($path);
    }

    if (str_starts_with($path, 'storage/uploads/blogs/')) {
        return SWC_BLOG_UPLOAD_WEB_DIR . '/' . basename($path);
    }

    return SWC_BLOG_DEFAULT_IMAGE;
}

function swc_blog_admin_image_url(?string $path): string
{
    $path = swc_blog_image_path($path);
    if (preg_match('~^(?:https?:)?//|^(?:data|blob):~i', $path) || str_starts_with($path, '/') || str_starts_with($path, '../')) {
        return $path;
    }
    return '../' . $path;
}

function swc_blog_upload_error_message(int $error): string
{
    return match ($error) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The image is larger than the server upload limit.',
        UPLOAD_ERR_PARTIAL => 'The image upload was interrupted. Please try again.',
        UPLOAD_ERR_NO_TMP_DIR => 'The server upload directory is unavailable.',
        UPLOAD_ERR_CANT_WRITE => 'The server could not save the uploaded image.',
        UPLOAD_ERR_EXTENSION => 'The server rejected the uploaded image.',
        default => 'The image could not be uploaded.',
    };
}

function swc_store_blog_upload(array $file): string
{
    $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) {
        throw new InvalidArgumentException('No image was uploaded.');
    }
    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException(swc_blog_upload_error_message($error));
    }

    $tmp = (string)($file['tmp_name'] ?? '');
    $size = (int)($file['size'] ?? 0);
    if ($tmp === '' || !is_file($tmp) || $size <= 0) {
        throw new RuntimeException('The uploaded image is invalid.');
    }
    if ($size > SWC_BLOG_MAX_UPLOAD_BYTES) {
        throw new RuntimeException('Image size must be 5MB or less.');
    }

    $mime = '';
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($tmp);
    } elseif (function_exists('mime_content_type')) {
        $mime = (string)mime_content_type($tmp);
    }

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if (!isset($extensions[$mime])) {
        throw new RuntimeException('Only JPG, PNG, GIF and WebP images are allowed.');
    }

    $dir = swc_blog_upload_fs_dir();
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Could not create the blog uploads directory.');
    }
    if (!is_writable($dir)) {
        throw new RuntimeException('The blog uploads directory is not writable.');
    }

    $filename = sprintf(
        'blog_%s_%s.%s',
        date('Ymd_His'),
        bin2hex(random_bytes(6)),
        $extensions[$mime]
    );
    $destination = $dir . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($tmp, $destination)) {
        throw new RuntimeException('The server could not move the uploaded image into uploads/blogs.');
    }

    return SWC_BLOG_UPLOAD_WEB_DIR . '/' . $filename;
}

function swc_is_generated_blog_upload(?string $path): bool
{
    $path = swc_blog_image_path($path);
    if (!str_starts_with($path, SWC_BLOG_UPLOAD_WEB_DIR . '/')) {
        return false;
    }

    $name = basename($path);
    return (bool)(
        preg_match('/^blog_\d{8}_\d{6}_[a-f0-9]{12}\.(?:jpg|png|gif|webp)$/i', $name) ||
        preg_match('/^blog_\d+_[a-f0-9]+\.(?:jpg|png|gif|webp)$/i', $name)
    );
}

function swc_delete_generated_blog_upload(?string $path): void
{
    if (!swc_is_generated_blog_upload($path)) {
        return;
    }

    $name = basename(swc_blog_image_path($path));
    $file = swc_blog_upload_fs_dir() . DIRECTORY_SEPARATOR . $name;
    if (is_file($file)) {
        @unlink($file);
    }
}
