<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/importcsv/submit')?>" enctype="multipart/form-data">
        <div class="card shadow-sm mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Department<span class="text-danger">*</span> </label>
                        <select name="dept_info" id="dept_info" class="form-control">
                            <option value="">Select a department</option>
                            <?php if($depts) {
                                foreach($depts as $dept) {
                                    $deptObj = json_encode(array("dept_code"=>$dept->dept_code, "dept_name" => $dept->dept_name));
                                    echo "<option value='{$deptObj}'>{$dept->dept_name}</option>";
                                }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("dept_info") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Service<span class="text-danger">*</span> </label>
                        <select name="service_info" id="service_info" class="form-control">
                            <option value="">Please Select</option>
                        </select>
                        <?= form_error("service_id") ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Specified csv file<span class="text-danger">*</span></label>
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
    $(document).ready(function () {
        $(document).on("change", "#dept_info", function(){
            var deptInfo = $(this).val();
            if(deptInfo.length > 10) {
                var dept_code = $.parseJSON(deptInfo).dept_code;
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/svcs/get_services')?>",
                    data: {"dept_code": dept_code},
                    dataType:'json',
                    beforeSend:function(){
                        $("#service_info").empty();
                        $("#service_info").append($('<option></option>').val('').html('Please wait...'));
                    },
                    success:function(res){
                        if(res.length) {
                            $("#service_info").empty();
                            $("#service_info").append($('<option></option>').val('').html('Please select a service'));
                            $.each(res, function (index, item) {
                                $("#service_info").append($('<option></option>').val(item.service_obj).html(item.service_name));
                            });
                        } else {
                            alert("No records found");
                        }//End of if else
                    }
                });
            } else {
                alert("Please select a valid department");
            }//End of if else
        });//End of onChange #dept_info
        
        $('#uploadedfile').on('change',function(){ 
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>