<?php
/**
 * test-og-metadata.php
 *
 * Test script to verify the language-aware OG image selection logic.
 * Run this on the live server to validate metadata output.
 *
 * Usage:
 *   php test-og-metadata.php
 */

echo "=== OG Metadata Test ===\n\n";

// Simulate different page scenarios
$test_cases = [
    [
        'name' => 'Hebrew page (default)',
        'page_meta' => ['lang' => 'he'],
        'expected_image' => 'assets/hl/og-he.jpg',
    ],
    [
        'name' => 'English page',
        'page_meta' => ['lang' => 'en'],
        'expected_image' => 'assets/hl/og-en.jpg',
    ],
    [
        'name' => 'Page with custom OG image',
        'page_meta' => [
            'lang' => 'he',
            'og_image' => 'https://www.hlo-media.com/custom.jpg',
        ],
        'expected_image' => 'custom.jpg',
    ],
    [
        'name' => 'No lang specified (defaults to Hebrew)',
        'page_meta' => [],
        'expected_image' => 'assets/hl/og-he.jpg',
    ],
];

foreach ($test_cases as $test) {
    echo "Testing: {$test['name']}\n";
    
    $page_meta = $test['page_meta'];
    $cfg = require __DIR__ . '/includes/site-config.php';
    
    $lang = $page_meta['lang'] ?? 'he';
    
    // Logic from head-meta.php
    if (!empty($page_meta['og_image'])) {
        $og_image = $page_meta['og_image'];
    } elseif ($lang === 'en' && !empty($cfg['og_image_en'])) {
        $og_image = $cfg['og_image_en'];
    } elseif ($lang === 'he' && !empty($cfg['og_image_he'])) {
        $og_image = $cfg['og_image_he'];
    } else {
        $og_image = $cfg['default_og_image'];
    }
    
    echo "  Result: $og_image\n";
    echo "  Expected: {$test['expected_image']}\n";
    $match = strpos($og_image, $test['expected_image']) !== false ? '✓' : '✗';
    echo "  Status: $match\n\n";
}

echo "=== Test Complete ===\n";
echo "\nIMPORTANT: Ensure the following image files exist on the live server:\n";
echo "  - assets/hl/og-he.jpg (1200x630px, Hebrew/default)\n";
echo "  - assets/hl/og-en.jpg (1200x630px, English)\n";
echo "\nThese images should be copied from the current live site.\n";
