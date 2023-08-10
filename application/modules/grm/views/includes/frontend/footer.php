<!--FOOTER-->
<?php
$this->load->model('site/settings_model');
$footer = $this->settings_model->get_settings('footer_new');
$lang = $this->rtps_lang;
// $theme = $this->theme;
// pre($header);

?>

<footer class="mb-0 mt-5">

    <div class="copyright py-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-start text-white small">
                    <?= $footer->nic->title->$lang  ?>
                    <br>
                    <a href="https://assam.nic.in/" style="color: #fdc12d;" target="_blank" rel="noopener noreferrer">
                        National Informatics Centre, Assam
                    </a>
                </div>
                <div class="col-md-4 text-start text-white small d-flex justify-content-md-center">
                    <img class="d-block" style="" src="<?= base_url('assets/site/theme1/images/nic.png') ?>" alt="NIC logo" width="100">
                </div>
                <div class="col-md-4 text-start text-white small">
                    <?= $footer->copyright->t1->$lang  ?> &copy; <span> <?= date("Y") ?> </span> | <?= $footer->copyright->t2->$lang  ?>
                </div>
            </div>
        </div>
    </div>

</footer>


</body>

</html>