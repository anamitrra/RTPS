// api url

const api_url = `${baseURL}dashboard/top-services-last-month`;
const DARK_MODE_KEY = 'rtps-dark-mode';
const DEFAULT_DARK_MODE = 'light';
const DARK_MODE = 'dark';
const arr = [];


async function getapi(url) {

    try {

        // Storing response
        const response = await fetch(url);

        // Storing data in form of JSON
        var data = await response.json();
        // console.log(data);


        var queyString = data.reduce(function (acc, item) {
            return `${acc}${item.service_id},`
        }, '?mis_id=');

        queyString = queyString.replace(/,$/, '');
        //  console.log(queyString);

        $.ajax({
            url: baseURL + "site/get_popular" + queyString,
            method: 'GET',
            success: function (result, status, xhr) {
                // console.log(result);
                show_popular_services(result);
            },
            error: function (xhr, status, error) {
                // Portal API Error
            },
            complete: function (xhr, status) {
                // console.log('D:PORTAL');

                // Hide the loader
                $('#loading').addClass('d-none');

            }
        });

    }
    catch (error) {
        // MIS API Error
    }
    finally {
        // console.log('D:MIS');
        // Hide the loader
        $('#loading').addClass('d-none');
    }

}


getapi(api_url);


// Function to define innerHTML for HTML table
function show_popular_services(data) {
    let html = '';
    // console.log(data, siteLanguage, baseURL);

    html = data.reduce(function (acc, item) {

        return acc + `
    
    <a class="popw list-group-item" href="${baseURL}site/service-apply/${item.seo_url}">${item.service_name[siteLanguage]}</a>

    `;



    }, html);

    document.querySelector('.popular_services').innerHTML = html;

    switch (localStorage.getItem(DARK_MODE_KEY)) {

        case DEFAULT_DARK_MODE:     // saved :: light

            $('.popw').removeClass("darkpop");

            break;

        case DARK_MODE:          // saved :: dark

            $('.popw').addClass("darkpop");

    }

}
