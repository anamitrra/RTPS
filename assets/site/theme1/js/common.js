function closeNewLoginModal() {
    const myModalEl = document.getElementById('newLoginModel');
    const modal = bootstrap.Modal.getInstance(myModalEl);
    modal.hide();

    // Clear the checked radios

    myModalEl.addEventListener('hidden.bs.modal', event => {
        document.querySelectorAll('#newLoginModel input[type="radio"]').forEach(function (radioBtn) {
            radioBtn.checked = false;
        });
    });
}


$(document).ready(function () {

    /* Enable tooltips globally */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });


    /* New Login popup click handler */
    $('.official-login-link').on('click', function (event) {

        event.preventDefault();

        const myModalEl = document.getElementById('newLoginModel');
        const modal = new bootstrap.Modal(myModalEl, { keyboard: true });
        modal.show();
    });


    // Portal Alert Dialog
    if (Boolean(siteAlertFlag) && (window.location.pathname == '/' || window.location.pathname == '/site' || window.location.pathname == '/site/' || window.location.pathname === '/rtps/site' || window.location.pathname === '/NIC/rtps/site')) {

        $('#footerModal .modal-title').html(siteAlertTitle);
        $('#footerModal .modal-body').html(siteAlertMsg);

        // make modal larger
        $('#footerModal > .modal-dialog').removeClass('modal-sm');
        $('#footerModal > .modal-dialog').addClass('modal-lg');

        const myModalEl = document.getElementById('footerModal');
        const modal = new bootstrap.Modal(myModalEl, { keyboard: true, backdrop: 'static' });
        modal.show();
    }


    /* Official loggin button */

    document.querySelector('#officialLogin').addEventListener('click', function (event) {
        if (document.querySelector('#nav-official input[type="radio"]:checked') == null) {
            alert('Please select an option');
            return true;
        }

        // Option selected

        switch (document.querySelector('#nav-official input[type="radio"]:checked').id) {
            case 'offL3':
                // dept user/ officials
                window.open(`${window.location.origin}/deptusr/loginWindow.do?servApply=N&<csrf:token%20uri=%27loginWindow.do%27/>`, '_blank');

                break;

            default:
                window.open(`${window.location.origin}/spservices/mcc/user-login`, '_blank');

                break;
        }

        closeNewLoginModal();

    });


});
