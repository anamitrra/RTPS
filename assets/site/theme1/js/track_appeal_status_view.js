'use strict';

$(document).ready(function () {

    $(document).on("click", "#track_status_btn", function () {
        const reference_number = $("#reference_number").val().trim();

        if (reference_number.length <= 3) {
            alert(input_error);
        }
        else {
            getDetails(reference_number);
        }//End of if else        
    });//End of onClick #track_status_btn 
 
    function getDetails(refNo) {
        $.ajax({
            type: "POST",
            url: baseURL + "site/trackappealstatus/get_details",
            data: { "ref_no": refNo },
            beforeSend: function () {
                $("#details_div").html('<div class="loading"></div>');
            },
            success: function (res) {
                if (res === '') {
                    res = '<h2 class="my-4 text-capitalize text-center">' + notFound + '</h2>';
                }

                $("#details_div").html(res);
            },
            error: function (params) {
                alert(api_error);
                $("#details_div").html('');
            },
            complete: function () {
                // Hide the loader
            }

        });
    }//End of getDetails()
});
