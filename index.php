<?php
$page_meta = [
    'lang'        => 'he',
    'title'       => 'הלו מדיה - חברת תוכנה ושיווק דיגיטלי בתל אביב | פיתוח אתרים, CRM, SEO',
    'description' => 'חברת תוכנה בתל אביב המתמחה בפיתוח אתרים, מערכות CRM וקידום אורגני SEO. ליווי אישי, פתרונות מותאמים, הצעת מחיר חינם. ☎ 052-681-4747',
    'keywords'    => 'חברת תוכנה תל אביב, פיתוח אתרים, מערכת CRM, קידום אתרים, SEO, שיווק דיגיטלי, בניית אתרים, דפי נחיתה, הלו מדיה',
    'canonical'   => 'https://www.hlo-media.com/',
    'hreflang'    => [
        'he'    => 'https://www.hlo-media.com/',
        'en'    => 'https://www.hlo-media.com/en.php',
    ],
];
?>
<!DOCTYPE html>
<html lang="he">

<head>
    <?php include __DIR__ . '/includes/head-meta.php'; ?>
    <?php include __DIR__ . '/includes/analytics.php'; ?>

    <!-- Geo redirect: non-IL visitors go to /en.php (skipped for bots & users with ?nogeo=1) -->
    <script>
    (function () {
      var ua = navigator.userAgent || '';
      var isBot = /bot|crawl|spider|slurp|bingpreview|googlebot|yandex|duckduckbot|baiduspider/i.test(ua);
      var skip  = location.search.indexOf('nogeo=1') !== -1 || sessionStorage.getItem('hlm_geo_done');
      if (isBot || skip) return;
      fetch('https://ipinfo.io/json?token=08515a51bbad4c')
        .then(function (r) { return r.json(); })
        .then(function (d) {
          sessionStorage.setItem('hlm_geo_done', '1');
          if (d && d.country && d.country !== 'IL') {
            window.location.replace('./en.php');
          }
        })
        .catch(function () { /* silent fallback */ });
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-digimedia-v1.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/ui-enhance.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.png">

    <!-- Schema.org JSON-LD (Organization + LocalBusiness + WebSite + FAQ + Services) -->
    <?php
    $page_schemas = [
        [
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name'  => 'כמה עולה לבנות אתר אינטרנט?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'מחיר בניית אתר אצלנו תלוי בסוג האתר: אתר תדמית מתחיל מ-₪3,000, אתר חנות (E-commerce) מ-₪7,000, ופלטפורמה מותאמת אישית מתומחרת לפי אפיון. אנחנו מספקים הצעת מחיר חינם וללא התחייבות.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'מה זה מערכת CRM ולמה אני צריך אחת?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'מערכת CRM (Customer Relationship Management) מאפשרת לנהל לידים, לקוחות ותהליכי מכירה במקום אחד. אנחנו בונים מערכות CRM מותאמות אישית לכל עסק, עם אוטומציות לוואטסאפ, מייל, וSMS - חוסכים זמן ומגדילים סגירות.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'כמה זמן לוקח עד שרואים תוצאות מ-SEO?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'בדרך כלל לוקח 3-6 חודשים לראות התקדמות משמעותית בדירוגים האורגניים בגוגל, ו-6-12 חודשים לתוצאות יציבות. אנחנו מספקים דוחות חודשיים שמראים את ההתקדמות במילות מפתח רלוונטיות לעסק שלך.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'באיזה אזורים גיאוגרפיים אתם עובדים?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'המשרד שלנו ממוקם בתל אביב, אך אנחנו עובדים עם לקוחות בכל הארץ - ירושלים, חיפה, באר שבע, נתניה, ראשון לציון, ועוד. את רוב הפרויקטים אנחנו מנהלים מרחוק עם פגישות פיזיות לפי צורך.',
                    ],
                ],
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type'    => 'Service',
            'serviceType' => 'פיתוח אתרים',
            'provider' => ['@id' => 'https://www.hlo-media.com/#business'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Israel'],
            'description' => 'פיתוח אתרי תדמית, חנויות מקוונות, ודפי נחיתה רספונסיביים עם דגש על SEO וביצועים.',
        ],
        [
            '@context' => 'https://schema.org',
            '@type'    => 'Service',
            'serviceType' => 'בניית מערכות CRM',
            'provider' => ['@id' => 'https://www.hlo-media.com/#business'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Israel'],
            'description' => 'מערכות ניהול קשרי לקוחות מותאמות אישית עם אוטומציה של תהליכי מכירה ושירות.',
        ],
        [
            '@context' => 'https://schema.org',
            '@type'    => 'Service',
            'serviceType' => 'קידום אורגני (SEO)',
            'provider' => ['@id' => 'https://www.hlo-media.com/#business'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Israel'],
            'description' => 'קידום אתרים בגוגל באמצעות מחקר מילות מפתח, אופטימיזציה טכנית, ובניית קישורים איכותיים.',
        ],
    ];
    include __DIR__ . '/includes/schema.php';
    ?>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="tipsModal" tabindex="-1" aria-labelledby="tipsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header lang">
                    <button type="button" class="btn-close btn-close-white ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <h5 class="modal-title text-center mb-4" id="tipsModalLabel" dir="rtl">10 טיפים לשיפור הקידום האורגני</h5>

                    <ol class="list-group">
                        <li class="list-group-item">בצע מחקר מילות מפתח יסודי כדי להבין מה הלקוחות שלך מחפשים.</li>
                        <li class="list-group-item">אופטימיזציה של התוכן שלך למנועי חיפוש, כולל תיאורים, כותרות ותגי מטא.</li>
                        <li class="list-group-item">השתמש בתוכן איכותי ועשיר במידע שיענה על צורכי המשתמשים.</li>
                        <li class="list-group-item">ודא שהאתר שלך נטען במהירות ושהתמונות אופטימיזטיות.</li>
                        <li class="list-group-item">בנה קישורים פנימיים וחיצוניים כדי לשפר את סמכות הדומיין שלך.</li>
                        <li class="list-group-item">השתמש ברשתות החברתיות כדי לקדם את התוכן שלך ולמשוך מבקרים.</li>
                        <li class="list-group-item">בצע אופטימיזציה של האתר שלך למכשירים ניידים.</li>
                        <li class="list-group-item">עקוב אחר נתוני הביצועים של האתר שלך והשתמש בכלים כמו Google Analytics.</li>
                        <li class="list-group-item">פרסם תוכן חדש באופן קבוע כדי לשמור על האתר רלוונטי.</li>
                        <li class="list-group-item">הקפד על תיאורים עשירים ואטרקטיביים כדי לשפר את שיעור הקלקות.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>




<div id="loader">
    <img src="./assets/images/loader.gif" alt="Loading...">
</div>

    <!-- ***** Preloader Start ***** -->
    <!-- <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div> -->
    <!-- ***** Preloader End ***** -->

    <!-- Pre-header Starts -->
    <div class="pre-header">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-lg-8 col-sm-8 col-6">
                    <ul class="info">
                        <li>
                            <a href="mailto:hlomedia.office@gmail.com" aria-label="שליחת מייל ל-hlomedia.office@gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span class="text">hlomedia.office@gmail.com</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+972526814747" aria-label="חיוג מהיר ל-052-681-4747">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span class="text">052-681-4747</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-5">
                    <ul class="social-media">
                        <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li> -->
                        <!-- <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li> -->
                        <?php
                        echo (basename($_SERVER['PHP_SELF']) === 'index.php')
                            ? '<li title="שנה שפה לאנגלית"><a href="./en.php" class="lang">EN</a></li>'
                            : '<li><a href="./index.php" class="lang">HE</a></li>';
                        ?>


                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-header End -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky wow slideInDown">
        <div class="container">
            <div class="row">
                <div class="col-12 p-0">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo" aria-label="הלו מדיה - דף הבית">
                            <img src="assets/images/logo_1.jpg" alt="הלו מדיה - חברת תוכנה ושיווק דיגיטלי" id="logo">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section">
                                <a href="#blog"> הבלוג שלנו <i class="fa fa-solid fa-comment text-secondary" aria-hidden="true"></i></a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#free-quote">סרטון הסבר <i class="fa fa-solid fa-film text-secondary"></i> </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#portfolio"> פרויקטים <i class="fa fas fa-briefcase text-secondary"></i></a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#services"> שירותים שלנו <i class="fa fas fa-cogs text-secondary"></i></a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#about"> מי אנחנו <i class="fa fas fa-users text-secondary"></i></a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#top" class="active"> דף הבית <i class="fa fas fa-home text-secondary"></i></a>
                            </li>
                            <li class="scroll-to-section">
                                <div class="border-first-button"><a href="#contact"> צרו קשר </a></div>
                            </li>
                        </ul>

                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <div class="main-banner wow fadeIn" id="top">
        <div class="container">
            <div class="row m-0" dir="rtl">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 align-self-center ps-md-5">
                            <div class="left-content show-up header-text wow "
                                data-wow-delay="1s">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <h4 class="pink title">פתרונות תוכנה מתקדמים</h4>
                                        <h2 class=""> מקדמים את הנוכחות הדיגיטלית שלך באינטרנט</h2>
                                            <p class="px-0 mx-0 txt"> חברת תוכנה מתקדמת המתמחה בפיתוח פתרונות טכנולוגיים, כולל פיתוח תוכנה, CRM, בניית אתרים, קידום אורגני SEO ושיווק דיגיטלי, המותאמות אישית לצרכי הלקוחות.</p>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="border-first-button scroll-to-section">
                                            <a href="#contact"> צרו קשר</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 pe-md-5">
                            <div class="right-image wow fadeInLeft">
                                <img src="assets/images/slider-dec.png" alt="פיתוח תוכנה ושיווק דיגיטלי בהלו מדיה" loading="eager" fetchpriority="high">
                                <!-- <div id="video" style="position: relative; overflow: hidden; aspect-ratio: 1920/1080"><iframe src="https://share.synthesia.io/embeds/videos/67cbaada-9ac4-44c9-8d7b-8ab238ed9d59" loading="lazy" title="Synthesia video player - ברוכים הבאים להלו מדיה" allow="fullscreen"  style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0; overflow:hidden;"></iframe></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="about section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1">
                            <div class="about-left-image  wow fadeInLeft">
                                <img src="assets/images/about-dec.png" alt="צוות הלו מדיה - פיתוח אתרים, CRM ו-SEO בתל אביב" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-6 align-self-center order-1 order-lg-2 wow fadeInRight">
                            <div class="about-right-content" dir="rtl">
                                <div class="section-heading">
                                    <h6> קצת עלינו</h6>
                                    <h4> במה חברת <em> הלו מדיה </em> מתמחה ?</h4>
                                    <div class="line-dec"></div>
                                </div>
                                <p class="txt">אנו מתמחים ביצירת פתרונות דיגיטליים מתקדמים לעסקים. אנו מספקים שירותי
                                    בניית אתרים, מערכות CRM בהתאמה אישית, קידום אורגני SEO, ושיווק דיגיטלי המותאם לצרכי
                                    כל לקוח. בואו לגלות כיצד נוכל למנף את ההצלחה העסקית שלכם.</p>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4">
                                        <div class="skill-item first-skill-item wow fadeIn">
                                            <div class="progress" data-percentage="100">
                                                <span class="progress-left">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <span class="progress-right">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <div class="progress-value">
                                                    <div>
                                                        100%<br>
                                                        <span>מערכות crm</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4">
                                        <div class="skill-item second-skill-item wow fadeIn">
                                            <div class="progress" data-percentage="100">
                                                <span class="progress-left">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <span class="progress-right">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <div class="progress-value">
                                                    <div>
                                                        100%<br>
                                                        <span> בניית אתרים</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4">
                                        <div class="skill-item third-skill-item wow fadeIn">
                                            <div class="progress" data-percentage="100">
                                                <span class="progress-left">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <span class="progress-right">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <div class="progress-value">
                                                    <div>
                                                        100%<br>
                                                        <span>שיווק דיגיטלי</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="services" class="services section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading  wow fadeInDown">
                        <h6>השירותים שלנו </h6>
                        <h4>פתרונות דיגיטליים בהתאמה אישית </h4>
                        <div class="line-dec"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="naccs">
                        <div class="grid">
                            <div class="row" dir="rtl">
                                <div class="col-lg-12">
                                    <div class="menu">
                                        <div class="active">
                                            <div class="thumb">
                                                <span class="icon"><img src="assets/images/service-icon-01.png"
                                                        alt="בניית אתרים מקצועיים" loading="lazy"></span>
                                                אתרים
                                            </div>
                                        </div>
                                        <div>
                                            <div class="thumb">
                                                <span class="icon"><img src="assets/images/service-icon-02.png"
                                                        alt="פיתוח מערכות CRM בהתאמה אישית" loading="lazy"></span>
                                                CRM
                                            </div>
                                        </div>



                                        <div class="thumb">
                                            <div class="thumb">
                                                <span class="icon"><img src="assets/images/service-icon-02.png"
                                                        alt="שיווק דיגיטלי לעסקים" loading="lazy"></span>
                                                שיווק דיגיטלי
                                            </div>
                                        </div>
                                        <div>
                                            <div class="thumb">
                                                <span class="icon"><img src="assets/images/service-icon-04.png"
                                                        alt="דפי נחיתה ממירים" loading="lazy"></span>
                                                דפי נחיתה
                                            </div>
                                        </div>


                                        <div>
                                            <div class="thumb">
                                                <span class="icon"><img src="assets/images/service-icon-03.png"
                                                        alt="קידום אורגני SEO בגוגל" loading="lazy"></span>
                                                קידום אורגני
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" dir="ltr">
                                    <ul class="nacc">

                                        <li class="active">
                                            <div>
                                                <div class="thumb">
                                                    <div class="row">
                                                        <div class="col-lg-6 align-self-center">
                                                            <div class="left-text" dir="rtl">
                                                                <img src="assets/images/website.jpg" alt="פיתוח אתרים רספונסיביים - הלו מדיה תל אביב" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 align-self-center text-end" dir="rtl">
                                                            <div class="right-image">
                                                                <h4>אתרים</h4>
                                                                <p>אנו מתמחים ביצירת אתרי אינטרנט מרהיבים, המשלבים עיצוב
                                                                    ייחודי עם חוויית משתמש מעולה. צוות המומחים שלנו דואג
                                                                    לפתח אתרים מותאמים אישית שמתאימים לצרכים של כל לקוח.
                                                                </p>
                                                                <div class="p-2">
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> עיצוב
                                                                        רספונסיבי</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> קידום
                                                                        אתרים (SEO)</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        פתרונות תוכן מתקדמים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        טכנולוגיות מתקדמות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניתוח
                                                                        ביצועים מתמיד</div>
                                                                </div>
                                                                <p>בין אם אתה זקוק לאתר תדמית, חנות מקוונת או פלטפורמה
                                                                    מורכבת, אנו כאן כדי ללוות אותך בתהליך ולספק פתרונות
                                                                    חדשניים.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div>
                                                <div class="thumb">
                                                    <div class="row">
                                                        <div class="col-lg-6 align-self-center">
                                                            <div class="left-text" dir="rtl">
                                                                <img src="assets/images/crm.jpg" alt="מערכת CRM בהתאמה אישית לעסקים" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 align-self-center text-end" dir="rtl">
                                                            <div class="right-image">
                                                                <h4>CRM</h4>
                                                                <p>פתרונות ניהול קשרי לקוחות (CRM) שלנו מאפשרים לך לנהל
                                                                    את הקשרים עם לקוחותיך בצורה חכמה ויעילה. המערכות
                                                                    שלנו מותאמות אישית כדי לסייע לך להפיק את המקסימום
                                                                    מהקשרים העסקיים שלך.</p>
                                                                <div class="p-2">
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניהול
                                                                        לידים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אוטומציה של תהליכים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניתוח
                                                                        נתוני לקוחות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> תמיכה
                                                                        ושירות לקוחות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אינטגרציה עם מערכות אחרות</div>
                                                                </div>
                                                                <p>עם פתרונות CRM שלנו, תוכל לשפר את חוויית הלקוח ולמקסם
                                                                    את הפוטנציאל העסקי שלך.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div>
                                                <div class="thumb">
                                                    <div class="row">
                                                        <div class="col-lg-6 align-self-center">
                                                            <div class="left-text" dir="rtl">
                                                                <img src="assets/images/marketing.png" alt="שיווק דיגיטלי וקמפיינים ממומנים בגוגל ופייסבוק" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 align-self-center text-end" dir="rtl">
                                                            <div class="right-image">
                                                                <h4>שיווק דיגיטלי</h4>
                                                                <p>אנו מציעים פתרונות שיווק דיגיטליים מותאמים אישית,
                                                                    הממוקדים בהגדלת החשיפה ובשיפור המכירות של העסק שלך.
                                                                </p>
                                                                <div class="p-2">
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> תכנון
                                                                        אסטרטגי</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניתוח
                                                                        נתונים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אופטימיזציה של SEO</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניהול
                                                                        קמפיינים ממומנים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> שיווק
                                                                        ברשתות חברתיות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> תכנון
                                                                        תוכן שיווקי</div>
                                                                </div>
                                                                <p>כל שירות שלנו מתוכנן בקפידה כדי להביא תוצאות מדידות
                                                                    ולמקסם את הערך העסקי שלך.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div>
                                                <div class="thumb">
                                                    <div class="row">
                                                        <div class="col-lg-6 align-self-center">
                                                            <div class="left-text" dir="rtl">
                                                                <img src="assets/images/business.png" alt="עיצוב דפי נחיתה ממירים לקמפיינים שיווקיים" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 align-self-center text-end" dir="rtl">
                                                            <div class="right-image">
                                                                <h4>דפי נחיתה</h4>
                                                                <p>הצוות שלנו מתמחה ביצירת דפי נחיתה ממירים, אשר מעוצבים
                                                                    במיוחד כדי למשוך את תשומת הלב של המשתמשים ולהניע
                                                                    אותם לפעולה. דפי הנחיתה שלנו מותאמים אישית לכל
                                                                    קמפיין ומטרות שיווקיות.</p>
                                                                <div class="p-2">
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> עיצוב
                                                                        שמושך תשומת לב</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> קריאות
                                                                        לפעולה ברורות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניתוח
                                                                        ביצועים ומדידה</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אופטימיזציה להמרות</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אינטגרציה עם כלים שיווקיים</div>
                                                                </div>
                                                                <p>באמצעות דפי הנחיתה שלנו, תוכל לשפר את שיעור ההמרה שלך
                                                                    ולהשיג תוצאות מדידות בכל קמפיין.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>


                                        <li>
                                            <div>
                                                <div class="thumb">
                                                    <div class="row">
                                                        <div class="col-lg-6 align-self-center">
                                                            <div class="left-text" dir="rtl">
                                                                <img src="assets/images/seo.png" alt="קידום אתרים אורגני SEO בגוגל - שיטות מתקדמות" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 align-self-center text-end" dir="rtl">
                                                            <div class="right-image">
                                                                <h4>קידום אורגני</h4>
                                                                <p>אנו מציעים שירותי קידום אורגני (SEO) שיסייעו לך להגיע
                                                                    למקומות הראשונים בתוצאות החיפוש. הצוות שלנו מתמחה
                                                                    בשיטות מתקדמות ובניתוח מתמשך כדי להבטיח את הצלחתך
                                                                    ברשת.</p>
                                                                <div class="p-2">
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> מחקר
                                                                        מילות מפתח מעמיק</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        אופטימיזציה של תוכן</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i>
                                                                        קישורים נכנסים איכותיים</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> ניתוח
                                                                        תחרותי</div>
                                                                    <div class="my-2"><i
                                                                            class="fa fa-check text-success"></i> דוחות
                                                                        ביצועים מפורטים</div>
                                                                </div>
                                                                <p>באמצעות שירותי SEO שלנו, תוכל להגדיל את התנועה לאתר
                                                                    שלך ולמשוך לקוחות פוטנציאליים חדשים.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>



                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="free-quote" class="free-quote">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div id="video" style="position: relative; overflow: hidden; aspect-ratio: 1920/1080;border-radius:20px">
                        <video controls style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0; overflow: hidden;">
                            <source src="./assets/video/video.mp4" type="video/mp4">
                            הדפדפן שלך אינו תומך בניגון וידאו.
                        </video>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-4 offset-lg-4">
                    <div class="section-heading  wow fadeIn">
                    <!-- <h4> פתרונות מותאמים אישית</h4> -->
                    <h6>הצעת מחיר חינם, ללא התחייבות</h6>
                        <div class="line-dec"></div>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-2  wow fadeIn">
                    <form id="search" onsubmit="quotation(event)">
                        <div class="row">
                            <!-- <div class="col-lg-4 col-sm-4" dir="rtl">
                                <fieldset>
                                    <input type="web" name="web" class="website" placeholder="שם האתר אם קיים"
                                        autocomplete="on" required>
                                </fieldset>
                            </div> -->
                            <div class="col-lg-8 col-sm-8" dir="rtl">
                                <fieldset>
                                    <input class="border-none" type="address" id="mail" class="email" placeholder="אימייל">
                                </fieldset>
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <fieldset>
                                    <button type="submit" class="main-button" name="email_input_submit">  <h4>  לקבלת הצעת מחיר </h4>   </button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="portfolio" class="our-portfolio section">
        <div class="container">
            <div class="row" dir="rtl">
                <div class="col-lg-5 text-end">
                    <div class="section-heading wow fadeInRight">
                        <h6>בקרוב.. </h6>
                        <h4>מוזמנים לראות  <em>פרוייקטים </em> שלנו</h4>
                        <div class="line-dec"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid wow fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="loop owl-carousel">
                        <div class="item">
                            <!-- <a href="#"> -->
                                <div class="portfolio-item">
                                    <div class="thumb">
                                        <img src="assets/images/portfolio-01.jpg" alt="Academee - אתר לימודים שפיתחנו" loading="lazy">
                                    </div>
                                    <div class="down-content">
                                        <h4>academee</h4>
                                        <span>אתר לימודים</span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                        <div class="item">
                            <!-- <a href="#"> -->
                                <div class="portfolio-item">
                                    <div class="thumb">
                                        <img src="assets/images/portfolio-01.jpg" alt="Smart-Calc - מחשבון בניית בית שפיתחנו" loading="lazy">
                                    </div>
                                    <div class="down-content">
                                        <h4>smart-calc</h4>
                                        <span>מחשבון בניית בית </span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                        <div class="item">
                            <!-- <a href="#"> -->
                                <div class="portfolio-item">
                                    <div class="thumb">
                                        <img src="assets/images/portfolio-02.jpg" alt="Isuzu Israel - מערכת CRM לחברת רכב" loading="lazy">
                                    </div>
                                    <div class="down-content">
                                        <h4>isuzu israel</h4>
                                        <span>CRM מערכות </span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                        <div class="item">
                            <!-- <a href="#"> -->
                                <div class="portfolio-item">
                                    <div class="thumb">
                                        <img src="assets/images/portfolio-03.jpg" alt="CryptoPop - דף נחיתה לקמפיין קריפטו" loading="lazy">
                                    </div>
                                    <div class="down-content">
                                        <h4>cryptopop</h4>
                                        <span>דפי נחיתה</span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                        <div class="item">
                            <!-- <a href="#"> -->
                                <div class="portfolio-item">
                                    <div class="thumb">
                                        <img src="assets/images/portfolio-04.jpg" alt="Meat-Man - דף נחיתה לעסק במזון" loading="lazy">
                                    </div>
                                    <div class="down-content">
                                        <h4>meat-man</h4>
                                        <span> דפי נחיתה</span>
                                    </div>
                                </div>
                            <!-- </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <?php
    $configPath = __DIR__ . '/server/config.php';
    if (file_exists($configPath)) {
        include $configPath;
    } else {
        error_log('הקובץ config.php לא נמצא בנתיב: ' . $configPath);
        exit('שגיאה: הקובץ config.php לא נמצא.');
    }



    // שאילתת SQL כדי להביא את 4 המאמרים האחרונים
    $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 4";
    $result = $con->query($sql);

    ?>

    <div id="blog" class="blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 offset-lg-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.3s">
                    <div class="section-heading">
                        <h6>חדשות אחרונות</h6>
                        <h4>הבלוג <em>שלנו</em></h4>
                        <div class="line-dec"></div>
                    </div>
                </div>
                <?php   $i = 1 ?>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-lg-6 show-up wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                            <div class="blog-post">
                                <div class="thumb">
                                    <a href="blog/<?= htmlspecialchars($row['slug']); ?>.php" target="_blank">
                                    <img src="assets/images/blog-post-0<?=$i?>.jpg" alt="<?= htmlspecialchars($row['title']); ?>" style="height:300px">
                                    </a>
                                </div>
                                <div class="down-content">
                                    <div class="row d-flex justify-content-end">
                                        <div class="col-md-6 text-end p-0">
                                            <a target="_blank" href="blog/<?= htmlspecialchars($row['slug']); ?>.php">
                                                <span class="category">קידום ושיווק  דיגיטלי</span>
                                            </a>
                                        </div>
                                    </div>
                                    <a href="blog/<?= htmlspecialchars($row['slug']); ?>" target="_blank" dir="rtl">
                                        <h4><?= htmlspecialchars($row['title']); ?></h4>
                                    </a>
                                    <p dir="rtl">
                                        <?= mb_substr(strip_tags($row['content']), 0, 120, "UTF-8") . "..."; ?>
                                    </p>
                                    <p class="text-muted">
                                        🗓️ פורסם ב- <?= date("d/m/Y", strtotime($row['created_at'])); ?>
                                    </p>
                                    <div class="border-first-button">
                                        <a href="blog/<?= htmlspecialchars($row['slug']); ?>.php" target="_blank" style="position:relative !important;bottom:35px">קרא עוד</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php   $i++  ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                   
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- <div id="blog" class="blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 offset-lg-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.3s">
                    <div class="section-heading">
                        <h6>חדשות אחרונות</h6>
                        <h4>  הבלוג <em>שלנו</em></h4>
                        <div class="line-dec"></div>
                    </div>
                </div>
                <div class="col-lg-6 show-up wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <div class="blog-post">
                    <div class="thumb">
                        <a href="./blog/organic-seo-tips.php" target="blank"><img src="assets/images/blog-post-01.jpg" alt="טיפים לקידום אורגני"></a>
                    </div>
                    <div class="down-content">
                        <div class="row d-flex justify-content-end">
                            <div class="col-md-6 text-end p-0">
                                <a href="./blog/organic-seo-tips.php">
                                    <span class="category">טיפים לקידום אורגני</span>
                                </a>
                            </div>
                        </div>
                        <a href="./blog/organic-seo-tips.php" target="blank" dir="rtl">
                            <h4>10 טיפים לשיפור הקידום האורגני של האתר שלך</h4>
                        </a>
                        <p dir="rtl">קידום אורגני הוא תהליך מתמשך שמחייב השקעה ומקצועיות. במאמר זה נציג 10 טיפים יעילים לשיפור הנוכחות הדיגיטלית שלך.</p>
                        <span class="author"><img src="assets/images/idan.jpg" alt="טיפים לקידום אורגני"></span>
                        <div class="border-first-button">
                            <a href="./blog/organic-seo-tips.php" target="blank">גלה עוד</a>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                    <div class="blog-posts">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="post-item">
                                    <div class="thumb">
                                        <a href="./blog/web-development.php" target="blank"><img src="assets/images/blog-post-02.jpg" alt="איך לבחור את הפלטפורמה הנכונה לבניית אתרים?"></a>
                                    </div>
                                    <div class="right-content">
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-md-6 text-end p-0">
                                            <a href="./blog/web-development.php" target="blank">
                                                <span class="category">פיתוח אתרים</span>
                                            </a>
                                            </div>
                                        </div>

                                        <a href="web-development.php" target="blank" dir="rtl">
                                            <h4>איך לבחור את הפלטפורמה הנכונה לבניית אתרים?</h4>
                                        </a>
                                        <p dir="rtl">בחירת הפלטפורמה המתאימה לבניית אתרים היא משימה קריטית להצלחת העסק שלך. במאמר זה נסקור את היתרונות והחסרונות של הפלטפורמות הפופולריות ונתווה דרך להחלטה מושכלת.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="post-item">
                                    <div class="thumb">
                                        <a href="./blog/crm-solutions.php" target="blank"><img src="assets/images/blog-post-03.jpg" alt=""></a>
                                    </div>
                                    <div class="right-content">
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-md-6 text-end p-0">
                                            <a href="./blog/crm-solutions.php" target="blank">
                                                <span class="category">פתרונות CRM</span>
                                            </a>
                                            </div>
                                        </div>
                                                              
                                        <a href="./blog/crm-solutions.php" target="blank" dir="rtl">
                                            <h4>חשיבות השימוש במערכות CRM לניהול קשרי לקוחות</h4>
                                        </a>
                                        <p dir="rtl">מערכת CRM מאפשרת לעסקים לנהל את הקשרים עם לקוחותיהם בצורה מקצועית ויעילה. במאמר זה נבחן את היתרונות והיכולות של מערכות CRM שונות וכיצד הן תורמות לשיפור השירות.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="post-item last-post-item">
                                    <div class="thumb">
                                        <a href="./blog/digital-marketing.php" target="blank"><img src="assets/images/blog-post-04.jpg" alt=""></a>
                                    </div>
                                    <div class="right-content">
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-md-6 text-end p-0">
                                            <a href="./blog/digital-marketing.php" target="blank">
                                                <span class="category">שיווק דיגיטלי</span>
                                            </a>
                                            </div>
                                        </div>
                                       
                                        <a href="./blog/digital-marketing.php" target="blank" dir="rtl">
                                            <h4>מהו שיווק דיגיטלי ואיך הוא משפיע על העסק שלך?</h4>
                                        </a>
                                        <p dir="rtl">שיווק דיגיטלי הפך לכלי חיוני לכל עסק בעידן המודרני. במאמר זה נסקור אסטרטגיות שיווק דיגיטלי שונות, כולל קידום ברשתות חברתיות, SEO ודוא"ל שיווקי, ונבחן כיצד ליישם אותן בהצלחה.</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div> -->



    <div id="contact" class="contact-us section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading wow fadeIn">
                        <h6>דברו איתנו </h6>
                        <h4>צרו איתנו קשר  <em>עכשיו</em></h4>
                        <div class="line-dec"></div>
                    </div>
                </div>
                <div class="col-lg-12 wow fadeInUp">
                    <form id="contact" onsubmit="lead(event)" aria-label="טופס יצירת קשר עם הלו מדיה">
                        <?php include __DIR__ . '/includes/attribution-capture.php'; ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="contact-dec">
                                    <img src="assets/images/contact-dec.png" alt="צרו קשר עם הלו מדיה" loading="lazy">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div id="map">
                                <iframe
                                src="https://maps.google.com/maps?q=Tel+Aviv,+Israel&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                width="100%" height="636px" frameborder="0" style="border:0"
                                allowfullscreen loading="lazy" title="מפת תל אביב - מיקום משרדי הלו מדיה">
                              </iframe>

                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="fill-form">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="info-post">
                                                <div class="icon">
                                                    <img src="assets/images/phone-icon.png" alt="" aria-hidden="true" loading="lazy">
                                                    <a href="tel:+972526814747" aria-label="חיוג מהיר ל-052-681-4747">
                                                        <i class="fa fa-phone" aria-hidden="true"></i> לחצו כאן לחיוג מהיר
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="info-post">
                                                <div class="icon">
                                                    <img src="assets/images/email-icon.png" alt="" aria-hidden="true" loading="lazy">

                                                      <a href="mailto:hlomedia.office@gmail.com" aria-label="שליחת מייל ל-hlomedia.office@gmail.com">לחצו כאן לשליחת מייל</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="info-post">
                                                <div class="icon">
                                                    <img src="assets/images/location-icon.png" alt="" aria-hidden="true" loading="lazy">
                                                    <span>תל-אביב ישראל</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6" dir="rtl">
                                            <fieldset>
                                                <label for="name" class="visually-hidden">שם מלא</label>
                                                <input type="text" name="name" id="name" placeholder="שם מלא*" autocomplete="name" required>
                                            </fieldset>
                                            <fieldset>
                                                <label for="email" class="visually-hidden">אימייל</label>
                                                <input type="email" name="email" id="email" placeholder="אימייל" autocomplete="email">
                                            </fieldset>
                                            <fieldset>
                                                <label for="subject" class="visually-hidden">נושא</label>
                                                <input type="text" name="subject" id="subject" placeholder="נושא">
                                            </fieldset>
                                            <fieldset>
                                                <label for="phone" class="visually-hidden">טלפון</label>
                                                <input type="tel" name="phone" id="phone" placeholder="טלפון*" autocomplete="tel" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-6" dir="rtl">
                                            <fieldset>
                                                <label for="message" class="visually-hidden">הודעה</label>
                                                <textarea name="message" class="form-control" id="message" placeholder="במה נוכל לעזור?"></textarea>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset>
                                                <button type="submit" id="form-submit" class="main-button" name="form_submit">שלח/י</button>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="whatsapp">
        <a href="https://wa.me/972526814747" target="_blank" rel="noopener" aria-label="שליחת הודעה בוואטסאפ ל-052-681-4747">
            <i class="fa fab fa-whatsapp" aria-hidden="true"></i>
        </a>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                <p>
                  כל הזכויות שמורות ל הלו-מדיה. © <?= date("Y"); ?> |
                  <a href="tel:+972526814747">052-681-4747</a> |
                  <a href="mailto:hlomedia.office@gmail.com">hlomedia.office@gmail.com</a>
              </p>

                </div>
            </div>
        </div>
    </footer>



    <!-- nagish li -->
      <script src="./assets/js/nagishli.js" charset="utf-8" defer></script>

    <script>
        nl_pos = "bl";
        nl_color = "purple";
        nl_contact = "n:הלו-מדיה|u:hlomedia.office+d:gmail.com";
    </script>


    <!-- Scripts (deferred for performance) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="vendor/jquery/jquery.min.js" defer></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/owl-carousel.js" defer></script>
    <script src="assets/js/custom.js" defer></script>
    <script src="assets/js/main.js" defer></script>

</body>

</html>