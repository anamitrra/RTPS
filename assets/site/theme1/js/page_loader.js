/* Page Loader */
(
    function () {
        // Hide Loader
        window.addEventListener('load', function (event) {
            window.setTimeout(function () {
                
                document.querySelector('.loader-container').style.display = 'none';

            }, 1500);
        });
    }
)();