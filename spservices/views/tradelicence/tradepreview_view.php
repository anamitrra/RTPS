<?php
//pre($dbrow);
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->service_data->rtps_trans_id;
$appref_no = $dbrow->form_data->appref_no;

$ulb = $dbrow->form_data->ulb;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$ubin = $dbrow->form_data->ubin;
$father_name =  $dbrow->form_data->father_name;
$mobile = $dbrow->form_data->mobile;
$email = isset($dbrow->form_data->email) ? $dbrow->form_data->email : "";
$area = $dbrow->form_data->area;
$mouza = $dbrow->form_data->mouza;
$post_office = $dbrow->form_data->post_office;
$country = $dbrow->form_data->country;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$pin_code = $dbrow->form_data->pin_code;
$police_station = $dbrow->form_data->police_station;
$ward_no = $dbrow->form_data->ward_no;
$applicant_age = $dbrow->form_data->applicant_age;
$business_est = $dbrow->form_data->business_est;
$business_name = $dbrow->form_data->business_name;
$business_type = $dbrow->form_data->business_type;
$reason = $dbrow->form_data->reason;
$father_name2 = $dbrow->form_data->father_name2;
$business_ownership = $dbrow->form_data->business_ownership;
$owner_name = $dbrow->form_data->owner_name;
$commencement_business = $dbrow->form_data->commencement_business;
$ward_no2 = $dbrow->form_data->ward_no2;
$other_business = $dbrow->form_data->other_business;
$holding_no = $dbrow->form_data->holding_no;
$road_name = $dbrow->form_data->road_name;
$trade_name = $dbrow->form_data->trade_name;
$fees = $dbrow->form_data->fees;
$area_in_square = $dbrow->form_data->area_in_square;
$proof_ownership_type = $dbrow->form_data->proof_ownership_type ?? '';
$identity_proof_type = $dbrow->form_data->identity_proof_type ?? '';
$proof_ownership = $dbrow->form_data->proof_ownership ?? '';
$identity_proof = $dbrow->form_data->identity_proof ?? '';
$ubin_certificate = $dbrow->form_data->ubin_certificate ?? '';
$ubin_certificate_type = $dbrow->form_data->ubin_certificate_type ?? '';
$identity_proof = $dbrow->form_data->identity_proof ?? '';
$status = $dbrow->service_data->appl_status;
$payment_status = $dbrow->form_data->payment_status ?? "UNPAID";
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }

    ol li {
        font-size: 14px;
        font-weight: bold;
    }

    td {
        font-size: 14px;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#printBtn", function() {
            $("#printDiv").print({
                addGlobalStyles: true,
                stylesheet: null,
                rejectWindow: true,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null
            });
        });

        $(document).on("click", "#finalsubmit", function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Once you submitted, you won&apos;t able to revert this',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    window.location.href = "<?= base_url('spservices/incomecertificate/registration1/post_data/' . $obj_id) ?>";
                }
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <?= $service_name ?>
            </div>
            <div class="card-body" style="padding:5px">

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Trade/Owner </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Application Ref.No. of Common Application Form<br><strong><?= $appref_no ?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Unique Business Identification No(UBIN)<br><strong><?= $ubin ?></strong> </td>
                                <td>Name of Applicant<br><strong><?= $applicant_name ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Gender<br><strong><?= $applicant_gender ?></strong> </td>
                                <td>Father's/Husband's Name<br><strong><?= $father_name ?></strong> </td>
                            <tr>
                                <td>ULB,Where you want to apply?<br><strong><?= $ulb ?></strong> </td>
                                <td>E-Mail ID<br><strong><?= $email ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address / স্হায়ী ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Area/Village<br><strong><?= $area ?></strong> </td>
                                <td>Mouza<br><strong><?= $mouza ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office<br><strong><?= $post_office ?></strong> </td>
                                <td>Country<br><strong><?= $country ?></strong> </td>
                            </tr>
                            <tr>
                                <td>State<br><strong><?= $state ?></strong> </td>
                                <td>District<br><strong><?= $district ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pin Code<br><strong><?= $pin_code ?></strong> </td>
                                <td>Police Station<br><strong><?= $police_station ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Ward No<br><strong><?= $ward_no ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">

                        <tr>
                            <td>Age of Applicant<br><strong><?= $applicant_age ?></strong> </td>

                        <tr>
                            <td>Name of Business Establishment<br><strong><?= $ward_no ?></strong> </td>
                            <td>Name of Business<br><strong><?= $business_name ?></strong> </td>
                        </tr>
                        <tr>
                            <td>Type of Business<br><strong><?= $business_type ?></strong> </td>
                            <td>If other,please specify<br><strong><?= $reason ?></strong> </td>
                        </tr>
                        <tr>
                            <td>Ownership of Building/Space where the business is to be established<br><strong><?= $business_ownership ?></strong> </td>
                            <td>Name of Owner<br><strong><?= $owner_name ?></strong> </td>
                        </tr>
                        <tr>
                            <td>Father's/Husband's Name<br><strong><?= $father_name2 ?></strong> </td>
                            <td>Date of Commencement of Business<br><strong><?= $commencement_business ?></strong> </td>
                        </tr>
                        <tr>
                            <td>Ward No.<br><strong><?= $ward_no2 ?></strong> </td>
                            <td>Any other Business in the name of Applicant located in the MB Area<br><strong><?= $other_business ?></strong> </td>
                        </tr>
                        <tr>
                            <td>Holding no.of the House/Room<br><strong><?= $holding_no ?></strong> </td>
                            <td>Name of the Road<br><strong><?= $road_name ?></strong> </td>
                        </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Fees</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tr>
                            <td>Name of Trade<br><strong><?= $trade_name ?></strong> </td>
                            <td>Fees<br><strong><?= $fees ?></strong> </td>
                        </tr>
                        </tbody>
                    </table>

                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">ATTACHED ENCLOSURE(S) </legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type of Enclosure</th>
                                <th>Enclosure Document</th>
                                <th>File/Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Address Proof</td>
                                <td style="font-weight:bold"><?= $proof_ownership_type ?></td>
                                <td>
                                    <?php if (strlen($proof_ownership)) { ?>
                                        <a href="<?= base_url($proof_ownership) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Identity Proof</td>
                                <td style="font-weight:bold"><?= $identity_proof_type ?></td>
                                <td>
                                    <?php if (strlen($identity_proof)) { ?>
                                        <a href="<?= base_url($identity_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>UBIN Certificate</td>
                                <td style="font-weight:bold"><?= $ubin_certificate_type ?></td>
                                <td>
                                    <?php if (strlen($ubin_certificate)) { ?>
                                        <a href="<?= base_url($ubin_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <?php  //End of if 
                            ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->



            <div class="card-footer text-center no-print">
                <?php if ($status === 'DRAFT') { ?>
                    <a href="<?= base_url('spservices/tradelicence/application/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>

                <?php if ($user_type == 'user') { ?>
                    <a href="<?= base_url('spservices/tradelicence/payment/initiate/' . $obj_id) ?>" class="btn btn-success frmsbbtn">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } else { ?>
                    <a href="<?= base_url('spservices/tradelicence/payment/initiate/' . $obj_id) ?>" class="btn btn-success frmsbbtn">
                        <i class="fa fa-angle-double-right"></i>Make Payment
                    </a>
                <?php } ?>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>