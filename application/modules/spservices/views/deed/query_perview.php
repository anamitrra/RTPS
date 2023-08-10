<?php
$startYear = date('Y') - 10;
$endYear =  date('Y');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->applicant_gender;
  
    $relation = $dbrow->relation;
    $mobile = $dbrow->mobile;
  
    $email = $dbrow->email;
    $applId = $dbrow->applId;
   
    $serial_registration_not_availabe =  isset ($dbrow->serial_registration_not_availabe ) ? $dbrow->serial_registration_not_availabe : false;
    $service_mode = $dbrow->service_mode;
    $deedno = $dbrow->deedno;
    $address = $dbrow->address;
    $year_of_registration = $dbrow->year_of_registration;
    $pa_district = $dbrow->pa_district;
    $sro_code = $dbrow->sro_code;

    $year_from =isset ($dbrow->year_from ) ? $dbrow->year_from : '';
    $year_to = isset ($dbrow->year_to ) ? $dbrow->year_to : '';
    $deed_party_name = isset ($dbrow->deed_party_name ) ? $dbrow->deed_party_name : '';
    $deed_patta_no = isset ($dbrow->deed_patta_no ) ? $dbrow->deed_patta_no : '';
    $deed_dag_no = isset ($dbrow->deed_dag_no ) ? $dbrow->deed_dag_no : '';
    $deed_total_land_area = isset ($dbrow->deed_total_land_area ) ? $dbrow->deed_total_land_area : '';
   
   
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $year_of_registration = set_value("year_of_registration");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $service_mode = set_value("service_mode");
    $deedno = set_value("deedno");
    $address = set_value("address");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    
    $relation = set_value("relation");
    $email = set_value("email");
    $applId = set_value("applId");
    
    $sro_code = set_value("sro_code");
    
    $pa_district = set_value("pa_district");

}//End of if else
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
</style>


<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {           
       
        
        $("#attachment1").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {

        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
             
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registereddeed/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input name="applId" value="<?=$applId?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
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
                        <legend class="h5">Query Remarks </legend>
                       <p><?=$remark?></p>
                    </fieldset>
                    <?php
                    if(isset($dbrow->attachment1)){ ?>
                         <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                            <legend class="h5">Attached Enclosures</legend>
                            <table class="table table-bordered bg-white mt-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>File</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                     <tr>
                                                <td>Attachment1</td>
                                                <td class="text-left">
                                                    <?php if(strlen($dbrow->attachment1)){ ?>
                                                        <a href="<?=base_url($dbrow->attachment1)?>" class="" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } else echo "NA"; //End of if ?>
                                                </td>
                                            
                                            </tr>
                                </tbody>
                            </table>
                        
                        </fieldset>
                    <?php }
                     ?>
                   

                    <!-- <fieldset class="border border-success">
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

                    </fieldset> -->
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Address/ঠিকনা *<span class="text-danger">*</span> </label>
                                <textarea name="address" rows="4" cols="100"><?=$address?></textarea>
                                <?= form_error("address") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relation/সম্পৰ্ক *<span class="text-danger">*</span> </label>
                                <select name="relation" class="form-control">
                                    <option value="">Please Select</option>
                                    <option  <?=($relation === "first-party")?'selected':''?> value="first-party" autocomplete="off">First Party/প্ৰথম পক্ষ</option>
                                    <option <?=($relation === "second-party")?'selected':''?> value="second-party" autocomplete="off">Second Party/দ্বিতীয় পক্ষ</option>
                                    <option <?=($relation === "third-party")?'selected':''?> value="third-party" autocomplete="off">Third Party/তৃতীয় পক্ষ</option>
                                    <option <?=($relation === "other")?'selected':''?>  value="other" autocomplete="off">Other/অন্যান্য</option>
                                    <option <?=($relation === "self")?'selected':''?>  value="self" autocomplete="off">Self/নিজেই</option>
                                   
                                </select>
                                <?= form_error("relation") ?>
                            </div>
                        </div>
                    
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Registered Deed/পঞ্জীয়ন হোৱা দলিলৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Nature of Deed/পঞ্জীয়নৰ প্ৰকাৰ *<span class="text-danger">*</span> </label>
                                <select name="deed_nature" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" autocomplete="off" selected="selected">Conveyance deed/বাহক পঞ্জীয়ন</option>
                                </select>
                                <?= form_error("nature_of_deed") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Serial number of documents/ নথিপত্ৰৰ ক্ৰমিক সংখ্যা </label>
                                <input type="text" class="form-control" name="deedno" value="<?=$deedno?>"/>
                                <?= form_error("deedno") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Year of registration/ পঞ্জীয়নৰ বছৰ </label>
                                <input type="text" class="form-control" name="year_of_registration" value="<?=$year_of_registration?>" />
                                <?= form_error("year_of_registration") ?>
                            </div>
                        </div>
                        
                       
                    </fieldset>
                    <?php if($serial_registration_not_availabe){ ?>
                        <fieldset class="border border-success" id="deed_additionals" style="margin-top:40px">
                        <legend class="h5">Other details related to the deed</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Year From</label>
                                <select name="year_from" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php for ($i = $endYear; $i >= $startYear; $i--) { ?>
                                        <option <?= ($i==$year_from )? "selected":''?>  value="<?=$i?>"><?=$i?></option>
                                    <?php }?>
                                </select>
                                <?= form_error("year_from") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Year To</label>
                                <select name="year_to" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php for ($i = $endYear; $i >= $startYear; $i--) { ?>
                                        <option <?= ($i==$year_to )? "selected":''?> value="<?=$i?>"><?=$i?></option>
                                    <?php }?>
                                </select>
                                <?= form_error("year_to") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Party Name (Any buyer,seller etc) </label>
                                <input type="text" class="form-control" name="deed_party_name" value="<?=$deed_party_name ?>" />
                                <?= form_error("deed_party_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Patta no of the land </label>
                                <input type="text" class="form-control" name="deed_patta_no" value="<?=$deed_patta_no?>" />
                                <?= form_error("deed_patta_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Dag no of the land  </label>
                                <input type="text" class="form-control" name="deed_dag_no" value="<?=$deed_dag_no?>" />
                                <?= form_error("deed_dag_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Total land area </label>
                                <input type="text" class="form-control" name="deed_total_land_area" value="<?=$deed_total_land_area?>" />
                                <?= form_error("deed_total_land_area") ?>
                            </div>
                        </div>
                       
                    </fieldset>
                    <?php }
                    ?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
                        <div class="row mt-3">
                            <div class="col-12">
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
                                            <td>Query Related<span class="text-danger"></span></td>
                                            <td>
                                                <span >Attachment1</span>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="attachment1" name="attachment1" type="file" />
                                                </div>
                                            </td>
                                        </tr>
                                        
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div>  -->
                    <!-- End of .row --> 
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-check"></i> Submit Query
                    </button>
                    <a class="btn btn-danger"  href="<?=base_url('spservices/applications')?>">
                        <i class="fa fa-back"></i> Go Back
                    </a>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>