<?php
/**
 * SEO Platform - Leads Summary API
 *
 * DEPLOY TO: /opt/seo-platform/api/leads-summary.php
 * SYMLINK:   ln -s /opt/seo-platform/api/leads-summary.php \
 *                  /home/api/htdocs/api.hlo-media.com/leads-summary.php
 *
 * Returns aggregated lead data for a site over a period.
 * Used by the weekly SEO agent workflow.
 *
 * URL:  GET https://api.hlo-media.com/leads-summary.php?site_slug=hlomedia&days=7
 * Auth: X-API-Key header (ADMIN_API_KEY from /opt/seo-platform/config/.env)
 */

define('DB_PATH', '/opt/seo-platform/data/master.db');
define('ENV_PATH', '/opt/seo-platform/config/.env');

function load_env($path) {
    $env = [];
    if (!file_exists($path)) return $env;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($k, $v) = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
    return $env;
}

$ENV = load_env(ENV_PATH);
$ADMIN_KEY = $ENV['ADMIN_API_KEY'] ?? '';

header('Content-Type: application/json; charset=utf-8');

// Auth - admin key only (internal endpoint)
$providedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
if (!$ADMIN_KEY || $providedKey !== $ADMIN_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$site_slug = $_GET['site_slug'] ?? '';
$days = max(1, min(90, (int) ($_GET['days'] ?? 7)));

if (!$site_slug) {
    http_response_code(400);
    echo json_encode(['error' => 'site_slug required']);
    exit;
}

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get site
    $stmt = $db->prepare('SELECT id, display_name, domain FROM sites WHERE slug = :slug');
    $stmt->execute([':slug' => $site_slug]);
    $site = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$site) {
        http_response_code(404);
        echo json_encode(['error' => 'Site not found']);
        exit;
    }

    // Get leads from period
    $stmt = $db->prepare("
        SELECT
            id, name, email, phone, message,
            landing_page, referrer, search_query,
            utm_source, utm_medium, utm_campaign,
            is_mobile, country, status, created_at
        FROM leads
        WHERE site_id = :site_id
          AND created_at >= datetime('now', :period)
        ORDER BY created_at DESC
    ");
    $stmt->execute([
        ':site_id' => $site['id'],
        ':period'  => "-{$days} days",
    ]);
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Aggregate
    $bySource       = [];
    $byLandingPage  = [];
    $byStatus       = ['new' => 0, 'contacted' => 0, 'qualified' => 0, 'won' => 0, 'lost' => 0];
    $mobileCount    = 0;

    foreach ($leads as $lead) {
        $source = $lead['utm_source'] ?: ($lead['search_query'] ? 'google_organic' : 'direct');
        $bySource[$source] = ($bySource[$source] ?? 0) + 1;

        $page = $lead['landing_page'] ?: 'unknown';
        $byLandingPage[$page] = ($byLandingPage[$page] ?? 0) + 1;

        if (isset($byStatus[$lead['status']])) {
            $byStatus[$lead['status']]++;
        }

        if ($lead['is_mobile']) $mobileCount++;
    }

    // Top search queries that produced leads
    $searchQueries = array_filter(array_column($leads, 'search_query'));
    $topQueries = array_count_values($searchQueries);
    arsort($topQueries);
    $topQueries = array_slice($topQueries, 0, 5, true);

    echo json_encode([
        'site' => [
            'slug'   => $site_slug,
            'name'   => $site['display_name'],
            'domain' => $site['domain'],
        ],
        'period_days'        => $days,
        'total'              => count($leads),
        'mobile_count'       => $mobileCount,
        'desktop_count'      => count($leads) - $mobileCount,
        'by_source'          => $bySource,
        'by_status'          => $byStatus,
        'top_landing_pages'  => $byLandingPage,
        'top_search_queries' => $topQueries,
        'leads' => array_map(function ($lead) {
            return [
                'id'           => $lead['id'],
                'name'         => $lead['name'],
                'email'        => $lead['email'],
                'phone'        => $lead['phone'],
                'message'      => mb_substr($lead['message'] ?? '', 0, 100),
                'landing_page' => $lead['landing_page'],
                'search_query' => $lead['search_query'],
                'source'       => $lead['utm_source'] ?: 'organic',
                'is_mobile'    => (bool) $lead['is_mobile'],
                'status'       => $lead['status'],
                'created_at'   => $lead['created_at'],
            ];
        }, $leads),
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    error_log('Leads summary error: ' . $e->getMessage());
}
