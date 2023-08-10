function formatNumber(num, notation = 'standard') {
    // console.log(num);

    return new Intl.NumberFormat('en-IN', {
        style: "decimal",
        // maximumSignificantDigits: 3,
        notation: notation
    }).format(num);
}

$(document).ready(function () {
    /* OWL slider */
    // $('.owl-carousel').owlCarousel({
    //     loop: true,
    //     // margin:10,
    //     responsiveClass: true,
    //     nav: false,
    //     center: true,
    //     autoplay: true,
    //     autoplayTimeout: 2000,
    //     autoplayHoverPause: true,
    //     responsive: {
    //         0: {
    //             items: 1
    //         },
    //         600: {
    //             items: 1
    //         },
    //         1000: {
    //             items: 5
    //         }
    //     }
    // });


    /* Service Search */
    $('#service-search').on('submit', function (event) {
        event.preventDefault();

        if ($('[name="service_name"]').val().trim().length >= 3) {

            event.target.submit();
        }
    });


    /* API to get dashboard data */
    // $.get(url_allServices, function (data, status) {
    //     if (data.status) {
    //         /* Now get gender data */
    //         $.get(url_genderData, function (genderData, status) {
    //             if (genderData.status) {

    //                 // console.log(data, genderData);

    //                 $('.rcv h2').text(data.data.received);
    //                 $('.dis h2').text(data.data.delivered + data.data.rejected);
    //                 $('.del h2').text(data.data.timely_delivered + data.data.rit);
    //                 $('.pen h2').text(data.data.pending);
    //                 $('.pentime h2').text(data.data.pending_in_time);

    //                 const gendereText = $('div.rcv').attr('data-bs-original-title');

    //                 $('div.rcv').attr('data-bs-original-title', gendereText.replace('[m]', `${genderData.data.Male}`).replace('[f]', `${genderData.data.Female}`).replace('[o]', `${genderData.data.Others}`).replace('[na]', `${genderData.data.NA}`));


    //                 // Now, Animate the numbers
    //                 $('.figures').each(function () {
    //                     $(this).prop('figures', 0).animate({
    //                         figures: $(this).text()
    //                     }, {
    //                         duration: 1500,
    //                         easing: 'swing',
    //                         step: function (now) {
    //                             // $(this).text(Math.ceil(now));

    //                             $(this).text(formatNumber(Math.ceil(now)));
    //                         }
    //                     });
    //                 });

    //             }
    //         });
    //     }
    // });




    /* Newly Lauched marquee section  */

    // $(".marquee").marquee({
    //     duration: 20000,
    //     delayBeforeStart: 0,
    //     direction: "left",
    //     pauseOnHover: true
    // });
    // setTimeout(() => {
    //     document.querySelector('.invisible').classList.remove('invisible');

    // }, 1000);


    // Login Error alert
    if (Boolean(loginError)) {
        $('#footerModal .modal-title').html(loginErrorTitle);
        $('#footerModal .modal-body').html(loginErrorMsg);

        const myModalEl = document.getElementById('footerModal');
        const modal = new bootstrap.Modal(myModalEl, { keyboard: true, backdrop: 'static' });
        modal.show();
    }

    // Flash Popup Model
    // const myModal = new bootstrap.Modal(document.getElementById('flashPopupModel'), {
    //     keyboard: false,
    //     backdrop: 'static',
    // });

    // Feedback ratings
   /*  $.get(url_feedback, function (data, status) {

        // console.log(data, status, typeof data);
        data = JSON.parse(data);
        const ratingValue = ((data.avg_submission + data.avg_delivered) / 2).toFixed(1);
        // console.log(ratingValue);

        const options = {
            // 0：svg  1：Font class  2：Unicode
            type: 0,
            // the number of stars
            length: 5,
            // allows half star
            half: true,
            // supports decimal?
            decimal: true,
            // is readonly?
            readonly: true,
            // shows the current rating value on hover
            hover: true,
            // shows rating text
            text: false,
            // an array of rating text
            textList: [],
            // color
            theme: '#FFC107',
            // text/star size
            size: '1.5rem',
            // space between stars
            gutter: '4px',
            // default CSS classes
            selectClass: 'fxss_rate_select',
            incompleteClass: '',
            customClass: ''
        }

        $('#rateBox').rate(Object.assign(options, { value: ratingValue }));
        $('#userRatingText').text(

            $('#userRatingText').text().replaceAll('[r]', (ratingValue + '/' + 5))

        );

    }); */


    // Counter Animation for Service counts
    $('.counter').counterUp({
        delay: 25,
        time: 3000
    });
});
