$(document).ready(function () {

    const DARK_MODE_KEY = 'rtps-dark-mode';
    const DEFAULT_DARK_MODE = 'light';
    const DARK_MODE = 'dark';

    function changeContrast(contrast = DEFAULT_DARK_MODE) {

        switch (contrast) {

            // dark
            case DARK_MODE:

                //sewasetu
                $('#main-contenet').addClass("darkpop");
                $('.container-fluid').addClass("darkpop");
                $('.card').addClass("darkpop");
                $('.list-group-item').addClass("darkpop");
                $('.login').addClass("darkpop");
                $('.track').addClass("darkpop");
                $('.card').addClass("darkcard");
                $('.topbt1').addClass("darkpop");
                $('.topbt2').addClass("darkpop");
                $('.screen-reader').addClass("darkpop");
                $('.top1').addClass("darkpop");
                $('.top2').addClass("darkpop");
                $('.top3').addClass("darkpop");
                $('.top4').addClass("darkpop");
                $('.search1').addClass("darkpop");
                $('.search2').addClass("darkpop");
                $('.footer-dark').addClass("darkpop");
                $('.footer-dark').addClass("darkcard");
                $('.logmodal').addClass("darkpop");
                $('.govt-title').addClass("line-color");
                $('.track-dark').addClass("darkpop");
                $('.searchbar').addClass("darkpop");
                $('.regbar').addClass("darkpop");
                $('.loginbar').addClass("darkpop");
                $('.trackbar').addClass("darkpop");
                $('.popw').addClass("darkpop");
                $('.dash').addClass("darkpop");

                // datatable
                $('.dataTables_info, .dataTables_length, .dataTables_filter').addClass('line-color');
                //$(' .table > td').addClass('line-color');


                // //$('#dropdownMenuButton1').addClass("darkpop");

                // //$('.container-fluid').addClass("darkmode");
                // $('.dashboard').addClass("dash-img");
                // $('main').addClass("darkmode");
                // $('body').addClass("darkmode");
                // $('.accordion-button:not(.collapsed)').addClass("darktext");
                // // $('.accordion-header').addClass("darkmode");
                // $('.new-artps-btn').addClass("logins");

                // $('.support').addClass("darklink");
                // $('a.nav-link.me-4.px-3').addClass("link-color");
                // $('a.nav-link.me-4').addClass("link-color");
                // $('.popular-services').addClass("background");
                // $('.query').addClass("queryback");
                // $('.accordion-item').addClass("darkbackground");
                // $('a.me-2.d-inline.text-decoration-none').addClass("link-color");
                // $('.about h4').addClass("line-color");
                // $('.about p').addClass("line-color");
                // $('.about span').addClass("line-color");
                // $('.accordion-button:not(.collapsed)').addClass("darkbackground");
                // $('footer a').addClass("link-color");
                // $('.service-cat p').addClass("link-color");
                // $('a.text-uppercase.text-decoration-none').addClass("link-color");
                // $('.support h4').addClass("line-color");
                // $('.services-icon h3').addClass("line-color");
                // $('.services-icon p').addClass("line-color");
                // $('.support h6').addClass("line-color");
                // $('.accordion-button').addClass("darkbackground");
                // $('footer .copyright').addClass("darkbackground");
                // $('.list-service').addClass("darkpop");
                // $('.inner-page-title').addClass("line-color");
                // $('.about-content').addClass("line-color");
                // $('.front_content_challenge_title').addClass("line-color");
                // $('.table').addClass("line-color");
                // $('.odd').addClass("line-color");
                // $('.text-center').addClass("line-color");
                // $('.faq-div').addClass("line-color");
                // $('.faq-section>label').addClass("line-color");
                // $('.faq-answer').addClass("line-color");
                // $('.kys-tab').addClass("line-color");
                // $('.transport-appl-para').addClass("line-color");
                // $('[type="search"]').addClass("darkpop");
                // $('.transport-appl-para').addClass("transparent");
                // $('.tab-pane>p>span').addClass("transparent");
                // $('#track-mod').addClass("darkpop");
                // $('.access').addClass("darkbackground");
                // $('.about a').addClass("link-color");
                // $('.abt').addClass("line-color");

                // //Screen Reader
                // $('.innerpage_section_title').addClass("darkbackground");
                // $('.field-content').addClass("darkbackground");
                // $('.Listdetails_info').addClass("darkbackground");

                // //feedback
                // $('.feed-head').addClass("line-color");
                // $('.feed-name').addClass("line-color");
                // $('.feed-email').addClass("line-color");
                // $('.feed-phone').addClass("line-color");
                // $('.feed-add').addClass("line-color");
                // $('.feed-feed').addClass("line-color");
                // $('#refreshCaptcha').addClass("line-color");


                // //Sitemap
                // $('.sitemap-title').addClass("darkpop-border");
                // $('.sitemap-content').addClass("darkpop");
                // $('.sitemap-content a').addClass("link-color");
                // $('.sitemap-content span.fw-bold').addClass("line-color");



                // //Elk Page
                // $('.serach-elk').addClass("darkpop");
                // $('.elk-result').addClass("darkpop-border px-3");
                // $('.mark').addClass("dark-yellow");
                // $('.elk-page').addClass("darkpop");
                // $('.elk-result h5').addClass('link-color');


                // // Adding borders
                // $('header').addClass("dark-mode-border");
                // $('section.container-fluid.brand-logos.darkmode').addClass("footer-border");
                // $('nav.navbar.navbar-expand-md.container-fluid.py-0.darkmode').addClass("footer-border");
                // $('section.container-fluid.py-2.darkmode').addClass("footer-border");
                // $('.list-group-item').addClass("list-group-border");

                // // Language change menu 
                // $('.dropdown-menu').addClass("darkpop");
                // $('.dropdown-item').addClass("darkpop");
                // $('a.dropdown-item.small.darkpop').addClass("darkpop:hover");


                // //service_cat
                // $('.my-3').addClass("line-color");
                // $('.btn-rtps').addClass("link-color");
                // $('.service-text').addClass("line-color");
                // $('.service-cat').addClass("queryback");

                // //login, track, all-service, footer Model,
                // $('.login-model').addClass("darkpop");
                // $('.track-dark').addClass("darkpop");
                // $('#allServicesModal .modal-content').addClass("darkpop");
                // $('#footerModal .modal-content').addClass("darkpop");
                // $('#alertModal .modal-content').addClass("darkpop");

                // // Nav links
                // $('.nav-pills .nav-link').addClass('link-color');
                // $('ol>li').addClass("line-color");


                // // Transport appl track page
                // $('.trans-card').addClass('darkpop');
                // $('.trans-card label').addClass('line-color');

                // $('.rtps-btn, .btn-rtps, .service-serach-btn').addClass('link-color');

                // // Videos Page
                // $('.videos .card-body').addClass('darkpop');



                // // service_forms page
                // $('#printable').addClass("darkbackground");

                // // Nav breadcrumbs
                // $('.breadcrumb-item').addClass("darklink");

                // // Brand Text
                // $('.heading-text h5, .heading-text p').addClass("line-color");

                // // New design
                // $('nav.navbar').addClass("darkbackground");
                // $('.top-panel .govt-title').addClass("text-white");
                // $('.error-404').addClass("darkpop");
                // $('footer .links').css("background-image", "none");

                // // Notice Board
                // $('.rtps-notification li').addClass("darkpop");

                // // Font change menu
                // $('.font-menu__content').addClass("darkpop");
                // $('.font-menu__content a').addClass("text-white");

                break;

            // light
            case DEFAULT_DARK_MODE:


                //sewasetu
                $('#main-contenet').removeClass("darkpop");
                $('.container-fluid').removeClass("darkpop");
                $('.card').removeClass("darkpop");
                $('.list-group-item').removeClass("darkpop");
                $('.login').removeClass("darkpop");
                $('.track').removeClass("darkpop");
                $('.card').removeClass("darkcard");
                $('.topbt1').removeClass("darkpop");
                $('.topbt2').removeClass("darkpop");
                $('.screen-reader').removeClass("darkpop");
                $('.top1').removeClass("darkpop");
                $('.top2').removeClass("darkpop");
                $('.top3').removeClass("darkpop");
                $('.footer-dark').removeClass("darkpop");
                $('.footer-dark').removeClass("darkcard");
                $('.logmodal').removeClass("darkpop");
                $('.search1').removeClass("darkpop");
                $('.search2').removeClass("darkpop");
                $('.track-dark').removeClass("darkpop");
                $('.govt-title').removeClass("line-color");
                $('.searchbar').removeClass("darkpop");
                $('.regbar').removeClass("darkpop");
                $('.loginbar').removeClass("darkpop");
                $('.trackbar').removeClass("darkpop");
                $('.popw').removeClass("darkpop");
                $('.dash').removeClass("darkpop");
                // datatable
                $('.dataTables_info, .dataTables_length, .dataTables_filter').removeClass('line-color');
                $('.dataTable .odd > td').removeClass('line-color');
                $('.dataTable .even > td').removeClass('line-color');




            // $('.container-fluid').removeClass("darkmode");
            // $('.dashboard').removeClass("dash-img");
            // $('main').removeClass("darkmode");
            // $('body').removeClass("darkmode");
            // $('.accordion-button:not(.collapsed)').removeClass("darktext");
            // $('.new-artps-btn').removeClass("login");
            // $('a.nav-link.me-4.px-3').removeClass("link-color");
            // $('a.nav-link.me-4').removeClass("link-color");
            // $('a.me-2.d-inline.text-decoration-none').removeClass("link-color");
            // $('.support').removeClass("darklink");
            // $('.popular-services').removeClass("background");
            // $('.query').removeClass("queryback");
            // $('.accordion-item').removeClass("darkbackground");
            // $('a.me-2.d-inline.text-decoration-none').removeClass("link-color");
            // $('.about h4').removeClass("line-color");
            // $('.about p').removeClass("line-color");
            // $('.about span').removeClass("line-color");
            // $('.accordion-button:not(.collapsed)').removeClass("darkbackground");
            // $('footer a').removeClass("link-color");
            // $('.support h4').removeClass("line-color");
            // $('.services-icon h3').removeClass("line-color");
            // $('.services-icon p').removeClass("line-color");
            // $('.support h6').removeClass("line-color");
            // $('.accordion-button').removeClass("darkbackground");
            // $('.service-cat p').removeClass("link-color");
            // $('footer .copyright').removeClass("darkbackground");
            // $('a.text-uppercase.text-decoration-none').removeClass("link-color");
            // $('.list-service').removeClass("darkpop");
            // $('.inner-page-title').removeClass("line-color");
            // $('.about-content').removeClass("line-color");
            // $('.table').removeClass("line-color");
            // $('.odd').removeClass("line-color");
            // $('.text-center').removeClass("line-color");
            // $('.faq-div').removeClass("line-color");
            // $('.faq-section>label').removeClass("line-color");
            // $('.faq-answer').removeClass("line-color");
            // $('.kys-tab').removeClass("line-color");
            // $('.transport-appl-para').removeClass("line-color");
            // $('[type="search"]').removeClass("darkpop");
            // $('.front_content_challenge_title').removeClass("line-color");
            // $('#track-mod').removeClass("darkpop");
            // $('.access').removeClass("darkbackground");
            // $('.about a').removeClass("link-color");
            // $('.abt').removeClass("line-color");



            // //Screen Reader
            // $('.innerpage_section_title').removeClass("darkbackground");
            // $('.field-content').removeClass("darkbackground");
            // $('.Listdetails_info').removeClass("darkbackground");

            // //feedback
            // $('.feed-head').removeClass("line-color");
            // $('.feed-name').removeClass("line-color");
            // $('.feed-email').removeClass("line-color");
            // $('.feed-phone').removeClass("line-color");
            // $('.feed-add').removeClass("line-color");
            // $('.feed-feed').removeClass("line-color");
            // $('#refreshCaptcha').removeClass("line-color");

            // //Sitemap
            // $('.sitemap-title').removeClass("darkpop-border");
            // $('.sitemap-content').removeClass("darkpop");
            // $('.sitemap-content a').removeClass("link-color");
            // $('.sitemap-content span.fw-bold').removeClass("line-color");



            // //Elk Page
            // $('.serach-elk').removeClass("darkpop");
            // $('.elk-result').removeClass("darkpop-border px-3");
            // $('.mark').removeClass("dark-yellow");
            // $('.elk-page').removeClass("darkpop");
            // $('.elk-result h5').removeClass('link-color');

            // // Remove borders
            // $('header').removeClass("dark-mode-border");
            // $('section.container-fluid.brand-logos.darkmode').addClass("footer-border");
            // $('nav.navbar.navbar-expand-md.container-fluid.py-0.darkmode').addClass("footer-border");
            // $('section.container-fluid.py-2.darkmode').removeClass("footer-border");
            // $('.list-group-item').removeClass("list-group-border");

            // // Language change menu 

            // $('.dropdown-menu').removeClass("darkpop");
            // $('.dropdown-item').removeClass("darkpop");
            // $('a.dropdown-item.small.darkpop').removeClass("darkpop:hover");


            // //service_cat
            // $('.my-3').removeClass("line-color");
            // $('.btn-rtps').removeClass("link-color");
            // $('.service-text').removeClass("line-color");
            // $('.service-cat').removeClass("queryback");

            // //login, track, allService, footer Model
            // $('.login-model').removeClass("darkpop");
            // $('.track-dark').removeClass("darkpop");
            // $('#allServicesModal .modal-content').removeClass("darkpop");
            // $('#footerModal .modal-content').removeClass("darkpop");
            // $('#alertModal .modal-content').removeClass("darkpop");

            // // Nav links
            // $('.nav-pills .nav-link').removeClass('link-color');
            // $('ol>li').removeClass("line-color");

            // // Transport appl track page
            // $('.trans-card').removeClass('darkpop');
            // $('.trans-card label').removeClass('line-color');

            // $('.rtps-btn, .btn-rtps, .service-serach-btn').removeClass('link-color');

            // // Videos Page
            // $('.videos .card-body').removeClass('darkpop');

            // // datatable
            // $('.dataTables_info, .dataTables_length, .dataTables_filter').removeClass('line-color');
            // $('.dataTable .odd > td').removeClass('line-color');

            // // service_forms page
            // $('#printable').removeClass("darkbackground");

            // // Nav breadcrumbs
            // $('.breadcrumb-item').removeClass("darklink");

            // // Brand Text
            // $('.heading-text h5, .heading-text p').removeClass("line-color");

            // // New design
            // $('nav.navbar').removeClass("darkbackground");
            // $('.top-panel .govt-title').removeClass("text-white");
            // $('.error-404').removeClass("darkpop");

            // $('footer .links').css("background-image", "url('/assets/site/theme1/images/rim.svg')");

            // // Notice Board
            // $('.rtps-notification li').removeClass("darkpop");

            // // Font change menu
            // $('.font-menu__content').removeClass("darkpop");
            // $('.font-menu__content a').removeClass("text-white");
        }

        window.localStorage.setItem(DARK_MODE_KEY, contrast);

    }

    // Set default contrast

    const contrast = (localStorage.getItem(DARK_MODE_KEY) === DARK_MODE) ? DARK_MODE : DEFAULT_DARK_MODE;

    /* Change website's contrast */
    changeContrast(contrast);


    // Change contrast
    $('.contrast').on('click', function (e) {
        e.preventDefault();


        switch (localStorage.getItem(DARK_MODE_KEY)) {

            case DEFAULT_DARK_MODE:     // saved :: light

                changeContrast('dark');

                break;

            case DARK_MODE:          // saved :: dark

                changeContrast('light');

        }

    });

});
