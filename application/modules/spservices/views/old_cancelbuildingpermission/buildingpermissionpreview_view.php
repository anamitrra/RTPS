<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$application_type = (!empty($dbrow->form_data->application_type) == "1")? "General Proposal": "NA";
if ($dbrow->form_data->case_type == 1) 
    $case_type = "New Type";
else if ($dbrow->form_data->case_type == 2)
    $case_type = "Alteration Addition Existing";
else
    $case_type = "NA";
$ertp = $dbrow->form_data->ertp;
$any_old_permission = $dbrow->form_data->any_old_permission;
$technical_person_name = !empty($dbrow->form_data->technical_person_name)? $dbrow->form_data->technical_person_name: "NA";
$old_permission_no = !empty($dbrow->form_data->old_permission_no)? $dbrow->form_data->old_permission_no: "NA";
$empanelled_reg_tech_person = !empty($dbrow->form_data->empanelled_reg_tech_person)? $dbrow->form_data->empanelled_reg_tech_person: "NA";

$house_no_landmak = $dbrow->form_data->house_no_landmak;
$mouza = $dbrow->form_data->mouza;
$name_of_road = $dbrow->form_data->name_of_road;
$panchayat = $dbrow->form_data->panchayat;
$revenue_village = $dbrow->form_data->revenue_village;
$dag_no = !empty($dbrow->form_data->dag_no)? $dbrow->form_data->dag_no: "NA";
$zone = $dbrow->form_data->zone;
$new_dag_no = $dbrow->form_data->new_dag_no;
$ward_no = $dbrow->form_data->ward_no;
$patta_no = $dbrow->form_data->patta_no;
$site_pin_code = $dbrow->form_data->site_pin_code;
$new_patta_no = $dbrow->form_data->new_patta_no;

$applicant_name = $dbrow->form_data->applicant_name;
if ($dbrow->form_data->applicant_gender == 1) 
    $applicant_gender = "Male";
else if ($dbrow->form_data->applicant_gender == 2)
    $applicant_gender = "Female";
else if ($dbrow->form_data->applicant_gender == 3)
    $applicant_gender = "Others";
else
    $applicant_gender = "NA";
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$spouse_name = !empty($dbrow->form_data->spouse_name)? $dbrow->form_data->spouse_name: "NA";
$permanent_address = $dbrow->form_data->permanent_address;
$pin_code = $dbrow->form_data->pin_code;
$mobile = $dbrow->form_data->mobile; 
if ($dbrow->form_data->monthly_income == 1) 
    $monthly_income = "Below Rs.25000/-";
else if ($dbrow->form_data->monthly_income == 2)
    $monthly_income = "Between Rs.25000/- and Rs.45000/-";
else if ($dbrow->form_data->monthly_income == 3)
    $monthly_income = "Above Rs.45000/-";
else
    $monthly_income = "NA";
$pan_no = $dbrow->form_data->pan_no;
$email = !empty($dbrow->form_data->email)? $dbrow->form_data->email: "NA";

$owner_details = $dbrow->form_data->owner_details; 

