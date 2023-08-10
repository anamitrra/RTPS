<main class="rtps-container">
    <div class="container my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Update SRO list</div>
            <form action="<?= base_url('spservices/sro/update_sro') ?>" method="post">
            <div class="card-body">
                <?php 
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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Select District</label>
                            <select name="pa_district" id="pa_district" class="form-control" required>
                                <option value="">Select </option>
                                <?php if($sro_dist_list){
                                    foreach($sro_dist_list as $item){ ?>
                                        <option value="<?=$item->parent_org_unit_code?>"><?=$item->org_unit_name_2?></option>
                                <?php }
                                }?>                            
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="obj_id" id="obj_id">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Select Office for application submission</label>
                            <select name="sro_code" id="sro_code" class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Office code</label>
                            <input type="text" class="form-control office_code" id="office_code" name="office_code">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Treasury code</label>
                            <input type="text" class="form-control tcode" name="tcode" id="tcode">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <input type="button" value="Update" id="updatbtn" class="btn btn-sm btn-success">
                </div>
                </form>
            </div>
        </div>
    </div>
</main>


<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
$(document).ready(function () {
    $(document).on("change", "#pa_district", function(){      
                $('.office_code').val('')
                $('.tcode').val('')    
                $('#obj_id').val('')    

                     
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                $.getJSON("<?=base_url("spservices/sro/getlocation")?>?id="+selectedVal, function (data) {
                    let selectOption = '';
                    $('#sro_code').empty().append('<option value="">Select a location</option>')
                    $.each(data, function (key, value) {
                        selectOption += '<option value="'+value.org_unit_code+'">'+value.org_unit_name+'</option>';
                    });
                    $('#sro_code').append(selectOption);
                    $('#obj_id').val(data[0]['_id'].$id)    
                    // console.log(data[0]['_id'].$id)

                });
            }
    });
    $(document).on("change", "#sro_code", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                $('.office_code').val('')
                $('.tcode').val('')
                $.getJSON("<?=base_url("spservices/sro/getExtradata")?>?id="+selectedVal, function (data) {
                    $('.office_code').val(data[0].office_code)
                    $('.tcode').val(data[0].treasury_code)
                });
            }
    });

    $('#updatbtn').on('click',function(){
        $.ajax({
                url : "<?=base_url("spservices/sro/update_sro_ajax")?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    sro_code : $('#sro_code').val(),
                    office_code : $("#office_code").val(),
                    tcode : $("#tcode").val()
                },
                success:function (response) {
                    if(response.status){
                        Swal.fire(
                            'Success',
                            response.msg,
                            'success'
                        );
                    }else{
                        Swal.fire(
                            'Failed!',
                            response.error_msg !== undefined ? response.error_msg : response.msg,
                            'error'
                        );
                    }
                },
                error:function (error) {
                    console.log('error');
                    
                    Swal.fire(
                        'Failed!',
                        'Something went wrong!',
                        'error'
                    );
                }
            }).always(function(){
            });
    })
});

</script>