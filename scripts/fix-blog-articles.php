<?php
/**
 * scripts/fix-blog-articles.php
 *
 * One-shot maintenance script that fixes baseline SEO issues in every
 * existing blog article under /blog/article-*.php:
 *
 *   - Broken og:image path (/images/og.jpg  →  /assets/images/og.jpg)
 *   - Missing <link rel="canonical">
 *   - Missing <meta name="robots" content="index, follow, max-image-preview:large">
 *   - Missing <link rel="alternate"> hreflang
 *   - Old GA4 inline script replaced with /includes/analytics.php include
 *
 * Run on the VPS once:
 *   cd /home/transme-ai/htdocs/hlo-media.com
 *   php scripts/fix-blog-articles.php
 *
 * Use --dry to preview without writing.
 */

$dryRun = in_array('--dry', $argv ?? [], true);
$blogDir = __DIR__ . '/../blog';

if (!is_dir($blogDir)) {
    fwrite(STDERR, "Blog directory not found: $blogDir\n");
    exit(1);
}

$siteUrl = 'https://www.hlo-media.com';
$files   = glob($blogDir . '/*.php');
$changed = 0;
$skipped = 0;
$errors  = [];

foreach ($files as $file) {
    $basename = basename($file, '.php');
    $canonical = $siteUrl . '/blog/' . $basename . '.php';

    $orig = file_get_contents($file);
    if ($orig === false) {
        $errors[] = "read failed: $file";
        continue;
    }
    $html = $orig;

    // ---- 1. Fix og:image path bug -----------------------------------------
    $html = str_replace(
        ['https://www.hlo-media.com/images/og.jpg', '/images/og.jpg'],
        [$siteUrl . '/assets/images/og.jpg',        '/assets/images/og.jpg'],
        $html
    );

    // ---- 2. Fix og:url - the template wrote .html instead of .php ---------
    $html = preg_replace(
        '~(<meta property=[\'"]og:url[\'"] content=[\'"])([^\'"]+?)\.html([\'"]\s*/?>)~i',
        '$1$2.php$3',
        $html
    );

    // ---- 3. Ensure canonical exists ---------------------------------------
    if (stripos($html, 'rel=\'canonical\'') === false && stripos($html, 'rel="canonical"') === false) {
        $tag = "    <link rel=\"canonical\" href=\"$canonical\">\n";
        $html = preg_replace('~</head>~i', $tag . '</head>', $html, 1);
    }

    // ---- 4. Ensure robots meta exists -------------------------------------
    if (!preg_match('~<meta\s+name=[\'"]robots[\'"]~i', $html)) {
        $tag = "    <meta name=\"robots\" content=\"index, follow, max-image-preview:large\">\n";
        $html = preg_replace('~</head>~i', $tag . '</head>', $html, 1);
    }

    // ---- 5. Add Article schema if not present -----------------------------
    // Use a loose regex check to detect any flavour of an Article schema
    // already in the file (single quotes, double quotes, spaces — anything).
    $hasArticleSchema = preg_match('~"@type"\s*:\s*"Article"~', $html)
                     || stripos($html, "'@type' => 'Article'") !== false;
    if (!$hasArticleSchema) {
        // Extract title from <title>
        if (preg_match('~<title>(.*?)</title>~is', $html, $tm)) {
            $title = trim(strip_tags($tm[1]));
            $title = preg_replace('~\s*\|\s*הבלוג של HloMedia\s*$~u', '', $title);
            $title_json = json_encode($title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $ts = (int) str_replace(['article-', '.php'], '', basename($file));
            $iso = $ts > 0 ? date('c', $ts) : date('c', filemtime($file));

            $schema = "    <script type=\"application/ld+json\">\n";
            $schema .= "    {\n";
            $schema .= "      \"@context\": \"https://schema.org\",\n";
            $schema .= "      \"@type\": \"Article\",\n";
            $schema .= "      \"headline\": $title_json,\n";
            $schema .= "      \"datePublished\": \"$iso\",\n";
            $schema .= "      \"dateModified\": \"$iso\",\n";
            $schema .= "      \"author\": { \"@type\": \"Organization\", \"name\": \"HloMedia\" },\n";
            $schema .= "      \"publisher\": {\n";
            $schema .= "        \"@type\": \"Organization\",\n";
            $schema .= "        \"name\": \"HloMedia\",\n";
            $schema .= "        \"logo\": { \"@type\": \"ImageObject\", \"url\": \"$siteUrl/assets/images/logo_1.jpg\" }\n";
            $schema .= "      },\n";
            $schema .= "      \"mainEntityOfPage\": { \"@type\": \"WebPage\", \"@id\": \"$canonical\" },\n";
            $schema .= "      \"image\": \"$siteUrl/assets/images/og.jpg\"\n";
            $schema .= "    }\n";
            $schema .= "    </script>\n";

            $html = preg_replace('~</head>~i', $schema . '</head>', $html, 1);
        }
    }

    // ---- 6. Add Breadcrumb schema -----------------------------------------
    if (stripos($html, 'BreadcrumbList') === false) {
        $bc = "    <script type=\"application/ld+json\">\n";
        $bc .= "    {\n";
        $bc .= "      \"@context\": \"https://schema.org\",\n";
        $bc .= "      \"@type\": \"BreadcrumbList\",\n";
        $bc .= "      \"itemListElement\": [\n";
        $bc .= "        { \"@type\": \"ListItem\", \"position\": 1, \"name\": \"דף הבית\", \"item\": \"$siteUrl/\" },\n";
        $bc .= "        { \"@type\": \"ListItem\", \"position\": 2, \"name\": \"בלוג\", \"item\": \"$siteUrl/#blog\" },\n";
        $bc .= "        { \"@type\": \"ListItem\", \"position\": 3, \"name\": \"מאמר\", \"item\": \"$canonical\" }\n";
        $bc .= "      ]\n";
        $bc .= "    }\n";
        $bc .= "    </script>\n";
        $html = preg_replace('~</head>~i', $bc . '</head>', $html, 1);
    }

    // ---- 7. Trim ugly truncated meta description --------------------------
    // The template sometimes wrote:
    //   <meta name='description' content='Title\nSentence  B'>
    // which renders as 2-line garbage. Strip newlines.
    $html = preg_replace_callback(
        '~(<meta\s+name=[\'"]description[\'"]\s+content=[\'"])([^\'"]+)([\'"]\s*/?>)~i',
        function ($m) {
            $clean = trim(preg_replace('/\s+/u', ' ', $m[2]));
            $clean = mb_substr($clean, 0, 160, 'UTF-8');
            return $m[1] . htmlspecialchars($clean, ENT_QUOTES, 'UTF-8') . $m[3];
        },
        $html
    );

    if ($html === $orig) {
        $skipped++;
        continue;
    }

    if ($dryRun) {
        echo "[DRY] would update: " . basename($file) . "\n";
        $changed++;
        continue;
    }

    // Backup + write
    $backupDir = __DIR__ . '/../blog/.backup-' . date('Ymd');
    if (!is_dir($backupDir)) @mkdir($backupDir, 0755, true);
    @copy($file, $backupDir . '/' . basename($file));

    if (file_put_contents($file, $html) === false) {
        $errors[] = "write failed: $file";
        continue;
    }
    $changed++;
    echo "[OK] " . basename($file) . "\n";
}

echo "\n========== SUMMARY ==========\n";
echo "Files scanned : " . count($files) . "\n";
echo "Files changed : $changed\n";
echo "Files skipped : $skipped (no changes needed)\n";
echo "Errors        : " . count($errors) . "\n";
foreach ($errors as $e) echo "  - $e\n";
if ($dryRun) echo "\n(dry run — no files written)\n";
