<?php
$page_meta = [
    'lang'        => 'en',
    'title'       => 'HloMedia - Tel Aviv Software & Digital Marketing Agency | Web Dev, CRM, SEO',
    'description' => 'Tel Aviv based software agency specializing in custom web development, CRM systems & SEO. Personal service, tailored solutions, free quote. ☎ +972-52-681-4747',
    'keywords'    => 'software development israel, web development tel aviv, CRM system, SEO agency, digital marketing, landing pages',
    'canonical'   => 'https://www.hlo-media.com/en.php',
    'hreflang'    => [
        'he'    => 'https://www.hlo-media.com/',
        'en'    => 'https://www.hlo-media.com/en.php',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/includes/head-meta.php'; ?>
    <?php include __DIR__ . '/includes/analytics.php'; ?>

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

    <!-- Schema.org JSON-LD -->
    <?php include __DIR__ . '/includes/schema.php'; ?>
</head>


<body>
    <!-- Modal -->
    <div class="modal fade" id="tipsModal" tabindex="-1" aria-labelledby="tipsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header lang">
                <button type="button" class="btn-close btn-close-white ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" dir="ltr">
                <h5 class="modal-title text-center mb-4" id="tipsModalLabel" dir="ltr">10 Tips for Improving Organic SEO</h5>

                <ol class="list-group">
                    <li class="list-group-item">Conduct thorough keyword research to understand what your customers are searching for.</li>
                    <li class="list-group-item">Optimize your content for search engines, including descriptions, headings, and meta tags.</li>
                    <li class="list-group-item">Use high-quality, informative content that meets user needs.</li>
                    <li class="list-group-item">Ensure your website loads quickly and that images are optimized.</li>
                    <li class="list-group-item">Build internal and external links to improve your domain authority.</li>
                    <li class="list-group-item">Utilize social media to promote your content and attract visitors.</li>
                    <li class="list-group-item">Optimize your website for mobile devices.</li>
                    <li class="list-group-item">Monitor your website's performance data and use tools like Google Analytics.</li>
                    <li class="list-group-item">Regularly publish new content to keep your site relevant.</li>
                    <li class="list-group-item">Ensure rich and attractive descriptions to improve click-through rates.</li>
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
                            <a href="mailto:hlomedia.office@gmail.com" aria-label="Send email to hlomedia.office@gmail.com">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span class="text">hlomedia.office@gmail.com</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+972526814747" aria-label="Call +972-52-681-4747">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span class="text">+972 52-681-4747</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-5">
                    <ul class="social-media">
                        <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
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
                        <a href="/en.php" class="logo" aria-label="HloMedia - Home">
                            <img src="assets/images/logo_1.jpg" alt="HloMedia - Software & Digital Marketing Agency" id="logo">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section">
                                <a href="#top" class="active"><i class="fa fas fa-home text-secondary"></i> Home  </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#services"> <i class="fa fas fa-cogs text-secondary"></i> Services </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#portfolio"><i class="fa fas fa-briefcase text-secondary"></i> Projects </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#blog" if class active color : #fa65b1 !important><i class="fa fa-solid fa-comment text-secondary"></i>  Blog </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#free-quote"><i class="fa fa-solid fa-film text-secondary"></i> Video  </a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="#about"><i class="fa fas fa-users text-secondary"></i> Who We Are </a>
                            </li>
                    
                            <li class="scroll-to-section">
                                <div class="border-first-button"><a href="#contact"> Contact Us </a></div>
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
        <div class="row m-0" dir="ltr">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center ps-md-5">
                        <div class="left-content show-up header-text wow" data-wow-delay="1s">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="pink title">Advanced Software Solutions</h4>
                                    <h2 class=""> Elevating Your Digital Presence Online</h2>
                                    <p class="px-0 mx-0 txt"> An advanced software company specializing in the development of technological solutions, including software development, CRM, website building, organic SEO, and digital marketing, tailored to the clients' needs.</p>
                                </div>
                                <div class="col-lg-12">
                                    <div class="border-first-button scroll-to-section">
                                        <a href="#contact"> Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 pe-md-5">
                        <div class="right-image wow fadeInLeft">
                            <img src="assets/images/slider-dec.png" alt="A bit about us">
                            <!-- <div id="video" style="position: relative; overflow: hidden; aspect-ratio: 1920/1080"><iframe src="https://share.synthesia.io/embeds/videos/67cbaada-9ac4-44c9-8d7b-8ab238ed9d59" loading="lazy" title="Synthesia video player - Welcome to Hlo Media" allow="fullscreen" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0; overflow:hidden;"></iframe></div> -->
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
                        <div class="about-left-image wow fadeInLeft">
                            <img src="assets/images/about-dec.png" alt="A bit about us">
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center order-1 order-lg-2 wow fadeInRight">
                        <div class="about-right-content" dir="ltr">
                            <div class="section-heading">
                                <h6> A Bit About Us</h6>
                                <h4> What Does <em> Hlo Media </em> Specialize In?</h4>
                                <div class="line-dec"></div>
                            </div>
                            <p class="txt"> We specialize in creating advanced digital solutions for businesses. We provide website development services, customized CRM systems, organic SEO, and digital marketing tailored to each client's needs. Come discover how we can leverage your business success.</p>
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
                                                    <span>CRM Systems</span>
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
                                                    <span>Website Development</span>
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
                                                    <span>Digital Marketing</span>
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
                <div class="section-heading wow fadeInDown">
                    <h6>Our Services</h6>
                    <h4>Custom Digital Solutions</h4>
                    <div class="line-dec"></div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="naccs">
                    <div class="grid">
                        <div class="row" dir="ltr">
                            <div class="col-lg-12">
                                <div class="menu">
                                    <div class="active">
                                        <div class="thumb">
                                            <span class="icon"><img src="assets/images/service-icon-01.png"
                                                    alt="Website Development"></span>
                                            Websites
                                        </div>
                                    </div>
                                    <div>
                                        <div class="thumb">
                                            <span class="icon"><img src="assets/images/service-icon-02.png"
                                                    alt="CRM Systems"></span>
                                            CRM
                                        </div>
                                    </div>
                                    <div class="thumb">
                                        <div class="thumb">
                                            <span class="icon"><img src="assets/images/service-icon-01.png"
                                                    alt="Digital Marketing"></span>
                                            Digital Marketing
                                        </div>
                                    </div>
                                    <div>
                                        <div class="thumb">
                                            <span class="icon"><img src="assets/images/service-icon-04.png"
                                                    alt="Landing Pages"></span>
                                            Landing Pages
                                        </div>
                                    </div>
                                    <div>
                                        <div class="thumb">
                                            <span class="icon"><img src="assets/images/service-icon-03.png"
                                                    alt="SEO"></span>
                                            SEO
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
                                                        <div class="left-text" dir="ltr">
                                                        <h4>Websites</h4>
                                                            <p>We specialize in creating stunning websites that combine unique design with excellent user experience. Our expert team ensures to develop custom websites tailored to each client's needs.</p>
                                                            <div class="p-2">
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Responsive Design</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> SEO Optimization</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Advanced Content Solutions</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Advanced Technologies</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Continuous Performance Analysis</div>
                                                            </div>
                                                            <p>Whether you need a showcase website, an online store, or a complex platform, we are here to guide you through the process and provide innovative solutions.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center text-end" dir="ltr">
                                                        <div class="right-image">
                                                        <img src="assets/images/website.jpg" alt="Websites">
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
                                                        <div class="left-text" dir="ltr">
                                                        <h4>CRM</h4>
                                                            <p>Our Customer Relationship Management (CRM) solutions allow you to manage your relationships with clients intelligently and efficiently. Our systems are customized to help you maximize your business relationships.</p>
                                                            <div class="p-2">
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Lead Management</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Process Automation</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Customer Data Analysis</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Customer Support and Service</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Integration with Other Systems</div>
                                                            </div>
                                                            <p>With our CRM solutions, you can enhance customer experience and maximize your business potential.</p>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center text-end" dir="ltr">
                                                        <div class="right-image">
                                                            <img src="assets/images/crm.jpg" alt="CRM">
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
                                                        <div class="left-text" dir="ltr">
                                                        <h4>Digital Marketing</h4>
                                                            <p>We offer personalized digital marketing solutions focused on increasing visibility and improving your business sales.</p>
                                                            <div class="p-2">
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Strategic Planning</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Data Analysis</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> SEO Optimization</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Management of Paid Campaigns</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Social Media Marketing</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Marketing Content Planning</div>
                                                            </div>
                                                            <p>Each of our services is carefully designed to deliver measurable results and maximize your business value.</p>
                                                         
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center text-end" dir="ltr">
                                                        <div class="right-image">
                                                        <img src="assets/images/marketing.png" alt="Digital Marketing">
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
                                                        <div class="left-text" dir="ltr">
                                                        <h4>Landing Pages</h4>
                                                            <p>Our team specializes in creating high-converting landing pages, designed specifically to grab users' attention and drive them to take action. Our landing pages are customized for each campaign and marketing objectives.</p>
                                                            <div class="p-2">
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Attention-Grabbing Design</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Clear Calls to Action</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Performance Analysis and Measurement</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Conversion Optimization</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Integration with Marketing Tools</div>
                                                            </div>
                                                            <p>With our landing pages, you can improve your conversion rates and achieve measurable results in every campaign.</p>
                                                          
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center text-end" dir="ltr">
                                                        <div class="right-image">
                                                        <img src="assets/images/business.png" alt="Landing Pages">
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
                                                        <div class="left-text" dir="ltr">
                                                        <h4>SEO</h4>
                                                            <p>We offer organic SEO services that will help you reach the top spots in search results. Our team specializes in advanced techniques and ongoing analysis to ensure your success online.</p>
                                                            <div class="p-2">
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> In-Depth Keyword Research</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Content Optimization</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Quality Backlinks</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Technical SEO</div>
                                                                <div class="my-2"><i class="fa fa-check text-success"></i> Competitor Analysis</div>
                                                            </div>
                                                            <p>With our SEO solutions, you can increase your online visibility and reach more clients in your target market.</p>
                                                         
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 align-self-center text-end" dir="ltr">
                                                        <div class="right-image">
                                                        <img src="assets/images/seo.png" alt="SEO">
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
        <!-- <div class="row">
            <div class="col-md-6 mx-auto">
                <div id="video" style="position: relative; overflow: hidden; aspect-ratio: 1920/1080">
                    <iframe src="https://share.synthesia.io/embeds/videos/67cbaada-9ac4-44c9-8d7b-8ab238ed9d59" loading="lazy" title="Synthesia video player - Welcome to Hello Media" allow="fullscreen" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0; overflow:hidden;"></iframe>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-4 offset-lg-4">
                <div class="section-heading wow fadeIn">
                    <!-- <h4> Custom Solutions</h4> -->
                    <h6>Free Quote, No Obligation</h6>
                    <div class="line-dec"></div>
                </div>
            </div>
            <div class="col-lg-8 offset-lg-2 wow fadeIn">
                <form id="search" onsubmit="quotation(event)">
                    <div class="row">
                        <!-- <div class="col-lg-4 col-sm-4" dir="ltr">
                            <fieldset>
                                <input type="web" name="web" class="website" placeholder="Website name if available" autocomplete="on" required>
                            </fieldset>
                        </div> -->
                        <div class="col-lg-8 col-sm-8" dir="ltr">
                            <fieldset>
                                <input class="border-none" type="address" id="mail" class="email" placeholder="Email">
                            </fieldset>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <fieldset>
                                <button type="submit" class="main-button" name="email_input_submit"><h4>Get a Quote</h4></button>
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
                    <h6>Coming Soon...</h6>
                    <h4>You are invited to see our <em>projects</em></h4>
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
                                    <img src="assets/images/portfolio-01.jpg" alt="Our projects">
                                </div>
                                <div class="down-content">
                                    <h4>academee</h4>
                                    <span>Learning Website</span>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                    <div class="item">
                        <!-- <a href="#"> -->
                            <div class="portfolio-item">
                                <div class="thumb">
                                    <img src="assets/images/portfolio-01.jpg" alt="Our projects">
                                </div>
                                <div class="down-content">
                                    <h4>smart-calc</h4>
                                    <span>Home Construction Calculator</span>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                    <div class="item">
                        <!-- <a href="#"> -->
                            <div class="portfolio-item">
                                <div class="thumb">
                                    <img src="assets/images/portfolio-02.jpg" alt="Our projects">
                                </div>
                                <div class="down-content">
                                    <h4>isuzu israel</h4>
                                    <span>CRM Systems</span>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                    <div class="item">
                        <!-- <a href="#"> -->
                            <div class="portfolio-item">
                                <div class="thumb">
                                    <img src="assets/images/portfolio-03.jpg" alt="Our projects">
                                </div>
                                <div class="down-content">
                                    <h4>cryptopop</h4>
                                    <span>Landing Pages</span>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                    <div class="item">
                        <!-- <a href="#"> -->
                            <div class="portfolio-item">
                                <div class="thumb">
                                    <img src="assets/images/portfolio-04.jpg" alt="Our projects">
                                </div>
                                <div class="down-content">
                                    <h4>meat-man</h4>
                                    <span>Landing Pages</span>
                                </div>
                            </div>
                        <!-- </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="blog" class="blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 offset-lg-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.3s">
                <div class="section-heading">
                    <h6>Latest News</h6>
                    <h4>Our <em>Blog</em></h4>
                    <div class="line-dec"></div>
                </div>
            </div>
            <div class="col-lg-6 show-up wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <div class="blog-post">
                    <div class="thumb">
                        <a href="./blog/organic-seo-tips.php" target="blank"><img src="assets/images/blog-post-01.jpg" alt="SEO Tips"></a>
                    </div>
                    <div class="down-content">
                        <div class="row d-flex justify-content-start">
                            <div class="col-md-6 text-start p-0">
                                <a href="./blog/organic-seo-tips.php">
                                    <span class="category">SEO Tips</span>
                                </a>
                            </div>
                        </div>
                        <a href="./blog/organic-seo-tips.php" target="blank" dir="ltr">
                            <h4>10 Tips to Improve Your Website's Organic SEO</h4>
                        </a>
                        <p dir="ltr">Organic SEO is an ongoing process that requires investment and professionalism. In this article, we will present 10 effective tips to improve your digital presence.</p>
                        <span class="author"><img src="assets/images/idan.jpg" alt="SEO Tips"></span>
                        <div class="border-first-button">
                            <a href="./blog/organic-seo-tips.php" target="blank">Discover More</a>
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
                                    <a href="./blog/web-development.php" target="blank"><img src="assets/images/blog-post-02.jpg" alt="How to Choose the Right Platform for Building Websites?"></a>
                                </div>
                                <div class="right-content">
                                    <div class="row d-flex justify-content-start">
                                        <div class="col-md-6 text-start p-0">
                                            <a href="./blog/web-development.php" target="blank">
                                                <span class="category">Web Development</span>
                                            </a>
                                        </div>
                                    </div>

                                    <a href="web-development.php" target="blank" dir="ltr">
                                        <h4>How to Choose the Right Platform for Building Websites?</h4>
                                    </a>
                                    <p dir="ltr">Choosing the right platform for building websites is critical to your business success. In this article, we will review the advantages and disadvantages of popular platforms and outline a path for informed decision-making.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="post-item">
                                <div class="thumb">
                                    <a href="./blog/crm-solutions.php" target="blank"><img src="assets/images/blog-post-03.jpg" alt=""></a>
                                </div>
                                <div class="right-content">
                                    <div class="row d-flex justify-content-start">
                                        <div class="col-md-6 text-start p-0">
                                            <a href="./blog/crm-solutions.php" target="blank">
                                                <span class="category">CRM Solutions</span>
                                            </a>
                                        </div>
                                    </div>

                                    <a href="./blog/crm-solutions.php" target="blank" dir="ltr">
                                        <h4>The Importance of Using CRM Systems for Customer Relationship Management</h4>
                                    </a>
                                    <p dir="ltr">A CRM system allows businesses to manage their relationships with customers professionally and efficiently. In this article, we will examine the benefits and capabilities of various CRM systems and how they contribute to improved service.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="post-item last-post-item">
                                <div class="thumb">
                                    <a href="./blog/digital-marketing.php" target="blank"><img src="assets/images/blog-post-04.jpg" alt=""></a>
                                </div>
                                <div class="right-content">
                                    <div class="row d-flex justify-content-start">
                                        <div class="col-md-6 text-start p-0">
                                            <a href="./blog/digital-marketing.php" target="blank">
                                                <span class="category">Digital Marketing</span>
                                            </a>
                                        </div>
                                    </div>

                                    <a href="./blog/digital-marketing.php" target="blank" dir="ltr">
                                        <h4>What is Digital Marketing and How Does it Affect Your Business?</h4>
                                    </a>
                                    <p dir="ltr">Digital marketing has become an essential tool for any business in the modern era. In this article, we will review various digital marketing strategies, including social media promotion, SEO, and email marketing, and examine how to successfully implement them.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="contact" class="contact-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading wow fadeIn">
                    <h6>Talk to Us</h6>
                    <h4>Contact Us <em>Now</em></h4>
                    <div class="line-dec"></div>
                </div>
            </div>
            <div class="col-lg-12 wow fadeInUp">
                <form id="contact" onsubmit="lead(event)" aria-label="Contact HloMedia">
                    <?php include __DIR__ . '/includes/attribution-capture.php'; ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="contact-dec">
                                <img src="assets/images/contact-dec.png" alt="Contact Us Decoration">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div id="map">
                            <iframe
                                src="https://maps.google.com/maps?q=losangeles,+USA&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                width="100%" height="636px" frameborder="0" style="border:0"
                                allowfullscreen>
                            </iframe>

                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="fill-form">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="info-post">
                                            <div class="icon">
                                                <img src="assets/images/phone-icon.png" alt="Call Us Now">
                                                <a href="tel:+972526814747">
                                                    <i class="fa fa-phone"></i> Quick Dial
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="info-post">
                                            <div class="icon">
                                                <img src="assets/images/email-icon.png" alt="Send an Email">
                                                <a href="mailto:hlomedia.office@gmail.com">Send an Email</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="info-post">
                                            <div class="icon">
                                            <img src="assets/images/location-icon.png" alt="California, USA">
                                            <a>Los Angeles, USA</a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" dir="ltr">
                                        <fieldset>
                                            <input type="name" name="name" id="name" placeholder="Full Name">
                                        </fieldset>
                                        <fieldset>
                                            <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Email">
                                        </fieldset>
                                        <fieldset>
                                            <input type="subject" name="subject" id="subject" placeholder="Subject">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6" dir="ltr">
                                        <fieldset>
                                            <textarea name="message" type="text" class="form-control" id="message" placeholder="Message"></textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <button type="submit" id="form-submit" class="main-button" name="form_submit">Send</button>
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
        <a class="" href="https://wa.me/972526814747" target="_blank">
            <i class="fa fab fa-whatsapp"></i>
        </a>
    </div>

    <footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>
                    All rights reserved to Hlo Media. © <?= date("Y"); ?>
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


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="vendor/jquery/jquery.min.js" defer></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/owl-carousel.js" defer></script>
    <script src="assets/js/custom.js" defer></script>
    <script src="assets/js/main.js" defer></script>
</body>

</html>