<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $dob = $dbrow->form_data->dob;
    $pan_no = $dbrow->form_data->pan_no;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $spouse_name = $dbrow->form_data->spouse_name;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $district_id = $dbrow->form_data->district_id;
    $sub_division = $dbrow->form_data->sub_division;
    $sub_division_id = $dbrow->form_data->sub_division_id;
    $revenue_circle = $dbrow->form_data->revenue_circle;
    $revenue_circle_id = $dbrow->form_data->revenue_circle_id;
    $mouza = $dbrow->form_data->mouza;
    $village_town = $dbrow->form_data->village_town;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;
    $police_station = $dbrow->form_data->police_station;
    $house_no = $dbrow->form_data->house_no;
    $landline_number = $dbrow->form_data->landline_number;
    $name_of_deceased = $dbrow->form_data->name_of_deceased;
    $deceased_gender = $dbrow->form_data->deceased_gender;
    $deceased_dob = $dbrow->form_data->deceased_dob;
    $deceased_dod = $dbrow->form_data->deceased_dod;
    $reason_of_death = $dbrow->form_data->reason_of_death;
    $place_of_death = $dbrow->form_data->place_of_death;
    $other_place_of_death = $dbrow->form_data->other_place_of_death;
    $guardian_name_of_deceased = $dbrow->form_data->guardian_name_of_deceased;
    $father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
    $mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;
    $spouse_name_of_deceased = $dbrow->form_data->spouse_name_of_deceased;
    $relationship_with_deceased = $dbrow->form_data->relationship_with_deceased;
    $other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "";
    $deceased_district = $dbrow->form_data->deceased_district;
    $deceased_district_id = $dbrow->form_data->deceased_district_id;
    $deceased_sub_division = $dbrow->form_data->deceased_sub_division;
    $deceased_sub_division_id = $dbrow->form_data->deceased_sub_division_id;
    $deceased_revenue_circle = $dbrow->form_data->deceased_revenue_circle;
    $deceased_revenue_circle_id = $dbrow->form_data->deceased_revenue_circle_id;
    $deceased_mouza = $dbrow->form_data->deceased_mouza;
    $deceased_village = $dbrow->form_data->deceased_village;
    $deceased_town = $dbrow->form_data->deceased_town;
    $deceased_village = $dbrow->form_data->deceased_village;
    $deceased_town = $dbrow->form_data->deceased_town;
    $deceased_post_office = $dbrow->form_data->deceased_post_office;
    $deceased_pin_code = $dbrow->form_data->deceased_pin_code;
    $deceased_police_station = $dbrow->form_data->deceased_police_station;
    $deceased_house_no = $dbrow->form_data->deceased_house_no;
    $family_details = $dbrow->form_data->family_details; 

    $name_of_kins = array();
    $relations = array();
    $age_y_on_the_date_of_applications= array();
    $age_m_on_the_date_of_applications = array();
    $kin_alive_deads = array();
    
    if(count($family_details)) {
        foreach($family_details as $family_detail) {
            //echo "OBJ : ".$plot->patta_no."<br>";
            array_push($name_of_kins, $family_detail->name_of_kin);
            array_push($relations, $family_detail->relation);
            array_push($age_y_on_the_date_of_applications, $family_detail->age_y_on_the_date_of_application);
            array_push($age_m_on_the_date_of_applications, $family_detail->age_m_on_the_date_of_application);
            array_push($kin_alive_deads, $family_detail->kin_alive_dead);
        }//End of foreach()
    }//End of if

    $affidavit_type = isset($dbrow->form_data->affidavit_type)? $dbrow->form_data->affidavit_type: ""; 
    $affidavit = isset($dbrow->form_data->affidavit)? $dbrow->form_data->affidavit: ""; 
    $others_type = isset($dbrow->form_data->others_type)? $dbrow->form_data->others_type: ""; 
    $others = isset($dbrow->form_data->others)? $dbrow->form_data->others: ""; 
    $death_proof_type = isset($dbrow->form_data->death_proof_type)? $dbrow->form_data->death_proof_type: ""; 
    $death_proof = isset($dbrow->form_data->death_proof)? $dbrow->form_data->death_proof: "";
    $doc_for_relationship_type = isset($dbrow->form_data->doc_for_relationship_type)? $dbrow->form_data->doc_for_relationship_type: ""; 
    $doc_for_relationship = isset($dbrow->form_data->doc_for_relationship)? $dbrow->form_data->doc_for_relationship: "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $dob = set_value("dob");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $spouse_name = set_value("spouse_name");
    $state = set_value("state");
    $district = "";
    $district_id = set_value("district_id");
    $sub_division = "";
    $sub_division_id = set_value("sub_division_id");
    $revenue_circle = "";
    $revenue_circle_id = set_value("revenue_circle_id");
    $mouza = set_value("mouza");
    $post_office = set_value("post_office");
    $village_town = set_value("village_town");
    $pin_code = set_value("pin_code");
    $police_station = set_value("police_station");
    $house_no = set_value("house_no");
    $landline_number = set_value("landline_number");
    $name_of_deceased = set_value("name_of_deceased");
    $deceased_gender = set_value("deceased_gender");
    $deceased_dob = set_value("deceased_dob");
    $deceased_dod = set_value("deceased_dod");
    $reason_of_death = set_value("reason_of_death");
    $place_of_death = set_value("place_of_death");
    $other_place_of_death = set_value("other_place_of_death");
    $guardian_name_of_deceased = set_value("guardian_name_of_deceased");
    $father_name_of_deceased = set_value("father_name_of_deceased");
    $mother_name_of_deceased = set_value("mother_name_of_deceased");
    $spouse_name_of_deceased = set_value("spouse_name_of_deceased");
    $relationship_with_deceased = set_value("relationship_with_deceased");
    $other_relation = set_value("other_relation");
    $deceased_district = set_value("deceased_district");
    $deceased_district_id = set_value("deceased_district_id");
    $deceased_sub_division = "";
    $deceased_sub_division_id = set_value("deceased_sub_division_id");
    $deceased_revenue_circle = "";
    $deceased_revenue_circle_id = set_value("deceased_revenue_circle_id");
    $deceased_mouza = set_value("deceased_mouza");
    $deceased_post_office = set_value("deceased_post_office");
    $deceased_village = set_value("deceased_village");
    $deceased_town = set_value("deceased_town");
    $deceased_pin_code = set_value("deceased_pin_code");
    $deceased_police_station = set_value("deceased_police_station");
    $deceased_house_no = set_value("deceased_house_no");
    $name_of_kins = set_value("name_of_kins");
    $relations = set_value("relations");
    $age_y_on_the_date_of_applications = set_value("age_y_on_the_date_of_applications");
    $age_m_on_the_date_of_applications = set_value("age_m_on_the_date_of_applications");
    $kin_alive_deads = set_value("kin_alive_deads");

    $affidavit_type = "";
    $affidavit = "";
    $others_type = "";
    $others = "";
    $death_proof_type = ""; 
    $death_proof = "";
    $doc_for_relationship_type = ""; 
    $doc_for_relationship = "";
    $soft_copy_type = ""; 
    $soft_copy = "";
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

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">   
    var index = 0;
    function checkDeadAlive(id, indx){
        var ids = id+indx
        if ($("#"+ids+"").val() == "Expired") {
            $("#age_y_on_the_date_of_applications"+indx+"").val("0");
            $("#age_m_on_the_date_of_applications"+indx+"").val("0");
        }
    }

    function resetDeadAlive(indx){
        $('#kin_alive_deads'+indx).prop('selectedIndex',0);
    }
    $(document).ready(function () {

        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
     
        // $.getJSON("<?=$apiServer?>district_list.php", function (data) {
        //     let selectOption = '';
        //     $.each(data.records, function (key, value) {
        //         selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
        //     });
        //     $('.dists').append(selectOption);
        // });        
       
        $.getJSON("<?=$apiServer."district_list.php"?>", function (data) {
            let selectOption = '';
            $('.district').empty().append('<option value="">Select District</option>');
            let selectedDistrict = "<?php print $district; ?>"
            $.each(data.ListOfDistricts, function (key, value) {
                if(selectedDistrict == value.DistrictName)
                    selectOption += '<option value="'+value.DistrictName +'/' + value.DistrictId + '" selected>'+value.DistrictName+'</option>';
                else
                    selectOption += '<option value="'+value.DistrictName +'/' + value.DistrictId + '">'+value.DistrictName+'</option>';
            });
            $('.district').append(selectOption);
        });
                
        $(document).on("change", "#applicant_district", function(){  
            $('#applicant_revenue_circle').empty().append('<option value="">Please select</option>')             
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"district_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#applicant_sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'/' + value.subdiv_id + '">'+value.subdiv_name+'</option>';
                    });
                    $('#applicant_sub_division').append(selectOption);
                });
            }
        });

        $(document).on("change", "#deceased_district", function(){    
            $('#deceased_revenue_circle').empty().append('<option value="">Please select</option>')             
                       
            // let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"district_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#deceased_sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'/' + value.subdiv_id + '">'+value.subdiv_name+'</option>';
                    });
                    $('#deceased_sub_division').append(selectOption);
                });
            }
        });

        $(document).on("change", "#applicant_sub_division", function(){   
            $('#applicant_revenue_circle').empty().append('<option value="">Please select</option>')  
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];
            json_body = '{"subdiv_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#applicant_revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'/' + value.circle_id + '">'+value.circle_name+'</option>';
                    });
                    $('#applicant_revenue_circle').append(selectOption);
                });
            }
        });

        $(document).on("change", "#deceased_sub_division", function(){     
            $('#deceased_revenue_circle').empty().append('<option value="">Please select</option>')             
                        
            // let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];
            json_body = '{"subdiv_name":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#deceased_revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'/' + value.circle_id + '">'+value.circle_name+'</option>';
                    });
                    $('#deceased_revenue_circle').append(selectOption);
                });
            }
        });

        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
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
        
        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });

        var getAge = function(db) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/nextofkin/registration/get_age')?>",
                data: {"dob":db},
                beforeSend:function(){
                    $("#age").val("Calculating...");
                },
                success:function(res){
                    $("#age").val(res);
                }
            });
        };
        
        var date_of_birth = '<?=$dob?>';
        if(date_of_birth.length == 10) {
            getAge(date_of_birth);
        }//End of if
        
        $(document).on("change", "#dob", function(){             
            var dd = $(this).val(); //alert(dd);
            var dateAr = dd.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dob);
        });

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        $('#aadhar_no').keyup(function () {    
            if($("#aadhar_no").val().length > 12) {
                $("#aadhar_no").val("");
                alert("Please! Enter upto only 12 digit"); 
            }                        
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

        $(document).on("click", "#addlatblrow", function(){
            let totRows = $('#familydetailstatustbl tr').length;
            index++;
            var trow = `<tr>
                            <td><input name="name_of_kins[]" class="form-control" type="text" /></td>
                            <td><input name="relations[]" class="form-control" type="text"/></td>
                            <td><input name="age_y_on_the_date_of_applications[]" class="form-control" type="text" id="age_y_on_the_date_of_applications`+index+`" onkeypress="resetDeadAlive(`+index+`);"/></td>
                            <td><input name="age_m_on_the_date_of_applications[]" class="form-control" id="age_m_on_the_date_of_applications`+index+`" type="text" onkeypress="resetDeadAlive(`+index+`);"/></td>
                            <td>
                                <select name="kin_alive_deads[]" class="form-control" id="kin_alive_deads`+index+`" onchange="checkDeadAlive('kin_alive_deads', `+index+`)">
                                    <option value="">Select</option>
                                    <option value="Alive">Alive</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 80) {
                $('#familydetailstatustbl tr:last').after(trow);
            }
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("change", "#relationship_with_deceased", function(){ 
            var thisVal = $(this).val();

            if(thisVal == "Other"){
                $('#other_relation_txt').val("");
                $('#other_relation').show();
            }
                
            else{
                $('#other_relation_txt').val("");
                $('#other_relation').hide();
            }
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/next-of-kin') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="affidavit_type" value="<?=$affidavit_type?>" type="hidden" />
            <input name="affidavit" value="<?=$affidavit?>" type="hidden" />
            <input name="death_proof_type" value="<?=$death_proof_type?>" type="hidden" />
            <input name="death_proof" value="<?=$death_proof?>" type="hidden" />
            <input name="doc_for_relationship_type" value="<?=$doc_for_relationship_type?>" type="hidden" />
            <input name="doc_for_relationship" value="<?=$doc_for_relationship?>" type="hidden" />
            <?php if(!empty($others_type)){ ?>
            <input name="others_type" value="<?=$others_type?>" type="hidden" />
            <input name="others" value="<?=$others?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Issuance of Next of Kin Certificate<br>
                        ( নিকট আত্মীয়ৰ প্ৰমানপত্ৰৰ বাবে আবেদন ) 
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
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul>   

                    </fieldset>
                    
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
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Age/ বয়স</label>
                                <input class="form-control" name="age" id="age" value="" type="text" readonly style="font-size:14px" />
                                <?= form_error("age") ?>
                            </div>  
                        </div>

                        <div class="row"> 
                            <!-- <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং<span class="text-danger">*</span> </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" value="<?=$father_name?>" maxlength="100" id="father_name" type="text" />
                                <?= form_error("father_name") ?>
                            </div> 
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Mother's Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="mother_name" value="<?=$mother_name?>" type="text" id="mother_name"/>
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Name of Spouse/ স্বামী/পত্নীৰ নাম </label>
                                <input class="form-control" name="spouse_name" value="<?=$spouse_name?>" maxlength="100" id="spouse_name" type="text" />
                                <?= form_error("spouse_name") ?>
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Address / আবেদনকাৰীৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control">
                                    <option>Please Select</option>
                                    <option value="Assam" selected>Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control district" id="applicant_district">
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control sub_division" id="applicant_sub_division">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($sub_division)){ ?>
                                        <option value="<?php print $sub_division.'/'.$sub_division_id; ?>" selected><?php print $sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenue_circle" class="form-control revenue_circle" id="applicant_revenue_circle">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($revenue_circle)){ ?>
                                        <option value="<?php print $revenue_circle.'/'.$revenue_circle_id; ?>" selected><?php print $revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("revenue_circle") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span></label>
                                <input class="form-control" name="mouza" value="<?=$mouza?>" maxlength="100" id="mouza" type="text" />
                                <?= form_error("mouza") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="village_town" value="<?=$village_town?>" maxlength="100" id="village_town" type="text" />
                                <?= form_error("village_town") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="post_office" value="<?=$post_office?>" maxlength="100" id="post_office" type="text" />
                                <?= form_error("post_office") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx) <span class="text-danger">*</span></label>
                                <input class="form-control number_input pin_code" value="<?=$pin_code?>" maxlength="6" name="pin_code" type="text" />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Police Station/ থানা <span class="text-danger">*</span></label>
                                <input class="form-control" name="police_station" value="<?=$police_station?>" maxlength="100" id="police_station" type="text" />
                                <?= form_error("police_station") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>House No/ ঘৰ নং </label>
                                <input class="form-control" name="house_no" value="<?=$house_no?>" maxlength="100" id="house_no" type="text" />
                                <?= form_error("house_no") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Landline Number/ দূৰভাস (if any) </label>
                                <input class="form-control" name="landline_number" value="<?=$landline_number?>" maxlength="100" id="landline_number" type="text" />
                                <?= form_error("landline_number") ?>
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Deceased Person&apos;s Information / মৃতকৰ তথ্য </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Name of the Deceased/ মৃত ব্যক্তিৰ নাম<span class="text-danger">*</span></label>
                                <input class="form-control" name="name_of_deceased" value="<?=$name_of_deceased?>" maxlength="100" id="name_of_deceased" type="text" />
                                <?= form_error("name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Deceased Gender/ মৃতকৰ লিংগ<span class="text-danger">*</span> </label>
                                <select name="deceased_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($deceased_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($deceased_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("deceased_gender") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্মৰ তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dob" id="deceased_dob" value="<?=$deceased_dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("deceased_dob") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Date of Death/ মৃত্যুৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dod" id="deceased_dod" value="<?=$deceased_dod?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("deceased_dod") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Reason of Death/ মৃত্যুৰ কাৰন <span class="text-danger">*</span></label>
                                <input class="form-control" name="reason_of_death" value="<?=$reason_of_death?>" maxlength="100" id="reason_of_death" type="text" />
                                <?= form_error("reason_of_death") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Place of Death/ মৃত্যুৰ ঠাই<span class="text-danger">*</span> </label>
                                <input class="form-control" name="place_of_death" value="<?=$place_of_death?>" maxlength="100" id="place_of_death" type="text" />
                                <?= form_error("place_of_death") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) </label>
                                <input class="form-control" name="other_place_of_death" value="<?=$other_place_of_death?>" maxlength="100" id="other_place_of_death" type="text" />
                                <?= form_error("other_place_of_death") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Father's/Guardian's Name of the Deceased/ মৃতকৰ পিতৃ/অভিভাৱকৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="guardian_name_of_deceased" value="<?=$guardian_name_of_deceased?>" maxlength="100" id="guardian_name_of_deceased" type="text" />
                                <?= form_error("guardian_name_of_deceased") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Father's Name of the Deceased/ মৃতকৰ পিতৃৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="father_name_of_deceased" value="<?=$father_name_of_deceased?>" maxlength="100" id="father_name_of_deceased" type="text" />
                                <?= form_error("father_name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Mother Name of the Deceased/ মৃতকৰ মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="mother_name_of_deceased" value="<?=$mother_name_of_deceased?>" maxlength="100" id="mother_name_of_deceased" type="text" />
                                <?= form_error("mother_name_of_deceased") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Spouse Name of the Deceased/ মৃতকৰ স্বামী/পত্নীৰ নাম </label>
                                <input class="form-control" name="spouse_name_of_deceased" value="<?=$spouse_name_of_deceased?>" maxlength="100" id="spouse_name_of_deceased" type="text" />
                                <?= form_error("spouse_name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Relation of the Applicant with the Deceased/ মৃতকৰ লগত আবেদনকাৰীৰ সম্পৰ্ক <span class="text-danger">*</span> </label>
                                <select name="relationship_with_deceased" id="relationship_with_deceased" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Husband" <?=($relationship_with_deceased === "Husband")?'selected':''?>>Husband</option>
                                    <option value="Wife" <?=($relationship_with_deceased === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Father" <?=($relationship_with_deceased === "Father")?'selected':''?>>Father</option>
                                    <option value="Mother" <?=($relationship_with_deceased === "Mother")?'selected':''?>>Mother</option>
                                    <option value="Son" <?=($relationship_with_deceased === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($relationship_with_deceased === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Brother" <?=($relationship_with_deceased === "Brother")?'selected':''?>>Brother</option>
                                    <option value="Sister" <?=($relationship_with_deceased === "Sister")?'selected':''?>>Sister</option>
                                    <option value="Other" <?=($relationship_with_deceased === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("relationship_with_deceased") ?>
                            </div>
                        </div>

                        <div class="row" id="other_relation" style="display: <?php ($relationship_with_deceased === "Other")? print 'block': print 'none'; ?>;"> 
                            <div class="col-md-6 form-group">
                                <label>Enter Other Relation (If Any)/ অন্য সম্পৰ্ক প্ৰৱেশ কৰক (যদি আছে)<span class="text-danger">*</span> </label>
                                <input class="form-control" name="other_relation" value="<?=$other_relation?>" maxlength="100" id="other_relation_txt" type="text" required/>
                                <?= form_error("other_relation") ?>
                            </div> 
                            <div class="col-md-6">
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="deceased_district" class="form-control district" id="deceased_district">
                                </select>
                                <?= form_error("deceased_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="deceased_sub_division" class="form-control" id="deceased_sub_division">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($deceased_sub_division)){ ?>
                                        <option value="<?php print $deceased_sub_division.'/'.$deceased_sub_division_id; ?>" selected><?php print $deceased_sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("deceased_sub_division") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="deceased_revenue_circle" class="form-control" id="deceased_revenue_circle">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($deceased_revenue_circle)){ ?>
                                        <option value="<?php print $deceased_revenue_circle.'/'.$deceased_revenue_circle_id; ?>" selected><?php print $deceased_revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("deceased_revenue_circle") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mouza/ মৌজা<span class="text-danger">*</span> </label>
                                <input class="form-control" name="deceased_mouza" value="<?=$deceased_mouza?>" maxlength="100" id="deceased_mouza" type="text" />
                                <?= form_error("deceased_mouza") ?>
                            </div> 
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Village/ গাওঁ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="deceased_village" value="<?=$deceased_village?>" maxlength="100" id="deceased_village" type="text" />
                                <?= form_error("deceased_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Town/চহৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_town" value="<?=$deceased_town?>" maxlength="100" id="deceased_town" type="text" />
                                <?= form_error("deceased_town") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_post_office" value="<?=$deceased_post_office?>" maxlength="100" id="deceased_post_office" type="text" />
                                <?= form_error("deceased_post_office") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx)<span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="deceased_pin_code" value="<?=$deceased_pin_code?>" maxlength="6" type="text" />
                                <?= form_error("deceased_pin_code") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Police Station/ থানা <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_police_station" value="<?=$deceased_police_station?>" maxlength="100" id="deceased_police_station" type="text" />
                                <?= form_error("deceased_police_station") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>House No/ ঘৰ নং </label>
                                <input class="form-control" name="deceased_house_no" value="<?=$deceased_house_no?>" maxlength="100" id="deceased_house_no" type="text" />
                                <?= form_error("deceased_house_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Family Details/পৰিয়ালৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="familydetailstatustbl">
                                    <thead>
                                        <tr>
                                            <th>Name of Kin/ আত্মীয়ৰ নাম<span class="text-danger">*</span></th>
                                            <th>Relation/ সম্পৰ্ক<span class="text-danger">*</span></th>
                                            <th>Age(Y) on the Date of Application/ আবেদনৰ সময়ত বয়স (বছৰ) <span class="text-danger">*</span></th>
                                            <th>Age(M) on the Date of Application/ আবেদনৰ সময়ত বয়স (মাহ)<span class="text-danger">*</span></th>
                                            <th>Kin Alive or Dead/ আত্মীয় জীৱিত নে মৃত<span class="text-danger">*</span></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $name_of_kin_cnt = (isset($name_of_kins) && is_array($name_of_kins)) ? count($name_of_kins) : 0;
                                        if ($name_of_kin_cnt > 0) {
                                            for ($i = 0; $i < $name_of_kin_cnt; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addlatblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="name_of_kins[]" value="<?= $name_of_kins[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="relations[]" value="<?= $relations[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="age_y_on_the_date_of_applications[]" value="<?= $age_y_on_the_date_of_applications[$i] ?>" class="form-control" type="text" id="age_y_on_the_date_of_applications0" onkeypress="resetDeadAlive(0);"/></td>
                                                    <td><input name="age_m_on_the_date_of_applications[]" value="<?= $age_m_on_the_date_of_applications[$i] ?>" class="form-control" type="text" id="age_m_on_the_date_of_applications0" onkeypress="resetDeadAlive(0);"/></td>
                                                    <td>
                                                        <select name="kin_alive_deads[]" class="form-control" id="kin_alive_deads0" onchange="checkDeadAlive('kin_alive_deads', 0);">
                                                            <option value="">Select</option>
                                                            <option value="Alive" <?=($kin_alive_deads[$i]==='Alive')?'selected':''?>>Alive</option>
                                                            <option value="Expired" <?=($kin_alive_deads[$i]==='Expired')?'selected':''?>>Expired</option>
                                                        </select>
                                                    </td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="name_of_kins[]" class="form-control" type="text" /></td>
                                                <td><input name="relations[]" class="form-control" type="text" /></td>
                                                <td><input name="age_y_on_the_date_of_applications[]" class="form-control" type="text" id="age_y_on_the_date_of_applications0" onkeypress="resetDeadAlive(0);"/></td>
                                                <td><input name="age_m_on_the_date_of_applications[]" class="form-control" type="text" id="age_m_on_the_date_of_applications0" onkeypress="resetDeadAlive(0);"/></td>
                                                <td>
                                                    <select name="kin_alive_deads[]" class="form-control" id="kin_alive_deads0" onchange="checkDeadAlive('kin_alive_deads', 0);">
                                                        <option value="">Select</option>
                                                        <option value="Alive">Alive</option>
                                                        <option value="Expired">Expired</option>
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
                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> -->
                     <!-- End of .row --> 
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>