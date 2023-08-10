<?php

if($dbrow) {
    $service_id = $dbrow->service_data->service_id;
    $service_name = $dbrow->service_data->service_name;
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $processing_history=isset( $dbrow->processing_history) ?  $dbrow->processing_history: false;
    
   
} else {
   die("something went wrong");

}//End of if else
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    ol li {
        font-size: 14px;
        font-weight: bold;
    }
    .btn-sm{
        font-size: 10px;
    }
</style>

        
<main class="rtps-container">
    <div class="container my-2">
      
           
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$rtps_trans_id?>
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    

                    <?php if($processing_history){ ?>
                        <fieldset class="border border-success">
                            <legend class="h5">Processing History </legend>
                             <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Processed By</th>
                                                <th>Action Taken</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($processing_history as $history){ ?>
                                                <tr>
                                                    <td><?=isset($history->processing_time) ? format_mongo_date($history->processing_time) : ""?></td>
                                                    <td><?=$history->processed_by?></td>
                                                    <td><?=$history->action_taken?></td>
                                                    <td><?=$history->remarks?>
                                                    <?php if(!empty($history->file_uploaded)){?>
                                                          <br/>
                                                          <a target="_blank" class="btn btn-sm btn-primary" href="<?=base_url($history->file_uploaded)?>">View Doc</a>  
                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                        </fieldset>
                    
                    <?php } ?> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-danger"  href="<?=base_url('iservices/transactions')?>">
                        <i class="fa fa-back"></i> Go Back
                    </a>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
       
    </div><!--End of .container-->
</main>