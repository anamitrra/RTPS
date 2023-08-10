

<div class="container-fluid"  style="background-color: #fff">
<div class="row">
        <div class="col-md-12 ">
          <?php 
          if(!empty($page_path)){
            $this->load->view($page_path,$intermediate_ids);
          }
    
          ?> 
        </div>
     </div>
<div class="content-wrapper">
    
        <div class="card">
          <div class="row">
          
          <div  class="col-sm-12 mx-auto">
            <button class="btn btn-default">
            <label>Payment Status : </label>  <?=$payment_status?>
            </button>
        
          </div>
          <div class="col-sm-12 mx-auto">
          <h4>Application data</h4>
              <?php
              echo "<pre>";
              print_r($preview);
              echo "</pre>";
              ?>
          </div>
      </div> 
        </div>
    
    <?php
            if(isset($payment_history) && !empty($payment_history)){ 
              ?>
                  <div class="row">
        
                    <div class="col-sm-12 mx-auto">
                    <h4>Payment History</h4>
                        <?php
                      
                        echo "<pre>";
                        print_r($payment_history);
                        echo "</pre>";
                        ?>
                    </div>
                </div>
            <?php }
    ?>

     </div>
</div>
