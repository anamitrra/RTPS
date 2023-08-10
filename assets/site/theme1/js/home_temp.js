$(document).ready(function () {
    
    /* OWL slider */
    $('.owl-carousel').owlCarousel({
        loop: true,
        // margin:10,
        responsiveClass: true,
        nav: false,
        center: true,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 5
            }
        }
    });

   

    // Sadbhavana Model
    // const myModal = new bootstrap.Modal(document.getElementById('sadbhavanaModel'), {
    //     keyboard: false,
    //     backdrop: 'static',
    // });
    // if (Boolean(showModel)) {
    //     myModal.show();
    //     setTimeout(() => {
    //         myModal.hide();
    //     }, 10000);
    // }
});
