

<div class="container"  style="background-color: #fff">
      <div class="row">
        <div class="col-md-12 ">
         
        </div>
     </div>
     <div class="content-wrapper">
    
        <h4>Application data</h4>
        <div class="card-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td><strong>RTPS NO</strong></td>
                  <td><?php echo $rtps_trans_id; ?></td>
                </tr>
                <tr>
                  <td><strong>App Ref No</strong></td>
                  <td><?php echo $app_ref_no; ?></td>
                </tr>  
              </tbody>
            </table>
          </div>

    <?php if(isset($status) && $status && $payment_type ==="KIOSK"){ ?>
        <div class="card-body">
            <table class="table table-striped">
              <tbody>
              <tr>
                  <td><strong> Department Id</strong></td>
                  <td><?=$department_id; ?></td>
                </tr>
                <tr>
                  <td><strong>GRN</strong></td>
                  <td><?= isset($payment_data) ? $payment_data->GRN : ""; ?></td>
                </tr>  
                <tr>
                  <td><strong>STATUS</strong></td>
                  <td><?= isset($payment_data) ? $payment_data->STATUS : ""; ?></td>
                </tr> 
              </tbody>
            </table>
            <?php if(!empty($department_id)){ ?>
                <a href="<?=base_url("iservices/admin/Transoprt_response/checkgrn/".$department_id)?>">Get GRN</a>
                <?php }?>


                <?php if(!empty($payment_data) && !empty($payment_data->GRN ) && ($payment_data->STATUS =="" || $payment_data->STATUS == "P")){ ?>
                    <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$payment_data->DEPARTMENT_ID?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$OFFICE_CODE?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$payment_data->AMOUNT?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('iservices/admin/get/cin-response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="GET CIN" />
                  </form>
                <?php }?>
          </div>
    <?php }elseif(isset($status) && $status && $payment_type ==="QUERY"){ ?>
        <div class="card-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td><strong>Query Department Id</strong></td>
                  <td><?=$query_department_id; ?></td>
                </tr>
                <tr>
                  <td><strong>GRN</strong></td>
                  <td><?= isset($payment_data) ? $payment_data->GRN : ""; ?></td>
                </tr>  
                <tr>
                  <td><strong>STATUS</strong></td>
                  <td><?= isset($payment_data) ? $payment_data->STATUS : ""; ?></td>
                </tr> 
              </tbody>
            </table>

            <?php if(!empty($query_department_id)){ ?>
                <a href="<?=base_url("spservices/Query_payment_response/checkgrn/".$query_department_id)?>">Get GRN</a>
                <?php }?>


                <?php if(!empty($payment_data) && !empty($payment_data->GRN ) && ($payment_data->STATUS =="" || $payment_data->STATUS == "P")){ ?>
                    <form method="post" name="getGRN" id="getGRN" action="<?=$this->config->item('egras_grn_cin_url')?>" >
                                                    <input type="hidden" id ="DEPARTMENT_ID" name="DEPARTMENT_ID" value="<?=$payment_data->DEPARTMENT_ID?>" />
                                                    <input type="hidden" id ="OFFICE_CODE" name="OFFICE_CODE" value="<?=$OFFICE_CODE?>" />
                                                    <input type="hidden" id ="AMOUNT" name="AMOUNT" value="<?=$payment_data->AMOUNT?>" />
                                                    <input type="hidden" id ="ACTION_CODE" name="ACTION_CODE" value="GETCIN" readonly/>
                                                    <input type="hidden" id ="SUB_SYSTEM" name="SUB_SYSTEM" value="ARTPS-SP|<?=base_url('spservices/get/query/cin-response')?>" />
                                                    <input type="submit" style="margin-top: 3px;color:white" id ="submit" class="btn btn-sm btn-warning mbtn" name="submit" target = "_BLANK" value="GET CIN" />
                  </form>
                <?php }?>
          </div>
    <?php }?>
       
       <?php
       echo "<pre>";
       print_r($d); 
       ?>
     </div>
</div>