$technical_person_document = $dbrow->form_data->technical_person_document ?? '';
$technical_person_document_type = $dbrow->form_data->technical_person_document_type ?? '';
$old_permission_copy  = $dbrow->form_data->old_permission_copy ?? '';
$old_permission_copy_type  = $dbrow->form_data->old_permission_copy_type  ?? '';
$old_drawing  = $dbrow->form_data->old_drawing  ?? '';
$old_drawing_type  = $dbrow->form_data->old_drawing_type  ?? '';
$drawing  = $dbrow->form_data->drawing  ?? '';
$drawing_type  = $dbrow->form_data->drawing_type  ?? '';
$trace_map  = $dbrow->form_data->trace_map  ?? '';
$trace_map_type  = $dbrow->form_data->trace_map_type  ?? '';
$key_plan  = $dbrow->form_data->key_plan  ?? '';
$key_plan_type  = $dbrow->form_data->key_plan_type  ?? '';
$site_plan  = $dbrow->form_data->site_plan  ?? '';
$site_plan_type  = $dbrow->form_data->site_plan_type  ?? '';
$building_plan  = $dbrow->form_data->building_plan  ?? '';
$building_plan_type  = $dbrow->form_data->building_plan_type  ?? '';
$certificate_of_supervision = $dbrow->form_data->certificate_of_supervision  ?? '';
$certificate_of_supervision_type  = $dbrow->form_data->certificate_of_supervision_type  ?? '';
$area_statement = $dbrow->form_data->area_statement  ?? '';
$area_statement_type  = $dbrow->form_data->area_statement_type  ?? '';
$amended_byelaws = $dbrow->form_data->amended_byelaws  ?? '';
$amended_byelaws_type  = $dbrow->form_data->amended_byelaws_type  ?? '';
$form_no_six = $dbrow->form_data->form_no_six  ?? '';
$form_no_six_type  = $dbrow->form_data->form_no_six_type  ?? '';
$indemnity_bond = $dbrow->form_data->indemnity_bond  ?? '';
$indemnity_bond_type  = $dbrow->form_data->indemnity_bond_type  ?? '';
$undertaking_signed = $dbrow->form_data->undertaking_signed??'';
$undertaking_signed_type = $dbrow->form_data->undertaking_signed_type??'';
$party_applicant_form = $dbrow->form_data->party_applicant_form??'';
$party_applicant_form_type = $dbrow->form_data->party_applicant_form_type??'';
$date_property_tax = $dbrow->form_data->date_property_tax??'';
$date_property_tax_type = $dbrow->form_data->date_property_tax_type??'';
$service_plan = $dbrow->form_data->service_plan??'';
$service_plan_type = $dbrow->form_data->service_plan_type??'';
$parking_plan = $dbrow->form_data->parking_plan??'';
$parking_plan_type = $dbrow->form_data->parking_plan_type??'';
$ownership_document_of_land = $dbrow->form_data->ownership_document_of_land??'';
$ownership_document_of_land_type = $dbrow->form_data->ownership_document_of_land_type??'';
$any_other_document = $dbrow->form_data->any_other_document??'';
$any_other_document_type = $dbrow->form_data->any_other_document_type??'';
$construction_estimate = $dbrow->form_data->construction_estimate??'';
$construction_estimate_type = $dbrow->form_data->construction_estimate_type??'';

