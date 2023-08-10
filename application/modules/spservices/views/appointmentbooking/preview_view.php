<?php
$obj_id = $dbrow->{'_id'}->{'$id'};    
$appl_ref_no_temp = $dbrow->service_data->appl_ref_no;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$fathers_name = $dbrow->form_data->fathers_name;
$mobile_number = $dbrow->form_data->mobile_number;
$email_id = $dbrow->form_data->email_id;
$applicant_address = $dbrow->form_data->applicant_address;
$office_district = $dbrow->form_data->office_district;
$district_name = $dbrow->form_data->district_name;
$sro_code = $dbrow->form_data->sro_code;
$office_name = $dbrow->form_data->office_name;
$appointment_type = $dbrow->form_data->appointment_type;
$at_id = $appointment_type->at_id;
$appointment_date = $dbrow->form_data->appointment_date;
$appointee_ref_no = $dbrow->form_data->appointee_ref_no;
$appointee_name = $dbrow->form_data->appointee_name;
$appointee_bride_name = $dbrow->form_data->appointee_bride_name;
$appointee_groom_name = $dbrow->form_data->appointee_groom_name;
$deed_type = $dbrow->form_data->deed_type;
$status = $dbrow->service_data->appl_status??''; 
$payment_status = $dbrow->form_data->payment_status??"UNPAID";
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">   
    $(document).ready(function () {     
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
        
        $(document).on("click", "#finalsubmit", function(){
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
                    window.location.href = "<?=base_url('spservices/necertificate/post_data/'.$obj_id)?>";
                }
            });
        });     
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$service_name?> 
            </div>
            <div class="card-body" style="padding:5px">
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name / পিতৃৰ নাম<br><strong><?=$fathers_name?></strong> </td>
                                <td>Address of the applicant/আবেদনকাৰীৰ ঠিকনা<br><strong><?=$applicant_address?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile_number?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email_id?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Office for application submission/আবেদন জমা কৰিবলগীয়া কাৰ্য্যালয়</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">                            
                            <tr>
                                <td>District/জিলা নিৰ্বাচন কৰক<br><strong><?=$district_name?></strong> </td>
                                <td> Office/কাৰ্য্যালয় নিৰ্বাচন কৰক<br><strong><?=$office_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Appointment details/নিযুক্তিৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">                            
                            <tr>
                                <td>Appointment Type/ নিযুক্তিৰ ধৰণ<br><strong><?=$appointment_type->at_name?></strong> </td>
                                <td>Date of appointment/নিযুক্তিৰ সময়<br><strong><?=$appointment_date?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Marriage Details/বিবাহৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:40px; display: <?=($at_id == 2)?'block':'none'?>">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Application Ref No/দৰ্খাস্তৰ নং<br><strong><?=$appointee_ref_no?></strong> </td>
                                <td>Applicant Name/আবেদনকাৰীৰ নাম <br><strong><?=$appointee_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Bride Name/কইনাৰ নাম <br><strong><?=$appointee_bride_name?></strong> </td>
                                <td>Groom Name/দৰাৰ নাম<br><strong><?=$appointee_groom_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px; display: <?=($at_id == 1)?'block':'none'?>">
                    <legend class="h5">Deed Details/দলিলৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">                            
                            <tr>
                                <td>Deed type/দলিলৰ ধৰণ<br><strong><?=$deed_type?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/appointmentbooking/registration/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if($payment_status !== 'PAID') { ?>    
                    <a href="<?=base_url('spservices/appointmentbooking/payment/initiate/'.$obj_id)?>" class="btn btn-warning frmsbbtn">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>                    
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>