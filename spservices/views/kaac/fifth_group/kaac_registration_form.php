<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://artpskaac.in/"; //For testing
// pre($dbrow);
if ($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
   
   
    $applicant_title = !empty(set_value("applicant_title"))? set_value("applicant_title"):(isset($dbrow->form_data->applicant_title)? $dbrow->form_data->applicant_title: "");
    $first_name = !empty(set_value("first_name"))? set_value("first_name"):(isset($dbrow->form_data->first_name)? $dbrow->form_data->first_name: "");
    $middle_name = !empty(set_value("middle_name"))? set_value("middle_name"):(isset($dbrow->form_data->middle_name)? $dbrow->form_data->middle_name: "");
    $last_name = !empty(set_value("last_name"))? set_value("last_name"):(isset($dbrow->form_data->last_name)? $dbrow->form_data->last_name: "");
    $applicant_gender = !empty(set_value("applicant_gender"))? set_value("applicant_gender"):(isset($dbrow->form_data->applicant_gender)? $dbrow->form_data->applicant_gender: "");
    $caste = !empty(set_value("caste"))? set_value("caste"):(isset($dbrow->form_data->caste)? $dbrow->form_data->caste: "");
    $father_title = !empty(set_value("father_title"))? set_value("father_title"):(isset($dbrow->form_data->father_title)? $dbrow->form_data->father_title: "");
    $father_name = !empty(set_value("father_name"))? set_value("father_name"):(isset($dbrow->form_data->father_name)? $dbrow->form_data->father_name: "");
    $aadhar_no = !empty(set_value("aadhar_no"))? set_value("aadhar_no"):(isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "");
    $mobile = $this->session->mobile ?? $dbrow->form_data->mobile; 
    $email = !empty(set_value("email"))? set_value("email"):(isset($dbrow->form_data->email)? $dbrow->form_data->email: "");

    
    $district = !empty(set_value("district"))? set_value("district"):(isset($dbrow->form_data->district)? $dbrow->form_data->district: "");
    $district_name = !empty(set_value("district_name"))? set_value("district_name"):(isset($dbrow->form_data->district_name)? $dbrow->form_data->district_name: "");
    $police_station = !empty(set_value("police_station"))? set_value("police_station"):(isset($dbrow->form_data->police_station)? $dbrow->form_data->police_station: "");
    $post_office = !empty(set_value("post_office"))? set_value("post_office"):(isset($dbrow->form_data->post_office)? $dbrow->form_data->post_office: "");

    $bank_account_no=!empty(set_value("bank_account_no"))? set_value("bank_account_no"):(isset($dbrow->form_data->bank_account_no)? $dbrow->form_data->bank_account_no: "");
    $bank_name=!empty(set_value("bank_name"))? set_value("bank_name"):(isset($dbrow->form_data->bank_name)? $dbrow->form_data->bank_name: "");
    $bank_branch=!empty(set_value("bank_branch"))? set_value("bank_branch"):(isset($dbrow->form_data->bank_branch)? $dbrow->form_data->bank_branch: "");
    $ifsc_code=!empty(set_value("ifsc_code"))? set_value("ifsc_code"):(isset($dbrow->form_data->ifsc_code)? $dbrow->form_data->ifsc_code: "");
    

    $land_district = !empty(set_value("land_district"))? set_value("land_district"):(isset($dbrow->form_data->land_district)? $dbrow->form_data->land_district: "");
    $land_district_name = !empty(set_value("land_district_name"))? set_value("land_district_name"):(isset($dbrow->form_data->land_district_name)? $dbrow->form_data->land_district_name: "");

    $sub_division = !empty(set_value("sub_division"))? set_value("sub_division"):(isset($dbrow->form_data->sub_division)? $dbrow->form_data->sub_division: "");
    $sub_division_name = !empty(set_value("sub_division_name"))? set_value("sub_division_name"):(isset($dbrow->form_data->sub_division_name)? $dbrow->form_data->sub_division_name: "");

    $circle_office = !empty(set_value("circle_office"))? set_value("circle_office"):(isset($dbrow->form_data->circle_office)? $dbrow->form_data->circle_office: "");
    $circle_office_name = !empty(set_value("circle_office_name"))? set_value("circle_office_name"):(isset($dbrow->form_data->circle_office_name)? $dbrow->form_data->circle_office_name: "");    

    $mouza_name = !empty(set_value("mouza_name"))? set_value("mouza_name"):(isset($dbrow->form_data->mouza_name)? $dbrow->form_data->mouza_name: "");
    $mouza_office_name = !empty(set_value("mouza_office_name"))? set_value("mouza_office_name"):(isset($dbrow->form_data->mouza_office_name)? $dbrow->form_data->mouza_office_name: "");

    $revenue_village = !empty(set_value("revenue_village"))? set_value("revenue_village"):(isset($dbrow->form_data->revenue_village)? $dbrow->form_data->revenue_village: "");
    $revenue_village_name = !empty(set_value("revenue_village_name"))? set_value("revenue_village_name"):(isset($dbrow->form_data->revenue_village_name)? $dbrow->form_data->revenue_village_name: "");


    $patta_type = !empty(set_value("patta_type"))? set_value("patta_type"):(isset($dbrow->form_data->patta_type)? $dbrow->form_data->patta_type: "");
    $patta_type_name = !empty(set_value("patta_type_name"))? set_value("patta_type_name"):(isset($dbrow->form_data->patta_type_name)? $dbrow->form_data->patta_type_name: "");
    
    $dag_no = !empty(set_value("dag_no"))? set_value("dag_no"):(isset($dbrow->form_data->dag_no)? $dbrow->form_data->dag_no: "");
    $patta_no = !empty(set_value("patta_no"))? set_value("patta_no"):(isset($dbrow->form_data->patta_no)? $dbrow->form_data->patta_no: "");
    $name_of_pattadar =!empty(set_value("name_of_pattadar"))? set_value("name_of_pattadar"):(isset($dbrow->form_data->name_of_pattadar)? $dbrow->form_data->name_of_pattadar: "");
    $pattadar_father_name = !empty(set_value("pattadar_father_name"))? set_value("pattadar_father_name"):(isset($dbrow->form_data->pattadar_father_name)? $dbrow->form_data->pattadar_father_name: "");
    $relationship_with_pattadar = !empty(set_value("relationship_with_pattadar"))? set_value("relationship_with_pattadar"):(isset($dbrow->form_data->relationship_with_pattadar)? $dbrow->form_data->relationship_with_pattadar: "");
    $land_category = !empty(set_value("land_category"))? set_value("land_category"):(isset($dbrow->form_data->land_category)? $dbrow->form_data->land_category: "");
    $cultivated_land = !empty(set_value("cultivated_land"))? set_value("cultivated_land"):(isset($dbrow->form_data->cultivated_land)? $dbrow->form_data->cultivated_land: "");
    $production = !empty(set_value("production"))? set_value("production"):(isset($dbrow->form_data->production)? $dbrow->form_data->production: "");
    $crop_variety = !empty(set_value("crop_variety"))? set_value("crop_variety"):(isset($dbrow->form_data->crop_variety)? $dbrow->form_data->crop_variety: "");
    $crop_variety_name = !empty(set_value("crop_variety_name"))? set_value("crop_variety_name"):(isset($dbrow->form_data->crop_variety_name)? $dbrow->form_data->crop_variety_name: "");
   
    $surplus_production =!empty(set_value("surplus_production"))? set_value("surplus_production"):(isset($dbrow->form_data->surplus_production)? $dbrow->form_data->surplus_production: "");
   
    $cultivator_type = !empty(set_value("cultivator_type"))? set_value("cultivator_type"):(isset($dbrow->form_data->cultivator_type)? $dbrow->form_data->cultivator_type: "");
    $cultivator_type_name = !empty(set_value("cultivator_type_name"))? set_value("cultivator_type_name"):(isset($dbrow->form_data->cultivator_type_name)? $dbrow->form_data->cultivator_type_name: "");

    $bigha = !empty(set_value("bigha"))? set_value("bigha"):(isset($dbrow->form_data->bigha)? $dbrow->form_data->bigha: "");
    $kotha = !empty(set_value("kotha"))? set_value("kotha"):(isset($dbrow->form_data->kotha)? $dbrow->form_data->kotha: "");
    $loosa = !empty(set_value("loosa"))? set_value("loosa"):(isset($dbrow->form_data->loosa)? $dbrow->form_data->loosa: "");
    $land_area = !empty(set_value("land_area"))? set_value("land_area"):(isset($dbrow->form_data->land_area)? $dbrow->form_data->land_area: "");
    
    $ado_circle_office =!empty(set_value("ado_circle_office"))? set_value("ado_circle_office"):(isset($dbrow->form_data->ado_circle_office)? $dbrow->form_data->ado_circle_office: "");
    $ado_circle_office_name =!empty(set_value("ado_circle_office_name"))? set_value("ado_circle_office_name"):(isset($dbrow->form_data->ado_circle_office_name)? $dbrow->form_data->ado_circle_office_name: "");
     
} else {
    $obj_id = NULL;
    $appl_ref_no = NULL;

   
   
    $applicant_title = set_value("applicant_title");
    $first_name = set_value("first_name");
    $middle_name = set_value("middle_name");    
    $last_name = set_value("last_name");
    $applicant_gender = set_value("applicant_gender");
    $caste = set_value("caste");
    $father_title = set_value("father_title");
    $father_name = set_value("father_name");
    $aadhar_no = set_value("aadhar_no");
    $mobile = $this->session->mobile ?? ($dbrow->form_data->mobile ?? ''); 
    $email = set_value("email");

    
    $district_name = set_value("district_name");
    $district = set_value("district");
    $police_station = set_value("police_station");
    $post_office = set_value("post_office");
   
    
    $bank_account_no=set_value("bank_account_no");
    $bank_name=set_value("bank_name");
    $bank_branch=set_value("bank_branch");
    $ifsc_code=set_value("ifsc_code");
    
    $land_district = set_value("land_district");
    $land_district_name= set_value("land_district_name");

    $sub_division = set_value("sub_division");
    $circle_office = set_value("circle_office");
    $circle_office_name = set_value("circle_office_name");
    $mouza_name = set_value("mouza_name");
    $mouza_office_name= set_value("mouza_office_name");
    $revenue_village = set_value("revenue_village");
    $revenue_village_name = set_value("revenue_village_name");

    $patta_type = set_value("patta_type");
    $patta_type_name = set_value("patta_type_name");
    $dag_no = set_value("dag_no");
    $patta_no = set_value("patta_no");
    $name_of_pattadar = set_value("name_of_pattadar");
    $pattadar_father_name = set_value("pattadar_father_name");
    $relationship_with_pattadar = set_value("relationship_with_pattadar");
    $land_category = set_value("land_category");
    $cultivated_land = set_value("cultivated_land");
    $production = set_value("production");
    $crop_variety = set_value("crop_variety");
    $surplus_production = set_value("surplus_production");
    $cultivator_type = set_value("cultivator_type");
    $cultivator_type_name = set_value("cultivator_type_name");

    $bigha = set_value("bigha");
    $kotha = set_value("kotha");
    $loosa = set_value("loosa");
    $land_area = set_value("land_area");
    $crop_variety_name= set_value("crop_variety_name");    
    $ado_circle_office = set_value("ado_circle_office");    
    $ado_circle_office_name = set_value("ado_circle_office_name");    
} //End of if else
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

