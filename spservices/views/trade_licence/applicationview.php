<?php
$obj_id = $dbrow->{'_id'}->{'$id'};       
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $form_status = $dbrow->service_data->appl_status;

    $circle_id = $dbrow->form_data->circle_id;
    $district_id_ca = $dbrow->form_data->district_id_ca;
    
    $first_name = $dbrow->form_data->first_name;
    $last_name = $dbrow->form_data->last_name;
    $father_name = $dbrow->form_data->father_name;
    $contact_number = $dbrow->form_data->mobile_number;
    $emailid = $dbrow->form_data->email;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $age = $dbrow->form_data->age;
    $community = $dbrow->form_data->community;
    $profession = $dbrow->form_data->profession;
    $nationality = $dbrow->form_data->nationality;

    $pa_address_line_1 = $dbrow->form_data->pa_address_line_1;
    $pa_address_line_2 = $dbrow->form_data->pa_address_line_2;
    $pa_address_line_3 = $dbrow->form_data->pa_address_line_3;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_police_station = $dbrow->form_data->pa_police_station;
    $pa_circle = $dbrow->form_data->pa_circle;

    $address_same = $dbrow->form_data->address_same;

    $ca_address_line_1 = $dbrow->form_data->ca_address_line_1;
    $ca_address_line_2 = $dbrow->form_data->ca_address_line_2;
    $ca_address_line_3 = $dbrow->form_data->ca_address_line_3;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_police_station = $dbrow->form_data->ca_police_station;
    $ca_district = $dbrow->form_data->ca_district;

    $market_place = $dbrow->form_data->market_place;
    $commodities = $dbrow->form_data->commodities;
    $location = $dbrow->form_data->location;
    $govt_emp = $dbrow->form_data->govt_emp;
    $trade_area = $dbrow->form_data->trade_area;

    $soft_doc_type = $dbrow->form_data->soft_doc_type;
    $soft_doc = $dbrow->form_data->soft_doc;
    $passport_pic_type = $dbrow->form_data->passport_pic_type;
    $passport_pic = $dbrow->form_data->passport_pic;
    $doc_type = $dbrow->form_data->doc_type;
    $doc = $dbrow->form_data->doc;
    $date = $dbrow->service_data->submission_date;
    $payment_status ="";

if($form_status==='QS'){
//        $query_doc = $dbrow->query_doc??'';
        $query_answered = $dbrow->form_data->query_answered??'';
}
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
                   APPLICATION FORM FOR TRADING LICENCE
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
                                    TRADING LICENCE
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
                                <td style="width:50%">First Name<strong> : <?=$first_name?></strong> </td>
                                <td>Last Name<strong> : <?=$last_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name/Husband Name<strong> : <?=$father_name?></strong> </td>
                                <td>Mobile Number<strong> : <?=$contact_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>Email<strong> : <?=$emailid?></strong> </td>
                                <td>Age<strong> : <?=$age?></strong> </td>
                            </tr>
                            <tr>
                                <td>Gender<strong> : <?=$applicant_gender?></strong> </td>
                                <td>Profession<strong> : <?=$profession?> </strong> </td>
                            </tr>
                            <tr>
                                <td>Nationality<strong> :<?=$nationality?></strong> </td>
                                <td>Community<strong> :<?=$community?></strong> </td>
                            </tr>
                            
                            
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Permanent Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Address Line 1<strong> : <?=$pa_address_line_1?></strong> </td>
                                <td>Address Line 2<strong> : <?=$pa_address_line_2?></strong> </td>
                            </tr>
                            <tr>
                                <td>State<strong> : Assam</strong> </td>
                                <td>Country<strong> : India</strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : Dima Hasao</strong> </td>
                                <td>Address Line 3<strong> : <?=$pa_address_line_3?></strong> </td>
                            </tr>
                            <tr>
                                <td>Revenue Circle<strong> : <?=$pa_circle?></strong> </td>
                                <td>Postal / Zip Code<strong> : <?=$pa_pin_code?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office<strong> : <?=$pa_post_office?></strong> </td>
                                <td>Police Station<strong> : <?=$pa_police_station?></strong> </td>
                            </tr> 
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Current Address</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                         <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Address Line 1<strong> : <?=$ca_address_line_1?></strong> </td>
                                <td>Address Line 2<strong> : <?=$ca_address_line_2?></strong> </td>
                            </tr>
                            <tr>
                                <td>State<strong> : Assam</strong> </td>
                                <td>Country<strong> : India</strong> </td>
                            </tr>
                            <tr>
                                <td>District<strong> : <?=$ca_district?></strong> </td>
                                <td>Address Line 3<strong> : <?=$ca_address_line_3?></strong> </td>
                            </tr>
                            <tr>
                                <td>Postal / Zip Code<strong> : <?=$ca_pin_code?></strong> </td>
                                <td>Police Station<strong> : <?=$ca_police_station?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office<strong> : <?=$ca_post_office?></strong> </td>
                                
                            </tr> 
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Other Details</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Name of Market Place<strong> : <?=$market_place?> </strong> </td>
                                <td>Commodities to be dealt<strong> :<?=$commodities?> </strong> </td>
                            </tr>
                            <tr>
                                <td>If any other working unit in N.C. Hills Dist., Please state the location<strong> : <?=$location?></strong> </td>
                                
                                <td>Are you an employee of Govt./Semi Govt./Govt. Undertaking<strong> : <?=$govt_emp?></strong> </td>
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
                            <?php if($this->slug !== 'user') { ?>
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
                            <?php }//End of if ?>
                      
                            <td>Documents</td>
                            <td style="font-weight:bold"><?=$doc_type?></td>
                                <td>
                                    <?php if(strlen($doc)){ ?>
                                        <a href="<?=base_url($doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <?php if($form_status === "QS") { ?>
                                                                              
                                            <tr>
                                                <td colspan="3">
                                                    <label>Your remarks </label>
                                                    <?=$query_answered?></textarea>
                                                </div>
                                            </tr>
                                        <?php }//End of if ?>
                            
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <div class="card-footer text-center no-print">
                <a href="<?= base_url("iservices/transactions")?>" class="btn btn-info btn-sm mbtn" >Back</a>

            </div><!--End of .card-footer-->
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>

