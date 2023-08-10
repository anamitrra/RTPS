<main class="rtps-container">
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">All Services</div>
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
                    <div class="col-sm-12 text-right">
                        <a href="<?= base_url('spservices/spservice/create_service') ?>" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Add Service</a>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-responsive">
                    <thead class="table-info">
                        <tr>
                            <th>#</th>
                            <th>Service ID</th>
                            <th>Service Name</th>
                            <th>Department Name</th>
                            <th>Department Code</th>
                            <th>Stipulated Time</th>
                            <th>Major Head</th>
                            <th>Payment Type</th>
                            <th>Head of account</th>
                            <th>HOA1</th>
                            <th>HOA2</th>
                            <th>HOA3</th>
                            <th>Non Treasury payment type</th>
                            <th>Non Treasury account</th>
                            <th>Acoumt3</th>
                            <th>Account1</th>
                            <th>Account2</th>
                        
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if($services) {
                            foreach($services as $val) {  ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $val->service_id ?></td>
                                    <td style="text-align:left; width: 200px">
                                        <a href="<?=base_url('spservices/spservice/edit_service/'.base64_encode($val->_id->{'$id'}))?>" class="btn btn-sm text-primary">
                                            <?= $val->service_name ?>
                                        </a>                                    
                                    </td>
                                    <td><?= $val->department_name ?></td>
                                    <td><?= $val->DEPT_CODE ?></td>
                                    <td><?= $val->stipulated_timeline??'' ?></td>
                                    <td><?= $val->MAJOR_HEAD??'' ?></td>
                                    <td><?= $val->PAYMENT_TYPE??'' ?></td>
                                    <td><?= $val->HEAD_OF_ACCOUNT_COUNT??'' ?></td>
                                    <td><?= $val->HOA1??'' ?></td>
                                    <td><?= $val->HOA2??'' ?></td>
                                    <td><?= $val->HOA3??'' ?></td>
                                    <td><?= $val->NON_TREASURY_PAYMENT_TYPE??'' ?></td>
                                    <td><?= $val->NON_TREASURY_ACCOUNT_COUNT??'' ?></td>
                                    <th><?= isset($val->AMOUNT3) ? $val->AMOUNT3 : ''?></th>
                                    <td><?= $val->ACCOUNT1??'' ?></td>
                                    <td><?= $val->ACCOUNT2??'' ?></td>
                                    <td>
                                        <a href="<?=base_url('spservices/spservice/edit_service/'.base64_encode($val->_id->{'$id'}))?>" class="btn btn-sm text-primary"><i class="fa fa-edit"></i></a>
                                        <!-- <a href="<?=base_url('spservices/spservice/delete_service/'.base64_encode($val->_id->{'$id'}))?>" class="btn btn-sm text-danger"><i class="fa fa-trash"></i></a> -->
                                    </td>
                                </tr>
                        <?php } } ?>
                    </tbody>
                </table>
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
});

</script>