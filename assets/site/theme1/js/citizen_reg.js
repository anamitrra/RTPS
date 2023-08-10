$(document).ready(function () {
    // const domain = window.location.origin;
    // const domain = "https://rtps.assam.gov.in/services";
    const domain = `${window.location.origin}/services`;
    
    var trackAppForm = document.createElement("form");
    trackAppForm.method = "POST";
    trackAppForm.action = domain+"/citizenRegistration.html";
    trackAppForm.target = "iframe_a";

    $('.citizen-iframe').append(trackAppForm);

    trackAppForm.submit();

});