<?php
/**
 * /opt/seo-platform/api/register-publish-token.php
 *
 * Called by n8n right after the SEO agent opens a PR. Generates a
 * one-time publish token, stores it in master.db, and returns the
 * token + the publish URL.
 *
 * POST JSON body:
 *   { "site_slug": "hlomedia", "pr_number": 42, "file_path": "blog/article-1234.php" }
 * Auth: X-API-Key (ADMIN_API_KEY)
 *
 * Response:
 *   { "token": "...", "publish_url": "https://api.hlo-media.com/publish-article.php?pr=42&token=..." }
 */

define('DB_PATH', '/opt/seo-platform/data/master.db');
define('ENV_PATH', '/opt/seo-platform/config/.env');
define('PUBLISH_BASE', 'https://api.hlo-media.com/publish-article.php');
define('TOKEN_TTL_DAYS', 7);

function load_env($path) {
    $env = [];
    if (!file_exists($path)) return $env;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
    return $env;
}

header('Content-Type: application/json; charset=utf-8');

$ENV = load_env(ENV_PATH);
$ADMIN_KEY = $ENV['ADMIN_API_KEY'] ?? '';

if (!$ADMIN_KEY || ($_SERVER['HTTP_X_API_KEY'] ?? '') !== $ADMIN_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true) ?: [];

$site_slug = trim($input['site_slug'] ?? '');
$pr        = (int) ($input['pr_number'] ?? 0);
$file      = trim($input['file_path'] ?? '');

if (!$site_slug || !$pr || !$file) {
    http_response_code(400);
    echo json_encode(['error' => 'site_slug, pr_number, file_path are required']);
    exit;
}

// Strict file path whitelist - only allow blog/article-NUMERIC.php
if (!preg_match('#^blog/article-\d+\.php$#', $file)) {
    http_response_code(400);
    echo json_encode(['error' => 'file_path must match blog/article-NUMERIC.php']);
    exit;
}

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure table exists (idempotent)
    $db->exec("
        CREATE TABLE IF NOT EXISTS publish_tokens (
            token       TEXT PRIMARY KEY,
            pr_number   INTEGER NOT NULL,
            file_path   TEXT NOT NULL,
            site_slug   TEXT NOT NULL,
            created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            used_at     DATETIME,
            expires_at  DATETIME NOT NULL
        )
    ");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_pt_used ON publish_tokens(used_at)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_pt_expires ON publish_tokens(expires_at)");

    // Confirm site exists
    $stmt = $db->prepare("SELECT id FROM sites WHERE slug = :slug");
    $stmt->execute([':slug' => $site_slug]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'site_slug not found in sites table']);
        exit;
    }

    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + TOKEN_TTL_DAYS * 86400);

    $stmt = $db->prepare("
        INSERT INTO publish_tokens (token, pr_number, file_path, site_slug, expires_at)
        VALUES (:tok, :pr, :file, :slug, :exp)
    ");
    $stmt->execute([
        ':tok'  => $token,
        ':pr'   => $pr,
        ':file' => $file,
        ':slug' => $site_slug,
        ':exp'  => $expires,
    ]);

    echo json_encode([
        'token'       => $token,
        'expires_at'  => $expires,
        'publish_url' => PUBLISH_BASE . '?pr=' . $pr . '&token=' . $token,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    error_log('register-publish-token: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
}
