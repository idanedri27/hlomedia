<?php
/**
 * server/seo-platform-config.php
 *
 * Central SEO Platform integration config.
 * Edit values via environment variables, not in this file.
 *
 * The api_key here is the client-side key for HloMedia specifically.
 * It is intentionally not fully secret (visible in Network tab of any
 * lead form submission) - it only authorizes posting leads to
 * HloMedia's bucket on the central platform, nothing more.
 */

return [
    'api_url'         => getenv('SEO_PLATFORM_API_URL') ?: 'https://api.hlo-media.com/lead.php',
    'api_key'         => getenv('SEO_PLATFORM_API_KEY') ?: '8c98de1b4d58a72e442b54bf6b68caf20be43b1e4c1541835cb3e8f29327fc2c',
    'timeout'         => (int) (getenv('SEO_PLATFORM_TIMEOUT') ?: 10),
    'fallback_email'  => getenv('SEO_PLATFORM_FALLBACK_EMAIL') ?: 'idanedri27@gmail.com',
    'site_slug'       => 'hlomedia',
];
