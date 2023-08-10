<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
  <?php //var_dump($saheb);die;?>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-4">
                          <form method="post" name="getGRN" id="getGRN" action="https://uatgras.assam.gov.in/challan/models/frmgetgrn.php" >
    <input type="text" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="AS201310A7379237" />
    <input type="text" id ="OFFICE_CODE" name="OFFICE_CODE" value="IGR013" />
    <input type="text" id ="AMOUNT" name="AMOUNT" value="30" />
    <input type="text" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
    <input type="text" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ECHLN" />
    <input type="submit" id ="submit" name="submit" target = "_BLANK" value="getCIN" />
    </form>

                    </div>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
