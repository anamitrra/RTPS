<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$mutation_doc_type = $dbrow->form_data->mutation_doc_type??null;
$revenue_receipt_type = $dbrow->form_data->revenue_receipt_type??null;
$other_doc_type = $dbrow->form_data->other_doc_type??null;    
$mutation_doc = $dbrow->form_data->mutation_doc??null;
$revenue_receipt = $dbrow->form_data->revenue_receipt??null;
$other_doc = $dbrow->form_data->other_doc??null;
$remarks = $dbrow->form_data->remarks??null;
$queried_time = isset($dbrow->form_data->query_time)?date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->form_data->query_time))):'';
$oldFiles = array(
    "mutation_doc" => $mutation_doc,
    "revenue_receipt" => $revenue_receipt,
    "other_doc" => $other_doc,
);
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        
        var landPatta = parseInt(<?=strlen($mutation_doc)?1:0?>);
        $("#mutation_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: landPatta?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var khajnaReceipt = parseInt(<?=strlen($revenue_receipt)?1:0?>);
        $("#revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: khajnaReceipt?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE_NEXT') {
                var msg = "Once you submitted, you won't able to revert this";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) { //alert(clickedBtn+" : "+result.value);
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE_NEXT')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/mutationorder/query/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="mutation_doc_old" value="<?=$mutation_doc?>" type="hidden" />
            <input name="revenue_receipt_old" value="<?=$revenue_receipt?>" type="hidden" />
            <input name="other_doc_old" value="<?=$other_doc?>" type="hidden" />
            <input name="old_files" value='<?=json_encode($oldFiles)?>' type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm">
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
                    <?php }
                    if(strlen($remarks)) { ?>
                        <fieldset class="border border-danger" style="margin-top:40px">
                            <legend class="h5"><?=$this->lang->line('query_details')?></legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <p>The following query is made by the <strong>Departmental user</strong> on <strong><?=$queried_time?></strong></p>
                                    <?=$remarks?>
                                </div>
                            </div>
                        </fieldset>
                    <?php }//End of if ?>
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Attach Enclosures </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th style="width:220px">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="mutation_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought" <?=($mutation_doc_type === 'Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought')?'selected':''?>>Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought</option>
                                                </select>
                                                <?= form_error("mutation_doc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mutation_doc" name="mutation_doc" type="file" />
                                                </div>
                                                <?php if(strlen($mutation_doc)){ ?>
                                                    <a href="<?=base_url($mutation_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Land Revenue Receipt<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="revenue_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Revenue Receipt" <?=($revenue_receipt_type === 'Land Revenue Receipt')?'selected':''?>>Land Revenue Receipt</option>
                                                </select>
                                                <?= form_error("revenue_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="revenue_receipt" name="revenue_receipt" type="file" />
                                                </div>
                                                <?php if(strlen($revenue_receipt)){ ?>
                                                    <a href="<?=base_url($revenue_receipt)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <!--<tr>
                                            <td>Remarks (If any)</td>
                                            <td colspan="2">
                                                <textarea name="remarks" class="form-control"></textarea>
                                            </td>
                                        </tr>-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE_NEXT" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>