.blink {
    animation: blinker 2s linear infinite;
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

.message {
    color: red;
    font-weight: bold;
}

.instructions li {
    font-size: 13px;
}
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>




<script type="text/javascript">


$(document).ready(function() {
    
        get_sub_division(<?php echo $district ?>);
        
        var sub_div="<?php echo $sub_division ?>";
        if(sub_div)
        {
            $("#sub_division option[value='"+sub_div+"']").prop('selected', true);
        }
        getmouza(<?php echo $circle_office ?>);
        
        res = "<?php echo $police_station ?>"
            $.get("<?= $apiServer . "api/ccsdp/police_station" ?>", function(data) {
                $.each(data.records, function(i, val) {
                    police_station_name = val.police_station_name;
                    // console.log($police_station_name);
                    cond = (res == police_station_name) ? "selected" : "";
                    $("#police_station").append('<option value="' + val.police_station_name + '"' + cond + '>' + val.police_station_name + '</option>');
                });
        });
        
        function get_sub_division(dist)
        {
            if(dist==1)
            {
                $("#sub_division").empty();
                $("#sub_division").append('<option value="">Select Any One</option><option value="Diphu">Diphu</option><option value="Bokajan">Bokajan</option><option value="Howraghat">Howraghat</option>');

            }
            else
            {
                $("#sub_division").empty();
                $("#sub_division").append('<option value="">Select Any One</option><option value="Hamren">Hamren</option>');
            }
        }


        $(document).on("change", "#land_district", function() {
            var value = $("#land_district").val();
            get_sub_division(value);
        });


        function getmouza(circle_id)
        {

            console.log();
            $("#mouza_name").empty();
            $.ajax({
            url: "<?= base_url("spservices/kaac/registration/mouza"); ?>",
            type: 'POST',
            data: {
                "jsonbody": circle_id
            },
            dataType: 'json',
            success: function(res) {
                    var obj = $.parseJSON(res);                   
                    $.each(obj.records, function(i, val) {
                        id = val.mouza_id;
                        $("#mouza_name").append('<option value="' + id + '">' + val.mouza_name + '</option>');
                    });
                    var mouza_name="<?php echo $mouza_name ?>";
       
                    if(mouza_name)
                    {
                        $("#mouza_name option[value='"+mouza_name+"']").prop('selected', true);
                    }
                }
            });
        }
        // land_district,sub_division
        $(document).on('change', '#circle_office', function() {
        
        circle_id = $(this).val();
            getmouza(circle_id);     
        });


            // alert(mouza_name)
        function getrv(mouza_name)
        {
    
            $("#revenue_village").empty();
            // mouza_id = $("#mouza_name").val();
            $.ajax({
                url: "<?= base_url("spservices/kaac/registration/revenue_village"); ?>",
                type: 'POST',
                // contentType: "application/json",
                data: {
                    "jsonbody": mouza_name
                },
                dataType: 'json',
                success: function(res) {
                    var obj = $.parseJSON(res);
                    $.each(obj.records, function(i, val) {
                        id = val.village_id;
                        $("#revenue_village").append('<option value="' + val.village_id + '">' + val.village_name + '</option>');
                    });

                    var revenue_village="<?php echo $revenue_village ?>";
                    if(revenue_village)
                    {
                        $("#revenue_village option[value='"+revenue_village+"']").prop('selected', true);
                    }
                }
            });
        }

        getrv(<?php echo $mouza_name ?>);
       
        $(document).on('change', '#mouza_name', function() {
            $("#revenue_village").empty();
            mouza_id = $(this).val();
            getrv(mouza_id);
        });
   
    $(document).on("click", "#open_camera", function() {
        $("#live_photo_div").show();
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        $("#open_camera").hide();
    });

    $(document).on("click", "#capture_photo", function() {
        Webcam.snap(function(data_uri) { //alert(data_uri);
            $("#captured_photo").attr("src", data_uri);
            $("#photo_data").val(data_uri);
        });
    });

        $(document).on('change', '#circle_office', function() {
        var value = $("#circle_office option:selected").text();
        $('#circle_office_name').val(value);
        })

        $(document).on('change', '#mouza_name', function() {
        var value = $("#mouza_name option:selected").text();
        $('#mouza_office_name').val(value);
        })

        $(document).on('change', '#revenue_village', function() {
        var value = $("#revenue_village option:selected").text();
        $('#revenue_village_name').val(value);
        })

        $(document).on('change', '#land_district', function() {
        var value = $("#land_district option:selected").text();
        $('#land_district_name').val(value);
        })
        
        $(document).on('change', '#district', function() {
        var value = $("#district option:selected").text();

        $('#district_name').val(value);
        });
       
        $(document).on('change', '#patta_type', function() {
        var value = $("#patta_type option:selected").text();
        
        $('#patta_type_name').val(value);
        })

        $(document).on('change', '#cultivator_type', function() {
        var value = $("#cultivator_type option:selected").text();
       
        $('#cultivator_type_name').val(value);
        })
        $(document).on('change', '#crop_variety', function() {
        var value = $("#crop_variety option:selected").text();
       
        $('#crop_variety_name').val(value);
        })
        $(document).on('change', '#ado_circle_office', function() {
        var value = $("#ado_circle_office option:selected").text();
       
        $('#ado_circle_office_name').val(value);
        })
        

        $(document).on('change', '#sub_division', function() {
        var value = $("#sub_division option:selected").text();
       
        $('#sub_division_name').val(value);
        })

    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        console.log(clickedBtn);
        $("#submission_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
                "You want to save in Draft mode that will allows you to edit and can submit later";
        } else if (clickedBtn === 'SAVE') {
            var msg = "Do you want to procced";
        } else if (clickedBtn === 'CLEAR') {
            var msg = "Once you Reset, All filled data will be cleared";
        } else {
            var msg = "";
        } //End of if else            
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
                if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                    $("#myfrm").submit();
                } else if (clickedBtn === 'CLEAR') {
                    $("#myfrm")[0].reset();
                } else {} //End of if else
            }
        });
    });
});
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac-farmer') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br><?= $pageTitleAssamese?></b></h4>
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
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not
                                exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য </li>
                        </ul>
                        <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the
                            application:</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Address proof [Mandatory]</li>
                            <li>Identity proof [Mandatory] </li>
                            <li>Three passport Size Photos [Mandatory] </li>
                            <li>Valid MB/TC House Tax deposit receipt.</li>
                            <li>Valid MBTC Room rent deposit [ Not mandatory] [If room occupied than mandatory]</li>
                            <li>Special reason for Consideration letter [ Not Mandatory] </li>

                        </ul>

                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Farmer's Basic Details / কৃষকৰ মৌলিক বিৱৰণ </legend>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Applicant&apos;s First Name/ আবেদনকাৰীৰ প্ৰথম নাম<span
                                        class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="applicant_title">
                                                <option value="">Select</option>
                                                <?php
                                            $titles = array(
                                                "Mr" => "Mr",
                                                "Mrs" => "Mrs",
                                                "Sri" => "Sri",
                                                "Smt" => "Smt",
                                                "Late" => "Late"
                                            );

                                            foreach ($titles as $value => $display) {
                                                $selected = ($applicant_title === $value) ? 'selected' : '';
                                                echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                            }
                                            ?>
                                            </select>

                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        value="<?= $first_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("applicant_title") . form_error("first_name") ?>

                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Middle Name/ আবেদনকাৰীৰ মধ্যনাম<span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="middle_name" id="middle_name"
                                    value="<?= $middle_name ?>" maxlength="255" />
                                <?= form_error("middle_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="last_name" id="last_name"
                                    value="<?= $last_name ?>" maxlength="255" />
                                <?= form_error("last_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $genders = array(
                                        "Male" => "Male",
                                        "Female" => "Female",
                                        "Transgender" => "Transgender"
                                    );

                                    foreach ($genders as $value => $display) {
                                        $selected = ($applicant_gender === $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Caste/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <select name="caste" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $castes = array(
                                        "General" => "General",
                                        "ST(H)" => "ST(H)",
                                        "ST(P)" => "ST(P)",
                                        "OBC" => "OBC",
                                        "Other" => "Other"
                                    );

                                    foreach ($castes as $value => $display) {
                                        $selected = ($caste === $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>

                                <?= form_error("caste") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span>
                                </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">

                                            <select name="father_title">
                                                <option value="">Select</option>
                                                <?php
                                                $father_titles = array(
                                                    "Mr" => "Mr",
                                                    "Mrs" => "Mrs",
                                                    "Sri" => "Sri",
                                                    "Smt" => "Smt",
                                                    "Late" => "Late"
                                                );

                                                foreach ($father_titles as $value => $display) {
                                                    $selected = ($father_title === $value) ? 'selected' : '';
                                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="father_name" id="father_name"
                                        value="<?= $father_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("father_title") . form_error("father_name") ?>

                            </div>
                            <div class="col-md-4">
                                <label>Aadhar Number / আধাৰ নম্বৰ </label>
                                <input type="text" class="form-control" name="aadhar_no" value="<?= $aadhar_no ?>" />
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ($usser_type === "user") { ?>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly
                                    maxlength="10" />
                                <?php } else { ?>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>"
                                    maxlength="10" />
                                <?php } ?>

                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-4">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <!-- <legend class="h5">Applicant Address/ ঠিকনা </legend> -->
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>District/জিলা <span class="text-danger">*</span></label>
                                <input type="hidden" name="district_name" id="district_name" value="<?= $district_name ?>" />
                                <select id="district" name="district" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                $districts = array(
                                    "1" => "East Karbi Anglong",
                                    "2" => "West Karbi Anglong"
                                );

                                foreach ($districts as $value => $display) {
                                    $selected = ($district == $value) ? 'selected' : '';
                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                }
                                ?>
                                </select>
                                <?= form_error("district") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office"
                                    value="<?= $post_office ?>" maxlength="255" />
                                <?= form_error("post_office") ?>
                            </div>
                            <!-- <div class="col-md-4">
                                <label>Police Station/থানা <span class="text-danger">*</span></label>
                                <select id="police_station" name="police_station" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                        $police_stations = array(
                                            "Anjokpani Police Station" => "Anjokpani Police Station",
                                            "Baithalangso Police Station" => "Baithalangso Police Station",
                                            "Bokajan Police Station" => "Bokajan Police Station",
                                            "Bokulia Police Station" => "Bokulia Police Station",
                                            "Borlongfer Police Station" => "Borlongfer Police Station",
                                            "Borpathar Police Station" => "Borpathar Police Station",
                                            "Chowkohola Police Station" => "Chowkohola Police Station",
                                            "Deithor Police Station" => "Deithor Police Station",
                                            "Dillai Police Station" => "Dillai Police Station",
                                            "Diphu Police Station" => "Diphu Police Station",
                                            "Dokmoka Police Station" => "Dokmoka Police Station",
                                            "Dolamara Police Station" => "Dolamara Police Station",
                                            "Hamren Police Station" => "Hamren Police Station",
                                            "Howraghat Police Station" => "Howraghat Police Station",
                                            "Jirikingding Police Station" => "Jirikingding Police Station",
                                            "Khatkhati Police Station" => "Khatkhati Police Station",
                                            "Kheroni Police Station" => "Kheroni Police Station",
                                            "Manja Police Station" => "Manja Police Station",
                                            "Rongmongwe Police Station" => "Rongmongwe Police Station"
                                        );

                                        foreach ($police_stations as $value => $display) {
                                            $selected = ($value === $police_station) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                        }
                                    ?>
                                </select>
                                <?= form_error("police_station") ?>
                            </div> -->
                            <div class="col-md-4">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="police_station_name" id="police_station_name" value="<?= $police_station ?>" />
                                <select id="police_station" name="police_station" class="form-control">
                                    <option value="">Please Select</option>
                                    
                                </select>
                                <?= form_error("police_station") ?>
                            </div>
                        </div>

                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Farmer's Bank Details/ কৃষক বেংকৰ সবিশেষ </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Bank Account No./ বেংক একাউণ্ট নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bank_account_no" id="bank_account_no"
                                    value="<?= $bank_account_no ?>" maxlength="255" />
                                <?= form_error("bank_account_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Bank Name/বেংকৰ নাম <span class="text-danger">*</span></label>
                                <select id="bank_name" name="bank_name" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                $banks = array(
                                    "SBI" => "State Bank of India",
                                    "HDFC" => "HDFC Bank",
                                    "ICICI" => "ICICI Bank",
                                    "AXIS" => "AXIS Bank",
                                    "UBI" => "United Bank of India",
                                    "CBI" => "Central Bank of India",
                                    "AGVB" => "Assam Gramin Vikas Bank",
                                );

                                foreach ($banks as $value => $display) {
                                    $selected = ($bank_name == $value) ? 'selected' : '';
                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                }
                                ?>
                                </select>
                                <?= form_error("bank_name") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Bank Branch/ বেংক শাখা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bank_branch" id="bank_branch"
                                    value="<?= $bank_branch ?>" maxlength="255" />
                                <?= form_error("bank_branch") ?>
                            </div>
                            <div class="col-md-4">
                                <label>IFSC Code/আই এফ এছ চি ক'ড <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                    value="<?= $ifsc_code ?>" maxlength="255" />
                                <?= form_error("ifsc_code") ?>
                            </div>


                        </div>

                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Land details/ ভূমিৰ সবিশেষ </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>District / জিলা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="land_district_name" id="land_district_name" value="<?= $land_district_name ?>" />
                                <select id="land_district" name="land_district" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                $districts = array(
                                    "1" => "East Karbi Anglong",
                                    "2" => "West Karbi Anglong"
                                );

                                foreach ($districts as $value => $display) {
                                    $selected = ($district == $value) ? 'selected' : '';
                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                }
                                ?>
                                </select>
                                <?= form_error("land_district") ?>
                            </div>

                            <div class="col-md-4">
                            <label>Sub-Division / মহকুমা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="sub_division_name" id="sub_division_name" value="<?= $sub_division ?>" />
                                <select id="sub_division" name="sub_division" class="form-control">
                                    <option value="">Please Select</option>
                                    
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                            <!-- <div class="col-md-4">
                                <label>Sub-Division / মহকুমা <span class="text-danger">*</span> </label>
                                <select id="sub_division" name="sub_division" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                        $district_one = array(
                                            "Diphu" => "Diphu",
                                            "Bokajan" => "Bokajan",
                                            "Howraghat" => "Howraghat",
                                            "Hamren" => "Hamren"

                                        );
                                        // $district_two = array(
                                        //     "Hamren" => "Hamren"
                                        // );
                                        foreach ($district_one as $value => $display) {
                                            $selected = ($sub_division === $value) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                        }
                                    ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div> -->
                            <div class="col-md-4">
                                <label>Circle/ চক্ৰ <span class="text-danger">*</span> </label>
                                <input type="hidden" name="circle_office_name" id="circle_office_name" value="<?= $circle_office_name ?>" />
                                
                                <select id="circle_office" name="circle_office" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1383118" <?= ($circle_office === "1383118") ? 'selected' : '' ?>
                                        data-entity-level="">Diphu</option>
                                    <option value="1383119" data-entity-level=""
                                        <?= ($circle_office === "1383119") ? 'selected' : '' ?>>Dongkamukam</option>
                                    <option value="1383120" data-entity-level=""
                                        <?= ($circle_office === "1383120") ? 'selected' : '' ?>>Phuloni</option>
                                    <option value="1383121" data-entity-level=""
                                        <?= ($circle_office === "1383121") ? 'selected' : '' ?>>Silonijan</option>
                                </select>
                                <?= form_error("circle_office") ?>
                            </div>
                            
                            <div class="col-md-4">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="mouza_office_name" id="mouza_office_name" value="<?= $mouza_office_name ?>">
                                <select id="mouza_name" name="mouza_name" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("mouza_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Revenue Village/ ৰাজহ গাঁও <span class="text-danger">*</span> </label>
                                <input type="hidden" name="revenue_village_name" id="revenue_village_name" value="<?= $revenue_village_name ?>">
                                <select id="revenue_village" name="revenue_village" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("revenue_village") ?>
                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Patta Type/ পট্টা টাইপ<span class="text-danger">*</span> </label>

                                <input name="patta_type_name" id="patta_type_name" type="hidden" value="<?= $patta_type_name ?>"/>
                                <select name="patta_type" id="patta_type" class="form-control">
                                    <option value="">Please Select</option>
                                    
                                    <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Annual Patta
                                    <option value="2" <?= ($patta_type === "2") ? 'selected' : '' ?>>Periodic Patta (Myadi)
                                     
                                    <option value="3" <?= ($patta_type === "3") ? 'selected' : '' ?>>Non-Cadastral
                                    </option>
                                    
                                </select>
                                <?= form_error("patta_type") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Dag No. / ডাগ নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="dag_no" id="dag_no" value="<?= $dag_no ?>"
                                    maxlength="255" />
                                <?= form_error("dag_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Patta No. / ফ্লেপ নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="patta_no" id="patta_no"
                                    value="<?= $patta_no ?>" maxlength="255" />
                                <?= form_error("patta_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Name of Pattadar / নাম পট্টাদৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name_of_pattadar" id="name_of_pattadar"
                                    value="<?= $name_of_pattadar ?>" maxlength="255" />
                                <?= form_error("name_of_pattadar") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Pattadar Father Name /পট্টাদাৰ পিতৃৰ নাম<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="pattadar_father_name"
                                    id="pattadar_father_name" value="<?= $pattadar_father_name ?>" maxlength="255" />
                                <?= form_error("pattadar_father_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Relationship with pattadar/ পট্টাদাৰৰ সৈতে সম্পৰ্ক<span
                                        class="text-danger">*</span> </label>
                                <select name="relationship_with_pattadar" id="relationship_with_pattadar"
                                    class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Self"
                                        <?= ($relationship_with_pattadar === "Self") ? 'selected' : '' ?>>Self</option>
                                    <option value="Legal"
                                        <?= ($relationship_with_pattadar === "Legal") ? 'selected' : '' ?>>Legal
                                    </option>

                                    <option value="Heir"
                                        <?= ($relationship_with_pattadar === "Heir") ? 'selected' : '' ?>>Heir </option>
                                    <option value="Others"
                                        <?= ($relationship_with_pattadar === "Others") ? 'selected' : '' ?>>Others
                                    </option>

                                </select>
                                <?= form_error("relationship_with_pattadar") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Land Category / ভূমিৰ শ্ৰেণী<span class="text-danger">*</span> </label>
                                <select name="land_category" id="land_category" class="form-control">
                                    <option value="">Please Select</option>

                                    <option value="Bari" <?= ($land_category === "Bari") ? 'selected' : '' ?>>Bari
                                    </option>
                                    <option value="Sali" <?= ($land_category === "Sali") ? 'selected' : '' ?>>Sali
                                    </option>

                                    <option value="Lahi" <?= ($land_category === "Lahi") ? 'selected' : '' ?>>Lahi
                                    </option>
                                    <option value="Baow" <?= ($land_category === "Baow") ? 'selected' : '' ?>>Baow
                                    </option>

                                </select>
                                <?= form_error("land_category") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Cultivated Land (In Bigha Only) / খেতি কৰা ভূমি (কেৱল বিঘাত)<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="cultivated_land" id="cultivated_land"
                                    value="<?= $cultivated_land ?>" maxlength="255" />
                                <?= form_error("cultivated_land") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Production (In Quintals Only)/ উৎপাদন (কেৱল কুইণ্টালত)<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="production" id="production"
                                    value="<?= $production ?>" maxlength="255" />
                                <?= form_error("production") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Crop Variety/ শস্যৰ জাত<span class="text-danger">*</span> </label>
                                <input type="hidden" name="crop_variety_name" id="crop_variety_name" value="<?= $crop_variety_name ?>" />
                                <select name="crop_variety" id="crop_variety" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?= ($crop_variety === "1") ? 'selected' : '' ?>>Goya (Common
                                        Paddy)</option>
                                    <option value="2" <?= ($crop_variety === "2") ? 'selected' : '' ?>>Bora</option>
                                    <option value="3" <?= ($crop_variety === "3") ? 'selected' : '' ?>>Ranjit </option>
                                    <option value="4" <?= ($crop_variety === "4") ? 'selected' : '' ?>>Bahadur </option>
                                    <option value="5" <?= ($crop_variety === "5") ? 'selected' : '' ?>>Binadhan
                                    </option>
                                    <option value="6" <?= ($crop_variety === "6") ? 'selected' : '' ?>>Hybrid Arize
                                    </option>
                                    <option value="7" <?= ($crop_variety === "7") ? 'selected' : '' ?>>Gold-6444
                                    </option>
                                    <option value="8" <?= ($crop_variety === "8") ? 'selected' : '' ?>>Khusboo </option>
                                    <option value="9" <?= ($crop_variety === "9") ? 'selected' : '' ?>>Joha (Aromatic)
                                        Rice </option>
                                </select>
                                <?= form_error("crop_variety") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Surplus Production / উদ্বৃত্ত উৎপাদন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="surplus_production"
                                    id="surplus_production" value="<?= $surplus_production ?>" maxlength="255" />
                                <?= form_error("surplus_production") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Cultivator Type/ খেতিয়কৰ প্ৰকাৰ<span class="text-danger">*</span> </label>
                                <input type="hidden" name="cultivator_type_name" id="cultivator_type_name" value="<?= $cultivator_type_name ?>" />
                                <select name="cultivator_type" id="cultivator_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?= ($cultivator_type === "1") ? 'selected' : '' ?>>
                                        Self-Cultivated</option>
                                    <option value="2" <?= ($cultivator_type === "2") ? 'selected' : '' ?>>Shared Cropper
                                    </option>
                                    <option value="3" <?= ($cultivator_type === "3") ? 'selected' : '' ?>>Land on Rent
                                    </option>
                                    <option value="4" <?= ($cultivator_type === "4") ? 'selected' : '' ?>>Contract
                                        Farming </option>

                                </select>
                                <?= form_error("cultivator_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Bigha / বিঘা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="bigha" id="bigha"
                                    value="<?= $bigha ?>" maxlength="255" />
                                <?= form_error("bigha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Kotha / কঠা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="kotha" id="kotha"
                                    value="<?= $kotha ?>" maxlength="255" />
                                <?= form_error("kotha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Loosa / লেচা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="loosa" id="loosa"
                                    value="<?= $loosa ?>" maxlength="255" />
                                <?= form_error("loosa") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Land Area / ভূমিৰ আয়তন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area" id="land_area"
                                    value="<?= $land_area ?>" maxlength="255" readonly />
                                <?= form_error("land_area") ?>
                            </div>

                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Submission Location/ জমা দিয়াৰ স্থান </legend>
                        <div class="row form-group">

                            <div class="col-md-4">
                                <label>ADO Circle Office/ এ ডি অ’ চাৰ্কল অফিচ <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                            <input type="hidden" name="ado_circle_office_name" id="ado_circle_office_name" value="<?= $ado_circle_office_name ?>" />
                                <select id="ado_circle_office" name="ado_circle_office">
                                    <option value="">Please Select</option>
                                    <?php
                                    $circleOffices = array(
                                        "1311" => "Howraghat",
                                        "1310" => "Lumbajong",
                                        "1312" => "Bakalia",
                                        "1313" => "Samelangso",
                                        "1314" => "Dokmoka",
                                        "1315" => "Rongmongve",
                                        "1316" => "Nilip",
                                        "1317" => "Bokajan",
                                        "1318" => "Tumpreng",
                                        "1319" => "Kheroni",
                                        "1320" => "Socheng",
                                        "1321" => "Chinthong",
                                        "1322" => "Donkamokam",
                                        "1323" => "Amri"
                                    );

                                    foreach ($circleOffices as $value => $display) {
                                        $selected = ($ado_circle_office == $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>

                                <?= form_error("ado_circle_office") ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save &amp Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>

<script>
$(document).on('keyup', '.land_area', function() {
    appBigha = parseInt($('#bigha').val()) ? parseInt($('#bigha').val()) : 0;
    appKotha = parseInt($('#kotha').val()) ? parseInt($('#kotha').val()) : 0;
    appLoosa = parseInt($('#loosa').val()) ? parseInt($('#loosa').val()) : 0;

    oneBigha = 14400
    oneKotha = 2880
    oneLoosa = 144

    bigha = appBigha * oneBigha;
    kotha = appKotha * oneKotha;
    loosa = appLoosa * oneLoosa;
    total = bigha + kotha + loosa

    $('#land_area').val(total);
    // console.log(total);
})
</script>