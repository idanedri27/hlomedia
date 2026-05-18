<?php
/**
 * includes/schema.php
 *
 * Emits JSON-LD schema.org markup for SEO rich results.
 * Includes: Organization, LocalBusiness, WebSite (with SearchAction).
 *
 * Per-page additions (Article, BreadcrumbList, Service, FAQPage) can be set
 * BEFORE including by populating $page_schemas as an array of decoded arrays.
 *
 * Example for blog article:
 *   $page_schemas = [[
 *       '@context' => 'https://schema.org',
 *       '@type'    => 'Article',
 *       'headline' => 'Some title',
 *       ...
 *   ]];
 *   include __DIR__ . '/includes/schema.php';
 */

$cfg = require __DIR__ . '/site-config.php';

$page_schemas = $page_schemas ?? [];

$organization = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Organization',
    '@id'         => $cfg['site_url'] . '/#organization',
    'name'        => $cfg['business_name'],
    'alternateName' => $cfg['business_alt_name'],
    'url'         => $cfg['site_url'],
    'logo'        => $cfg['logo_url'],
    'image'       => $cfg['logo_url'],
    'email'       => $cfg['email'],
    'telephone'   => $cfg['phone_e164'],
    'address'     => [
        '@type'           => 'PostalAddress',
        'addressLocality' => $cfg['address_locality'],
        'addressCountry'  => $cfg['address_country'],
    ],
];
if (!empty($cfg['social'])) {
    $organization['sameAs'] = $cfg['social'];
}

$local_business = [
    '@context'    => 'https://schema.org',
    '@type'       => 'LocalBusiness',
    '@id'         => $cfg['site_url'] . '/#business',
    'name'        => $cfg['business_name'],
    'alternateName' => $cfg['business_alt_name'],
    'description' => 'חברת פיתוח תוכנה ושיווק דיגיטלי בתל אביב',
    'url'         => $cfg['site_url'],
    'telephone'   => $cfg['phone_e164'],
    'email'       => $cfg['email'],
    'image'       => $cfg['logo_url'],
    'logo'        => $cfg['logo_url'],
    'priceRange'  => $cfg['price_range'],
    'address'     => [
        '@type'           => 'PostalAddress',
        'addressLocality' => $cfg['address_locality'],
        'addressCountry'  => $cfg['address_country'],
    ],
    'geo'         => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $cfg['geo_lat'],
        'longitude' => $cfg['geo_lon'],
    ],
    'areaServed'  => [
        '@type' => 'Country',
        'name'  => 'Israel',
    ],
    'openingHoursSpecification' => [[
        '@type'     => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
        'opens'     => '09:00',
        'closes'    => '18:00',
    ]],
    'serviceType' => [
        'פיתוח אתרים',
        'מערכות CRM',
        'קידום אורגני SEO',
        'שיווק דיגיטלי',
        'דפי נחיתה',
    ],
];
if (!empty($cfg['social'])) {
    $local_business['sameAs'] = $cfg['social'];
}

$website = [
    '@context'      => 'https://schema.org',
    '@type'         => 'WebSite',
    '@id'           => $cfg['site_url'] . '/#website',
    'url'           => $cfg['site_url'],
    'name'          => $cfg['business_name'],
    'description'   => 'חברת פיתוח תוכנה ושיווק דיגיטלי בתל אביב',
    'inLanguage'    => 'he-IL',
    'publisher'     => ['@id' => $cfg['site_url'] . '/#organization'],
];

$schemas = array_merge([$organization, $local_business, $website], $page_schemas);

foreach ($schemas as $schema) {
    echo "\n<script type=\"application/ld+json\">";
    echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "</script>\n";
}
