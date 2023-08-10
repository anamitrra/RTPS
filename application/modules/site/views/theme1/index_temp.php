<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        var u = new URL(window.location.href);
        if (window.location.href.indexOf("www") > -1) {
            //alert("your url contains the www");
            window.location = "https://rtps.assam.gov.in/";
        }
    </script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This is official state site for ARTPS Portal.">
    <link rel="shortcut icon" href="<?= base_url('assets/site/theme1/images/favicon.ico') ?>" type="image/x-icon">

    <title>ARTPS | Government of Assam</title>

    <meta property="og:title" content="RTPS Assam" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= base_url('site') ?>" />
    <meta property="og:image" content="<?= base_url('assets/site/theme1/images/xohari.png') ?>" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:height" content="200">
    <meta property="og:image:width" content="200">
    <meta property="og:description" content="Right To Public Services, Assam" />

    <meta name="twitter:title" content="RTPS Assam ">
    <meta name="twitter:description" content="Right To Public Services, Assam.">
    <meta name="twitter:image" content="<?= base_url('assets/site/theme1/images/xohari.png') ?>">
    <meta name="twitter:site" content="@accsdp_assam">
    <meta name="twitter:card" content="summary_large_image">

    <link href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/css/rwd.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site/theme1/plugins/owl-carousel/css/owl.carousel.min.css') ?>">

    <style>
        main {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
        }

        .error-template {
            padding: 40px 15px;
            text-align: center;
        }

        .message-box h1 {
            color: #252932;
            font-size: 98px;
            font-weight: 700;
            line-height: 98px;
            text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
        }
    </style>

    <script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.5.1.min.js"); ?>" defer></script>
    <script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/site/theme1/plugins/owl-carousel/owl.carousel.min.js') ?>" defer></script>
    <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>
    <script src="<?= base_url('assets/site/theme1/js/home_temp.js') ?>" defer></script>

</head>

