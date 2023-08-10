<style type="text/css">
    .loading {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 100px;
        height: 100px;
        margin: 10px auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script type="text/javascript">
    $(document).on("click", "#export_btn", function(){
            let service_code = $("#service_code").val();
            if(service_code.length >= 3) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/export/process')?>",
                    data: {"service_code":service_code},
                    beforeSend:function(){
                        $("#matched_data").html('<div class="loading"></div>');
                    },
                    success:function(res){ //alert(res);
                        $("#matched_data").html(res);
                    }
                });
            } else {
                alert("Please select a service");
                $("#service_code").val("");
                $("#service_code").focus();
            }//End of if else            
        });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm mt-2">
        <div class="card-body">
            <div class="row mt-5">
                <div class="offset-md-2 col-md-8 offset-md-2">     
                    <div class="input-group">
                        <select id="service_code" class="form-control">
                            <option value="">Please select a service</option>
                            <?php if($services) { 
                                foreach($services as $service) {
                                    echo "<option value='{$service->service_code}'>{$service->service_name}</option>";
                                }//End of foreach()
                            }//End of if ?>
                        </select>
                        <button id="export_btn" class="btn btn-light" type="button" style="border-radius: 0px; border: 1px solid #ced4da; border-left: none; border-bottom-right-radius: 0.25rem; border-top-right-radius: 0.25rem">
                            <i class="fa fa-file-export"></i> EXPORT TO JSON
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div id="matched_data" class="offset-md-2 col-md-8 offset-md-2"></div>
            </div>
        </div><!--End of .card-body-->
    </div><!--End of .card-->
</div>