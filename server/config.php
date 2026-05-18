<?php
/**
 * server/config.php
 *
 * MySQL connection for the blog posts table. Lead form data does NOT
 * touch this DB anymore - leads are forwarded to the central SEO platform
 * (see server/seo-platform-config.php). This connection is used only by
 * index.php (renders latest blog posts on homepage) and sitemap.php
 * (lists all posts in the XML sitemap).
 *
 * Credentials come from environment variables. On Herd / local dev the
 * `.env` file in the project root is loaded automatically. In production
 * set the same vars via CloudPanel → Settings → PHP-FPM → env, or via
 * Apache `SetEnv` directives.
 *
 * Required env vars:
 *   DB_HOST       (default: localhost)
 *   DB_NAME       (default: hlomedia)
 *   DB_USER       (no default - required)
 *   DB_PASSWORD   (no default - required)
 */

// -- Tiny .env loader (only if real env vars not set) ---------------------
$envPath = __DIR__ . '/../.env';
if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        // Strip surrounding quotes if present
        $value = preg_replace('/^([\'"])(.*)\1$/', '$2', $value);
        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

$dbHost     = getenv('DB_HOST')     ?: 'localhost';
$dbName     = getenv('DB_NAME')     ?: 'hlomedia';
$dbUser     = getenv('DB_USER')     ?: '';
$dbPassword = getenv('DB_PASSWORD');
if ($dbPassword === false) $dbPassword = '';

if ($dbUser === '') {
    error_log('server/config.php: DB_USER not configured (check .env)');
    http_response_code(500);
    die('Database not configured');
}

$con = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($con->connect_error) {
    error_log('DB connect error: ' . $con->connect_error);
    http_response_code(500);
    die('Database connection failed');
}

$con->set_charset('utf8mb4');
