<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
$apiURL= "https://localhost/castapis/"; //For testing

//$startYear = date('Y') - 10;
//$endYear =  date('Y');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $language = $dbrow->form_data->fillUpLanguage;

    $application_for = $dbrow->form_data->application_for;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $pan_no = isset($dbrow->form_data->pan_no)? $dbrow->form_data->pan_no: "";
    $aadhar_no = isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "";
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "";
    $epic_no = isset($dbrow->form_data->epic_no)? $dbrow->form_data->epic_no: "";;
    $dob = $dbrow->form_data->dob;
    $caste = $dbrow->form_data->caste;
    $subcaste = $dbrow->form_data->subcaste;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    //$husband_name = $dbrow->form_data->husband_name;
    //$religion = $dbrow->form_data->religion;
   
    $address_line_1 = $dbrow->form_data->address_line_1;
    $address_line_2 = $dbrow->form_data->address_line_2;
    $house_no = $dbrow->form_data->house_no;
    $district = $dbrow->form_data->district;
    $sub_division = $dbrow->form_data->sub_division;
    $circle_office = $dbrow->form_data->circle_office;
    $mouza = $dbrow->form_data->mouza;
    $village_town = $dbrow->form_data->village_town;
    $police_station = $dbrow->form_data->police_station;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;
    
    $photo_type = isset($dbrow->form_data->photo_type)? $dbrow->form_data->photo_type: ""; 
    $photo = isset($dbrow->form_data->photo)? $dbrow->form_data->photo: ""; 
    $date_of_birth_type = isset($dbrow->form_data->date_of_birth_type)? $dbrow->form_data->date_of_birth_type: ""; 
    $date_of_birth = isset($dbrow->form_data->date_of_birth)? $dbrow->form_data->date_of_birth: "";
    $proof_of_residence_type = isset($dbrow->form_data->proof_of_residence_type)? $dbrow->form_data->proof_of_residence_type: ""; 
    $proof_of_residence = isset($dbrow->form_data->proof_of_residence)? $dbrow->form_data->proof_of_residence: "";
    $caste_certificate_of_father_type = isset($dbrow->form_data->caste_certificate_of_father_type)? $dbrow->form_data->caste_certificate_of_father_type: ""; 
    $caste_certificate_of_father = isset($dbrow->form_data->caste_certificate_of_father)? $dbrow->form_data->caste_certificate_of_father: "";
    $recomendation_certificate_type = isset($dbrow->form_data->recomendation_certificate_type)? $dbrow->form_data->recomendation_certificate_type: ""; 
    $recomendation_certificate = isset($dbrow->form_data->recomendation_certificate)? $dbrow->form_data->recomendation_certificate: "";
    $others_type = isset($dbrow->form_data->others_type)? $dbrow->form_data->others_type: ""; 
    $others = isset($dbrow->form_data->others)? $dbrow->form_data->others: "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    $language = set_value("language");

    $application_for = set_value("application_for");;
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $epic_no = set_value("epic_no");
    $dob = set_value("dob");
    $caste = set_value("caste");
    $subcaste = set_value("subcaste");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    //$husband_name = set_value("husband_name");
    //$religion = set_value("religion");

    $address_line_1 = set_value("address_line_1");
    $address_line_2 = set_value("address_line_2");
    $house_no = set_value("house_no");
    $district = set_value("district");
    $sub_division = set_value("sub_division");
    $circle_office = set_value("circle_office");
    $mouza = set_value("mouza");
    $village_town = set_value("village_town");
    $police_station = set_value("police_station");
    $post_office = set_value("post_office");
    $pin_code = set_value("pin_code");

    $photo_type = "";
    $photo = "";
    $caste_certificate_of_father_type = "";
    $caste_certificate_of_father = "";
    $recomendation_certificate_type = "";
    $recomendation_certificate = "";
    $proof_of_residence_type = ""; 
    $proof_of_residence = "";
    $others_type = ""; 
    $others = "";
    $date_of_birth_type = ""; 
    $date_of_birth = "";
    $soft_copy_type = ""; 
    $soft_copy = "";
}//End of if else //End of if else
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
    $(document).ready(function() {

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
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

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

        let selectedVal = "<?php print $application_for; ?>"
        $.getJSON("<?= $apiURL ?>caste_list.php", function(data) {
            let selectOption = '';
            $('#app_for').empty().append('<option value="">Application For</option>')
            $.each(data.records, function(key, value) {
                if (selectedVal === value.caste_name) {
                    selectOption += '<option selected value="' + value.caste_name + '">' + value.dis_name + '</option>';
                } else {
                    selectOption += '<option value="' + value.caste_name + '">' + value.dis_name + '</option>';
                }

            });
            $('#app_for').append(selectOption);
        });
        if ((selectedVal == "ST(H)") || (selectedVal == "Plain Tribes in Hills")) {
            $("#district option").each(function (index, val) {
                $(this).show();

                if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                    $(this).show();
                } else $(this).hide();
            });    
        } 

        if ((selectedVal == "ST(P)") || (selectedVal == "Hill Tribes in Plains")) {
            if (selectedVal == "Barmans in Cachar") {
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                        $(this).show();
                    } else $(this).hide();
                });
            } else {
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                        $(this).hide();
                    } else $(this).show();
                }); 
            }
        }

        $(document).on("change", "#app_for", function() {
            $("#district").val('');
            $("#sub_division").val('');
            $("#circle_office").val('');
            $("#district option").each(function (index, val) {
                $(this).show();
            }); 

            let selectedCaste = '<?= $caste ?>';
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.caste_name = selectedVal; //alert(JSON.stringify(myObject));
                $.getJSON("<?= $apiURL ?>community_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('#caste').empty().append('<option value="">Select a Caste</option>')
                    $.each(data.records, function(key, value) {
                        if (selectedCaste === value.cname) {
                            selectOption += '<option value="' + value.cname + '">' + value.cname + '</option>';
                        } else {
                            selectOption += '<option value="' + value.cname + '">' + value.cname + '</option>';
                        }

                    });
                    $('#caste').append(selectOption);
                });
            }

            if ((selectedVal == "ST(H)") || (selectedVal == "Plain Tribes in Hills")) {
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                        $(this).show();
                    } else $(this).hide();
                });    
            } 

            if ((selectedVal == "ST(P)") || (selectedVal == "Hill Tribes in Plains")) {
                if (selectedVal == "Barmans in Cachar") {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            $(this).show();
                        } else $(this).hide();
                    });
                } else {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                            $(this).hide();
                        } else $(this).show();
                    }); 
                }
            }
        });

        if ($("#app_for").val() != "") {

            $("#district").val('');
            $("#sub_division").val('');
            $("#circle_office").val('');
            $("#district option").each(function (index, val) {
                $(this).show();
            }); 

            let selectedCaste = '<?= $caste ?>';
            let selectedVal = $("#app_for").val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.caste_name = selectedVal; //alert(JSON.stringify(myObject));
                $.getJSON("<?= $apiURL ?>community_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('#caste').empty().append('<option value="">Select a Caste</option>')
                    $.each(data.records, function(key, value) {
                        if (selectedCaste === value.cname) {
                            selectOption += '<option value="' + value.cname + '" selected>' + value.cname + '</option>';
                        } else {
                            selectOption += '<option value="' + value.cname + '">' + value.cname + '</option>';
                        }

                    });
                    $('#caste').append(selectOption);
                });
            }

            if ((selectedVal == "ST(H)") || (selectedVal == "Plain Tribes in Hills")) {
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                        $(this).show();
                    } else $(this).hide();
                });    
            } 

            if ((selectedVal == "ST(P)") || (selectedVal == "Hill Tribes in Plains")) {
                if (selectedVal == "Barmans in Cachar") {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            $(this).show();
                        } else $(this).hide();
                    });
                } else {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                            $(this).hide();
                        } else $(this).show();
                    }); 
                }
            }
        }

        //alert($("#app_for").val());

        $(document).on("change", "#caste", function() {
            $("#district").val('');
            $("#sub_division").val('');
            $("#circle_office").val('');
            $('#check_caste').val("");
            let selectedVal = $(this).val();
            if (selectedVal.length) { 
                var myObject = new Object();
                myObject.community_name = selectedVal; //alert(JSON.stringify(myObject));

                $.getJSON("<?= $apiURL ?>subcategory_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('.subcaste').empty().append('<option value="">Select a Sub-Caste</option>')
                    $.each(data.records, function(key, value) {
                        $('#check_caste').val("Yes");

                        if ((selectedVal == "Tea Garden Labourers,Tea Garden Tribes,Ex-Tea Garden Labourers,Ex-Tea Garden Tribes as listed below")) {
                            //console.log(selectedVal);
                            selectOption += '<option value="' + value.sname + '(' + selectedVal + ')">' + value.sname + '</option>';
                        } else if (selectedVal == "Nepali(Chhetri,Gurung,Dami,Gaine,Lama,Lihu/Limbu,Lohar,Magar,Newar,Rai,Sarki i.e.Cobbler,Thapa,Bhujel)") {
                            // alert(selectedVal);
                            //console.log(selectedVal);
                            selectOption += '<option value="' + value.sname + '(Nepali)">' + value.sname + '</option>';
                        }else {
                            selectOption += '<option value="' + value.sname + '">' + value.sname + '</option>';
                        }
                    });

                    $('.subcaste').append(selectOption);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    let selectOption = '';
                    $('.subcaste').empty().append('<option value="">Select a Sub-Caste</option>')
                });
            }

            if ((selectedVal == "Ganak in Districts of Cachar,Karimganj,Hailakandi") || (selectedVal == "Kiran Sheikh community of Barak Valley") || (selectedVal == "Maimal(Muslim Fisherman)")) {

                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                        $(this).show();
                    } else $(this).hide();
                });   
            }else if(selectedVal == "Jolha"){
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Golaghat") || ($(this).val() == "Jorhat") || ($(this).val() == "Sivasagar") || ($(this).val() == "Biswanath") || ($(this).val() == "Tinsukia") || ($(this).val() == "Charaideo") || ($(this).val() == "Dibrugarh") || ($(this).val() == "Lakhimpur") || ($(this).val() == "Sonitpur") || ($(this).val() == "Nagaon")) {
                        $(this).show();
                    } else $(this).hide();
                });
            }else {
                $("#district option").each(function (index, val) {
                    $(this).show();
                });
            }


            if (($("#app_for").val() == "ST(H)") || ($("#app_for").val() == "Plain Tribes in Hills")) {
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                        $(this).show();
                    } else $(this).hide();
                });    
            }

            if (($("#app_for").val() == "ST(P)") || ($("#app_for").val() == "Hill Tribes in Plains")) {
                if (selectedVal == "Barmans in Cachar") {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            $(this).show();
                        } else $(this).hide();
                    });
                } else {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                            $(this).hide();
                        } else $(this).show();
                    }); 
                }
            }
        });

        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '">' + value.DistrictName + '</option>';
            });
            $('#district').append(selectOption);

            if (selectedVal != "") {
                if ((selectedVal == "ST(H)") || (selectedVal == "Plain Tribes in Hills")) {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                            $(this).show();
                        } else $(this).hide();
                    });    
                } 

                if ((selectedVal == "ST(P)") || (selectedVal == "Hill Tribes in Plains")) {
                    if (selectedVal == "Barmans in Cachar") {
                        $("#district option").each(function (index, val) {
                            $(this).show();

                            if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                                $(this).show();
                            } else $(this).hide();
                        });
                    } else {
                        $("#district option").each(function (index, val) {
                            $(this).show();

                            if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                                $(this).hide();
                            } else $(this).show();
                        }); 
                    }
                }
            }

            let casteSelectedVal = "<?php print $caste; ?>"
            if (casteSelectedVal != "") {
                if ((casteSelectedVal == "Ganak in Districts of Cachar,Karimganj,Hailakandi") || (casteSelectedVal == "Kiran Sheikh community of Barak Valley") || (casteSelectedVal == "Maimal(Muslim Fisherman)") || (casteSelectedVal == "Barmans in Cachar")) {
                    
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            $(this).show();
                        } else $(this).hide();
                    });   
                }

                if(casteSelectedVal == "Jolha"){
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Golaghat") || ($(this).val() == "Jorhat") || ($(this).val() == "Sivasagar") || ($(this).val() == "Biswanath") || ($(this).val() == "Tinsukia") || ($(this).val() == "Charaideo") || ($(this).val() == "Dibrugarh") || ($(this).val() == "Lakhimpur") || ($(this).val() == "Sonitpur") || ($(this).val() == "Nagaon")) {
                                
                            $(this).show();
                        } else{
                                
                            $(this).hide();
                        } 
                    });
                }
            }

            let subCasteSelectedVal = "<?php print $subcaste; ?>";
            if (subCasteSelectedVal != "") {
                if (subCasteSelectedVal == "Rudra Paul of Cachar/Karimganj/Hailakandi") {
                    
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            $(this).show();
                        } else $(this).hide();
                    });   
                }
            }
        });

        let casteSelectedVal = "<?php print $caste; ?>";
        if (casteSelectedVal != "") {
            if ((casteSelectedVal == "Ganak in Districts of Cachar,Karimganj,Hailakandi") || (casteSelectedVal == "Kiran Sheikh community of Barak Valley") || (casteSelectedVal == "Maimal(Muslim Fisherman)") || (casteSelectedVal == "Barmans in Cachar")) {
                    
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                            
                        $(this).show();
                    } else{
                            
                        $(this).hide();
                    } 
                });   
            }

            if(casteSelectedVal == "Jolha"){
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Golaghat") || ($(this).val() == "Jorhat") || ($(this).val() == "Sivasagar") || ($(this).val() == "Biswanath") || ($(this).val() == "Tinsukia") || ($(this).val() == "Charaideo") || ($(this).val() == "Dibrugarh") || ($(this).val() == "Lakhimpur") || ($(this).val() == "Sonitpur") || ($(this).val() == "Nagaon")) {
                            
                        $(this).show();
                    } else{
                            
                        $(this).hide();
                    } 
                });
            }
        }

        $(document).on("change", ".subcaste", function() {
            $("#district").val('');
            let selectedVall = $(this).val();
            if (selectedVall == "Rudra Paul of Cachar/Karimganj/Hailakandi") {
                
                $("#district option").each(function (index, val) {
                    $(this).show();

                    if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                        $(this).show();
                    } else $(this).hide();
                });   
            }else {
                if (($("#app_for").val() == "ST(H)") || ($("#app_for").val() == "Plain Tribes in Hills")) {
                    $("#district option").each(function (index, val) {
                        $(this).show();

                        if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                            $(this).show();
                        } else $(this).hide();
                    });    
                }else if (($("#app_for").val() == "ST(P)") || ($("#app_for").val() == "Hill Tribes in Plains")) {
                    if ($("#caste").val() == "Barmans in Cachar") {
                        $("#district option").each(function (index, val) {
                            $(this).show();

                            if (($(this).val() == "Cachar") || ($(this).val() == "Karimganj") || ($(this).val() == "Hailakandi")) {
                                $(this).show();
                            } else $(this).hide();
                        });
                    } else {
                        $("#district option").each(function (index, val) {
                            $(this).show();

                            if (($(this).val() == "Dima Hasao") || ($(this).val() == "Karbi Anglong") || ($(this).val() == "West Karbi Anglong")) {
                                $(this).hide();
                            } else $(this).show();
                        }); 
                    }    
                }else{
                    $("#district option").each(function (index, val) {
                        $(this).show();
                    });
                }
            }
        });

        $(document).on("change", "#district", function() {
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.district_name = selectedVal; //alert(JSON.stringify(myObject));
                $.getJSON("<?= $apiServer ?>sub_division_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('#sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.subdiv_name + '">' + value.subdiv_name + '</option>';
                    });
                    $('#sub_division').append(selectOption);
                });
            }
        });
        let districtSelectedVal = "<?php print $district; ?>";
        if (districtSelectedVal != "") {
            var myObject = new Object();
            myObject.district_name = districtSelectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>sub_division_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('#sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.subdiv_name + '">' + value.subdiv_name + '</option>';
                });
                $('#sub_division').append(selectOption);
            });
        }

        $(document).on("change", "#sub_division", function() {
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal; //alert(JSON.stringify(myObject));
                $.getJSON("<?= $apiServer ?>revenue_circle_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('#circle_office').empty().append('<option value="">Select a Circle Office</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.circle_name + '">' + value.circle_name + '</option>';
                    });
                    $('#circle_office').append(selectOption);
                });
            }
        });

        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced?";
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/castecertificate/registration/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />         
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <input id="check_caste" name="check_caste" value="" type="hidden" />
            <input name="photo_type" value="<?=$photo_type?>" type="hidden" />
            <input name="photo" value="<?=$photo?>" type="hidden" />
            <input name="caste_certificate_of_father_type" value="<?=$caste_certificate_of_father_type?>" type="hidden" />
            <input name="caste_certificate_of_father" value="<?=$caste_certificate_of_father?>" type="hidden" />
            <input name="recomendation_certificate_type" value="<?=$recomendation_certificate_type?>" type="hidden" />
            <input name="recomendation_certificate" value="<?=$recomendation_certificate?>" type="hidden" />
            <input name="proof_of_residence_type" value="<?=$proof_of_residence_type?>" type="hidden" />
            <input name="proof_of_residence" value="<?=$proof_of_residence?>" type="hidden" />
            <?php if(!empty($others_type)){ ?>
            <input name="others_type" value="<?=$others_type?>" type="hidden" />
            <input name="others" value="<?=$others?>" type="hidden" />
            <?php } ?>
            <input name="date_of_birth_type" value="<?=$date_of_birth_type?>" type="hidden" />
            <input name="date_of_birth" value="<?=$date_of_birth?>" type="hidden" />
            <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                        Issuance of Caste Certificate<br>
                        ( জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন )
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

                            <ol style="margin-left: 24px; margin-top: 20px">
                                <li>The certificate will be delivered within 30 Days of application.</li>
                                <li>প্ৰমাণ পত্ৰ ৩০ দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                            </ol>


                            <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                            </ul>

                            <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                                <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                                <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                                <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                                <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                                <li>3. Applicant photo should be in JPEG format.</li>
                                <li>৩. আবেদনকাৰীৰ ফটো  jpeg formatত হ’ব লাগিব </li>
                            </ul>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Language of the certificate /প্ৰমাণপত্ৰৰ ভাষা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Language/ ভাষা <span class="text-danger">*</span> </label>
                                <select name="language" class="form-control">
                                    <option value="English" selected <?=($language === "English")?'selected':''?>>English/ ইংৰাজী</option>
                                    <option value="Assamese" <?=($language === "Assamese")?'selected':''?>>Assamese/ অসমীয়া</option>
                                    <option value="Bodo" <?=($language === "Bodo")?'selected':''?>>Bodo/ বডো</option>
                                    <option value="Bengali" <?=($language === "Bengali")?'selected':''?>>Bengali/ বাংলা</option>
                                </select>
                                <?= form_error("language") ?>
                            </div>
                            <div class="col-md-6">
                            </div>   
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Application For/ আবেদনৰ বাবে <span class="text-danger">*</span> </label>
                                <select name="application_for" class="form-control" id="app_for">
                                    <option value="">Please Select</option>
                                    <option value="OBC" <?=($application_for === "OBC")?'selected':''?>>OBC/MOBC</option>
                                    <option value="ST(P)" <?=($application_for === "ST(P)")?'selected':''?>>ST(P)</option>
                                    <option value="ST(H)" <?=($application_for === "ST(H)")?'selected':''?>>ST(H)</option>
                                    <option value="SC" <?=($application_for === "SC")?'selected':''?>>SC</option>
                                    <option value="Hill Tribes in Plains" <?=($application_for === "Hill Tribes in Plains")?'selected':''?>>Hill Tribes in Plains</option>
                                    <option value="Plain Tribes in Hills" <?=($application_for === "Plain Tribes in Hills")?'selected':''?>>Plain Tribes in Hills</option>
                                </select>
                                <?= form_error("application_for") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of the Applicant/আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>   
                        </div>

                        <div class="row form-group">      
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender === "Others")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" <?=(strlen($mobile)==10)?'readonly':''?>  />
                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>EPIC No./ ইপিআইচি নম্বৰ </label>
                                <input type="text" class="form-control" name="epic_no" id="epic_no" value="<?=$epic_no?>" maxlength="255" />
                                <?= form_error("epic_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ<span class="text-danger">*</span> </label>
                                <input type="dob" class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="255" />
                                <?= form_error("dob") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label>Fathers Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother Name/মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <!-- <div class="col-md-6">
                                <label>Religion/ ধৰ্ম<span class="text-danger">*</span> </label>
                                <select name="religion" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Hindusim" <?=($religion === "Hindusim")?'selected':''?>>Hindusim</option>
                                    <option value="Islam" <?=($religion === "Islam")?'selected':''?>>Islam</option>
                                    <option value="Budhisim" <?=($religion === "Budhisim")?'selected':''?>>Budhisim</option>
                                    <option value="Christan" <?=($religion === "Christan")?'selected':''?>>Christan</option>
                                    <option value="Other" <?=($religion === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("religion") ?>
                            </div> -->
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label> Caste/Tribe/Community/ জাতি/জনজাতি/সম্প্ৰদায়<span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control" id="caste">
                                    <option value="<?= $caste ?>">
                                        <?= strlen($caste) ? $caste : "Caste/Tribe/Community" ?>
                                    </option>
                                    <?= form_error("caste") ?>
                                </select>
                                <?= form_error("caste") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label> Caste/Tribe/Community/ জাতি/জনজাতি/সম্প্ৰদায়<span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control" id="caste">
                                    <option value="<?= $caste ?>">
                                        <?= strlen($caste) ? $caste : "Caste/Tribe/Community" ?>
                                    </option>
                                    <?= form_error("caste") ?>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Caste/ উপ-জাতি<span class="text-danger">*</span> </label>
                                <select name="subcaste" class="form-control subcaste">
                                    <option value="<?= $subcaste ?>">
                                        <?= strlen($subcaste) ? $subcaste : "Sub-Caste" ?>
                                    </option>
                                    <?= form_error("subcaste") ?>
                                </select>
                                <?= form_error("subcaste") ?>
                            </div>
                        </div>
                        
                        <!-- <div class="row form-group">
                            <div class="col-md-6">
                                <label>Fathers Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div> -->

                        <!-- <div class="row form-group">
                            <div class="col-md-6">
                                <label>Husband Name/ স্বামীৰ নাম </label>
                                <input type="text" class="form-control" name="husband_name" id="husband_name" value="<?=$husband_name?>" maxlength="255" />
                                <?= form_error("husband_name") ?>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div> -->
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                            <legend class="h5">Address of the Applicant/ আবেদনকাৰীৰ ঠিকনা</legend>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Address Line 1/ ঠিকনা ৰেখা ১ </label>
                                    <input type="text" class="form-control" name="address_line_1" value="<?= $address_line_1 ?>" maxlength="100" />
                                    <?= form_error("address_line_1") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Address Line 2/ ঠিকনা ৰেখা ২ </label>
                                    <input type="text" class="form-control" name="address_line_2" value="<?= $address_line_2 ?>" maxlength="100" />
                                    <?= form_error("address_line_2") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>House No/ ঘৰ নং<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="house_no" value="<?= $house_no ?>" maxlength="100" />
                                    <?= form_error("house_no") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                    <select name="state" class="form-control">
                                        <option value="Assam" selected="selected">Assam</option>
                                    </select>
                                    <?= form_error("state") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>District/ জিলা <span class="text-danger">*</span> </label>
                                    <select name="district" class="form-control" id="district">
                                        <option value="<?= $district ?>">
                                            <?= strlen($district) ? $district : "Select District" ?>
                                        </option>
                                    </select>
                                    <?= form_error("district") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Sub-Division/ মহকুমা<span class="text-danger">*</span> </label>
                                    <select name="sub_division" class="form-control" id="sub_division">
                                        <option value="<?= $sub_division ?>">
                                            <?= strlen($sub_division) ? $sub_division : "Sub-Division" ?>
                                        </option>
                                        <?= form_error("sub_division") ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Circle Office/ ৰাজহ চক্ৰ<span class="text-danger">*</span> </label>
                                    <select name="circle_office" id="circle_office" class="form-control">
                                        <option value="<?= $circle_office ?>">
                                            <?= strlen($circle_office) ? $circle_office : "Circle Office" ?>
                                        </option>
                                    </select>
                                    <?= form_error("circle_office") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Mouza/ মৌজা<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mouza" value="<?= $mouza ?>" maxlength="100" />
                                    <?= form_error("mouza") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Village/ Town/ গাওঁ/চহৰ<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="village_town" value="<?= $village_town ?>" maxlength="100" />
                                    <?= form_error("village_town") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Police Station/ আৰক্ষী থানা<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="police_station" value="<?= $police_station ?>" maxlength="100" />
                                    <?= form_error("police_station") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Post Office/ ডাকঘৰ<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="post_office" value="<?= $post_office ?>" maxlength="100" />
                                    <?= form_error("post_office") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Pin Code/ পিন কোড<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control pin_code number_input" name="pin_code" value="<?= $pin_code ?>" maxlength="6" />
                                    <?= form_error("pin_code") ?>
                                </div>
                            </div>
                    </fieldset>
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