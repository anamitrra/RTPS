<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<style>
    .mandatory {
        color:red
    }
</style>
<div class="content-wrapper">
<div class="container mt-3">
    <div class="card shadow">
        <div class="card-header bg-info text-center">Application Details</div>
        <div class="card-body">
        <?php foreach($data as $value) { ?>
            <img src="<?= base_url($value->applicant_photo); ?>" width="15%">
            <table class="table table-bordered" id="application_list_table">
                <!-- <thead> -->
                        <tr>
                        <th>Service Name</th>
                        <td><?php echo $value->service_name; ?></td>
                    </tr>
                    <tr>
                        <th>Application Id</th>
                        <td><?php echo $value->rtps_trans_id; ?></td>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <td><?php echo $value->certificate_purpose??""; ?></td>
                    </tr>
                    
                    <tr>
                        <th>Application Received Date</th>
                        <td><?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at))); ?></td>
                    </tr>
            </table>

            <table class="table table-bordered mt-2">
                <tr>
                    <th colspan="4" class="text-center text-primary">Personal Details</th>
                </tr>
                        <tr>
                            <td width="25%"><b>Name of the Applican</b></td>
                            <td width="25%"><?php echo $value->applicant_name; ?></td>
                            <td width="25%"><b>Gender</b></td>
                            <td width="25%"><?php echo $value->applicant_gender; ?></td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Age</b></td>
                            <td width="25%"><?php echo $value->age??''; ?></td>
                            <td width="25%"><b>Mobile Number</b></td>
                            <td width="25%"><?php echo $value->mobile_number; ?></td>
                        </tr>
                        <tr>
                        <td width="25%"><b>Email</b></td>
                            <td width="25%"><?php echo $value->email; ?></td>
                            <td width="25%"><b>Father's Name</b></td>
                            <td width="25%"><?php echo $value->mother_name; ?></td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Mother's Name</b></td>
                            <td width="25%"><?php echo $value->father_name; ?></td>
                            <td width="25%"><b>Spouse Name</b></td>
                            <td width="25%"><?php echo $value->spouse_name; ?></td>
                        </tr>  
                        <tr>
                            <td width="25%"><b>Religion</b></td>
                            <td width="25%"><?php echo $value->religion; ?></td>
                            <td><b>Sub Caste</b></td>
                            <td><?php echo $value->sub_caste; ?></td>
                        </tr>
                        <tr>
                            <td><b>District</b></td>
                            <td><?php echo $value->pa_district; ?></td>
                            <td><b>Mouza / মৌজা</b></td>
                            <td><?php echo $value->pa_mouza; ?></td>
                        </tr>
                        <tr>
                            <td><b>Forefather Occupation</b></td>
                            <td><?php echo $value->forefather_occupation; ?></td>
                        </tr>
            </table>
            <table class="table table-bordered mt-2" id="application_list_table">
                <tr>
                    <th colspan="4" class="text-center text-primary">Ancestor/Other Details</th>
                </tr>
                        <tr>
                            <td><b>Ancestor Name</b></td>
                            <td colspan="3"><?php echo $value->ancestor_name; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor Address 1</b></td>
                            <td colspan="3"><?php echo $value->ancestor_address1; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor Address 2</b></td>
                            <td colspan="3"><?php echo $value->ancestor_address2; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor Village</b></td>
                            <td colspan="3"><?php echo $value->ancestor_village; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor PO</b></td>
                            <td><?php echo $value->ancestor_po; ?></td>
                            <td><b>Ancestor PS</b></td>
                            <td><?php echo $value->ancestor_ps; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor District</b></td>
                            <td><?php echo $value->ancestor_district; ?></td>
                            <td><b>Ancestor Sub-division</b></td>
                            <td><?php echo $value->ancestor_subdivision; ?></td>
                        </tr>
                        <tr>
                            <td><b>Ancestor CO</b></td>
                            <td><?php echo $value->ancestor_circleoffice; ?></td>
                            <td><b>Ancestor Mouza</b></td>
                            <td><?php echo $value->ancestor_mouza; ?></td>
                        </tr>
                        <tr>
                            <td><b>State</b></td>
                            <td><?php echo $value->ancestor_state; ?></td>
                            <td><b>Pin</b></td>
                            <td><?php echo $value->ancestor_pin; ?></td>
                        </tr>
                        <tr>
                            <td><b>Relationship</b></td>
                            <td><?php echo $value->ancestor_relation; ?></td>
                            <td><b>Sub-caste</b></td>
                            <td><?php echo $value->ancestor_subcaste; ?></td>
                        </tr>
            </table>
            <table class="table table-bordered mt-2" id="application_list_table">
                    <tr>
                        <th colspan="4" class="text-center text-primary">Address</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-danger"><strong>Permanent Address<strong></td>
                    </tr>
                    <tr>
                        <td><b>House No</b></td>
                        <td><?php echo $value->pa_houseno; ?></td>
                        <td><b>Landmark</b></td>
                        <td><?php echo $value->pa_landmark; ?></td>
                    </tr>
                    <tr>
                        <td><b>Village</b></td>
                        <td colspan="3"><?php echo $value->pa_village; ?></td>
                    </tr>
                    <tr>
                        <td><b>PO</b></td>
                        <td><?php echo $value->pa_po; ?></td>
                        <td><b>PS</b></td>
                        <td><?php echo $value->pa_ps; ?></td>
                    </tr>
                    <tr>
                        <td><b>State</b></td>
                        <td><?php echo $value->pa_state; ?></td>
                        <td><b>District</b></td>
                        <td><?php echo $value->pa_district; ?></td>
                    </tr>
                    <tr>
                        <td><b>Sub-division</b></td>
                        <td><?php echo $value->pa_subdivision; ?></td>
                        <td><b>Revenue Circle</b></td>
                        <td><?php echo $value->pa_revenuecircle; ?></td>
                    </tr>
                    <tr>
                        <td><b>Mouza</b></td>
                        <td><?php echo $value->pa_mouza; ?></td>
                        <td><b>Pincode</b></td>
                        <td><?php echo $value->pa_pincode; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-danger"><strong>Communication Address<strong></td>
                    </tr>
                    <tr>
                        <td><b>House No</b></td>
                        <td><?php echo $value->ra_houseno; ?></td>
                        <td><b>Landmark</b></td>
                        <td><?php echo $value->ra_landmark; ?></td>
                    </tr>
                    <tr>
                        <td><b>Village</b></td>
                        <td colspan="3"><?php echo $value->ra_village; ?></td>
                    </tr>
                    <tr>
                        <td><b>PO</b></td>
                        <td><?php echo $value->ra_po; ?></td>
                        <td><b>PS</b></td>
                        <td><?php echo $value->ra_ps; ?></td>
                    </tr>
                    <tr>
                        <td><b>State</b></td>
                        <td><?php echo $value->ra_state; ?></td>
                        <td><b>District</b></td>
                        <td><?php echo $value->ra_district; ?></td>
                    </tr>
                    <tr>
                        <td><b>Sub-division</b></td>
                        <td><?php echo $value->ra_subdivision; ?></td>
                        <td><b>Revenue Circle</b></td>
                        <td><?php echo $value->ra_revenuecircle; ?></td>
                    </tr>
                    <tr>
                        <td><b>Mouza</b></td>
                        <td><?php echo $value->ra_mouza; ?></td>
                        <td><b>Pincode</b></td>
                        <td><?php echo $value->ra_pincode; ?></td>
                    </tr>
            </table>
            <table class="table table-bordered mt-2" id="application_list_table">
                    <tr>
                        <th colspan="4" class="text-center text-primary">Enclosures</th>
                    </tr>
                    <tr>
                        <td><a href="<?= base_url($value->caste_certificate); ?>" target="_blank"><?php echo $value->caste_certificate_type; ?></a></td>
                        <td><a href="<?= base_url($value->gaonbura_report); ?>" target="_blank"><?php echo $value->gaonbura_report_type; ?></a></td>
                    </tr>
                    <tr>
                        <td><a href="<?= base_url($value->prc); ?>" target="_blank"><?php echo $value->prc_type; ?></a></td>
                        <td><a href="<?= base_url($value->nrc); ?>" target="_blank"><?php echo $value->nrc_type; ?></a></td>
                    </tr>
                    <tr>
                        <td><a href="<?= base_url($value->ajp); ?>" target="_blank"><?php echo $value->ajp_type; ?></a></td>
                        <td><a href="<?= base_url($value->other_doc); ?>" target="_blank"><?php echo $value->other_doc_type; ?></a></td>
                    </tr>
                    <tr>
                        <td><a href="<?= base_url($value->soft_copy); ?>" target="_blank"><?php echo $value->soft_copy_type; ?></a></td>
                    </tr>
            </table>
            <?php } ?>
        </button>
        </div>
    </div>
</div>
</div>
    <?PHP $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>

    <input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
    <script type="text/javascript">
    $(document).ready(function(){
        // user_div
      $('#action_type').change(function() {
        // $(this).find(":selected").text();
        let action = $(this).val();
        if(action =='F'){
            $('.task_div').show();
        }
        else{
            $('.task_div').hide();
            $('.user_div').hide();
            $('.query_div').hide();
            $('input[name="task_option"]').prop('checked', false);
            $('input:checkbox').prop('checked', false);
            $('#feedback').val('');
        }
      })

    $('.task_option').on('click', function () {
        let value = $("input[name=task_option]:checked").val()
        if(value == 'RBTAFD') {
            $('.query_div').show();
            $('.user_div').hide();
            $('input:checkbox').prop('checked', false);
        }
        else if(value == 'DAART') {
            $('.query_div').hide();
            $('.user_div').show();
            $('#feedback').val('');
        }
        else {
            $('.query_div').hide();
            $('.user_div').hide();
            $('#feedback').val('');
        }
        // alert(value);
    })
    })
</script>