<?php
/**
 * includes/attribution-capture.php
 *
 * Renders hidden form fields + a small JS snippet that captures
 * lead-attribution data (landing page, UTM, referrer) into the form
 * before it submits. Include this INSIDE every <form> that you want
 * tracked.
 *
 * Usage:
 *   <form id="contact" onsubmit="lead(event)">
 *      <?php include __DIR__ . '/includes/attribution-capture.php'; ?>
 *      ... rest of form ...
 *   </form>
 */
?>
<!-- Lead attribution hidden fields -->
<input type="hidden" name="landing_page"      id="hlm_landing_page"      value="">
<input type="hidden" name="original_referrer" id="hlm_original_referrer" value="">
<input type="hidden" name="utm_source"        id="hlm_utm_source"        value="">
<input type="hidden" name="utm_medium"        id="hlm_utm_medium"        value="">
<input type="hidden" name="utm_campaign"      id="hlm_utm_campaign"      value="">
<input type="hidden" name="utm_term"          id="hlm_utm_term"          value="">
<input type="hidden" name="utm_content"       id="hlm_utm_content"       value="">
<input type="hidden" name="search_query"      id="hlm_search_query"      value="">

<!-- Honeypot anti-spam: bots fill this, humans don't see it -->
<input type="text" name="hp_website" tabindex="-1" autocomplete="off"
       style="position:absolute;left:-9999px;opacity:0;height:0;width:0;pointer-events:none" aria-hidden="true">

<script>
  (function () {
    var $ = function (id) { return document.getElementById(id); };
    if (!$('hlm_landing_page')) return;

    // First-touch attribution: persist UTM from initial landing
    var STORAGE_KEY = 'hlm_attr_v1';
    var stored = {};
    try { stored = JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}'); } catch (e) {}

    var params = new URLSearchParams(window.location.search);
    ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function (k) {
      var fresh = params.get(k);
      if (fresh) stored[k] = fresh;
      var el = $('hlm_' + k);
      if (el) el.value = stored[k] || '';
    });

    // Landing page (this page) + original referrer
    $('hlm_landing_page').value      = window.location.href;
    $('hlm_original_referrer').value = stored.original_referrer || document.referrer || 'direct';

    // First time? cache referrer so multi-page sessions still know origin
    if (!stored.original_referrer) {
      stored.original_referrer = document.referrer || 'direct';
    }

    // If referrer is Google search, try to extract query (rare these days, but free signal)
    var m = (document.referrer || '').match(/[?&]q=([^&]+)/);
    if (m) {
      try { $('hlm_search_query').value = decodeURIComponent(m[1]); } catch (e) {}
    }

    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(stored));
  })();
</script>
