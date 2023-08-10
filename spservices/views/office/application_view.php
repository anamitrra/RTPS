<?php foreach($application_data as $value) { ?>
            <table class="table table-bordered table-sm" id="application_list_table">
                    <tr>
                        <td>
                            <p style="font-size:16px"><b>RTPS Ref. No. : <span class="text-danger"><?php echo $value->service_data->appl_ref_no; ?></span></b></p>
                            <p style="font-size:16px"><b>Application Receiving Date : <?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->form_data->created_at))); ?></b></p>
                        </td>
                        <td  width="15%"><img src="<?= base_url($value->form_data->passport_photo); ?>" width="100%"></td>
                    </tr>
            </table>

            <table class="table table-bordered mt-2 table-sm">
                <tr>
                    <th colspan="4" class="text-center text-primary">Personal Details</th>
                </tr>
                        <tr>
                            <td width="25%"><b>Name of the Applicant</b></td>
                            <td width="25%"><?php echo $value->form_data->applicant_name; ?></td>
                            <td width="25%"><b>Gender</b></td>
                            <td width="25%"><?php echo $value->form_data->applicant_gender; ?></td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Date of Birth</b></td>
                            <td width="25%"><?php echo $value->form_data->dob??''; ?></td>
                            <td width="25%"><b>Mobile Number</b></td>
                            <td width="25%"><?php echo $value->form_data->mobile_number; ?></td>
                        </tr>
                        <tr>
                        <td width="25%"><b>Email</b></td>
                            <td width="25%"><?php echo $value->form_data->email_id ?? ''; ?></td>
                            <td width="25%"><b>Father's Name</b></td>
                            <td width="25%"><?php echo $value->form_data->father_name; ?></td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Mother's Name</b></td>
                            <td width="25%"><?php echo $value->form_data->mother_name; ?></td>
                            <td width="25%"><b>Community</b></td>
                            <td width="25%"><?php echo $value->form_data->community; ?></td>
                        </tr>
            </table>
            <table class="table table-bordered mt-2 table-sm" id="application_list_table">
                    <tr>
                        <th colspan="4" class="text-center text-primary">Address</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-danger"><strong>Permanent Address<strong></td>
                    </tr>
                    <tr>
                        <td width="15%"><b>House No./Flat No.</b></td>
                        <td width="35%"><?php echo $value->form_data->pa_house_no; ?></td>
                        <td width="15%"><b>Street/Locality</b></td>
                        <td width="35%"><?php echo $value->form_data->pa_street; ?></td>
                    </tr>
                    <tr>
                        <td><b>Village/Town</b></td>
                        <td colspan="3"><?php echo $value->form_data->pa_village; ?></td>
                    </tr>
                    <tr>
                        <td><b>Post Office</b></td>
                        <td><?php echo $value->form_data->pa_post_office; ?></td>
                        <td><b>State</b></td>
                        <td><?php echo $value->form_data->pa_state; ?></td>
                    </tr>
                    <tr>
                        <td><b>District</b></td>
                        <td><?php echo $value->form_data->pa_district_name; ?></td>
                        <td><b>Circle</b></td>
                        <td><?php echo $value->form_data->pa_circle; ?></td>
                    </tr>
                    <tr>
                        <td><b>Pincode</b></td>
                        <td colspan="3"><?php echo $value->form_data->pa_pin_code; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-danger"><strong>Communication Address<strong></td>
                    </tr>
                    <tr>
                        <td><b>House No./Flat No.</b></td>
                        <td><?php echo $value->form_data->ca_house_no; ?></td>
                        <td><b>Street/Locality</b></td>
                        <td><?php echo $value->form_data->ca_street; ?></td>
                    </tr>
                    <tr>
                        <td><b>Village/Town</b></td>
                        <td colspan="3"><?php echo $value->form_data->ca_village; ?></td>
                    </tr>
                    <tr>
                        <td><b>Post Office</b></td>
                        <td><?php echo $value->form_data->ca_post_office; ?></td>
                        <td><b>State</b></td>
                        <td><?php echo $value->form_data->ca_state; ?></td>
                    </tr>
                    <tr>
                        <td><b>District</b></td>
                        <td><?php echo $value->form_data->ca_district_name; ?></td>
                        <td><b>Circle</b></td>
                        <td><?php echo $value->form_data->ca_circle; ?></td>
                    </tr>
                    <tr>
                        <td><b>Pincode</b></td>
                        <td colspan="3"><?php echo $value->form_data->ca_pin_code; ?></td>
                    </tr>
            </table>
            <table class="table table-bordered mt-2 table-sm" id="application_list_table">
                    <tr>
                        <th colspan="4" class="text-center text-primary">Enclosures</th>
                    </tr>
                    <tr>
                        <td><b>SL No.</b></td>
                        <td><b>Document Type<b></td>
                        <td><b>Documents Name<b></td>
                        <td width="20%"></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>ID Proof</td>
                        <td><?= $value->form_data->id_proof_type ?></td>
                        <td class="text-center"><a href="<?= base_url($value->form_data->id_proof); ?>" class="text-success " target="_blank"><b><i class="fa fa-download"></i> View/Download</b></a></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Address Proof</td>
                        <td><?= $value->form_data->address_proof_type ?></td>
                        <td class="text-center"><a href="<?= base_url($value->form_data->address_proof); ?>" class="text-success " target="_blank"><b><i class="fa fa-download"></i> View/Download</b></a></td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Age Proof</td>
                        <td><?= $value->form_data->age_proof_type ?></td>
                        <td class="text-center"><a href="<?= base_url($value->form_data->age_proof); ?>" class="text-success " target="_blank"><b><i class="fa fa-download"></i> View/Download</b></a></td>
                    </tr>
                    <?php $i = 4; 
                if(isset($value->execution_data)){
                $exc_data = $value->execution_data; 
                for($j= 0 ; $j<count($exc_data); $j++){ 
                    if (isset($exc_data[$j]->applicant_task_details->query_ans_file)){?>
                <tr>
                    <td><?= $i++ .'.'; ?></td>
                    <td>Queried answered document</td>
                    <td>Other file</td>
                    <td class="text-center"><a href="<?= base_url($exc_data[$j]->applicant_task_details->query_ans_file) ?>"class="text-success " target="_blank"><b><i class="fa fa-download"></i> View/Download</b></a></td>
                </tr>
                 <?php }}}?>
            </table>
            <?php } ?>