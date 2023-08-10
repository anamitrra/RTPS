<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<div class="container">
    <div class="row " id="print">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <div class="row" >
                    <h3 class="h2" style="background:black;color:white">NOC for Immovable Application Receipt</h3>
                  </div>

                  <div class="row">
                        <table>
                          <tr>
                            <td><b>Sl No:</b></td>
                            <td><?=isset($application_details->slno)?$application_details->slno:''?></td>
                          </tr>
                          <tr>
                            <td><b>Application No:</b></td>
                            <td><?=isset($app_ref_no)?$app_ref_no:''?></td>
                          </tr>
                          <tr>
                            <td><b>Application Date:</b></td>
                            <td><?=isset($submission_date)?$submission_date:''?></td>
                          </tr>
                          <?php if ($applicant_details): ?>
                              <?php foreach ($applicant_details as $key => $applicant): ?>
                                <tr>
                                  <td><b>Name of the Applicant:</b></td>
                                  <td><?=isset($applicant->applicant_name)?$applicant->applicant_name:''?></td>
                                </tr>
                                <tr>
                                  <td><b>Address of Applicant:</b></td>
                                  <td>
                                    <?=isset($applicant->address_line_1)?$applicant->address_line_1.", ":''?>
                                    <?=isset($applicant->address_line_2)?$applicant->address_line_2.", ":''?>
                                    <?=isset($applicant->district)?$applicant->district:''?>
                                    <?=isset($applicant->pin_code)?$applicant->pin_code:''?>
                                    <?=isset($applicant->state)?$applicant->state:''?>,
                                    <?=isset($applicant->country)?$applicant->country:''?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><b>Mobile No:</b></td>
                                  <td><?=isset($applicant->mobile_number)?$applicant->mobile_number:''?></td>
                                </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>

                          <?php if (isset($expected_dt)): ?>
                            <tr>
                              <td><b>Expected Date of Issue of Permission:  </b></td>
                              <td><?=isset($expected_dt)?$expected_dt:''?></td>
                            </tr>
                          <?php endif; ?>

                          <tr>
                            <td><b>Application Fee:</b></td>
                            <td><?=isset($amount)?$amount:''?></td>
                          </tr>
                        </table>
                  </div>

                </div>
            </div>
        </div>
    </div>
    <button type="button" id="btnPrint" class="btn btn-primary mb-2" >
      Print
    </button>
    <!-- <button type="button" id="" class="btn btn-danger mb-2" >
      Exit
    </button> -->
</div>
<script>
$(function(){
  $("#btnPrint").on('click',function(){
    var prtContent = document.getElementById("print");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
  })
})
</script>
