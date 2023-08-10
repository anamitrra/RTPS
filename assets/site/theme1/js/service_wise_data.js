$(document).ready(function () {

    // Common Settings
    $.extend(true, $.fn.dataTable.defaults, {
        "pageLength": 10,
        "order": [[0, "asc"]],
        "processing": true,
        "deferRender": true,
        "language": {
            url: langPath
        },
    });

    var table = $('#tableServiceWise').DataTable({
        "ajax": url_service,
        "columnDefs": [
            {
                "targets": 0,
                "width": "40%",
                "className": 'dt-body-left'
            }
        ],
        "columns": [
            { "data": "service_name" },

            {
                "render": function (data, type, row) {

                    return '<span data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right" title="<span>' + male + ' : ' + row.gender_data.Male + '</span><br/>  <span>' + female + ' : ' + row.gender_data.Female + '</span> <br/> <span>' + others + ' : ' + row.gender_data.Others + '</span><br/> <span>' + na + ' : ' + row.gender_data.NA + '</span>">' + row.total_received + '</span>'
                }

            },
            { "data": "pending" },
            { "data": "pit" },

            {
                "render": function (data, type, row) {

                    return (row.delivered + row.rejected);
                }

            },

            // { "data": "timely_delivered" },
            {
                "render": function (data, type, row) {

                    return (row.timely_delivered + row.rit);
                }

            },
        ],

        "drawCallback": function (settings) {
            // Enable tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        },

        "initComplete": function (settings, json) {
            $("#tableServiceWise").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
        },

    });

    var table2 = $('#tableOfficeWise').DataTable({
        "ajax": url_office,
        "columnDefs": [
            {
                "targets": [0, 1],
                "width": "30%",
                "className": 'dt-body-left'
            }
        ],
        "columns": [
            { "data": "submission_location" },
            { "data": "department_name" },
            { "data": "total_received" },
            { "data": "pending" },
            { "data": "pit" },
            { 
                "data": "delivered",
                "render": function (data, type, row, meta) {
                    return (row.delivered + row.rejected);
                }
            },
            // { "data": "timely_delivered" },
            {
                "render": function (data, type, row) {

                    return (row.timely_delivered + row.rit);
                }

            },
        ],

        "initComplete": function (settings, json) {
            $("#tableOfficeWise").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
        },
    });


    // Listene for DataTable draw event
    $('table.table').on('draw.dt', function () {
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
    });

});