<body>

    <header>

        <!-- Topmost Panel -->
        <div class="container-fluid top-panel">
            <section class="container d-flex justify-content-between align-items-stretch flex-wrap">

                <div class="py-1">
                    <img class="d-inline-block me-1" src="<?= base_url('assets/site/theme1/images/govtofindia.png') ?>" alt="indian flag" width="16">

                    <span class="small text-uppercase fw-bold">Government of assam</span>
                </div>
            </section>
        </div>

        <!-- Brand Logos -->
        <section class="container-fluid py-2">
            <div class="container d-flex justify-content-between align-items-center">

                <div class="d-flex justify-content-between">
                    <img class="d-none d-md-inline-block" src="<?= base_url('assets/site/theme1/images/emblem.png') ?>" style="transform: translateX(-10px);" id="emblem" alt="Emblem" height="80">

                    <div class="heading-text">
                        <h2 class="emb fw-bold mb-0 text-uppercase"> Right to Public Services </h2>
                        <h5 class="mb-0 fst-italic fw-bolder">
                            A Comprehensive Citizen Platform
                        </h5>
                        <p class="mb-0 fw-light">
                            An Initiative of the Administrative Reforms, Training, Pension and Public Grievances Department
                        </p>
                    </div>

                </div>

                <img class="img-fluid" src="<?= base_url('assets/site/theme1/images/xohari.png') ?>" id="xohari" alt="Xohari" width="64">

            </div>
        </section>


        <!-- Main Navbar -->
        <nav class="navbar navbar-expand-md container-fluid py-0">
            <div class="container">

                <a class="navbar-brand p-2" href="<?= base_url('site') ?>">
                    <img src="<?= base_url('assets/site/theme1/images/home.png') ?>" alt="Home" width="16">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" style="color: #ffd303;"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav text-uppercase"></div>
                </div>

            </div>
        </nav>

    </header>

    <main>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="error-template">
                        
                        <h2>
                            Temporarily down for maintenance</h2>
                        <h1>
                            We'll be back soon!</h1>
                        <div>
                            <p>
                                Sorry for the inconvenience but we’re performing some maintenance at the moment.
                                we'll be back online shortly!</p>
                            <p>
                                — Team RTPS</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <svg class="svg-box" width="380px" height="500px" viewbox="0 0 837 1045" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                            <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

    </main>

    <footer>

        <section class="links container-fluid py-5">

            <div class="container">
                <div class="row gy-4 g-lg-0">
                    <div class="col-12 col-md-6 col-lg-3">

                        <h6 class="mb-3">Help Desk</h6>

                        <section class="recent-updates mb-2">

                            <article class="py-1 mb-1">
                                <div>
                                    <img width="16" src="<?= base_url('assets/site/theme1/images/phone.png') ?>" alt="Phone icon">
                                    <span class="dept-text text-white fw-bold small">
                                        Toll Free Helpline No </span>
                                </div>
                                <p class="dept-text small my-1">
                                    1800-345-3574 </p>
                                <p class="dept-text small m-0">
                                    On All Days from 8:00 AM to 8:00 PM </p>
                            </article>

                            <article class="py-1">
                                <div>
                                    <img width="16" src="<?= base_url('assets/site/theme1/images/email.png') ?>" alt="Email icon">
                                    <span class="dept-text text-white fw-bold small">
                                        E-mail </span>
                                </div>
                                <span class="dept-text  d-block text-truncate small mt-1">
                                    rtps-assam@assam.gov.in </span>

                            </article>

                        </section>

                        <section class="social-links mt-2">
                            <p class="dept-text text-white fw-bold small">
                                Follow us on: </p>


                            <a href="https://twitter.com/accsdp_assam?s=08" title="@accsdp_assam" class="d-inline-block me-1" target="_blank" rel="noopener noreferrer">
                                <img width="20" src="<?= base_url('assets/site/theme1/images/twitter.png') ?>" alt="social icon">
                            </a>


                            <a href="https://www.youtube.com/channel/UCumAwJZJlkmc1BHjqg1CPCA" title="ACCSDP ARIAS Society" class="d-inline-block me-1" target="_blank" rel="noopener noreferrer">
                                <img width="20" src="<?= base_url('assets/site/theme1/images/youtube.png') ?>" alt="social icon">
                            </a>


                            <a href="https://www.facebook.com/accsdp.assam" title="Assam State Citizen-Centric Service Delivery Project" class="d-inline-block me-1" target="_blank" rel="noopener noreferrer">
                                <img width="20" src="<?= base_url('assets/site/theme1/images/facebook.png') ?>" alt="social icon">
                            </a>


                            <a href="https://www.instagram.com/accsdp__assam/?igshid=8mxmsmaq019b" title="@accsdp__assam" class="d-inline-block me-1" target="_blank" rel="noopener noreferrer">
                                <img width="20" src="<?= base_url('assets/site/theme1/images/instagram.png') ?>" alt="social icon">
                            </a>


                        </section>

                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <h6 class="mb-3">Right to Public Services</h6>

                        <ul class="list-unstyled rtps-footer-links">

                            <li class="py-1">
                                <a href="#" class="dept-text small text-decoration-none">
                                    General Terms and Conditions </a>
                            </li>

                            <li class="py-1">
                                <a href="#" class="dept-text small text-decoration-none">
                                    Privacy Policy </a>
                            </li>

                            <li class="py-1">
                                <a href="#" class="dept-text small text-decoration-none">
                                    Copyright Policy </a>
                            </li>

                            <li class="py-1">
                                <a href="#" class="dept-text small text-decoration-none">
                                    Refund &amp; Cancellation Policy </a>
                            </li>

                        </ul>

                        <div class="w-75 visitors d-none border border-light border-1 rounded-0 p-2 d-flex justify-content-start align-items-start">

                            <img width="30" src="<?= base_url('assets/site/theme1/images/feedbackicon.png') ?>" alt="visitor icon">

                            Share your Feedback
                            <a href="https://rtps.assam.gov.in/site/feedback" class="visitors ms-3 fw-bold text-decoration-none">Share your Feedback</a>
                        </div>

                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <h6 class="imp mb-3">Important Links</h6>

                        <ul class="list-unstyled">


                            <li class="py-1">
                                <a href="https://assam.gov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    Assam State Portal </a>

                            </li>


                            <li class="py-1">
                                <a href="https://assam.mygov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    MyGov Assam </a>

                            </li>


                            <li class="py-1">
                                <a href="https://www.meity.gov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    Meity </a>

                            </li>


                            <li class="py-1">
                                <a href="https://swachhbharat.mygov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    Swachh Bharat </a>

                            </li>


                            <li class="py-1">
                                <a href="https://www.india.gov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    National Portal of India </a>

                            </li>


                            <li class="py-1">
                                <a href="https://covid19.assam.gov.in/" target="_blank" rel="noopener noreferrer" class="dept-text small text-decoration-none">
                                    Assam Covid-19 Advisory </a>

                            </li>


                        </ul>

                        <div class="w-75 visitors d-none border border-light border-1 rounded-0 p-2 d-flex justify-content-start align-items-start">

                            <img width="30" src="<?= base_url('assets/site/theme1/images/visitoricon.png') ?>" alt="visitor icon">
                            <p class="visitors ms-3 fw-bold">
                                Visitors:
                                <span>5491</span>
                            </p>
                        </div>

                    </div>
                    <div class="col-12 col-md-6 col-lg-3">

                        <h6 class="nodal mb-3">Nodal Department</h6>
                        <a href="https://art.assam.gov.in/" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                            <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                            Administrative Reforms &amp; Training Department </a>

                        <h6 class="my-3">Implementation Agency</h6>
                        <a href="http://www.arias.in/" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                            <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                            ARIAS Society, Government of Assam </a>

                        <h6 class="my-3">Designed &amp; Developed by</h6>
                        <a href="https://assam.nic.in/" class="dept-text small text-decoration-none" target="_blank" rel="noopener noreferrer">
                            <img class="d-inline-block me-1" width="16" src="<?= base_url('assets/site/theme1/images/agency.png') ?>" alt="agency logo">
                            National Informatics Centre, Assam </a>

                        <img class="d-block mt-2" style="transform: translateX(-10px);" width="188" src="<?= base_url('assets/site/theme1/images/nic.png') ?>" alt="NIC logo">

                    </div>
                </div>
            </div>

        </section>

        <section class="copyright container-fluid pt-4 pb-2">

            <div class="container d-flex align-items-baseline flex-wrap">
                
                <p class="dept-text text-white small">
                    Copyright © <span> <?= date('Y') ?> </span> |
                    Government of Assam </p>
            </div>

        </section>



    </footer>

</body>

</html>