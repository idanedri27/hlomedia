<?php
/**
 * includes/site-config.php
 *
 * Central configuration for SEO + analytics + business info.
 * Edit here ONCE -> change propagates to every page that includes the
 * head-meta / schema / analytics partials.
 */

return [
    // ---- Business identity (used by schema, footer, contact) -------------
    'business_name'      => 'HloMedia - הלו מדיה',
    'business_legal'     => 'הלו-מדיה',
    'business_alt_name'  => 'Hlo-Media',
    'phone_e164'         => '+972526814747',
    'phone_display'      => '052-681-4747',
    'email'              => 'hlomedia.office@gmail.com',
    'whatsapp_intl'      => '972526814747',
    'address_locality'   => 'תל אביב',
    'address_country'    => 'IL',
    'geo_lat'            => '32.0853',
    'geo_lon'            => '34.7818',
    'price_range'        => '₪₪',

    // ---- Site identity ---------------------------------------------------
    'site_url'           => 'https://www.hlo-media.com',
    'default_og_image'   => 'https://www.hlo-media.com/assets/images/og.jpg',
    'logo_url'           => 'https://www.hlo-media.com/assets/images/logo_1.jpg',

    // ---- Analytics -------------------------------------------------------
    'ga4_id'             => 'G-FDD7G7KZ8W',
    'hotjar_id'          => 5155834,
    'hotjar_sv'          => 6,

    // ---- SEO defaults (override per-page via $page_meta) -----------------
    'default_title'      => 'הלו מדיה - חברת תוכנה ושיווק דיגיטלי בתל אביב | פיתוח אתרים, CRM, SEO',
    'default_description'=> 'חברת תוכנה בתל אביב המתמחה בפיתוח אתרים, מערכות CRM ו-SEO. ליווי אישי, פתרונות מותאמים אישית, הצעת מחיר חינם. ☎ 052-681-4747',
    'default_keywords'   => 'חברת תוכנה תל אביב, פיתוח אתרים, מערכת CRM, קידום אתרים, SEO, שיווק דיגיטלי, בניית אתרים, דפי נחיתה',

    // ---- Social profiles (fill in when ready) ---------------------------
    'social' => [
        // 'https://www.facebook.com/hlomedia',
        // 'https://www.instagram.com/hlomedia',
        // 'https://www.linkedin.com/company/hlomedia',
    ],
];
