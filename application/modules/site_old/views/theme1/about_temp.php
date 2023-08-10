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
  <meta name="description" content="This is official state site for ARTPS Appeal Management System.">
  <link rel="shortcut icon" href="<?= base_url('assets/site/theme1/images/favicon.ico') ?>" type="image/x-icon">

  <title>ARTPS | Government of Assam</title>

  <meta property="og:title" content="RTPS Assam" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= base_url('appeal') ?>" />
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

  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

  <script src="<?= base_url("assets/site/theme1/plugins/jquery/jquery-3.5.1.min.js"); ?>" defer></script>
  <script src="<?= base_url('assets/site/theme1/plugins/bootstrap-5.0.1/js/bootstrap.bundle.min.js') ?>" defer></script>
  <script src="<?= base_url("assets/site/theme1/js/common.js"); ?>" defer></script>

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
        <section class="d-md-flex justify-content-end align-items-stretch flex-wrap">

          <div class="d-flex justify-content-between flex-wrap">

            <a class="login p-2 me-1 small text-uppercase fw-bold" href="#">
              <img class="d-inline-block" src="<?= base_url('assets/site/theme1/images/login.png') ?>" alt="sitemap" width="16">

              Login/Register
            </a>


          </div>


        </section>

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
          <div class="navbar-nav text-uppercase">

            <!-- RTPS Services Links -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Services
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/all_services') ?>">
                    All Services
                  </a>
                </li>
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/all_services/pfc') ?>">
                    PFC Services
                  </a></li>
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('site/all_services/csc') ?>">
                    CSC Services
                  </a></li>
              </ul>
            </li>

            <!-- RTPS Dashboard Links -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dashboard
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                    State Administrator
                  </a></li>
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('dashboard/login') ?>">
                    Department Administrator
                  </a></li>
                <li><a class="dropdown-item text-capitalize" href="<?= base_url('dashboard/dlogin') ?>">
                    Office Login
                  </a></li>
              </ul>
            </li>


            <a class="nav-link text-white px-md-3" href="<?= base_url('site/about') ?>">
              About
            </a>
            <a class="nav-link text-white px-md-3" href="<?= base_url('site/contact') ?>">
              Contact
            </a>


          </div>
        </div>

      </div>
    </nav>

  </header>

  <!-- Login Model -->

  <div class="modal fade" id="loginModel" tabindex="-1" aria-labelledby="loginModel" aria-hidden="true" data-bs-keyboard="true">

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="login-model modal-header">
          <h5 class="modal-title">Please Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="login-model modal-body p-0"></div>
        <div class="login-model modal-footer">
          <button type="button" class="btn btn-rtps" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>

  </div>
  <!-- Modal Ends -->

  <!-- Track Model -->

  <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModal" aria-hidden="true" data-bs-keyboard="true">

    <div class="modal-dialog">
      <div class="track-dark modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h3>What do you want to track?</h3>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('site/trackappstatus') ?>" class="btn btn-rtps">
            Services
          </a>
          <a href="<?= base_url('site/trackappealstatus') ?>" class="btn btn-rtps">
            Appeals
          </a>
          <a href="<?= base_url('grm/trackstatus') ?>" class="btn btn-rtps">
            Grievances
          </a>
        </div>
      </div>
    </div>

  </div>
  <!-- Modal Ends -->


  <main class="container">

    <div class="container">
      <div class="mt-3">

        <div>
          <h3><b><u>ABOUT ARTPS ACT</u></b></h3>
        </div>
        <div><br></div>
        <div>The Assam Right to Public Service Act in 2012, enacted by the Government of Assam, is an act to provide for the delivery of notified public services to the people of the state of Assam within the stipulated time limit and for matters connected therewith and incidental thereto. This Act came into force across all districts of the state, except for the Sixth Schedule areas. However, the Act has now been extended and adopted in the Sixth Schedule Districts which includes Bodoland Territorial Council (BTC), Dima Hasao Autonomous Territorial Council (DHATC) and Karbi Anglong Autonomous Council (KAAC).&nbsp;</div>
        <div><br></div>
        <div>The Act was amended in September 2019, and is now known as the "The Assam Right to Public Services (Amendment) Act, 2019".&nbsp; The Act is a step towards Bringing Services Closer to People. Some of the Key Provisions under this Act are - <br></div>
        <div>&nbsp;</div>
        <ol>
          <li>Right to get ARTPS notified public services within a specified time frame&nbsp;</li>
          <li>Right to get Acknowledgement receipt on application submission</li>
          <li>Designated Public Servant (DPS) for notified public services</li>
          <li>Provision of appeal to Appellate Authority in case of non-receipt of notified public services</li>
          <li>There is a Provision for Second Appeal in case the applicant is not satisfied with the Decision of the Appellate Authority</li>
        </ol>
        <div>
          <div>Government of Assam has undertaken a lot of e-governance initiatives to strengthen the public service delivery system.</div>
          <div><br></div>
          <div>
            <h3><b><u>About the Portal</u></b></h3>
          </div>
          <div><b><br></b></div>
          <div>The RTPS Portal is created under the World Bank Financed Assam Citizen Centric Service Delivery Project of the Administrative Reforms and Training Department to allow citizens to apply for ARTPS notified services online and also to promote proactive disclosure related to citizens’ entitlements under the Act and procedures for accessing ARTPS services. Some of the Key Features of the web portal are:</div>
        </div>
        <div><br></div>
        <ol>
          <li>Online application of services</li>
          <li>SMS alerts to applicant and Designated officials</li>
          <li>Acknowledgement receipt on application submission</li>
          <li>Dashboard for real time monitoring</li>
          <li>Online tracking of status of application</li>
          <li>Process automation</li>
          <li>Online appeal provision for non-delivery/ delay in providing services</li>
          <li>Lodge online grievance</li>
          <li>Information repository of RTPS Services</li>
        </ol>This portal is designed and developed by NIC Assam, Ministry of Electronics &amp; Information Technology, Government of India.
      </div>
    </div>

  </main>

  <footer class="mt-5">

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

      <div class="container d-flex justify-content-between align-items-baseline flex-wrap">
        <p class="dept-text text-white small">
          Last updated <span>31.03.2022</span>

        </p>

        <p class="dept-text text-white small">
          Copyright © <span> 2022 </span> |
          Government of Assam </p>
      </div>

    </section>



  </footer>

</body>

</html>