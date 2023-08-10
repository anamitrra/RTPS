<?php
// pre($dbrow->form_data->application_form_path);
$service_id = $dbrow->service_data->service_id;
$service_name = $dbrow->service_data->service_name;
$date_of_application = isset($dbrow->service_data->submission_date)?$dbrow->service_data->submission_date :'';
$applicant_name =$dbrow->form_data->applicant_name;
$gender = $dbrow->form_data->applicant_gender;
$age =$dbrow->form_data->age;

$mobile =$dbrow->form_data->mobile;
$timeline = $dbrow->service_data->service_timeline;
// $department_name = $dbrow->service_data->department_name;
$amount = $dbrow->form_data->amount;

$pa_house_no = $dbrow->form_data->pa_house_no;
$pa_street = $dbrow->form_data->pa_street;
$pa_village = $dbrow->form_data->pa_village;
$pa_post_office = $dbrow->form_data->pa_post_office;
$pa_pin_code = $dbrow->form_data->pa_pin_code;
$pa_state = $dbrow->form_data->pa_state;
$pa_district_id = $dbrow->form_data->pa_district_id;
$pa_district_name = $dbrow->form_data->pa_district_name;
$pa_circle = $dbrow->form_data->pa_circle;
$pa_police_station = $dbrow->form_data->pa_police_station;

