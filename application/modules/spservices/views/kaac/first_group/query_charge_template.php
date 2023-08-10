<main class="rtps-container">
    <form method="POST" action="">
        <div class="container my-2">
            <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    KAAC AMOUNT PAYMENT
                </div>
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-md-12 text-center">
                                <b style="font-size: 18px;">Total Payable Amount:</b>
                            <b style="font-size: 18px;"><?php print $amount;?></b>
                        </div>
                    </div>
                </div><!--End of .card-body -->
                <div class="card-footer text-center no-print">
                    <a class="btn btn-success" style="color: white;" href="<?= base_url('spservices/kaac/payment_query/verify/'.$obj_id)?>">
                        <i class="fa fa-angle-double-right"></i> Pay Now
                    </a>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </div><!--End of .container-->
    </form>
</main>