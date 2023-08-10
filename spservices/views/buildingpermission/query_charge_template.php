<main class="rtps-container">
    <form method="POST" action="<?= base_url('spservices/buildingpermission/registration/cancel_form') ?>">
        <div class="container my-2">
            <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni
                </div>
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-md-12 text-center">
                            <?php
                            if($type == "bp"){?>
                            <b style="font-size: 18px;">Amount to be Paid for Building Permit:</b> <?php } ?>
                            <?php if($type == "pp"){?>
                            <b style="font-size: 18px;">Amount to be Paid for Planning Permit:</b><?php } ?>
                            <b style="font-size: 18px;"><?php print $charge;?></b>

                            <?php
                            if(isset($lc_charge)){
                            ?>
                            <br><br><b style="font-size: 18px;">Amount to be Paid for Labour Cess:</b>
                            <b style="font-size: 18px;"><?php print $lc_charge;?></b>
                            <?php  
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!--End of .card-body -->
                <div class="card-footer text-center no-print">
                    <?php if($type == "bp"){?>
                    <a class="btn btn-success" style="color: white;"
                        href="<?= base_url('spservices/buildingpermission/payment_bp_query/verify/'.$obj_id)?>">
                        <i class="fa fa-angle-double-right"></i> Pay Now
                    </a>
                    <?php } ?>

                    <?php if($type == "pp"){?>
                    <a class="btn btn-success" style="color: white;"
                        href="<?= base_url('spservices/buildingpermission/payment_pp_query/verify/'.$obj_id)?>">
                        <i class="fa fa-angle-double-right"></i> Pay Now
                    </a>
                    <?php } ?>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </div>
        <!--End of .container-->
    </form>
</main>