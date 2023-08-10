<div class="content-wrapper">

    <div class="container">
        <?php //var_dump($saheb);die;
    ?>
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                Please wait..
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <form id="paymentForm" action="<?= $this->config->item('egras_url') ?>" method="post">
                                    <?php foreach ($department_data as $key => $field) { ?>
                                    <input type="hidden" name=<?= $key ?> value="<?= $field ?>" />
                                    <?php } ?>

                                    <!-- <button type="button" id="saveAndPFCAmount" class="btn btn-primary"  >
                                Make a payment
                              </button> -->
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentForm').submit();
</script>