$address_same = $dbrow->form_data->address_same;
$ca_house_no = $dbrow->form_data->ca_house_no;
$ca_street = $dbrow->form_data->ca_street;
$ca_village = $dbrow->form_data->ca_village;
$ca_post_office = $dbrow->form_data->ca_post_office;
$ca_pin_code = $dbrow->form_data->ca_pin_code;
$ca_state = $dbrow->form_data->ca_state;
$ca_district_id = $dbrow->form_data->ca_district_id;
$ca_district_name = $dbrow->form_data->ca_district_name;
$ca_circle = $dbrow->form_data->ca_circle;
$ca_police_station =$dbrow->form_data->ca_police_station;
$rtps_trans_id = $dbrow->form_data->rtps_trans_id;
$obj_id = $dbrow->{'_id'}->{'$id'};
$application_form_path=isset($dbrow->form_data->application_form_path) ?  $dbrow->form_data->application_form_path : false;
$supporting_docs=isset( $dbrow->form_data->supporting_docs) ?  $dbrow->form_data->supporting_docs : false;

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

    $(document).on("click", ".frmbtn", function(){ 
            return false;
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            let url='<?=base_url('spservices/offline/acknowledgement/form_submit/'.$obj_id)?>';
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced? Once you procced it won't be revert";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
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
                    if((clickedBtn === 'SAVE')) {
                       location.href=url;
                    } 
                }
            });
        });  
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for <?=$service_name?><br>
                         
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
                    <br/>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Application Ref No :</label> 
                        </div>
                        <div class="col-sm-3"> <?=$rtps_trans_id?>
                        </div>
                        <div class="col-sm-7">
                        </div>
                        
                    </div>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details  </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Service Name : </td>
                                <td style="font-weight:bold"><?=$service_name?></td>
                                <td style="width:4%">&nbsp;</td>
                                <!-- <td>Date of Application : </td>
                                <td style="font-weight:bold"><?=$date_of_application?></td> -->
                            </tr>
                            <tr>
                                <td>Applicant Name : </td>
                                <td style="font-weight:bold"><?=$applicant_name?></td>
                                <td>&nbsp;</td>
                                <td>Gender : </td>
                                <td style="font-weight:bold"><?=$gender?></td>
                            </tr>
                            <tr>
                                <td>Age : </td>
                                <td style="font-weight:bold"><?=$age?></td>
                                <td>&nbsp;</td>
                                <td>Mobile Number : </td>
                                <td style="font-weight:bold"><?=$mobile?></td>
                            </tr>
                            <!-- <tr>
                                <td>Stipulated Timeline for service delivery(days) : </td>
                                <td style="font-weight:bold"><?=$timeline?></td>
                                <td>&nbsp;</td>
                                <td>User Charges : </td>
                                <td style="font-weight:bold"><?=$amount?></td>
                            </tr> -->
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5"><?=$this->lang->line('pa_address')?><span style="font-size:12px; color: #f31d12"></span></legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td> <?=$this->lang->line('pa_house_no')?> : </td>
                                <td style="font-weight:bold"><?=$pa_house_no?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td><?=$this->lang->line('pa_street')?> : </td>
                                <td style="font-weight:bold"><?=$pa_street?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('pa_village')?> : </td>
                                <td style="font-weight:bold"><?=$pa_village?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('pa_post_office')?> : </td>
                                <td style="font-weight:bold"><?=$pa_post_office?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('pa_pin_code')?> : </td>
                                <td style="font-weight:bold"><?=$pa_pin_code?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('pa_state')?> : </td>
                                <td style="font-weight:bold"><?=$pa_state?></td>
                            </tr>
                            <tr>
                                <td>District : </td>
                                <td style="font-weight:bold"><?=$pa_district_name?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('pa_circle')?> : </td>
                                <td style="font-weight:bold"><?=$pa_circle?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('pa_police_station')?> : </td>
                                <td style="font-weight:bold"><?=$pa_police_station?></td>
                                <td>&nbsp;</td>
                                <td> </td>
                                <td</td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5"><?=$this->lang->line('ca_address')?><span style="font-size:12px; color: #f31d12"></span></legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td> <?=$this->lang->line('ca_house_no')?> : </td>
                                <td style="font-weight:bold"><?=$ca_house_no?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td><?=$this->lang->line('ca_street')?> : </td>
                                <td style="font-weight:bold"><?=$ca_street?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('ca_village')?> : </td>
                                <td style="font-weight:bold"><?=$ca_village?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('ca_post_office')?> : </td>
                                <td style="font-weight:bold"><?=$ca_post_office?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('ca_pin_code')?> : </td>
                                <td style="font-weight:bold"><?=$ca_pin_code?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('ca_state')?> : </td>
                                <td style="font-weight:bold"><?=$ca_state?></td>
                            </tr>
                            <tr>
                                <td>District : </td>
                                <td style="font-weight:bold"><?=$ca_district_name?></td>
                                <td>&nbsp;</td>
                                <td><?=$this->lang->line('ca_circle')?> : </td>
                                <td style="font-weight:bold"><?=$ca_circle?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line('ca_police_station')?> : </td>
                                <td style="font-weight:bold"><?=$ca_police_station?></td>
                                <td>&nbsp;</td>
                                <td> </td>
                                <td</td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5">Supporting Documents<span style="font-size:12px; color: #f31d12"></span></legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width: 179px;"> Application Form : </td>
                                <td style="font-weight:bold">
                                <?php if($application_form_path){?>
                                        <a target="_blank" href="<?=base_url($application_form_path)?>" class="btn btn-sm btn-primary"> View & Download</a>
                                <?php }else{
                                    echo "Not uploaded";
                                }?>
                                </td>
                                
                            </tr>
                            <?php if($supporting_docs){
                                foreach($supporting_docs as $doc){ ?>
                                    <tr>
                                        <td style="width: 179px;"><?= ucfirst($doc->doc_type)?> : </td>
                                        <td style="font-weight:bold">
                                            <?php if(!empty($doc->file_name)){?>
                                                 <a  target="_blank" href="<?=base_url($doc->file_name)?>" class="btn btn-sm btn-primary"> View & Download</a>
                                            <?php }else{
                                                echo "Not uploaded";
                                            }?>
                                      </td>
                                        
                                    </tr>
                                <?php }
                            }?>
                            
                            
                        </tbody>
                    </table>
                </fieldset>


            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <a href="<?=base_url('spservices/offline/acknowledgement/form/'.$obj_id)?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <a class="btn btn-success" id="" href="<?= base_url('spservices/offline/payment/verify/'.$obj_id) ?>">
                        <i class="fa fa-save"></i> Make Payment
                </a>
                <!-- <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                </button> -->
                
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>