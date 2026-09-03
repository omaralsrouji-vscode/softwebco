<?php
/**
 * Minimal environment loader.
 *
 * Production environments can provide variables through Apache, PHP-FPM, or
 * the operating system. For local development, an uncommitted .env file in
 * the project root is also supported.
 */

function swc_load_environment(?string $path = null): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    $path = $path ?? dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    $values = parse_ini_file($path, false, INI_SCANNER_RAW);
    if (!is_array($values)) {
        throw new RuntimeException('Could not parse the local .env file.');
    }

    foreach ($values as $name => $value) {
        if (!is_string($name) || !preg_match('/^[A-Z][A-Z0-9_]*$/', $name)) {
            continue;
        }
        if (getenv($name) !== false || array_key_exists($name, $_ENV)) {
            continue;
        }
        $value = (string)$value;
        $_ENV[$name] = $value;
        putenv($name . '=' . $value);
    }
}

function swc_env(string $name, ?string $default = null): ?string
{
    $value = getenv($name);
    if ($value !== false) {
        return $value;
    }
    return array_key_exists($name, $_ENV) ? (string)$_ENV[$name] : $default;
}

function swc_env_list(string $name): array
{
    $value = trim((string)swc_env($name, ''));
    if ($value === '') {
        return [];
    }
    return array_values(array_filter(array_map('trim', explode(',', $value)), static fn ($item) => $item !== ''));
}

swc_load_environment();
