<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/importjson/submit')?>" enctype="multipart/form-data">
        <div class="card shadow-sm mt-2">
            <div class="card-body">
                <div class="row mt-5">
                    <div class="col-md-6 form-group">     
                        <label>MongoDB collection for UMPS only</label>
                        <select name="collection_name" class="form-control">
                            <option value="">Please choose a collection</option>  
                            <option value="upms_services">upms_services : For a single service</option>   
                            <option value="upms_levels">upms_levels : For all task levels</option>  
                            <option value="upms_users">upms_users : For all users</option>                      
                        </select>
                        <?= form_error("collection_name") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Exported MongoDB collection from UMPS</label>
                        <div class="custom-file">
                            <input name="uploadedfile" class="custom-file-input" id="uploadedfile"  type="file" />
                            <label class="custom-file-label" for="uploadedfile">Choose a file...</label>
                            <?= form_error("uploadedfile") ?>
                        </div>
                    </div>
                </div>
            </div><!--End of .card-body-->
            <div class="card-footer text-center">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-upload"></i> IMPORT NOW
                </button>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </form>        
</div>
<script type="text/javascript">
    $('#uploadedfile').on('change',function(){ 
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>