$(document).ready(function () {

    /* Docs and Charges formating */

    function getList(aList = []) {
        let contentStr = '<ol>';

        for (const item of aList) {
            if (typeof item === 'string') {

                contentStr += `<li>${item}</li>`;
            }
            else if (Array.isArray(item)) {
                //
            }
            else {
                // Object
                contentStr += `<li>${getObject(item)}</li>`;
            }
        }

        contentStr += '</ol>';
        return contentStr;
    }

    function getObject(aObject = {}) {

        let contentStr = '';

        for (const property in aObject) {

            contentStr += `<p>${property} : </p>`;

            if (Array.isArray(aObject[property])) {
                contentStr += getList(aObject[property]);
            }
        }

        return contentStr;
    }

    function formatData(data) {

        let contentString = '';

        if (Array.isArray(data)) {
            // List

            contentString += getList(data);
        }
        else {
            // Object

            contentString = getObject(data);

        }

        return contentString;
    }


    // DataTable Config
    const table = $('#example')
        .DataTable({
            pageLength: 10,
            order: [[0, 'asc'], [1, 'asc']],
            scrollX: true,
            processing: true,
            deferRender: true,
            language: {
                url: langPath
            },
            ajax: {
                url: url,
                dataSrc: ''
            },
            columns: [
                { data: 'autonomous_council' },
                { data: 'department' },
                { data: 'rtps_service' },
                { data: 'notification_no' },
                { data: 'date_of_notification' },
                { data: 'designation_of_dps' },
                { data: 'designation_of_aa' },
                {
                    data: 'stipulated_timeline',
                    render: function (data, type, row) {

                        return `<span data-bs-toggle="tooltip" title="${row.tooltip}">${data}</span>`;
                    }
                },
                { data: 'documents' },
                { data: 'charges' },
                {
                    data: 'action',
                    defaultContent: `<button class="btn rtps-btn doc" style="margin-bottom: 0.5rem;" >${docBtn}</button>
        <button class="btn rtps-btn charge">${chargeBtn}</button>`
                },
            ],
            columnDefs: [
                { targets: [8, 9], visible: false },
                { targets: [4], className: 'dt-body-center' },
                { targets: [5, 6, 7], className: 'dt-body-left' },
                { targets: 2, width: "15%", }
            ]

        });


    // Required Docs Event Handling
    $('#example tbody').on('click', '.doc', function () {

        const data = table.row($(this).parents('tr')).data();
        // const data = table.row($(this)).data();

        $('#allServicesModal .modal-title').html(data['rtps_service']);
        $('#allServicesModal .modal-body').html(
            (data['documents']) ? formatData(data['documents']) : naMsg
        );


        const myModalEl = document.getElementById('allServicesModal');
        const modal = new bootstrap.Modal(myModalEl, { keyboard: true, backdrop: 'static' });
        modal.show();

    });

    // User Charge Event Handling
    $('#example tbody').on('click', '.charge', function () {

        const data = table.row($(this).parents('tr')).data();
        // const data = table.row($(this)).data();

        $('#allServicesModal .modal-title').html(data['rtps_service']);
        $('#allServicesModal .modal-body').html(
            (data['charges']) ? formatData(data['charges']) : naMsg
        );

        const myModalEl = document.getElementById('allServicesModal');
        const modal = new bootstrap.Modal(myModalEl, { keyboard: true, backdrop: 'static' });
        modal.show();

    });


    /* Listene for DataTable events */

    $('#example').on('draw.dt', function () {
        // Add/Remove Dark Mode

        switch (localStorage.getItem('rtps-dark-mode')) {
            case 'light':

                $('.dataTable .odd > td').removeClass('line-color');
                $('.dataTable .even > td').removeClass('line-color');
                $('.dataTables_info, .dataTables_length, .dataTables_filter').removeClass('line-color');
                $('.rtps-btn').removeClass('link-color');

                break;

            case 'dark':

                $('.dataTable .odd > td').addClass('line-color');
                $('.dataTable .even > td').addClass('line-color');
                $('.rtps-btn').addClass('link-color');
                $('.dataTables_info, .dataTables_length, .dataTables_filter').addClass('line-color');

        }

        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

    });

});
