$(document).ready(function () {

    function showResultOnUI(result) {

        if (result.success) {
            const response = result.data;

            // Parsing response
            var key = Object.keys(response)[0];
            var status = response[key].detaillist;
            var finalStatus = '';

            if (status) {
                $(status).each(function (key, val) {
                    var sl = key + 1;
                    if (val !== "")
                        finalStatus += "<span>" + sl + ". " + val.statusDesc + "</span>";
                })
            }
            else {
                finalStatus = 'Unknown';
            }

            // Populate track data
            $('#applNo').html(response[key].currentlist[0].appl_no);
            $('#applDate').html(response[key].currentlist[0].appl_dt);
            $('#regNo').html(response[key].currentlist[0].regno);
            $('#desc').html(response[key].currentlist[0].purCdDescr);
            $('#regAt').html(response[key].currentlist[0].registeredAt);
            $('#curStatus').html(finalStatus);

            // display track data

            $('.track-data').removeClass('d-none');

        }
        else {
            // display track error message

            $('.alert').removeClass('d-none');
        }

    }


    function getCurrentData(data) {
        $.ajax(
            {
                url: 'https://dashboard.amtron.in/rtpsapp/external-portal/track-vahan',
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify(data),
                type: 'POST',
                dataType: 'json',

                beforeSend: function (xhr) {
                    // Show loading animation

                    $('#track-btn > div:first-child').addClass('d-none');
                    $('#track-btn > div:last-child').removeClass('d-none');
                  

                },
                success: function (result, status, xhr) {

                    showResultOnUI({
                        success: true,
                        data: result
                    });
                },
                error: function (xhr, status, error) {
                    // HTTP Error Handler : 404, 500 or NULL response

                    showResultOnUI({ success: false });
                },
                complete: function (xhr,status) {
                    // Hide loading animation

                    $('#track-btn > div:first-child').removeClass('d-none');
                    $('#track-btn > div:last-child').addClass('d-none');

                    $('#track-btn').blur();
                }
            }
        );
    }


    // Track Button Handler
    $('#track-btn').on('click', function (event) {
        event.preventDefault();

        $('.alert').addClass('d-none');
        $('.track-data').addClass('d-none');
        
        const applNo = $('#appl-no').val().trim();
        const stateCode = "AS";

        if (applNo !== '') {

            // Hide error mesages
            $('.form-text').addClass('d-none');

            // Send data to Server
            // getCurrentData({ applNo, stateCode });
            // console.log(applNo);

            const url = `https://rtps.assam.gov.in/iservices/status/external/v-check-status?vahan_app_no=${applNo}`;

            document.querySelector('#ext-link').setAttribute('href', url);

            var myModal = new bootstrap.Modal(document.getElementById('externalPortal'));
            myModal.show();

            // https://rtps.assam.gov.in/apps/iservices/status/external/v-check-status?vahan_app_no=
        }
        else {
            // Show error message
            $('.form-text').removeClass('d-none');

        }

    });

});
