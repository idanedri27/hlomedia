<?php
/**
 * /opt/seo-platform/api/publish-article.php
 *
 * Receives a click from the weekly email's "פרסם באתר" button.
 * Validates the one-time token, merges the PR via GitHub API, fetches
 * the merged file from raw.githubusercontent.com, and writes it to
 * the site's webroot.
 *
 * URL: GET /publish-article.php?pr=<NUMBER>&token=<HEX>
 *
 * On success: shows a nice Hebrew HTML page + records publish in
 * content_pieces table.
 *
 * Hard rules:
 *   - Single specific file written (no directory traversal, no globbing)
 *   - File path must already match blog/article-NUMERIC.php
 *   - No `git pull` — fetches the one merged file via raw URL
 *   - Token marked as used so the same link can't be replayed
 */

define('DB_PATH',  '/opt/seo-platform/data/master.db');
define('ENV_PATH', '/opt/seo-platform/config/.env');
define('REPO',     'idanedri27/hlomedia');

// Webroot mapping (site_slug → absolute filesystem path)
// Keep tight - only sites explicitly listed here are eligible
const WEBROOTS = [
    'hlomedia' => '/home/hlomedia/htdocs/www.hlo-media.com',
];

// Public URL prefix per site (for showing the final article link in the success page)
const PUBLIC_URLS = [
    'hlomedia' => 'https://www.hlo-media.com',
];

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

