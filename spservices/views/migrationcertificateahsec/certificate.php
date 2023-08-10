<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#printBtn", function() {
            $("#printDiv").print({
                addGlobalStyles: true,
                stylesheet: null,
                rejectWindow: true,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null
            });
        });
        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if (clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            } //End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>
<style>
/* body {visibility:hidden;} */
.print {
    visibility: visible;
}

.text-size {
    font-size: 17px;
    line-height: 50px;
}

.watermark {
    background-image: url("/rtps/assets/frontend/images/logo_ahsec1.png");
    /* Additional CSS properties for positioning and styling the div */
   
    background-size: cover;
}
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<?php


?>
<div class="content-wrapper">
    <div class="container ">
        <div class="row " id="print">
            <div class="col-sm-12">
                <div class="card my-4 watermark" style="border: 1px solid; padding:5px;">
                    <div class="card-body ">
                        <form id="myfrm" method="POST"
                            action="<?= base_url('spservices/upms/dscsign/withoutpdfsigned/') ?>"
                            enctype="multipart/form-data">
                            <input id="obj_id" name="obj_id" value="<?= $dbrow->_id->{'$id'} ?>" type="hidden" />
                            <input id="certificate_no" name="certificate_no" value="<?= $certificate_no ?>"
                                type="hidden" />
                                <input id="certificate_path" name="certificate_path" value="<?= $certificate_path ?>" type="hidden" />

                            <div class="row">
                                <img class="img"
                                    src="https://upload.wikimedia.org/wikipedia/en/e/e1/Assam_higher_secondary_education_council_logo.png"
                                    width="100" height="120" />
                                <br />
                               
                            </div>
                            <div class="row">
                                <h3 class="h2">ASSAM HIGHER SECONDARY EDUCATION COUNCIL</h3>

                            </div>
                            <div class="row">
                                <h3 class="h2" style="color: red;"><b>Migration Certificate</b></h3>

                            </div>

                            <br />
                            <br />
                            <br />
                            <br />
                            <div class="row akno">
                                <span><u>Sl. No.:
                                <?= $certificate_no ?></u></span>
                                <span style="float: right;"><u>Date: 7/6/2023</u></span>
                                
                            </div>
                            <div class="row">
                                <div style="col-md-12 text-align:justify; line-height: 50px; font-size:18px;">

                                    This Certificate is issued in respect of
                                    <strong><?=ucwords($dbrow->form_data->applicant_name)?></strong>
                                    <br />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 text-size">

                                    Son/Daughter of
                                </div>

                                <div class="col-sm-8 text-size">
                                    <strong><?=ucwords($dbrow->form_data->father_name)?></strong>

                                </div>

                                <div class="col-sm-2 text-size">
                                    (Father)

                                </div>
                                <div class="col-sm-2 text-size">


                                </div>

                                <div class="col-sm-8 text-size">
                                    <strong><?=ucwords($dbrow->form_data->mother_name)?></strong>

                                </div>

                                <div class="col-sm-2 text-size">
                                    (Mother)

                                </div>
                                <div class="col-sm-3 text-size">
                                    under Registration No.
                                </div>

                                <div class="col-sm-9 text-size">
                                    <strong><?=ucwords($dbrow->form_data->ahsec_reg_no)?> / 
                                    <?= $dbrow->form_data->ahsec_reg_session ?>
                                </strong>

                                </div>

                                <div class="col-sm-12 text-size">
                                    that this council has no objection to his continuing studies at
                                    another University/Board.

                                </div>



                            </div>


                            <br />
                            <br />
                            <br />
                            <br />
                            <div class="row">
                                <div class="col-md-4 text-center" style="font-size:16px;">

                                    <br />
                                    <b>Dy. Secretary(RRR)<br />AHSEC</b>
                                </div>
                                <div class="col-md-4">
                                    <!-- <p style="font-size: 30px; color:red;">Internet Copy</p> -->
                                </div>
                                <div class="col-md-4 text-center">
                                    <b style="font-size:16px;">Sd/-</b>
                                    <br>
                                    <strong style="font-size:16px;">Secretary</strong>
                                    <br>
                                    <p style="font-style:12px;"> Assam Higher Secondary Education Council

                                        Bamunimaidam, Guwahati-21</p>
                                </div>

                            </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- <button type="button" id="btnPrint" class="btn btn-primary mb-2">
            <i class="fas fa-print"></i> Print</button> -->
    </div>
    <div class="card-footer text-center no-print">
    <br />
                            <br />
                            <br />
        <?php if ($user_type == 'official') { ?>
        <a href="<?= base_url('spservices/upms/myapplications/process/' . $dbrow->_id->{'$id'}) ?>"
            class="btn btn-primary">
            <i class="fa fa-angle-double-left"></i> BACK
        </a>
        <?php } ?>
        <button class="btn btn-info" id="printBtn" type="button">
            <i class="fa fa-print"></i> PRINT
        </button>
        <?php if ($user_type == 'official') { ?>
        <button class="btn btn-success frmbtn" id="SAVE" type="button">
            <i class="fa fa-check" aria-hidden="true"></i> DELIVER
        </button>
        <?php } ?>
        
    </div>
</div>
</form>
<script>
$(document).ready(function() {

    $("#btnPrint").on('click', function() {
        printDiv('print');

    });


    $("#btnPrintAssamese").on('click', function() {

        printDiv('printAssamese');

    });


    function printDiv(divname) {
        var divContents = document.getElementById(divname).innerHTML;
        var a = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        a.document.write('<html>');
        a.document.write('<body >  <br>');
        a.document.write(divContents);
        a.document.writeln(
            '<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">'
        );
        a.document.write('</body></html>');
        a.focus();
        a.print();
        a.document.close();

    }

});
</script>
<?php
function calculateTotal($response){
  $total=0;
  if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y"){

    if(!empty($response->no_printing_page)){
      $total  +=  ($response->no_printing_page) *($response->printing_charge_per_page);
    }

    if(!empty($response->no_scanning_page)){
      $total +=  ($response->no_scanning_page) *($response->scanning_charge_per_page);
    }
    if(!empty($response->rtps_convenience_fee)){
        $total += $response->rtps_convenience_fee;
    }
    if(!empty($response->service_charge)){
        $total += $response->service_charge;
    } 

    if(property_exists($response,'amountmut') && !empty($response->amountmut)){
        $total += floatval($response->amountmut);
    } 
    if(property_exists($response,'amountpart') && !empty($response->amountpart)){
        $total += floatval($response->amountpart);
    }
    if(!empty($response->amount)){
          $total += floatval($response->amount);
    }


  }else {
    if(!empty($response->amount)){
          $total += $response->amount;
    }
      
  }
  return $total.".00";
}
?>