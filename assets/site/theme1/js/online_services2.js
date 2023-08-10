$(".list-group-item").click(function() {
    $('#service_data').empty();
    $(".department-column")
        .animate({
            opacity: '0.8',
            width: "20%"
        }, {
            duration: 250,
        });
    // $('.loader').addClass('d-none');
    const loader = $(this).find('.loader');
    loader.fadeIn('slow');
    const seo_url = $(this).attr('data-seo');
    $.ajax({
            method: "GET",
            url: "http://localhost/rtps/site/overview/application-form-for-registration-of-cooperative-society-with-limited-liability-under-the-assam-cooperative-societies-act-2007-btad",
            // url: ""+seo_url+"",
        })
        .done(function(msg) {
            $('#services-content').css('width', '80%');
            //$('#service_data').removeClass('d-none');
            $('#tabContent').hide();
            loader.hide();
            $('#service_data').append(msg);
            $('#service_data').fadeIn('slow');
            //alert("Data Saved: " + msg);
        });
});
$(".department-name-links").click(function() {
    $('#service_data').hide();
    $('#tabContent').fadeIn('slow');
    $('#services-content').css('width', '66.6666666667%');
    $(".department-column")
        .animate({
            opacity: '0.8',
            width: "33.33333333%"
        }, {
            duration: 500,
        });
});