<style>
  /* body {visibility:hidden;} */
  .print {
    visibility: visible;
  }

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>payment_acknowledgment.css">

<div class="content-wrapper">
  <div class="container">
    <div class="row " id="print">
      <div class="col-sm-12 mx-auto">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
          <div class="card-body">
            <div class="row">
            <img src="<?= base_url("assets/site/sewasetu/assets/images/SS_LOGO.png") ?>" alt="Emblem" style="height:65px; width: 110px;margin: auto;" />
            </div>

            <div class="row">
              <h3 class="h2"><u>Payment Acknowledgement</u></h3>
            </div>

            <div style="margin: auto;width: fit-content;">
              <table >
                  <tr>
                    <td>APP REF NO</td>
                    <td><?=$app_ref_no?></td>
                  </tr> 
                  <tr>
                    <td>SERVICE NAME</td>
                    <td><?=$service_name?></td>
                  </tr> 
                  <tr>
                    <td>APPLICANT NAME</td>
                    <td><?=$applicant_name?></td>
                  </tr>
                  <tr>
                    <td>GRN</td>
                    <td><?=$GRN?></td>
                  </tr>
                   
                
                <tr>
                    <td>AMOUNT</td>
                    <td><?=$AMOUNT?></td>
                  </tr>
                <tr>
                    <td>BANKNAME</td>
                    <td><?=$BANKNAME?></td>
                  </tr>
                <tr>
                    <td>BANKCODE</td>
                    <td><?=$BANKCODE?></td>
                  </tr>
                <tr>
                    <td>PAYMENT DATE</td>
                    <td><?=$ENTRY_DATE?></td>
                  </tr>
                <tr>
                    <td>STATUS</td>
                    <td><?=$STATUS?></td>
                  </tr>
                <tr>
                    <td>PRN</td>
                    <td><?=$PRN?></td>
                  </tr>
                <tr>
                    <td>BANKCIN</td>
                    <td><?=$BANKCIN?></td>
                  </tr>
                <tr>
                    <td>TRANSCOMPLETIONDATETIME</td>
                    <td><?=$TRANSCOMPLETIONDATETIME?></td>
                  </tr>
              </table>
            </div>
         
         
            

         
           
      

            <!-- end of fee description -->

            <br />
            <br />
            <div class="row">
              <b>Thank You.</b>
            </div>
            <div class="row">
              <b>ARTPS</b>
            </div>
          </div>
        </div>
      </div>
    </div>




  
  <button type="button" id="btnPrint" class="btn btn-primary mb-2">
    Print
  </button>
  <?= !empty($back_to_dasboard) ? $back_to_dasboard : "" ?>
  <!-- <button type="button" id="" class="btn btn-danger mb-2" >
      Exit
    </button> -->
</div>
</div>
<script>
  $(function() {
    $("#btnPrint").on('click', function() {
      var prtContent = document.getElementById("print");
      var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
      WinPrint.document.write(prtContent.innerHTML);
      WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>payment_acknowledgment.css">');
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    })

 
  })
</script>
