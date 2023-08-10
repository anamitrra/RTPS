$(document).ready(function () {
    // const domain = window.location.origin;
    const domain = "https://rtps.assam.gov.in/services";
    
    var trackAppForm = document.createElement("form");
    trackAppForm.method = "POST";
    trackAppForm.action = domain+"/citizenApplication.html";
    trackAppForm.target = "iframe_a";

    $('.citizen-iframe').prepend(trackAppForm);

    trackAppForm.submit();

});