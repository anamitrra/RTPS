$(document).ready(function () {
 
    /* Scroll the selected Department into Viewport */

    document.querySelector('#accordionPanelsStayOpenExample')
    .querySelector('div.show')
    .parentElement
    .scrollIntoView({
        behavior: 'smooth'
    });

});
