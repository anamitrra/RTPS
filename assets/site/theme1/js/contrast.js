$(document).ready(function () {

    const DARK_MODE_KEY = 'rtps-dark-mode';
    const DEFAULT_DARK_MODE = 'light';
    const DARK_MODE = 'dark';

    function changeContrast(contrast = DEFAULT_DARK_MODE) {

        switch (contrast) {

            // dark
            case DARK_MODE:
                $('body').addClass("darkmode");
            
                $('nav').addClass("darkbackground");

                $('a').addClass('link-color');
                $('hr').addClass('line-color');
                $('footer').addClass('darkbackground');
                $('.card').addClass('darkbackground');
                $('.list-group-item').addClass('darkbackground');
                $('.accordion-button').addClass('darkbackground');
                $('.table').addClass('darkbackground');
                $('.table tr.odd td').addClass('darkbackground');
                $('#service-requirments li').addClass('darkbackground');
                
                $('.faq-section, .faq-answer').addClass('darkbackground');

               
    
                $('.modal-content').addClass('darkpop');
               

                // Adding borders
                $('header').addClass("dark-mode-border");
                $('.card').addClass("card-border");
                $('footer').addClass("footer-border");
                $('.list-group-item').addClass("list-group-border");

                // Language change menu 
                $('.top-panel .dropdown-menu').addClass('darkbackground');


            break;

            // light
            case DEFAULT_DARK_MODE:

                $('body').removeClass("darkmode");
            
                $('nav').removeClass("darkbackground");

                $('a').removeClass('link-color');
                $('hr').removeClass('line-color');
                $('footer').removeClass('darkbackground');
                $('.card').removeClass('darkbackground');
                $('.list-group-item').removeClass('darkbackground');
                $('.accordion-button').removeClass('darkbackground');
                $('.table').removeClass('darkbackground');
                $('.table tr.odd td').removeClass('darkbackground');
                $('#service-requirments li').removeClass('darkbackground');
                
                $('.faq-section, .faq-answer').removeClass('darkbackground');

               
    
                $('.modal-content').removeClass('darkpop');
               

                // Adding borders
                $('header').removeClass("dark-mode-border");
                $('.card').removeClass("card-border");
                $('footer').removeClass("footer-border");
                $('.list-group-item').removeClass("list-group-border");

                // Language change menu 
                $('.top-panel .dropdown-menu').removeClass('darkbackground');

        }

        window.localStorage.setItem(DARK_MODE_KEY, contrast);

    }

    // Set default contrast

    const contrast = (localStorage.getItem(DARK_MODE_KEY) === DARK_MODE) ? DARK_MODE : DEFAULT_DARK_MODE;

    /* Change website's contrast */
    changeContrast(contrast);


    // Change contrast
    $('#contrast').on('click', function (e) {
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
