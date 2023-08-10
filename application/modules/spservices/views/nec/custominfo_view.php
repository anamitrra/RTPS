<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="text-align: center; font-size: 24px; color: #000; font-family: georgia,serif; font-weight: bold; text-transform: uppercase">
                   <?=$msg_title?> 
            </div>
            <div class="card-body" style="padding:50px; font-size: 18px; text-align: center">
                <?=$msg_body?>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($this->session->role) { ?>
                    <a href="<?=base_url('iservices/admin/my-transactions')?>" class="btn btn-primary mb-2 text-center">
                        <i class="fas fa-angle-double-left"></i> Back
                    </a>
                <?php } else { ?>
                    <a href="<?=base_url('iservices/transactions')?>" class="btn btn-primary mb-2 text-center">
                        <i class="fas fa-angle-double-left"></i> Back
                    </a>
                <?php }//End of if else ?>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>