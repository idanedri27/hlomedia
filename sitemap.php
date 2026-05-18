<?php
/**
 * sitemap.php
 *
 * Dynamic sitemap.xml that ALWAYS reflects the current state of the site:
 *   - Homepage (HE + EN)
 *   - Every published blog article (from `posts` table)
 *
 * Mapped to /sitemap.xml via .htaccess rewrite (see .htaccess).
 *
 * If the DB is unreachable we still emit a valid sitemap with at least
 * the homepage so we never serve broken XML to googlebot.
 */

header('Content-Type: application/xml; charset=utf-8');

$siteUrl = 'https://www.hlo-media.com';

$entries = [];

// Homepage (HE) - highest priority
$entries[] = [
    'loc'        => $siteUrl . '/',
    'lastmod'    => date('c'),
    'changefreq' => 'weekly',
    'priority'   => '1.0',
];

// English version
$entries[] = [
    'loc'        => $siteUrl . '/en.php',
    'lastmod'    => date('c'),
    'changefreq' => 'monthly',
    'priority'   => '0.7',
];

// Blog posts from DB
try {
    $configPath = __DIR__ . '/server/config.php';
    if (file_exists($configPath)) {
        include $configPath;
        if (isset($con) && !$con->connect_error) {
            $sql = "SELECT slug, created_at, updated_at FROM posts ORDER BY created_at DESC";
            if ($result = @$con->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $slug = $row['slug'] ?? '';
                    if (!$slug) continue;
                    $lastmod = $row['updated_at'] ?? $row['created_at'] ?? date('c');
                    $entries[] = [
                        'loc'        => $siteUrl . '/blog/' . htmlspecialchars($slug, ENT_XML1, 'UTF-8') . '.php',
                        'lastmod'    => date('c', strtotime($lastmod)),
                        'changefreq' => 'monthly',
                        'priority'   => '0.6',
                    ];
                }
            }
        }
    }
} catch (Throwable $e) {
    error_log('sitemap.php DB error: ' . $e->getMessage());
}

// Fallback: scan blog/ directory if DB returned nothing
if (count($entries) <= 2) {
    $blogDir = __DIR__ . '/blog';
    if (is_dir($blogDir)) {
        foreach (glob($blogDir . '/article-*.php') as $file) {
            $basename = basename($file, '.php');
            $entries[] = [
                'loc'        => $siteUrl . '/blog/' . htmlspecialchars($basename, ENT_XML1, 'UTF-8') . '.php',
                'lastmod'    => date('c', filemtime($file)),
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ];
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($entries as $u): ?>
  <url>
    <loc><?= $u['loc'] ?></loc>
    <lastmod><?= $u['lastmod'] ?></lastmod>
    <changefreq><?= $u['changefreq'] ?></changefreq>
    <priority><?= $u['priority'] ?></priority>
<?php   if ($u['loc'] === $siteUrl . '/'): ?>
    <xhtml:link rel="alternate" hreflang="he" href="<?= $siteUrl ?>/"/>
    <xhtml:link rel="alternate" hreflang="en" href="<?= $siteUrl ?>/en.php"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="<?= $siteUrl ?>/"/>
<?php   endif; ?>
  </url>
<?php endforeach; ?>
</urlset>
