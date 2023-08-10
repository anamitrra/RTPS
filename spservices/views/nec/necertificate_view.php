<?php
$currentYear = date('Y');
$apiServer = $this->config->item('url');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;
        
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->applicant_gender;
    $father_name = $dbrow->father_name;
    $applicant_address = $dbrow->applicant_address;
    $mobile = $dbrow->mobile;
    $email = $dbrow->email;
        
    $office_district = $dbrow->office_district;
    $district_name = $dbrow->district_name??'';  
    
    $sro_code = $dbrow->sro_code;
    $office_name = $dbrow->office_name;

    $circle = $dbrow->circle;
    $circle_name = $dbrow->circle_name??'';
    $mouza = $dbrow->mouza??'';
    $mouza_name = $dbrow->mouza_name??'';
    $village = $dbrow->village;
    $village_name = $dbrow->village_name??'';
    $plots = $dbrow->plots;  
    
    $patta_nos = array();
    $dag_nos = array();
    $land_areas= array();
    $patta_types = array();
    
    if(count($plots)) {
        foreach($plots as $plot) {
            //echo "OBJ : ".$plot->patta_no."<br>";
            array_push($patta_nos, $plot->patta_no);
            array_push($dag_nos, $plot->dag_no);
            array_push($land_areas, $plot->land_area);
            array_push($patta_types, $plot->patta_type);
        }//End of foreach()
    }//End of if
    //pre($patta_nos);
    
    $searched_from = $dbrow->searched_from;
    $searched_to = $dbrow->searched_to;
    $land_doc_ref_no =  $dbrow->land_doc_ref_no;
    $land_doc_reg_year =  $dbrow->land_doc_reg_year;    
    $delivery_mode = $dbrow->delivery_mode;
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
        
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $applicant_address = set_value("applicant_address");
    $mobile = $this->session->mobile;//set_value("mobile");
    $email = set_value("email");
        
    $office_district = set_value("office_district");  
    $district_name = set_value("district_name");
    $sro_code = set_value("sro_code");
    $office_name = set_value("office_name");

    $circle = set_value("circle");
    $circle_name = set_value("circle_name");
    $mouza = set_value("mouza");
    $mouza_name = set_value('mouza_name');
    $village = set_value("village");
    $village_name = set_value("village_name");
    $patta_nos = set_value("patta_nos");
    $dag_nos = set_value("dag_nos");
    $land_areas= set_value("land_areas");
    $patta_types = set_value("patta_types");
    
    $searched_from = set_value("searched_from");
    $searched_to = set_value("searched_to");
    $land_doc_ref_no =  set_value("land_doc_ref_no");
    $land_doc_reg_year =  set_value("land_doc_reg_year");    
    $delivery_mode = set_value("delivery_mode");
}//End of if else
//die($title." : ".$obj_id);
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

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    $(document).ready(function () {  
        
        var getSros = function(parent_org_unit_code) {
            $.getJSON("<?=base_url('spservices/necertificate/getlocation/')?>"+parent_org_unit_code, function (data) {
                let selectOption = '';
                $('#sro_code').empty().append('<option value="">Select a location</option>');
                $.each(data, function (key, value) {
                    selectOption += '<option value="'+value.org_unit_code+'">'+value.org_unit_name+'</option>';
                });
                $('#sro_code').append(selectOption);
            });
        };//End of getSros()
        
        var getCircles = function(sroCode) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/necapi/get_circles')?>",
                data: {"sro_code": sroCode},
                beforeSend: function () {
                    $("#circle_div").html('<select name="circle" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#circle_div").html(res);
                }
            });
        };//End of getCircles()
        
        var getMouzas = function(sroCode, vlCode) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/necapi/get_mouzas')?>",
                data: {"sro_code": sroCode, "vlcode": vlCode},
                beforeSend: function () {
                    $("#mouza_div").html('<select name="mouza" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#mouza_div").html(res);
                }
            });
        };//End of getMouzas()
                
        var getVillages = function(sroCode, vlCode) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/necapi/get_villages')?>",
                data: {"sro_code": sroCode, "vlcode": vlCode},
                beforeSend: function () {
                    $("#village_div").html('<select name="village" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#village_div").html(res);
                }
            });
        };//End of getVillages()
                
        $(document).on("change", "#office_district", function(){               
            let selectedVal = $(this).val();
            var distName = $(this).find("option:selected").text(); 
            var distNameArr = distName.split(' DISTRICT - ');
            var district_name = distNameArr[1].slice(0,-1); //alert(district_name);
            $("#district_name").val(district_name);
            if(selectedVal.length) {
                getSros(selectedVal);
            }
        });
        
        $(document).on("change", "#sro_code", function(){               
            let sroCode = $(this).val();
            $("#office_name").val($(this).find("option:selected").text());
            getCircles(sroCode);
        });
        
        $(document).on("change", "#circle", function(){     
            let sroCode = $("#sro_code").val();
            let vlCode = $(this).val();
            $("#circle_name").val($(this).find("option:selected").text());
            getMouzas(sroCode, vlCode);
        });
        
        $(document).on("change", "#mouza", function(){     
            let sroCode = $("#sro_code").val();
            let vlCode = $(this).val();
            $("#mouza_name").val($(this).find("option:selected").text());
            getVillages(sroCode, vlCode);
        });
        
        $(document).on("change", "#village", function(){
            $("#village_name").val($(this).find("option:selected").text());
        });
        
        $(document).on("click", "#addlatblrow", function(){
            let totRows = $('#financialstatustbl tr').length;
            var trow = `<tr>
                            <td><input name="patta_nos[]" class="form-control" type="text" /></td>
                            <td><input name="dag_nos[]" class="form-control" type="text" /></td>
                            <td><input name="land_areas[]" class="form-control" type="text" /></td>
                            <td>
                                <select name="patta_types[]" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Khiraj Myadi">Khiraj Myadi</option>
                                    <option value="Nisfi Khiraj">Nisfi Khiraj</option>
                                </select>
                            </td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#financialstatustbl tr:last').after(trow);
            }
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });
        
        var checkSearchYear = function() {
            var searched_from = parseInt($("#searched_from").val());
            var searched_to = parseInt($("#searched_to").val());
            var yrDiff = searched_to-searched_from;
            if(yrDiff < 0) {
                alert("Search from year must be grater than search to");
                $("#searched_from").val('');
                $("#searched_to").val('');
            }
        };        
        $(document).on("change", ".searched-year", function(){ 
            checkSearchYear();
        });
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Once you submitted, you won't able to revert this";
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
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });        
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/necertificate/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />            
            <input id="district_name" name="district_name" value="<?=$district_name?>" type="hidden" />
            <input id="office_name" name="office_name" value="<?=$office_name?>" type="hidden" />
            <input id="circle_name" name="circle_name" value="<?=$circle_name?>" type="hidden" />
            <input id="mouza_name" name="mouza_name" value="<?=$mouza_name?>" type="hidden" />
            <input id="village_name" name="village_name" value="<?=$village_name?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                       <?=$service_name?> 
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
                        <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application :</strong>
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                            <li>Up-to-date Original Land Documents (Patta) <span style="color:red">[Mandatory]</span></li>
                            <li>Up-to date Khajna Receipt<span style="color:red">[Mandatory]</span></li>
                            <li>Soft copy of the applicant form if applied through PFC or CSC <span style="color:red">[Mandatory]</span></li>
                        </ol>
                                                
                        <strong style="font-size:16px;  margin-top: 10px">Charge Details :</strong>                        
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                            <li>Rs. 20/- per year search fee.</li>
                            <li>RTPS fee of rupees 20/- per application</li>
                            <li>Convenience fee- Rs. 10/-</li>
                            <li>Service charge (through PFC/CSC) - Rs. 30</li>
                            <li>Printing charge (in case of any printing from PFC/CSC) - Rs. 10 per page</li>
                            <li>Scanning charge (in case documents are scanned in PFC/CSC) - Rs. 5 per page</li>
                        </ol>  
                        <strong style="font-size:16px;">
                            Stipulated time limit : Within 15 days
                        </strong>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Applicant/আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>                            
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
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
                                <label>Fathers Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address of the applicant/আবেদনকাৰীৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_address" id="father_name" value="<?=$applicant_address?>" maxlength="255" />
                                <?= form_error("applicant_address") ?>
                            </div>
                        </div>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" <?=(strlen($mobile)==10)?'readonly':''?>  />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Office for application submission/আবেদন জমা কৰিবলগীয়া কাৰ্য্যালয় </legend>
                        <div class="row form-group">                            
                            <div class="col-md-6">
                                <label>Select District/জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="office_district" id="office_district" class="form-control">
                                <option value="">Select </option>
                                <?php if($sro_dist_list){
                                    foreach($sro_dist_list as $item){ ?>
                                        <option value="<?=$item->parent_org_unit_code?>" <?= ($office_district == $item->parent_org_unit_code) ? "selected":"" ?>><?=$item->org_unit_name_2?></option>
                                    <?php }
                                } ?>                                    
                                </select>
                                <?= form_error("office_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select Office/কাৰ্য্যালয় নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="sro_code" id="sro_code" class="form-control">
                                    <option value="<?=$sro_code?>"><?=(strlen($sro_code))?$office_name:'Select'?></option>
                                </select>
                                <?= form_error("sro_code") ?>
                            </div>
                            
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Particulars of land/মাটি'ৰ বিৱৰণ</legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Circle(p) / ৰাজহ চক্ৰ<span class="text-danger">*</span> </label>
                                <span id="circle_div">
                                    <select name="circle" id="circle" class="form-control">
                                        <option value="<?=$circle?>"><?=strlen($circle)?$circle_name:'Select'?></option>
                                    </select>
                                </span>                                    
                                <?= form_error("circle") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Mouza/মৌজা<span class="text-danger">*</span> </label>
                                <span id="mouza_div">
                                    <select name="mouza" id="mouza" class="form-control">
                                        <option value="<?=$mouza?>"><?=strlen($mouza)?$mouza_name:'Select'?></option>
                                    </select>
                                </span>                                    
                                <?= form_error("mouza") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Village/ গাওঁ<span class="text-danger">*</span> </label>
                                <span id="village_div">
                                    <select name="village" id="village" class="form-control">
                                        <option value="<?=$village?>"><?=strlen($village)?$village_name:'Select'?></option>
                                    </select>
                                </span>                                    
                                <?= form_error("village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="financialstatustbl">
                                    <thead>
                                        <tr>
                                            <th>Patta Number/পট্টা নং(Please specify Old/New)</th>
                                            <th>Daag Number/দাগ নং</th>
                                            <th>Land Area (in Bigha, Katha, Lessa)/মাটি'ৰ কালি( বিঘা, কঠা, লেচা)</th>
                                            <th>Patta Type/পট্টা প্ৰকাৰ</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $landAreas = (isset($patta_nos) && is_array($patta_nos)) ? count($patta_nos) : 0;
                                        if ($landAreas > 0) {
                                            for ($i = 0; $i < $landAreas; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addlatblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="patta_nos[]" value="<?= $patta_nos[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="dag_nos[]" value="<?= $dag_nos[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="land_areas[]" value="<?= $land_areas[$i] ?>" class="form-control" type="text" /></td>
                                                    <td>
                                                        <select name="patta_types[]" class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="Khiraj Myadi" <?=($patta_types[$i]==='Khiraj Myadi')?'selected':''?>>Khiraj Myadi</option>
                                                            <option value="Nisfi Khiraj" <?=($patta_types[$i]==='Nisfi Khiraj')?'selected':''?>>Nisfi Khiraj</option>
                                                        </select>
                                                    </td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td><input name="patta_nos[]" class="form-control" type="text" /></td>
                                                <td><input name="dag_nos[]" class="form-control" type="text" /></td>
                                                <td><input name="land_areas[]" class="form-control" type="text" /></td>
                                                <td>
                                                    <select name="patta_types[]" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Khiraj Myadi">Khiraj Myadi</option>
                                                        <option value="Nisfi Khiraj">Nisfi Khiraj</option>
                                                    </select>
                                                </td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="addlatblrow" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php }//End of if else  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other Details / অন্যান্য তথ্য  </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Records to be searched from/কেতিয়াৰ পৰা তথ্য লাগে <span class="text-danger">*</span> </label>
                                <select name="searched_from" id="searched_from" class="form-control searched-year">
                                    <option value="">Select</option>
                                    <?php for($yr=1993; $yr <= $currentYear; $yr++) {
                                        $selected = ($yr==$searched_from)?'selected':'';
                                        echo '<option value="'.$yr.'" '.$selected.'>'.$yr.'</option>';
                                    } ?>
                                </select>
                                <?= form_error("searched_from") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Records to be searched to/কেতিয়ালৈ তথ্য লাগে<span class="text-danger">*</span> </label>
                                <select name="searched_to" id="searched_to" class="form-control searched-year">
                                    <option value="">Select</option>
                                    <?php for($yr=1993; $yr <= $currentYear; $yr++) {
                                        $selected = ($yr==$searched_to)?'selected':'';
                                        echo '<option value="'.$yr.'" '.$selected.'>'.$yr.'</option>';
                                    } ?>
                                </select>
                                <?= form_error("searched_to") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Reference no of the land document to be uploaded/মাটি'ৰ তথ্যৰ নাম</label>
                                <input name="land_doc_ref_no" value="<?=$land_doc_ref_no?>" class="form-control" type="text" />
                                <?= form_error("land_doc_ref_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Year on which the document is registered/তথ্য নিবন্ধিত বছৰ</label>
                                <input name="land_doc_reg_year" value="<?=$land_doc_reg_year?>" class="form-control" type="text" maxlength="4" />
                                <?= form_error("land_doc_reg_year") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <!--<fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Mode of service delivery/সেৱা প্ৰদানৰ প্ৰকাৰ</legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Select desired mode/প্ৰকাৰ বাছনি কৰক <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input delivery_mode" type="radio" name="delivery_mode" id="dcsYes" value="delivery_general" <?=($delivery_mode === 'delivery_general')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">General (Delivery within 15 days)/সাধাৰণ ( ১৫ দিনৰ ভিতৰত)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input delivery_mode" type="radio" name="delivery_mode" id="dcsNo" value="delivery_urgent" <?=($delivery_mode === 'delivery_urgent')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">Urgent (Delivery within 3 days)/জৰুৰী ( ৩ দিনৰ ভিতৰত )</label>
                                </div>
                                <?=form_error("delivery_mode")?>
                            </div>
                        </div>
                    </fieldset>-->
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>