function show_page($title, $body_html, $success = true) {
    header('Content-Type: text/html; charset=utf-8');
    $color = $success ? '#10b981' : '#ef4444';
    $emoji = $success ? '🎉' : '⚠️';
    echo "<!DOCTYPE html>
<html dir='rtl' lang='he'>
<head>
<meta charset='UTF-8'>
<title>" . htmlspecialchars($title) . "</title>
<style>
  body { font-family: -apple-system, 'Segoe UI', sans-serif; padding: 40px 20px; max-width: 600px; margin: 0 auto;
         direction: rtl; text-align: right; line-height: 1.7; color: #1a1a1f; }
  h1 { color: {$color}; margin-bottom: 8px; font-size: 28px; }
  .box { background: #f8fafc; padding: 24px; border-radius: 12px; border-right: 4px solid {$color}; margin: 20px 0; }
  a.btn { display: inline-block; margin-top: 16px; background: linear-gradient(135deg,#fa65b1,#726ae3);
          color: #fff; padding: 12px 28px; border-radius: 999px; text-decoration: none; font-weight: 600;
          box-shadow: 0 8px 20px rgba(250,101,177,0.25); }
  a.btn:hover { transform: translateY(-1px); }
  code { background: #eef; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
  small { color: #888; }
</style>
</head>
<body>
  <h1>{$emoji} " . htmlspecialchars($title) . "</h1>
  <div class='box'>{$body_html}</div>
</body>
</html>";
}

function github_api($method, $path, $token, $body = null) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.github.com' . $path,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
            'User-Agent: HloMedia-Publisher/1.0',
            'Content-Type: application/json',
        ],
    ]);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return [
        'ok'   => $code >= 200 && $code < 300,
        'code' => $code,
        'body' => $resp,
        'err'  => $err,
    ];
}

// ============================================================
// Main
// ============================================================
$pr    = (int) ($_GET['pr']    ?? 0);
$token =        $_GET['token'] ?? '';

if (!$pr || !preg_match('/^[a-f0-9]{32,128}$/', $token)) {
    show_page('שגיאה', "<p>פרמטרים חסרים או לא תקינים.</p>", false);
    exit;
}

$ENV = load_env(ENV_PATH);
$GH_PAT = $ENV['GITHUB_PAT'] ?? '';
if (!$GH_PAT) {
    show_page('שגיאת תצורה', "<p>GITHUB_PAT לא מוגדר בשרת. פנה למפתח.</p>", false);
    exit;
}

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Validate token
    $stmt = $db->prepare("
        SELECT pt.token, pt.pr_number, pt.file_path, pt.site_slug, pt.used_at, pt.expires_at, s.id AS site_id
        FROM publish_tokens pt
        JOIN sites s ON s.slug = pt.site_slug
        WHERE pt.token = :tok AND pt.pr_number = :pr
    ");
    $stmt->execute([':tok' => $token, ':pr' => $pr]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        show_page('Token לא תקף', "<p>הקישור הזה אינו תקף או שה-PR לא תואם. בקש מהסוכן לשלוח קישור חדש.</p>", false);
        exit;
    }
    if ($row['used_at']) {
        show_page('כבר פורסם', "<p>המאמר הזה כבר פורסם ב-{$row['used_at']}.</p>" .
                 "<p>אם אתה רוצה לפרסם שוב - בקש מהסוכן ליצור PR חדש.</p>", false);
        exit;
    }
    if (strtotime($row['expires_at']) < time()) {
        show_page('פג תוקף', "<p>הקישור הזה פג תוקף ב-{$row['expires_at']} (יותר מ-7 ימים מהיצירה).</p>", false);
        exit;
    }

    $site_slug = $row['site_slug'];
    $file_path = $row['file_path'];

    // Defense-in-depth: re-validate file path
    if (!preg_match('#^blog/article-\d+\.php$#', $file_path)) {
        show_page('שגיאת אבטחה', "<p>file_path לא תקין.</p>", false);
        exit;
    }

    $webroot    = WEBROOTS[$site_slug]    ?? null;
    $public_url = PUBLIC_URLS[$site_slug] ?? null;
    if (!$webroot || !$public_url) {
        show_page('שגיאת תצורה', "<p>site_slug '{$site_slug}' לא מוגדר ב-WEBROOTS.</p>", false);
        exit;
    }

    $dest = $webroot . '/' . $file_path;
    // Resolve real paths to prevent traversal even if pattern was bypassed somehow
    $real_dest = realpath(dirname($dest));
    if ($real_dest === false || strpos($real_dest, realpath($webroot)) !== 0) {
        show_page('שגיאת אבטחה', "<p>נתיב היעד לא בתוך ה-webroot.</p>", false);
        exit;
    }

    // 2. Merge PR via GitHub API
    $merge = github_api('PUT', "/repos/" . REPO . "/pulls/{$pr}/merge", $GH_PAT, [
        'commit_title' => 'Publish: ' . $file_path,
        'merge_method' => 'squash',
    ]);

    // Allow 405 with reason "Pull Request is not mergeable" only if already merged
    if (!$merge['ok']) {
        $body_json = json_decode($merge['body'], true);
        $msg = $body_json['message'] ?? $merge['body'];
        // GitHub returns 405 for "already merged" too - check via a separate GET
        $info = github_api('GET', "/repos/" . REPO . "/pulls/{$pr}", $GH_PAT);
        $info_body = json_decode($info['body'], true);
        if (!$info['ok'] || empty($info_body['merged'])) {
            show_page('שגיאה ב-merge', "<p>GitHub החזיר HTTP {$merge['code']}:</p><p><code>" .
                     htmlspecialchars(substr($msg, 0, 300)) . "</code></p>", false);
            exit;
        }
        // Else: PR already merged — proceed to fetch + write file
    }

    // 3. Fetch the merged file from raw.githubusercontent.com main
    $raw_url = 'https://raw.githubusercontent.com/' . REPO . '/main/' . $file_path;
    $ch = curl_init($raw_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $GH_PAT,
            'User-Agent: HloMedia-Publisher/1.0',
        ],
    ]);
    $content = curl_exec($ch);
    $http    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http !== 200 || $content === false || $content === '') {
        show_page('שגיאה', "<p>לא ניתן לשלוף את הקובץ מ-GitHub raw (HTTP {$http}).</p>", false);
        exit;
    }

    // 4. Write file to webroot
    if (file_put_contents($dest, $content) === false) {
        show_page('שגיאה בכתיבה', "<p>לא ניתן לכתוב ל-{$dest}. בדוק הרשאות (api user חייב להיות ב-group hlomedia).</p>", false);
        exit;
    }
    @chmod($dest, 0644);

    // 5. Mark token used
    $db->prepare("UPDATE publish_tokens SET used_at = CURRENT_TIMESTAMP WHERE token = :t")
       ->execute([':t' => $token]);

    // 6. Log in content_pieces
    $public_article_url = $public_url . '/' . $file_path;
    try {
        $stmt = $db->prepare("
            INSERT INTO content_pieces
                (site_id, target_keyword, title, slug, url, content_type, status, published_at, created_by)
            VALUES
                (:site, :kw, :title, :slug, :url, 'article', 'published', CURRENT_TIMESTAMP, 'agent')
        ");
        $stmt->execute([
            ':site'  => $row['site_id'],
            ':kw'    => '(auto)',
            ':title' => $file_path,
            ':slug'  => basename($file_path, '.php'),
            ':url'   => $public_article_url,
        ]);
    } catch (Throwable $e) {
        // Don't fail the publish on logging issues
        error_log('publish-article: content_pieces log failed: ' . $e->getMessage());
    }

    // 7. Success page
    $body = "<p>המאמר עלה לאתר. גוגל יסרוק את הסיטמאפ בריצה הבאה שלו ויאנדקס.</p>" .
            "<p>הקובץ נכתב ל:</p><p><code>{$file_path}</code></p>" .
            "<p style='margin-top:24px'><a class='btn' href='" . htmlspecialchars($public_article_url) .
            "' target='_blank'>🔗 צפה במאמר באתר</a></p>" .
            "<p><small>PR #{$pr} נסגר עם merge. Token סומן כשומש.</small></p>";
    show_page('המאמר פורסם בהצלחה!', $body, true);

} catch (Throwable $e) {
    error_log('publish-article fatal: ' . $e->getMessage());
    show_page('שגיאה לא צפויה', "<p>" . htmlspecialchars($e->getMessage()) . "</p>", false);
}