$soft_copy_type = $dbrow->form_data->soft_copy_type??'';
$soft_copy = $dbrow->form_data->soft_copy??'';
$appl_status = $dbrow->service_data->appl_status;

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

        $(document).on("click", ".frmsbbtn", function(e){ 

            $(".frmsbbtn").text("Plese wait..");
            $(".frmsbbtn").prop('disabled',true);
            e.preventDefault();

            let url='<?=base_url('spservices/buildingpermission/registration/finalsubmition')?>'
            let ackLocation='<?=base_url('spservices/applications/acknowledgement/')?>'+'<?=$obj_id?>';
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            var msg = "Once you submitted, you won't able to revert this";   

            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type:'POST',
                        dataType: 'json',
                        data: {
                            "obj":'<?=$obj_id?>'
                        },
                        success:function (response) {
                            console.log(response);
                            if(response.status){
                                
                                Swal.fire(
                                    'Success',
                                    'Application submited successfully',
                                    'success'
                                );

                                window.location.replace(ackLocation)
                            }else{
                                Swal.fire(
                                    'Failed!',
                                    'Something went wrong please try again!',
                                    'fail'
                                );
                            }
                        },
                        error:function () {
                            $(".frmsbbtn").prop('disabled',false);
                            $(".frmsbbtn").text("Save");
                            Swal.fire(
                                'Failed!',
                                'Something went wrong please try again!',
                                'fail'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <?=$service_name?><br>
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

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Proposal Information/ প্ৰস্তাৱ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Application Type/ আবেদন প্ৰকাৰ<br><strong><?=$application_type?></strong> </td>
                                <td>Case Type/ কেছৰ ধৰণ <br><strong><?=$case_type?></strong> </td>
                            </tr>
                            <tr>
                                <td>Do you have any technical person to assist other than ERTP?/ ই আৰ টি পিৰ বাহিৰেও সহায় কৰিবলৈ আপোনাৰ কোনো কাৰিকৰী ব্যক্তি আছেনে? <br><strong><?=$ertp?></strong> </td>
                                <td>Do you have any old permission?/ আপোনাৰ কিবা পুৰণি অনুমতি আছে নেকি?<br><strong><?=$any_old_permission?></strong> </td>
                            </tr>
                            <tr>
                                <td>Technical Person Name/ কাৰিকৰী ব্যক্তিৰ নাম<br><strong><?=$technical_person_name?></strong> </td>
                                <td>Old Permission No/ পুৰণি অনুমতি নম্বৰ <br><strong><?=$old_permission_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Empanelled Registered Technical Person/ তালিকাভুক্ত পঞ্জীভুক্ত কাৰিকৰী ব্যক্তি<br><strong><?=$$empanelled_reg_tech_person = !empty($dbrow->form_data->empanelled_reg_tech_person)? $dbrow->form_data->empanelled_reg_tech_person: "NA";?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <?php if ($ertp == "yes") { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Site Address/স্থানৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>House No/Landmark/ঘৰ নং/লেণ্ডমাৰ্ক<br><strong><?=$house_no_landmak?></strong> </td>
                                <td>Mouza/ মৌজা<br><strong><?=$mouza?></strong> </td>
                            </tr>
                            <tr>
                                <td>Name Of Road/পথৰ নাম<br><strong><?=$name_of_road?></strong> </td>
                                <td>Panchayat/পঞ্চায়ত <br><strong><?=$panchayat?></strong> </td>
                            </tr>
                            <tr>
                                <td>Revenue Village/ৰাজহ গাঁও<br><strong><?=$revenue_village?></strong> </td>
                                <td>Dag No/দাগ নং <br><strong><?=$dag_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Zone/ জ'ন<br><strong><?=$zone?></strong> </td>
                                <td>New Dag No/ নতুন দাগ নম্বৰ <br><strong><?=$new_dag_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Ward No/ ৱাৰ্ড নং<br><strong><?=$ward_no?></strong> </td>
                                <td>Patta No/ পট্টা নং <br><strong><?=$patta_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pin Code/ পিন কোড<br><strong><?=$site_pin_code?></strong> </td>
                                <td>New Patta No/ নতুন পট্টা নং <br><strong><?=$new_patta_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>     
                <?php } ?>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Gender/ লিংগ<br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Fathers Name/পিতাৰ নাম<br><strong><?=$father_name?></strong> </td>
                                <td>Mother Name/মাতৃৰ নাম <br><strong><?=$mother_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Spouse Name/পত্নীৰ নাম<br><strong><?=$spouse_name?></strong> </td>
                                <td>Permanent Address/স্থায়ী ঠিকনা <br><strong><?=$permanent_address?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pin Code/ পিন কোড<br><strong><?=$pin_code?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>Monthly Income/ মাহিলী উপাৰ্জন<br><strong><?=$monthly_income?></strong> </td>
                                <td>PAN No./ পেন নং <br><strong><?=$pan_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <?php if (count($dbrow->form_data->owner_details) > 0) { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Ownership Information/ মালিকীস্বত্বৰ তথ্য </legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Owner's Name/ মালিকৰ নাম</th>
                                <th>Owner's Gender/ মালিকৰ লিংগ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($dbrow->form_data->owner_details as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?php print $value->owner_name; ?></td>
                                        <td>
                                            <?php
                                            if($value->owner_gender == "M") 
                                                print "Male"; 
                                            else if($value->owner_gender == "F") 
                                                print "Female";
                                            else if($value->owner_gender == "O") 
                                                print "Others";
                                            else
                                            print "NA";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </fieldset>

               <?php } ?>

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
                                <td>Upload Technical Person Qualification Document<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $technical_person_document_type ?></td>
                                <td>
                                    <?php if (strlen($technical_person_document)) { ?>
                                        <a href="<?= base_url($technical_person_document) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Upload Old Permission Copy <span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $old_permission_copy_type ?></td>
                                <td>
                                    <?php if (strlen($old_permission_copy)) { ?>
                                        <a href="<?= base_url($old_permission_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Upload Old Drawing <span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $old_drawing_type ?></td>
                                <td>
                                    <?php if (strlen($old_drawing)) { ?>
                                        <a href="<?= base_url($old_drawing) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <?php if(strlen($drawing)) { ?>
                            <tr>
                                <td>Drawing</td>
                                <td style="font-weight:bold"><?= $drawing_type ?></td>
                                <td>
                                    <?php if (strlen($drawing)) { ?>
                                        <a href="<?= base_url($drawing) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($trace_map)) { ?>
                            <tr>
                                <td>Trace map of the proposed site indicating the Dag no, Patta no, Revenue Village, Mouza and the Town of the concerned District</td>
                                <td style="font-weight:bold"><?= $trace_map_type ?></td>
                                <td>
                                    <?php if (strlen($trace_map)) { ?>
                                        <a href="<?= base_url($trace_map) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($key_plan)) { ?>
                            <tr>
                                <td>A key plan of the showing natural channels, drains, roads and landmarks</td>
                                <td style="font-weight:bold"><?=$key_plan_type?></td>
                                <td>
                                    <?php if(strlen($key_plan)){ ?>
                                        <a href="<?=base_url($key_plan)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($site_plan)) { ?>
                            <tr>
                                <td>A site plan drawn to a minimum scale 1:200 with detailed schedule of the plot</td>
                                <td style="font-weight:bold"><?=$site_plan_type?></td>
                                <td>
                                    <?php if(strlen($site_plan)){ ?>
                                        <a href="<?=base_url($site_plan)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>


                            <?php if(strlen($building_plan)) { ?>
                            <tr>
                                <td>A building plan accurately drawn in a minimum scale of 1:100 with dimensions in meters</td>
                                <td style="font-weight:bold"><?=$building_plan_type?></td>
                                <td>
                                    <?php if(strlen($building_plan)){ ?>
                                        <a href="<?=base_url($building_plan)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($certificate_of_supervision_type)) { ?>
                            <tr>
                                <td>A key plan of the showing natural channels, drains, roads and landmarks</td>
                                <td style="font-weight:bold"><?=$certificate_of_supervision_type?></td>
                                <td>
                                    <?php if(strlen($certificate_of_supervision)){ ?>
                                        <a href="<?=base_url($certificate_of_supervision)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($area_statement)) { ?>
                            <tr>
                                <td>Area statement in Form no. 22</td>
                                <td style="font-weight:bold"><?=$area_statement_type?></td>
                                <td>
                                    <?php if(strlen($area_statement)){ ?>
                                        <a href="<?=base_url($area_statement)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($amended_byelaws)) { ?>
                            <tr>
                                <td>Amended From 23 vide Clause 23 of amended Byelaws, 2020</td>
                                <td style="font-weight:bold"><?=$amended_byelaws_type?></td>
                                <td>
                                    <?php if(strlen($amended_byelaws)){ ?>
                                        <a href="<?=base_url($amended_byelaws)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($form_no_six)) { ?>
                            <tr>
                                <td>Form no. 6 in case of building located in hilly topography and in slopes above 30%</td>
                                <td style="font-weight:bold"><?=$form_no_six_type?></td>
                                <td>
                                    <?php if(strlen($form_no_six)){ ?>
                                        <a href="<?=base_url($form_no_six)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($indemnity_bond)) { ?>
                            <tr>
                                <td>Indemnity Bond in Appendix - IV for basement and retaining wall only</td>
                                <td style="font-weight:bold"><?=$indemnity_bond_type?></td>
                                <td>
                                    <?php if(strlen($indemnity_bond)){ ?>
                                        <a href="<?=base_url($indemnity_bond)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($undertaking_signed)) { ?>
                            <tr>
                                <td>An undertaking signed by the land owner or Power of Attorney Holder or Builder or Promoter or the Applicant as per Appendix - V of the bylaws</td>
                                <td style="font-weight:bold"><?=$undertaking_signed_type?></td>
                                <td>
                                    <?php if(strlen($undertaking_signed)){ ?>
                                        <a href="<?=base_url($undertaking_signed)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($party_applicant_form)) { ?>
                            <tr>
                                <td>The party/Applicant should submit an affidavit along with the application form</td>
                                <td style="font-weight:bold"><?=$party_applicant_form_type?></td>
                                <td>
                                    <?php if(strlen($party_applicant_form)){ ?>
                                        <a href="<?=base_url($party_applicant_form)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($date_property_tax)) { ?>
                            <tr>
                                <td>The up to date property tax paid receipt to be submitted, in case of existing building/structure, if any</td>
                                <td style="font-weight:bold"><?=$date_property_tax_type?></td>
                                <td>
                                    <?php if(strlen($date_property_tax)){ ?>
                                        <a href="<?=base_url($date_property_tax)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($service_plan)) { ?>
                            <tr>
                                <td>Service plan showing provision of all the services as provided in the bylaws</td>
                                <td style="font-weight:bold"><?=$service_plan_type?></td>
                                <td>
                                    <?php if(strlen($service_plan)){ ?>
                                        <a href="<?=base_url($service_plan)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($parking_plan)) { ?>
                            <tr>
                                <td>Detailed parking plan in appropriate scale where applicable</td>
                                <td style="font-weight:bold"><?=$parking_plan_type?></td>
                                <td>
                                    <?php if(strlen($parking_plan)){ ?>
                                        <a href="<?=base_url($parking_plan)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($ownership_document_of_land)) { ?>
                            <tr>
                                <td>Ownership document of land, sale deed, mutation/jamabandi/patta/power of attorney</td>
                                <td style="font-weight:bold"><?=$ownership_document_of_land_type?></td>
                                <td>
                                    <?php if(strlen($ownership_document_of_land)){ ?>
                                        <a href="<?=base_url($ownership_document_of_land)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($any_other_document)) { ?>
                            <tr>
                                <td>Any other document that the applicant feels necessary for consideration by the Authority</td>
                                <td style="font-weight:bold"><?=$any_other_document_type?></td>
                                <td>
                                    <?php if(strlen($any_other_document)){ ?>
                                        <a href="<?=base_url($any_other_document)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($construction_estimate)) { ?>
                            <tr>
                                <td>Construction Estimate</td>
                                <td style="font-weight:bold"><?=$construction_estimate_type?></td>
                                <td>
                                    <?php if(strlen($construction_estimate)){ ?>
                                        <a href="<?=base_url($construction_estimate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($soft_copy)) { ?>
                                <tr>
                                    <td>Soft copy of the applicant form</td>
                                    <td style="font-weight:bold"><?=$soft_copy_type?></td>
                                    <td>
                                        <a href="<?=base_url($soft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php }//End of if ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if (($appl_status === 'DRAFT') || ($appl_status === 'payment_initiated')) { ?>
                    <a href="<?=base_url('spservices/buildingpermission/registration/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <?php if(($user_type == 'user') && ($appl_status != 'QA') && ($appl_status != 'QS')) { ?>
                    <a href="JavaScript:Void(0);" class="btn btn-success frmsbbtn">
                            <i class="fa fa-angle-double-right"></i> Submit
                     </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if((($user_type != 'user') && ($appl_status === 'DRAFT')) || ($appl_status === 'payment_initiated')){ ?>
                     <a href="<?=base_url('spservices/buildingpermission/payment/initiate/'.$obj_id)?>" class="btn btn-success">
                            <i class="fa fa-angle-double-right"></i> Make Payment
                     </a>
                <?php }?>                 
            </div><!--End of .card-footer-->
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>