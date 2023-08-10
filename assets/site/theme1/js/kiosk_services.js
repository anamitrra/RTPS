'use strict';

var mulText = [
    {
        'en': 'Please wait...',
        'as': 'অনুগ্ৰহ কৰি ৰওক...',
        'bn': 'দয়া করে অপেক্ষা করো...'
    },
    {
        'en': 'Proceed',
        'as': 'আগবাঢ়ক',
        'bn': 'অগ্রসর'
    }
];


$(document).ready(function () {

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

        });


    /* Listener for DataTable events */

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

    });


    // Check for service sub-categories first
    if (document.querySelector('section.sub-cat-filters')) {

        serviceSubCategories();
    }

});


function serviceSubCategories() {

    document.querySelector('section.sub-cat-filters').addEventListener('click', function (event) {

        if (event.target.nodeName === 'BUTTON' || event.target.nodeName === 'IMG') {

            let selectedBtn = event.target;

            if (event.target.nodeName === 'IMG') {
                selectedBtn = event.target.parentElement;
            }

            const subcategory = selectedBtn.dataset.subcat; // Selected subcategory
            const category = selectedBtn.dataset.categ; // Selected category

            // Add focused state class
            document.querySelectorAll('section.sub-cat-filters > button').forEach(function (elm) {
                elm.classList.remove('service-cat-btn__focus');
                selectedBtn.classList.add('service-cat-btn__focus');
            });


            // URL
            const URL = `${baseURL}site/online/services-by-category/${category}/${subcategory}`;
            // console.log(URL);

            // Display loader
            displayLoader();

            window.fetch(URL)
                .then(function (response) {
                    if (response.ok) {
                        return response.json();
                    }

                    alert('Sorry, could not fetch the data. Please retry.');
                })
                .then(function (data) {
                    // Refresh datatable with new dataset

                    const table = $('#example').DataTable();
                    table.clear().draw();
                    table.rows.add(data.map(function (value, index) {
                        return [
                            value.service_name,
                            value.department_name,
                            `<a class="btn rtps-btn" role="button" href="${baseURL}/site/service-apply/${value.seo_url}">${mulText[1][siteLanguage]}</a>`
                        ];
                    })).draw();

                    // console.log(data, siteLanguage);

                })
                .catch(function (error) {
                    window.alert(error);
                })
                .finally(function () {
                    // Hide the loader
                    window.setTimeout(function () {
                        document.querySelector('p.loading_categ').remove();

                    }, 500);  // in ms

                });
        }

    });
}

function displayLoader() {
    const dtTable = document.querySelector('#example_wrapper');
    if (dtTable) {
        dtTable.style.position = 'relative';
        // Inject the <P></P>

        const pTag = document.createElement('p');
        pTag.classList.add('loading_categ');
        pTag.innerText = mulText[0][siteLanguage];
        dtTable.prepend(pTag);

    }
}
 