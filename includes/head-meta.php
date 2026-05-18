<?php
/**
 * includes/head-meta.php
 *
 * Renders all <head> SEO tags: charset, viewport, description, keywords,
 * canonical, robots, Open Graph, Twitter Card, hreflang.
 *
 * Usage:
 *   // Optional per-page overrides (set BEFORE including):
 *   $page_meta = [
 *       'title'       => 'Page Title | HloMedia',
 *       'description' => 'page-specific description',
 *       'keywords'    => 'page-specific, keywords',
 *       'og_image'    => 'https://www.hlo-media.com/assets/images/...',
 *       'canonical'   => 'https://www.hlo-media.com/full/path.php',
 *       'robots'      => 'index, follow',
 *       'lang'        => 'he', // or 'en'
 *       'hreflang'    => [ 'he' => 'https://...', 'en' => 'https://...' ],
 *   ];
 *   include __DIR__ . '/includes/head-meta.php';
 */

$cfg = require __DIR__ . '/site-config.php';

$page_meta = $page_meta ?? [];

$lang        = $page_meta['lang']        ?? 'he';
$title       = $page_meta['title']       ?? $cfg['default_title'];
$description = $page_meta['description'] ?? $cfg['default_description'];
$keywords    = $page_meta['keywords']    ?? $cfg['default_keywords'];
$og_image    = $page_meta['og_image']    ?? $cfg['default_og_image'];
$robots      = $page_meta['robots']      ?? 'index, follow, max-image-preview:large';

// Auto-build canonical from REQUEST_URI if not provided (strips query string)
if (!empty($page_meta['canonical'])) {
    $canonical = $page_meta['canonical'];
} else {
    $request_path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    // Normalise /index.php -> /
    if ($request_path === '/index.php') {
        $request_path = '/';
    }
    $canonical = rtrim($cfg['site_url'], '/') . $request_path;
}

// Trim description to a sane length (<= 160 chars for Google)
$description = mb_substr(trim(preg_replace('/\s+/u', ' ', $description)), 0, 160, 'UTF-8');

$esc = function ($v) {
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $esc($description) ?>">
<meta name="keywords" content="<?= $esc($keywords) ?>">
<meta name="author" content="HloMedia">
<meta name="robots" content="<?= $esc($robots) ?>">
<meta name="theme-color" content="#1e2a78">

<link rel="canonical" href="<?= $esc($canonical) ?>">

<?php if (!empty($page_meta['hreflang']) && is_array($page_meta['hreflang'])): ?>
<?php   foreach ($page_meta['hreflang'] as $hl_lang => $hl_url): ?>
<link rel="alternate" hreflang="<?= $esc($hl_lang) ?>" href="<?= $esc($hl_url) ?>">
<?php   endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= $esc($cfg['site_url']) ?>">
<?php endif; ?>

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="HloMedia">
<meta property="og:locale" content="<?= $lang === 'en' ? 'en_US' : 'he_IL' ?>">
<meta property="og:title" content="<?= $esc($title) ?>">
<meta property="og:description" content="<?= $esc($description) ?>">
<meta property="og:image" content="<?= $esc($og_image) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= $esc($canonical) ?>">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $esc($title) ?>">
<meta name="twitter:description" content="<?= $esc($description) ?>">
<meta name="twitter:image" content="<?= $esc($og_image) ?>">

<title><?= $esc($title) ?></title>
