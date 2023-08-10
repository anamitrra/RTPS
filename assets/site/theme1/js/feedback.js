$(document).ready(function () {

    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');

    refreshCaptchaRef.click(function () {
        $.get(captchaURL, function (response) {

            if (response.status) {
                captchaParentRef.html(response.captcha.image);
            }
            else {
                alert(errorMsg);
            }

        }).fail(function () {
            alert(errorMsg);
        });
    });


    /* Start Rating System */
    
    const allStarts = document.querySelectorAll('.star');
    const hiddenInput = document.querySelector('[name="rating"]');
    const clearRating = document.querySelector('#clear');

    function rateStart(userRating = 0) {
        hiddenInput.value = String(userRating);
                
        for (let i = 0; i < allStarts.length; i++) {

            if (i < userRating) {
                allStarts.item(i).classList.add('glow-star');
            }
            else {
                allStarts.item(i).classList.remove('glow-star');
            }

        }
    }

    for (const aStart of allStarts) {
        aStart.addEventListener('click', function (event) {
            rateStart(Number(this.dataset.value));
        });
    }

    clearRating.addEventListener('click', function (event) {
        rateStart();
    });

});