<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    ol li {
        font-size: 14px;
        font-weight: bold;
    }
</style>

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {           
       
        
        $(document).on("click", "#add_files_tbl_row", function() {
            let totRows = $('#language_tbl tr').length;
            var trow = `<tr>
                            <td><input name="file_types[]" class="form-control" type="text" /></td>
                            <td>
                             <input type="file" name="files[]"  class="form-control" />
                            </td>
                            <td style="text-align:center"><button class="btn btn-danger delete_education_tblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 10) {
                $('#language_tbl tr:last').after(trow);
            }
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input value="<?=$rtps_trans_id?>" name="rtps_trans_id" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                 <?=$servive_name?><br>
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
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"> Attach Supporting Document(S) </legend>
                        
                        <div class="row">
                            <div class="col-md-12">
                               
                                <table class="table table-bordered" id="language_tbl">
                                    <thead>
                                        <tr>
                                            <th width="50%">Document Type</th>
                                            <th>Document (PDF,JPG,JPEG)</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                                <td><input name="file_types[]" class="form-control" type="text" /></td>
                                                <td>
                                                    <input type="file" name="files[]"  class="form-control" />
                                                </td>

                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_files_tbl_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br/>
                        <br/>
                        <?php if($supporting_docs){ ?>

                            <input type="hidden" name="existing_file_path" value="true"/>
                            <label>Already Uploaded Files</label>
                            <div class="row">
                                <div class="col-md-12">
                                
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="50%">Document Type</th>
                                                <th>Document Uploaded</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            $count = (isset($supporting_docs) && is_array($supporting_docs)) ? count($supporting_docs) : 0;
                                            if ($count > 0) {
                                                for ($i = 0; $i < $count; $i++) { ?>
                                                    <tr>
                                                        <td><?= isset($supporting_docs[$i]->doc_type) ? $supporting_docs[$i]->doc_type : '' ?></td>
                                                        <td>
                                                        <a target="_blank" href="<?=base_url($supporting_docs[$i]->file_name)?>">View File</a>
                                                        </td>
                                                        
                                                    </tr>
                                                <?php }
                                            } //End of if else  
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php }?>
                        
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=base_url('spservices/offline/acknowledgement/fileuploads/'.$obj_id."?type=application_form")?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                         <i class="fa fa-check"></i> Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>