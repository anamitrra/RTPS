'use strict';

$(document).ready(function () {

    const myModal = new bootstrap.Modal(document.getElementById('thirdPartyVerModel'));

    // Clear input fields when modal is closed
    $('#thirdPartyVerModel').on('hidden.bs.modal', function (event) {
        $('.accordion input:not([type="hidden"])').val('');
    });

    $('form[method="post"]').on('submit', function (event) {
        event.preventDefault();

        const inputElm = $(this).find('input:not([type="hidden"])');
        const value = inputElm.val().trim();
        const name = inputElm.attr('name');

        // console.log(this == event.target);

        const db = $(this).find('input[type="hidden"][name="db"]').val();
        const service = $(this).find('input[type="hidden"][name="service"]').val();

        // console.log(name, value, db, service, );

        if (value.length < 3) {
            alert(invalidInput);
            return;
        }

        $.ajax({
            type: "POST",
            async: true,
            url: baseURL + "third-party-verification/get_details",
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: { [name]: value, 'db': db, 'service': (service ? service : null), },
            beforeSend: function () {
                // Show loader
                $(event.target).find('button[disabled]').removeClass('d-none');
                $(event.target).find('div.form_action').addClass('d-none');
            },
            success: function (res, status) {
                //called when successful

                // if (res === '') {
                //     res = '<h2 class="my-4 text-capitalize text-center text-danger">' + notFound + '</h2>';
                // }

                $('#details_div').html(res);

                // Show the modal

                myModal.show();

            },
            error: function (xhr, status, error) {
                //called when there is an error

                alert(`${status} : ${error}`);
            },
            complete: function (xhr, status) {
                //called when complete
                // Hide loader
                $(event.target).find('button[disabled]').addClass('d-none');
                $(event.target).find('div.form_action').removeClass('d-none');

            },

        });

    });

});
