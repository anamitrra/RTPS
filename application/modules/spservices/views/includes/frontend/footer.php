<!--FOOTER-->
<footer class="mb-0 mt-5">


    <div class="copyright py-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-start text-white small">
                    Designed &amp; Developed by <a href="https://assam.nic.in/" style="color: #fdc12d;" target="_blank" rel="noopener noreferrer"> <br>
                        National Informatics Centre, Assam </a>
                </div>
                <div class="col-md-4 text-start text-white small d-flex justify-content-md-center">
                    <img class="d-block" style="" src="<?= base_url('assets/site/theme1/images/nic.png') ?>" alt="NIC logo" width="100">
                </div>
                <div class="col-md-4 text-start text-white small">
                    Copyright &copy; <span> <?= date("Y") ?> </span> | Government of Assam
                </div>
            </div>
        </div>
    </div>

</footer>

<script>
    $(document).ready(function() {

        /* BS v5 updation changes */

        // Alerts
        $('div.alert > button').removeClass('close').addClass('btn-close').children('span').remove();

        // Form controls : selectbox, checkbox, labels
        $('select').addClass('form-select');
        $('input[type="checkbox"]').addClass('form-check');
        $('label').addClass('form-label');


    });
</script>


</body>

</html>