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


<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {           
       
        
        $("#attachment1").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {

        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
             
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/offline/acknowledgement/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
          
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$service_name?>
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
                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Query Response </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                     <label>Remarks<span class="text-danger">*</span></label>
                                     <textarea class="form-control"  name="remarks"></textarea>
                                   
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                     <label>Document (If Any)</label>
                                     <div class="file-loading">
                                                    <input id="attachment1" name="attachment1" type="file" />
                                    </div>
                                   
                            </div>
                        </div>

                        
                        
                    </fieldset>
                    
                    
                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div>  -->
                    <!-- End of .row --> 
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-check"></i> Submit Query
                    </button>
                    <a class="btn btn-danger"  href="<?=base_url('iservices/transactions')?>">
                        <i class="fa fa-back"></i> Go Back
                    </a>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>