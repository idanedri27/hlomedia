<?php
// Detect language from referring page (en.php → English, else Hebrew)
$referer = isset($_SERVER['HTTP_REFERER']) ? basename($_SERVER['HTTP_REFERER']) : '';
$isEnglish = ($referer === 'en.php');

$page_meta = [
    'lang'        => $isEnglish ? 'en' : 'he',
    'title'       => $isEnglish ? 'Thank you - HloMedia' : 'תודה - הלו מדיה',
    'description' => $isEnglish
        ? 'Thank you for contacting HloMedia - software development & digital marketing agency. We will get back to you soon.'
        : 'תודה על פנייתך להלו מדיה - חברת תוכנה ושיווק דיגיטלי בתל אביב. נחזור אליך בהקדם.',
    'robots'      => 'noindex, nofollow',
    'canonical'   => 'https://www.hlo-media.com/thanks.php',
];
?>
<!DOCTYPE html>
<html lang="<?= $isEnglish ? 'en' : 'he' ?>">

<head>
    <?php include __DIR__ . '/includes/head-meta.php'; ?>
    <?php include __DIR__ . '/includes/analytics.php'; ?>

    <script>
      // Conversion event - thank-you page is the confirmed lead funnel exit
      window.addEventListener('load', function () {
        if (typeof gtag !== 'undefined') {
          gtag('event', 'lead_conversion_complete', {
            event_category: 'conversion',
            event_label: 'thanks_page',
            value: 1
          });
        }
      });
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-digimedia-v1.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/thanks.css">
    <link rel="stylesheet" href="assets/css/ui-enhance.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.png">
</head>

<body>
    <?php
    if ($isEnglish) {
        $thankYouMessage = "Thank you!";
        $contactMessage  = "We will get back to you soon.";
        $returnToSite    = "Return to the website";
        $footerMessage   = "All rights reserved to HloMedia. © " . date("Y");
        $langLink        = '<li><a href="./index.php" class="lang" aria-label="Switch to Hebrew">HE</a></li>';
    } else {
        $thankYouMessage = "! תודה רבה";
        $contactMessage  = "אנו ניצור איתך קשר בהקדם";
        $returnToSite    = "חזרה לאתר";
        $footerMessage   = "כל הזכויות שמורות ל הלו-מדיה. © " . date("Y");
        $langLink        = '<li title="שנה שפה לאנגלית"><a href="./en.php" class="lang" aria-label="Switch to English">EN</a></li>';
    }
    ?>
    <!-- Pre-header Starts -->
    <div class="pre-header">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-lg-8 col-sm-8 col-6">
                    <ul class="info">
                        <li>
                            <a href="mailto:hlomedia.office@gmail.com" aria-label="Send email to hlomedia.office@gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span class="text">hlomedia.office@gmail.com</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+972526814747" aria-label="Call 052-681-4747">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span class="text">052-681-4747</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-5">
                    <ul class="social-media">
                        <?= $langLink ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="login-main-wrapper">
      <div class="main-container">
          <div class="login-process">
              <div class="login-main-container">
                  <div class="thankyou-wrapper">
                      <h1><?php echo $thankYouMessage; ?></h1>
                      <p><?php echo $contactMessage; ?></p>
                      <a href="./index.php"><?php echo $returnToSite; ?></a>
                      <div class="clr"></div>
                  </div>
                  <div class="clr"></div>
              </div>
          </div>
          <div class="clr"></div>
      </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $footerMessage; ?></p>
                </div>
            </div>
        </div>
    </footer>
</body>
