<?php
    $obj_id = $dbrow->{'_id'}->{'$id'};        
    $form_status = $dbrow->service_data->appl_status;
    //pre($form_status);
    $applicant_name = $dbrow->form_data->applicant_name;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $mobile_number = $dbrow->form_data->mobile_number;
    $email = $dbrow->form_data->email;
    $dob = $dbrow->form_data->dob;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $spouse_name = $dbrow->form_data->spouse_name;
    $passport_no = $dbrow->form_data->passport_no;
    $pan = $dbrow->form_data->pan;
    $aadhar_no = $dbrow->form_data->aadhar_no;

    $pa_house_no = $dbrow->form_data->pa_house_no;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_country = $dbrow->form_data->pa_country;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_police_station = $dbrow->form_data->pa_police_station;
    $pa_police_station_code = $dbrow->form_data->pa_police_station_code;
    $pa_subdivision = $dbrow->form_data->pa_subdivision;
    $pa_revenuecircle = $dbrow->form_data->pa_revenuecircle;
    $pa_year = $dbrow->form_data->pa_year;
    $pa_month = $dbrow->form_data->pa_month;
    $pa_mouza = $dbrow->form_data->pa_mouza;

    $address_same = $dbrow->form_data->address_same;

    $ca_house_no = $dbrow->form_data->ca_house_no;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_country = $dbrow->form_data->ca_country;
    $ca_state = $dbrow->form_data->ca_state;
    $ca_district = $dbrow->form_data->ca_district;
    $ca_police_station = $dbrow->form_data->ca_police_station;
    $ca_police_station_code = $dbrow->form_data->ca_police_station_code;
    $ca_subdivision = $dbrow->form_data->ca_subdivision;
    $ca_revenuecircle = $dbrow->form_data->ca_revenuecircle;
    $ca_year = $dbrow->form_data->ca_year;
    $ca_month = $dbrow->form_data->ca_month;
    $ca_mouza = $dbrow->form_data->ca_mouza;

    $purpose = $dbrow->form_data->purpose;
    $institute_name = $dbrow->form_data->institute_name;
    $criminal_rec = $dbrow->form_data->criminal_rec;
    $status = $dbrow->form_data->status;
    $payment_status = "";

    //$soft_doc_type = $dbrow->form_data->soft_doc_type;
    //$soft_doc = $dbrow->form_data->soft_doc;
    $birth_doc_type = $dbrow->form_data->birth_doc_type;
    $birth_doc = $dbrow->form_data->birth_doc;
    $passport_doc_type = $dbrow->form_data->passport_doc_type;
    $passport_doc = $dbrow->form_data->passport_doc;
    $emp_proof_type = $dbrow->form_data->emp_proof_type;
    $emp_doc = $dbrow->form_data->emp_doc;
    $address_doc_type = $dbrow->form_data->address_doc_type;
    $address_doc = $dbrow->form_data->address_doc;
    $forefathers_doc_type = $dbrow->form_data->forefathers_doc_type;
    $forefathers_doc = $dbrow->form_data->forefathers_doc;
    $property_doc_type = $dbrow->form_data->property_doc_type;
    $property_doc = $dbrow->form_data->property_doc;
    $voter_doc_type = $dbrow->form_data->voter_doc_type;
    $voter_doc = $dbrow->form_data->voter_doc;
    $passport_pic = $dbrow->form_data->passport_pic;
    $prc_doc_type = $dbrow->form_data->prc_doc_type;
    $prc_doc = $dbrow->form_data->prc_doc;
    $admit_doc_type = $dbrow->form_data->admit_doc_type;
    $admit_doc = $dbrow->form_data->admit_doc;
    $date = $dbrow->service_data->submission_date;

