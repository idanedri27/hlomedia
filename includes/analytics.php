<?php
/**
 * includes/analytics.php
 *
 * Drop into <head>:
 *   <?php include __DIR__ . '/includes/analytics.php'; ?>
 *
 * Wires up GA4 + Hotjar + automatic conversion event tracking for:
 *   - phone click  (tel:)
 *   - whatsapp click (wa.me / whatsapp.com)
 *   - email click (mailto:)
 *   - generic lead_form_submit (call window.trackLeadSubmit({source}) on submit)
 *
 * Replace the GA4 ID inside site-config.php if it ever changes.
 */

$cfg = require __DIR__ . '/site-config.php';
$ga4 = htmlspecialchars($cfg['ga4_id'], ENT_QUOTES, 'UTF-8');
$hj  = (int) $cfg['hotjar_id'];
$hjv = (int) $cfg['hotjar_sv'];
?>
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $ga4 ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= $ga4 ?>', {
    'page_path': window.location.pathname,
    'anonymize_ip': true
  });

  // Auto-track conversion events
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a[href^="tel:"]').forEach(function (link) {
      link.addEventListener('click', function () {
        gtag('event', 'phone_click', {
          event_category: 'engagement',
          event_label: link.getAttribute('href').replace('tel:', '')
        });
      });
    });

    document.querySelectorAll('a[href*="wa.me"], a[href*="whatsapp.com"]').forEach(function (link) {
      link.addEventListener('click', function () {
        gtag('event', 'whatsapp_click', { event_category: 'engagement' });
      });
    });

    document.querySelectorAll('a[href^="mailto:"]').forEach(function (link) {
      link.addEventListener('click', function () {
        gtag('event', 'email_click', {
          event_category: 'engagement',
          event_label: link.getAttribute('href').replace('mailto:', '')
        });
      });
    });

    // Hook called by main.js / contact form
    window.trackLeadSubmit = function (data) {
      data = data || {};
      gtag('event', 'lead_form_submit', {
        event_category: 'conversion',
        event_label: data.source || 'organic',
        value: 1
      });
    };
  });
</script>
<!-- End Google Analytics 4 -->

<!-- Hotjar -->
<script>
  (function (h, o, t, j, a, r) {
    h.hj = h.hj || function () { (h.hj.q = h.hj.q || []).push(arguments); };
    h._hjSettings = { hjid: <?= $hj ?>, hjsv: <?= $hjv ?> };
    a = o.getElementsByTagName('head')[0];
    r = o.createElement('script'); r.async = 1;
    r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
    a.appendChild(r);
  })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
</script>
<!-- End Hotjar -->
