<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col-md-12 mt-3">
                <?php if ($this->session->userdata('message') <> '') { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success</strong>
                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <?php if ($this->session->userdata('errmessage') <> '') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Alert:</strong>
                        <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header" style="background:#9edad8">Application Processing history</div>
            <div class="card-body">
                <table class="table table-sm" id="application_list_table">
                    <tr>
                        <td>
                            <p style="font-size:16px"><label>Application Ref. No. : <span class="text-danger"><?= isset($data->appl_ref_no) ? $data->appl_ref_no : ""; ?></span></label></p>
                        </td>
                        <td>
                            <?php //if (!empty($dbrow->service_data->submission_date)) { 
                            ?>
                            <p style="font-size:16px ; text-align:right "><label>Application Receiving Date : <?= isset($data->submission_date) ? date('d/m/Y', strtotime($data->submission_date)) : ""; ?></label></p>
                            <?php //} 
                            ?>
                        </td>
                    </tr>
                </table>

                <div class="row">
                    <div class="col-md-5"><label>Applicant Name</label></div>
                    <div class="col-md-7"><?= $data->attribute_details->applicant_name ?></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Application Ref. Number</label></div>
                    <div class="col-md-7"><?= $data->appl_ref_no ?></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Service Name</label></div>
                    <div class="col-md-7"><?= $data->service_name ?></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Department Name</label></div>
                    <div class="col-md-7"><?= $data->department_name ?></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Submission Location</label></div>
                    <div class="col-md-7"><?= $data->submission_location ?></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Submission Date</label></div>
                    <div class="col-md-7"><?php echo date('d/m/Y', strtotime($data->submission_date)); ?></div>
                </div>


                <div class="row">
                    <div class="col-md-5"><label>Application Form</label></div>
                    <div class="col-md-7">
                        <a href="<?= base_url('iservices/serviceplus/view_rtps_form_data/' . $object_id) ?>" target="_blank">View Form Data</a>
                    </div>
                </div>


                <?php
                if (isset($data->applicant_query) && $data->applicant_query == true) {
                    if (!empty($data->tiny_urls) && isset($data->tiny_urls[0]->tiny_url)) {
                ?>
                        <p style="font-size:20px;margin-top:5px;"><label><i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="text-danger"> Waiting for applicant response. Please <a href="<?= $data->tiny_urls[0]->tiny_url ?>" class="btn btn-info btn-sm">click here</a> to proceed.</span></label></p>
                    <?php } else { ?>

                        <p style="font-size:20px;margin-top:5px;"><label><i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="text-danger"> Waiting for applicant response. Please <a class="btn btn-info btn-sm" href="<?= base_url('iservices/serviceplus/sprtps/autosubmit/' . $object_id) ?>" target="_blank">click here</a> to proceed.</span></label></p>
                <?php
                    }
                }
                ?>
                <table class="table table-sm table-striped table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Task Name</th>
                            <th scope="col">Received Time</th>
                            <th scope="col">Executed Time</th>
                            <th scope="col">Issued Document(s)</th>
                            <th scope="col">Action</th>
                            <th scope="col">Remarks</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        if (!empty($execution_data)) {
                            $execution_time = '-';
                            $received_time = '-';
                            //$test_var = [];
                            //if(isset($test_var)){ echo 'hi';}
                            foreach ($execution_data as $key => $val) {
                                if (isset($val->task_details->received_time)) {
                                    if (!empty($val->task_details->received_time))
                                        $received_time = date('d/m/Y', strtotime($val->task_details->received_time));
                                }
                                if (isset($val->task_details->executed_time)) {
                                    if (!empty($val->task_details->executed_time))
                                        $execution_time = date('d/m/Y', strtotime($val->task_details->executed_time));
                                }
                        ?>
                                <tr>
                                    <td scope="col"><?= ($key + 1) ?></td>
                                    <td scope="col"><?= isset($val->task_details->task_name) ? $val->task_details->task_name : ''; ?></td>
                                    <td scope="col">
                                        <?= $received_time ?>
                                    </td>
                                    <td scope="col">
                                        <?= $execution_time ?>
                                    </td>
                                    <td scope="col">
                                        <?php
                                        $links = array(); // Array to store the generated links

                                        if (isset($data->certs)) {
                                            $certs = $data->certs;
                                            foreach ($certs as $cert) {
                                                if ($val->task_details->current_process_id == $cert->application_current_process_id) {
                                                    $curr_process_id =  $val->task_details->current_process_id;
                                                    if ($cert->application_cert_flag == 'C') {
                                                        $doc_label = "Download Certificate";
                                                    } else {
                                                        $doc_label = "Download Acknowledgement";
                                                    }

                                                    // Generate the link and store it in the links array
                                                    $link = '<a href="' . base_url("iservices/serviceplus/rtps_issued_doc/") . $curr_process_id . '/' . $object_id . '">' . $doc_label . '</a>';
                                                    $links[] = $link;
                                                    // pre($links);
                                                }
                                            }
                                        }
                                        if (empty($links)) {
                                            echo "Nil";
                                        } else {
                                            foreach ($links as $link) {
                                                echo $link . '<br>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td scope="col">
                                        <?php //Action
                                        if (isset($val->official_form_details) && $val->official_form_details != null) {
                                            if (isset($val->official_form_details->action) && $val->official_form_details->action != ' ') {
                                                echo $val->official_form_details->action;
                                            }
                                            if (isset($val->official_form_details->task) && $val->official_form_details->task != ' ') {
                                                echo " to " . $val->official_form_details->task . " ";
                                            }
                                            if (isset($val->official_form_details->select_query_type) && $val->official_form_details->select_query_type != ' ') {
                                                echo "<br> Query Type - (" . $val->official_form_details->select_query_type . ") ";
                                            }
                                            if (isset($val->official_form_details->select_type_of_query) && $val->official_form_details->select_type_of_query != ' ') {
                                                echo "<br> Query Type - (" . $val->official_form_details->select_type_of_query . ") ";
                                            }

                                            if (!isset($val->official_form_details->action) && !isset($val->official_form_details->task)) {
                                                echo "Action taken by applicant";
                                            }
                                        } else if (!isset($val->official_form_details) && !isset($val->applicant_task_details)) {
                                            //if both are null assigned (above condition);
                                            echo "Action taken by applicant";
                                        } else {
                                            echo "Action not taken";
                                        }
                                        ?>
                                    </td>
                                    <td scope="col">
                                        <?php
                                        if (isset($val->official_form_details)) {
                                            if (isset($val->official_form_details->query_to_applicant)) {
                                                echo $val->official_form_details->query_to_applicant;
                                            }
                                            if (isset($val->official_form_details->applicants_reply)) {
                                                echo "<br><br><b>Applicant Response -</b> " . $val->official_form_details->applicants_reply;
                                            }
                                        }
                                        ?>
                                        <?php
                                        if (isset($val->official_form_details)) {
                                            if (isset($val->official_form_details->remarks)) {
                                                echo $val->official_form_details->remarks;
                                            } else {
                                                echo 'NA';
                                            }
                                        } else {
                                            echo 'NA';
                                        }
                                        ?>
                                    </td>
                                </tr>

                            <?php }


                            ?>

                            <tr>
                                <td scope="col"><?= ($key + 2) ?></th>
                                <td scope="col">Application Submission</th>
                                <td scope="col">
                                    <?= $received_time ?>
                                </td>
                                <td scope="col">
                                    <?= $execution_time ?>
                                </td>
                                <td scope="col">
                                    <?php
                                    $current_process_id = '';
                                    if (isset($data->certs)) {
                                        $certs = $data->certs;
                                        foreach ($certs as $cert) {
                                            if ($cert->application_cert_flag == 'Ack') {
                                                $current_process_id =  $cert->application_current_process_id;
                                            }
                                            break;
                                        }
                                    } else {
                                        $current_process_id = '';
                                    }
                                    if ($current_process_id != '') {
                                    ?>
                                        <a href="<?= base_url("iservices/serviceplus/rtps_issued_doc/") . $current_process_id . '/' . $object_id ?>">Download Application Submission Acknowledgement</a>
                                    <?php
                                    } else {
                                        echo 'NA';
                                    }
                                    ?>
                                </td>
                                <td scope="col">Application Submitted Successfully</td>
                                <td scope="col">NA</td>
                            </tr>
                        <?php
                        } else {
                        ?>
                            <tr>
                                <td scope="col">1</th>
                                <td scope="col">Application Submission</th>
                                <td scope="col">
                                    <?= $data->submission_date ?>
                                </td>
                                <td scope="col">
                                    <?= $data->submission_date ?>
                                </td>
                                <td scope="col">
                                    <?php
                                    $current_process_id = '';
                                    if (isset($data->certs)) {
                                        $certs = $data->certs;
                                        foreach ($certs as $cert) {
                                            if ($cert->application_cert_flag == 'C') {
                                                $current_process_id =  $cert->application_current_process_id;
                                            }
                                            break;
                                        }
                                    } else {
                                        $current_process_id = '';
                                    }
                                    if ($current_process_id != '') {
                                    ?>
                                        <a href="<?= base_url("iservices/serviceplus/rtps_issued_doc/") . $current_process_id . '/' . $object_id ?>">Download Issued Document</a>
                                    <?php
                                    } else {
                                        echo 'NA';
                                    }
                                    ?>
                                </td>
                                <td scope="col">Application Submitted Successfully</td>
                                <td scope="col">NA</td>
                            </tr>

                        <?php

                        }
                        ?>
                    </tbody>
                </table>











            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
    /* Login Model */
    function showSPLoginModal() {
        const domain = `${window.location.origin}/services`;
        //const sendurl = domain + "/directApply.do?serviceId=1886";
        //const frame = '<iframe is="x-frame-bypass" id="iframeIdLogin" src="' + sendurl + '" style="width: 100%; height: 430px; border: none;"></iframe>';
        const frame = '<iframe is="x-frame-bypass" id="iframeIdLogin" src="' + domain + '/loginWindow.do?servApply=N&&lt;csrf:token%20uri=%27loginWindow.do%27/&gt;" style="width: 100%; height: 430px; border: none;"></iframe>';
        Swal.fire({
            html: frame,
            showCloseButton: true,
            confirmButtonText: 'Close',
            confirmButtonAriaLabel: 'Close',
        });




        // const iFrame = document.getElementById('iframeIdLogin');
        // console.log(iFrame);
        // iFrame.addEventListener('load', function (event) {

        //     const preTag = iFrame.contentWindow.document.querySelector('pre');
        //     // console.log(preTag);

        //     if (preTag !== null) {
        //         iFrame.contentWindow.document.body.innerHTML = `
        //         <p> Your Session may not be Closed Properly.</p>
        //         <a href="${domain}" target="_top">Please Click Here to Re-login</a>
        //         `;

        //         // console.log('Do the Work');
        //     }

        // });

    }
</script>