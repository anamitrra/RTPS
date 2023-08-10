$(document).ready(function () {

    // DataTable Config
    const table = $('#example')
        .DataTable({
            pageLength: 10,
            order: [[0, 'asc'], [1, 'asc']],
            scrollX: true,
            processing: true,
            deferRender: true,
            
            columnDefs: [
                { orderable: false, targets: 2 }
            ],
        });


});