//if($form_status==='QS'){
//        $query_doc = $dbrow->query_doc??'';
//        $query_answered = $dbrow->form_data->query_answered??'';
//    }
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
    .table td, .table th {
        font-size: 14px;
        padding: 2px;        
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">   
    $(document).ready(function () {      
        
        var txn, clickedBtnId;
        $(document).on("click", "#printBtn", function(){
            $("#printDiv").print({
                addGlobalStyles : true,
                stylesheet : null,
                rejectWindow : true,
                noPrintSelector : ".no-print",
                iframe : true,
                append : null,
                prepend : null
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="text-align: center; font-size: 20px; color: #000; font-family: georgia,serif; font-weight: bold">
                   PREVIEW OF APPLICATION FORM FOR PERMANENT RESIDENCE CERTIFICATE FOR HIGHER EDUCATION
            </div>
            <div class="card-body" style="padding:5px">
                  <?php if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="text-align: left; width: 25%">
                                <img src="<?=base_url('assets/frontend/images/assam_logo.png')?>" style="width: 80px; height: 100px">
                            </td>
                            <td class="text-center">
                                <h1 style="font-size: 22px; padding: 0px; margin: 0px; line-height: 33px; font-weight: bold; color: #00346c">
                                    Permanent Residence Certificate
                                </h1>
                            </td>
                            <td style="text-align: right; width: 25%">
                                <img src="<?=base_url($passport_pic)?>" style="width: 100px; height: 100px">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                
                                <span style="float:right; font-size: 12px;">Date:<?=format_mongo_date($date)?></span>
                            </td>                                
                        </tr>
                    </tbody>
                </table>
                
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Details of the Applicant </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Applicant’s Name<strong> : <?=$applicant_name?></strong> </td>
                                <td>Father's Name<strong> : <?=$father_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's Name<strong> : <?=$mother_name?></strong> </td>
                                <td>Mobile Number<strong> : <?=$mobile_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>Email<strong> : <?=$email?></strong> </td>
                                <td>Date of Birth<strong> : <?=$dob?></strong> </td>
                            </tr>
                            <tr>
                                <td>Gender<strong> : <?=$applicant_gender?></strong> </td>
                                <td>Spouse Name<strong> : <?=$spouse_name?> </strong> </td>
                            </tr>
                            <tr>
                                <td>Passport Number<strong> :<?=$passport_no?></strong> </td>
                                <td>PAN<strong> :<?=$pan?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar Number<strong> :<?=$aadhar_no?></strong> </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Permanent Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House no. /Flat no.<strong> : <?=$pa_house_no?></strong> </td>
                                <td>Village/Town<strong> : <?=$pa_village?></strong> </td>
                            </tr>
                            <tr>
                                <td>State<strong> : <?=$pa_state?></strong> </td>
                                <td>Country<strong> : <?=$pa_country?></strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : <?=$pa_district?></strong> </td>
                                <td>Sub Division<strong> : <?=$pa_subdivision?></strong> </td>
                            </tr>
                            <tr>
                                <td>Circle<strong> : <?=$pa_revenuecircle?></strong> </td>
                                <td>Mouza<strong> : <?=$pa_mouza?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office<strong> : <?=$pa_post_office?></strong> </td>
                                <td>Police Station<strong> : <?=$pa_police_station?></strong> </td>
                            </tr>
                             <tr>
                                <td>Pin Code<strong> : <?=$pa_pin_code?></strong> </td>
                                <td>Police Station Code<strong> : <?=$pa_police_station_code?></strong> </td>
                            </tr>
                            <tr>
                                <td>Duration Of Stay<strong> : Year : <?=$pa_year?>, Month :<?=$pa_month?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Current Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House no. /Flat no.<strong> : <?=$ca_house_no?></strong> </td>
                                <td>Village/Town<strong> : <?=$ca_village?></strong> </td>
                            </tr>
                            <tr>
                                <td>State<strong> : <?=$ca_state?></strong> </td>
                                <td>Country<strong> : <?=$ca_country?></strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : <?=$ca_district?></strong> </td>
                                <td>Sub Division<strong> : <?=$ca_subdivision?></strong> </td>
                            </tr>
                            <tr>
                                <td>Circle<strong> : <?=$ca_revenuecircle?></strong> </td>
                                <td>Mouza<strong> : <?=$ca_mouza?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office<strong> : <?=$ca_post_office?></strong> </td>
                                <td>Police Station<strong> : <?=$ca_police_station?></strong> </td>
                            </tr>
                             <tr>
                                <td>Pin Code<strong> : <?=$ca_pin_code?></strong> </td>
                                <td>Police Station Code<strong> : <?=$ca_police_station_code?></strong> </td>
                            </tr>
                            <tr>
                                <td>Duration Of Stay<strong> : Year : <?=$ca_year?>, Month :<?=$ca_month?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">PRC Related Information</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Reason for Application for PRC<strong> : <?=$purpose?> </strong> </td>
                                <td>Name of the Institution where Studied Last<strong> :<?=$institute_name?> </strong> </td>
                            </tr>
                            <tr>
                                <td>Do you have any criminal record or criminal proceeeding of you or your family in any part of the country?<strong> : <?=$criminal_rec?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Attached Enclosure(s)</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <thead>
                            <tr>
                                <th>Type of Enclosure</th>
                                <th>Enclosure Document</th>
                                <th>File/Reference</th>
                            </tr>
                        </thead>
                        <tbody style="border-top: none !important">
                            <!--
                            <tr>
                                <td>Upload hard copy of the User Form</td>
                                <td style="font-weight:bold"><?=$soft_doc_type?></td>
                                <td>
                                    <?php if(strlen($soft_doc)){ ?>
                                        <a href="<?=base_url($soft_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            -->
                            <tr>
                                <td>Copy of the Birth Certificate issued by competent authority</td>
                                <td style="font-weight:bold"><?=$birth_doc_type?></td>
                                <td>
                                    <?php if(strlen($birth_doc)){ ?>
                                        <a href="<?=base_url($birth_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                           <tr>
                                <td>Copy of Indian Passport or Certified copy of the NRC 1951</td>
                                <td style="font-weight:bold"><?=$passport_doc_type?></td>
                                <td>
                                    <?php if(strlen($passport_doc)){ ?>
                                        <a href="<?=base_url($passport_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Employment Certificate issued by the employer showing joining in present place of posting, if any</td>
                                <td style="font-weight:bold"><?=$emp_proof_type?></td>
                                <td>
                                    <?php if(strlen($emp_doc)){ ?>
                                        <a href="<?=base_url($emp_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Upload One Address proof documents of Self or Parent’s</td>
                                <td style="font-weight:bold"><?=$address_doc_type?></td>
                                <td>
                                    <?php if(strlen($address_doc)){ ?>
                                        <a href="<?=base_url($address_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years or Documents related to guardian having continuously resided in Assam for a minimum period of 20 years</td>
                                <td style="font-weight:bold"><?=$forefathers_doc_type?></td>
                                <td>
                                    <?php if(strlen($forefathers_doc)){ ?>
                                        <a href="<?=base_url($forefathers_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                            <td>Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.</td>
                            <td style="font-weight:bold"><?=$property_doc_type?></td>
                                <td>
                                    <?php if(strlen($property_doc)){ ?>
                                        <a href="<?=base_url($property_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            
                            <tr>
                            <td>Certified copy of the voters list to check the linkage</td>
                            <td style="font-weight:bold"><?=$voter_doc_type?></td>
                                <td>
                                    <?php if(strlen($voter_doc)){ ?>
                                        <a href="<?=base_url($voter_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                            <td>Copy of PRC of any member of family of the Applicant stating relationship, if any</td>
                            <td style="font-weight:bold"><?=$prc_doc_type?></td>
                                <td>
                                    <?php if(strlen($prc_doc)){ ?>
                                        <a href="<?=base_url($prc_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                            <td>Copy of HSLC Certificate/Admit Card</td>
                            <td style="font-weight:bold"><?=$admit_doc_type?></td>
                                <td>
                                    <?php if(strlen($admit_doc)){ ?>
                                        <a href="<?=base_url($admit_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <!--
                            <?php if($form_status === "QS") { ?>
                                            <tr>
                                                <td><label>Uploaded documents</label></td>
                                                <td style="font-weight:bold">Querried doc</td>
                                                <td> 
                                                    <?php if(strlen($query_doc)){ ?>
                                                        <a href="<?=base_url($query_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if ?>
                                                </td>                                    
                                            </tr>                                        
                                            <tr>
                                                <td colspan="3">
                                                    <label>Your remarks </label>
                                                    <?=$query_answered?></textarea>
                                                </div>
                                            </tr>
                                        <?php }//End of if ?>
                            -->
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <a href="<?= base_url("iservices/transactions")?>" class="btn btn-info btn-sm mbtn" >Back</a>

            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>

