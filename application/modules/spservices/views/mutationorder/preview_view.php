<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$fathers_name = $dbrow->form_data->fathers_name;
$mobile_number = $dbrow->form_data->mobile_number;
$email_id = $dbrow->form_data->email_id;
$spouse_name = $dbrow->form_data->spouse_name;
$pan = $dbrow->form_data->pan;
$address1 = $dbrow->form_data->address1;
$address2 = $dbrow->form_data->address2;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$district_code = $district->district_code;
$circle = $dbrow->form_data->circle;
$circle_code = $circle->circle_code;
$village = $dbrow->form_data->village;
$village_code = $village->village_code;
$patta = $dbrow->form_data->patta;
$patta_no = $patta->patta_no;
$pattadar_name = $dbrow->form_data->pattadar_name;
$case_no = $dbrow->form_data->case_no;
$office_district = $dbrow->form_data->office_district;
$office_district_code = $office_district->office_district_code;
$office_circle = $dbrow->form_data->office_circle;
$office_circle_code = $office_circle->office_circle_code;  
$mutation_doc_type = $dbrow->form_data->mutation_doc_type??null;
$revenue_receipt_type = $dbrow->form_data->revenue_receipt_type??null;
$other_doc_type = $dbrow->form_data->other_doc_type??null;    
$mutation_doc = $dbrow->form_data->mutation_doc??null;
$revenue_receipt = $dbrow->form_data->revenue_receipt??null;
$other_doc = $dbrow->form_data->other_doc??null;
$status = $dbrow->service_data->appl_status??'';
$payment_status = $dbrow->form_data->payment_status??'';
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
                    window.location.href = "<?=base_url('spservices/mutationorder/post_data/'.$obj_id)?>";
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
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's/Husband's Name / পিতৃৰ নাম<br><strong><?=$fathers_name?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email_id?></strong> </td>
                                <td>Spouse Name/পত্নীৰ নাম<br><strong><?=$spouse_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pan Number/পেন নম্বৰ<br><strong><?=$pan?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Location Details/ অৱস্থানৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Address 1/ঠিকনা ১<br><strong><?=$address1?></strong> </td>
                                <td>Address 2/ঠিকনা ২<br><strong><?=$address2?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ৰাজ্য<br><strong><?=$state?></strong> </td>
                                <td>District/জিলা<br><strong><?=$district->district_name??''?></strong> </td>
                            </tr>
                            <tr>
                                <td>Circle/ৰাজহ চক্ৰ<br><strong><?=$circle->circle_name??''?></strong> </td>
                                <td>Revenue Village/Town/গাওঁ/নগৰ<br><strong><?=$village->village_name??''?></strong> </td>
                            </tr>
                            <tr>
                                <td>Patta No/পাট্টা নম্বৰ<br><strong><?=$patta->details??''?></strong> </td>
                                <td>Mutation order to be issued in the name of/পৰিৱৰ্তনৰ আদেশ ৰ নামত জাৰী কৰিব লাগিব<br><strong><?=$pattadar_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mutation Case No/পৰিৱৰ্তন ৰ ক্ষেত্ৰ নম্বৰ<br><strong><?=$case_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Select where Application will be submitted/আৱেদন ক'ত দাখিল কৰা হ'ব বাছনি কৰক</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">                            
                            <tr>
                                <td style="width:50%">District/জিলা নিৰ্বাচন কৰক<br><strong><?=$office_district->office_district_name??''?></strong> </td>
                                <td> Office/কাৰ্য্যালয় নিৰ্বাচন কৰক<br><strong><?=$office_circle->office_circle_name??''?></strong> </td>
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
                                <th style="width:185px">File/Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mutation case no. generated during application for the mutation order and name of parties for which copy of Mutation (Registration) Order/Miscellaneous Case Order is sought</td>
                                <td style="font-weight:bold"><?=$mutation_doc_type?></td>
                                <td>
                                    <?php if(strlen($mutation_doc)){ ?>
                                        <a href="<?=base_url($mutation_doc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Land Revenue Receipt</td>
                                <td style="font-weight:bold"><?=$revenue_receipt_type?></td>
                                <td>
                                    <?php if(strlen($revenue_receipt)){ ?>
                                        <a href="<?=base_url($revenue_receipt)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php if(strlen($other_doc)) { ?>
                                <tr>
                                    <td>Other</td>
                                    <td style="font-weight:bold"><?=$other_doc_type?></td>
                                    <td>
                                        <a href="<?=base_url($other_doc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php }//End of if ?>
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/mutationorder/registration/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if($payment_status !== 'PAID') { ?>    
                    <a href="<?=base_url('spservices/mutationorder/payment/initiate/'.$obj_id)?>" class="btn btn-warning frmsbbtn">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>                    
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>