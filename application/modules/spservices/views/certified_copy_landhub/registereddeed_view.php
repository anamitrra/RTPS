<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->rtps_trans_id;
$applicant_name = $dbrow->applicant_name;
$applicant_gender = $dbrow->applicant_gender;
$relation = $dbrow->relation;
$mobile = $dbrow->mobile;
$address = $dbrow->address;
$email = $dbrow->email;
$deed_nature = $dbrow->deed_nature;
$deedno = $dbrow->deedno;
$pa_district_name = $dbrow->pa_district_name ?? $dbrow->pa_district_name;
$sro_name = $dbrow->sro_name ?? $dbrow->sro_name;
$year_of_registration = $dbrow->year_of_registration;
$serial_registration_not_availabe = isset($dbrow->serial_registration_not_availabe) ? $dbrow->serial_registration_not_availabe:false;
$service_mode = $dbrow->service_mode;
$soft_copy_type = $dbrow->soft_copy_type??'';
$soft_copy = $dbrow->soft_copy??'';

$year_from =isset ($dbrow->year_from ) ? $dbrow->year_from : '';
$year_to = isset ($dbrow->year_to ) ? $dbrow->year_to : '';
$deed_party_name = isset ($dbrow->deed_party_name ) ? $dbrow->deed_party_name : '';
$deed_patta_no = isset ($dbrow->deed_patta_no ) ? $dbrow->deed_patta_no : '';
$deed_dag_no = isset ($dbrow->deed_dag_no ) ? $dbrow->deed_dag_no : '';
$deed_total_land_area = isset ($dbrow->deed_total_land_area ) ? $dbrow->deed_total_land_area : '';

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
    });

    $(document).on("click", ".frmsbbtn", function(){ 
        let url='<?=base_url('spservices/certified_copy_landhub/registereddeed/finalsubmition')?>';
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
                                obj:'<?=$obj_id?>'
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
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" >
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
            Application for Certified Copy of Registered Deed<br>
                        ( পঞ্জীকৃত দলিলৰ প্ৰত্যায়িত নকলৰ বাবে আবেদন ) 
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

                <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 10 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ  ১0 দিনৰ ভিতৰত(সাধাৰণ) অথবা ৩ দিনৰ ভিতৰত(জৰুৰী) প্ৰদান কৰা হ'ব</li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  Rs. 5/- Per page(General Delivery) Rs. 10/- Per page(Urgent Delivery).</li>
                            <li>১. প্ৰতিটো পৃষ্ঠাৰ বাবে ৫ টকাকৈ ( সাধাৰণ ) /  ১0 টকাকৈ (জৰুৰীকালীন)</li>
                            <li>2. RTPS fee of rupees 20/- per appilcation.</li>
                            <li>২. প্ৰতিখন আবেদনৰ বাবত ২০ টকা Rtps ফিছ</li>
                        </ul>   
                        
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. Payment has to be made online and it is to be done when payment request is made by Official.</li>
                            <li>২. কাৰ্যালয়ৰ পৰা অনুৰোধ আহিলে মাছুল অনলাইনত আদায় দিব লাগিব</li>
                        </ul>   

                    </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম : </td>
                                <td style="font-weight:bold"><?=$applicant_name?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ : </td>
                                <td style="font-weight:bold"><?=$applicant_gender?></td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল ) : </td>
                                <td style="font-weight:bold"><?=$mobile?></td>
                                <td>&nbsp;</td>
                                <td>E-Mail / ই-মেইল: </td>
                                <td style="font-weight:bold"><?=$email?></td>
                            </tr>
                            <tr>
                                <td>Address/ঠিকনা : </td>
                                <td style="font-weight:bold"><?=$address?></td>
                                <td>&nbsp;</td>
                                <td>Relation/সম্পৰ্ক: </td>
                                <td style="font-weight:bold"><?=$relation?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Registered Deed/পঞ্জীয়ন হোৱা দলিলৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">Nature of Deed/পঞ্জীয়নৰ প্ৰকাৰ : </td>
                                <td style="width:23%; font-weight:bold"><?=$deed_nature?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Serial number of documents/ নথিপত্ৰৰ ক্ৰমিক সংখ্যা : </td>
                                <td style="width:23%; font-weight:bold"><?=$deedno?></td>
                            </tr>
                            <tr>
                                <td>Year of registration/ পঞ্জীয়নৰ বছৰ : </td>
                                <td style="font-weight:bold"><?=$year_of_registration?></td>
                                <td>&nbsp;</td>
                                <td>Tick on the checkbox if document serial number and year of registration is not available : </td>
                                <td style="font-weight:bold"><?=$serial_registration_not_availabe?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other details related to the deed</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">Year From: </td>
                                <td style="width:23%; font-weight:bold"><?=$year_from?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Year To: </td>
                                <td style="width:23%; font-weight:bold"><?=$year_to?></td>
                            </tr>
                            <tr>
                                <td>Party Name (Any buyer,seller etc) : </td>
                                <td style="font-weight:bold"><?=$deed_party_name?></td>
                                <td>&nbsp;</td>
                                <td>Patta no of the land : </td>
                                <td style="font-weight:bold"><?=$deed_party_name?></td>
                            </tr>
                            <tr>
                                <td>Dag no of the land : </td>
                                <td style="font-weight:bold"><?=$deed_dag_no?></td>
                                <td>&nbsp;</td>
                                <td>Total land area : </td>
                                <td style="font-weight:bold"><?=$deed_total_land_area?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>

           
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Mode of service delivery and Office of Submission/সেৱা প্ৰদানৰ ধৰণ  </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">Select desired mode/পছন্দৰ পদ্ধতি নিৰ্বাচন কৰক : </td>
                                <td style="width:23%; font-weight:bold"><?=$service_mode?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Select District/জিলা নিৰ্বাচন কৰক : </td>
                                <td style="width:23%; font-weight:bold"><?=$pa_district_name?></td>
                            </tr>
                            <tr>
                                <td>Select Office for application submission/আবেদন জমা কৰিবলগীয়া কাৰ্য্যালয় নিৰ্বাচন কৰক : </td>
                                <td style="font-weight:bold"><?=$sro_name?></td>
                                <td>&nbsp;</td>
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>
<!-- 
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
                                <td>Upload the Soft copy of the applicant form<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$soft_copy_type?></td>
                                <td>
                                    <?php if(strlen($soft_copy)){ ?>
                                        <a href="<?=base_url($soft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset> -->


            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <a href="<?=base_url('spservices/certified_copy_landhub/registereddeed/index/'.$obj_id)?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <a href="<?=base_url('spservices/certified_copy_landhub/payment/initiate/'.$obj_id)?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php /*if($user_type == 'user'){ ?>
                    <a href="JavaScript:Void(0);" class="btn btn-success frmsbbtn">
                            <i class="fa fa-angle-double-right"></i> Submit
                     </a>
                <?php }else{ ?>
                     <a href="<?=base_url('spservices/certified_copy_landhub/payment/initiate/'.$obj_id)?>" class="btn btn-success">
                            <i class="fa fa-angle-double-right"></i> Make Payment
                     </a>
                <?php }*/?>
               
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>