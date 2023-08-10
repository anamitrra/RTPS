$(document).ready(function () {

    /* Enable tooltips */

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });



    /* First, get application count data */
    $.get("https://rtps.assam.gov.in/apps/mis/api/get/status", function(data, status) {
        if (data.status) {
            /* Now get gender data */
            $.get("https://rtps.assam.gov.in/apps/mis/api/get/status/genderwise", function(genderData, status) {
                if (genderData.status) {
                   
                    $('.rcv .figure').text(data.received);
                    $('.dis .figure').text(data.delivered);
                    $('.del .figure').text(data.timely_delivered);
                    $('.pen .figure').text(data.pending);
                    $('.pentime .figure').text(data.pending_in_time);

                    const gendereText = $('div.rcv').attr('data-bs-original-title');

                    $('div.rcv').attr('data-bs-original-title', gendereText.replace('[m]', `${genderData.data.Male}`).replace('[f]', `${genderData.data.Female}`).replace('[o]', `${genderData.data.Others}`).replace('[na]', `${genderData.data.NA}`));
                    

                    // Now, Animate the numbers
                    $('.figure').each(function() {
                        $(this).prop('figure', 0).animate({
                            figure: $(this).text()
                        }, {
                            duration: 1500,
                            easing: 'swing',
                            step: function(now) {
                                $(this).text(Math.ceil(now));
                            }
                        });
                    });

                }
            });
        }
    });


});