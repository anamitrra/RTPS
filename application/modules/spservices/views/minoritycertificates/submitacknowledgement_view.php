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
                <a href="<?=base_url('spservices/minority-certificate/')?>" class="btn btn-primary">
                    <i class="fa fa-angle-double-left"></i> Back to home
                </a>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>