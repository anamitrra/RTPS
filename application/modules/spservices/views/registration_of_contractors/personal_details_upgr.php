<?php
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'} ?? set_value("obj_id");
    $deptt_name = isset($dbrow->form_data->deptt_name) ? $dbrow->form_data->deptt_name : set_value("deptt_name");
    $category = isset($dbrow->form_data->category) ? $dbrow->form_data->category : set_value("category");
    $zone = isset($dbrow->form_data->zone) ? $dbrow->form_data->zone : set_value("zone");
    $circle = isset($dbrow->form_data->circle) ? $dbrow->form_data->circle : set_value("circle");
    $applicant_type = isset($dbrow->form_data->applicant_type) ? $dbrow->form_data->applicant_type : set_value("applicant_type");
    $category_of_regs = isset($dbrow->form_data->category_of_regs) ? $dbrow->form_data->category_of_regs : set_value("category_of_regs");
    $org_name = isset($dbrow->form_data->org_name) ? $dbrow->form_data->org_name : set_value("org_name");
    $applicant_name = isset($dbrow->form_data->applicant_name) ? $dbrow->form_data->applicant_name : set_value("applicant_name");
    $father_husband_name = isset($dbrow->form_data->father_husband_name) ? $dbrow->form_data->father_husband_name : set_value("father_husband_name");
    $applicant_gender = isset($dbrow->form_data->applicant_gender) ? $dbrow->form_data->applicant_gender : set_value("applicant_gender");
    $date_of_birth = isset($dbrow->form_data->date_of_birth) ? $dbrow->form_data->date_of_birth : set_value("date_of_birth");
    $caste = isset($dbrow->form_data->caste) ? $dbrow->form_data->caste : set_value("caste");
    $religion = isset($dbrow->form_data->religion) ? $dbrow->form_data->religion : set_value("religion");
    $mobile_no = isset($dbrow->form_data->mobile) ? $dbrow->form_data->mobile : set_value("mobile");
    $land_line = isset($dbrow->form_data->land_line) ? $dbrow->form_data->land_line : set_value("land_line");
    $email = isset($dbrow->form_data->email) ? $dbrow->form_data->email : set_value("email");
    $pan_card = isset($dbrow->form_data->pan_card) ? $dbrow->form_data->pan_card : set_value("pan_card");
    $gst_no = isset($dbrow->form_data->gst_no) ? $dbrow->form_data->gst_no : set_value("gst_no");
    $date_of_deed = isset($dbrow->form_data->date_of_deed) ? $dbrow->form_data->date_of_deed : set_value("date_of_deed");
    $date_of_validity = isset($dbrow->form_data->date_of_validity) ? $dbrow->form_data->date_of_validity : set_value("date_of_validity");
    $bank_name = isset($dbrow->form_data->bank_name) ? $dbrow->form_data->bank_name : set_value("bank_name");
    $branch_name = isset($dbrow->form_data->branch_name) ? $dbrow->form_data->branch_name : set_value("branch_name");
    $ifsc_code = isset($dbrow->form_data->ifsc_code) ? $dbrow->form_data->ifsc_code : set_value("ifsc_code");
    $account_no = isset($dbrow->form_data->email) ? $dbrow->form_data->account_no : set_value("account_no");
    $date_of_working_contractor = isset($dbrow->form_data->date_of_working_contractor) ? $dbrow->form_data->date_of_working_contractor : set_value("date_of_working_contractor");
    $prev_reg_no = isset($dbrow->form_data->prev_reg_no) ? $dbrow->form_data->prev_reg_no : set_value("prev_reg_no");
    $owner_director_name = isset($dbrow->form_data->owner_director_name) ? $dbrow->form_data->owner_director_name : set_value("owner_director_name");
}
else {
    ?>
    <script type="text/javascript">
        alert('Your mobile no. not found!!!');
        location.href='<?= base_url('iservices/transactions') ?>';
        return;
    </script>
    <?php
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

    .ro {
        pointer-events: none;
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
        $('.org_div').fadeOut();
        getOfficeDetails('<?= $category?>');
        var applicant_type = '<?= $applicant_type ?>';
        if(applicant_type != '')
        {
            if (applicant_type != 'Individual' && applicant_type != '') {
                $('.org_div').fadeIn('slow');
                $('.ind_div').fadeOut();
                if(applicant_type == 'Proprietorship')
                {
                    $('#org_name_div').hide();
                    $('#deed').html("");
                    $('#deed_val').html("");
                }
                else {
                    $('#org_name_div').show();
                    $('#deed').html("*");
                    $('#deed_val').html("*");
                }
            } else {
                $('.org_div').fadeOut();
                $('.ind_div').fadeIn('slow');
            }
        }

        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });

        $(".dp1").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $(".dp-dob").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '-18y',
            autoclose: true
        });

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }

        $(document).on("change", "#applicant_type", function() {
            let applicant_type = $(this).val();

            if (applicant_type != 'Individual' && applicant_type != '') {
                $('.org_div').fadeIn('slow');
                $('.ind_div').fadeOut();
                if(applicant_type == 'Proprietorship')
                {
                    $('#org_name_div').hide();
                    $('#deed').html("");
                    $('#deed_val').html("");
                }
                else {
                    $('#org_name_div').show();
                    $('#deed').html("*");
                    $('#deed_val').html("*");
                }
            } else {
                $('.org_div').fadeOut();
                $('.ind_div').fadeIn('slow');
            }
        });
    });

    $(function () {//for onload change event
    $("select#deptt_name").change();
    let deptt_code = $("#deptt_name").val();
    let category = $("#category").val();
    let zone = '<?= $zone ?>';
    //if(deptt_code == 'PHED' || deptt_code == 'PWDB') {
        if(category == 'Class-II')
        {
            getCircles(zone);
        }
    //}
    
    });
    $(document).on("change", "#deptt_name", function(){
            $('#zone option:not(:first)').remove();
            $('#circle option:not(:first)').remove();
            var category_val = '<?= $category ?>';
            
            let deptt_code = $("#deptt_name").val();
            if(deptt_code == 'PHED') {
                $('#category option[value="Class-1(A)"]').remove();
                $('#category option[value="Class-1(B)"]').remove();
            }
            
            if(deptt_code == 'PWDB') {
                $('#category_of_regs option[value="Contractor from other department"]').remove();
            } else if(deptt_code != '' && deptt_code != 'PWDB') {
                $("#category_of_regs").html(`<select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Contractor from other department" <?= ($category_of_regs === "Contractor from other department") ? 'selected' : '' ?>>Contractor from other department</option>
                                    <option value="Unemployed Graduate Engineer" <?= ($category_of_regs === "Unemployed Graduate Engineer") ? 'selected' : '' ?>>Unemployed Graduate Engineer</option>
                                </select>`);
            }

            if(deptt_code == 'WRD') {
                if(document.getElementById("category").value == 'Class-II')
                {
                    $('#circle_lab').html('Circle <span class="text-danger">*</span>');
                }
                else {
                    $('#circle_lab').html('Circle');  
                }
            }
            if (deptt_code != '') {

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/registration_of_contractors/registration/get_zonal_offices')?>",
                    data: {"deptt": deptt_code},
                    beforeSend: function () {
                        $("#loading").attr("placeholder", "Please wait...");
                    },
                    success: function (res) {
                        if(res.status) {
                            if(deptt_code == 'PHED') {
                            $('#zone_div').fadeIn('slow');
                            var select = document.getElementById("zone");
                            var options = res.ret;

                            for(var i = 0; i < options.length; i++) {
                                var opt = options[i];
                                var el = document.createElement("option");
                                el.textContent = opt;
                                el.value = opt;
                                select.appendChild(el);

                                if (el.value == '<?= $zone ?>') {
                                    el.selected = true;
                                }
                            }
                            getOfficeDetails($("#category").val());
                            }
                            else if(deptt_code == 'WRD') {
                            $('#circle option:not(:first)').remove();
                            //$('#zone').val('');
                            //$('#zone_div').fadeOut();
                            $('#circle_div').fadeIn('slow');
                            var select = document.getElementById("zone");
                            var options = res.ret;

                            for(var i = 0; i < options.length; i++) {
                                var opt = options[i];
                                var el = document.createElement("option");
                                el.textContent = opt;
                                el.value = opt;
                                select.appendChild(el);

                                if (el.value == '<?= $circle ?>') {
                                    el.selected = true;
                                }
                            }
                            getOfficeDetails($("#category").val());
                            }
                            else if(deptt_code == 'PWDB') {
                            $('#circle option:not(:first)').remove();
                            $('#zone_div').fadeIn('slow');
                            var select = document.getElementById("zone");
                            var options = res.ret;

                            for(var i = 0; i < options.length; i++) {
                                var opt = options[i];
                                var el = document.createElement("option");
                                el.textContent = opt;
                                el.value = opt;
                                select.appendChild(el);

                                if (el.value == '<?= $zone ?>') {
                                    el.selected = true;
                                }
                            }
                            getOfficeDetails($("#category").val());
                            }
                        } else {
                            alert(res.msg);
                        }
                    }
                });
            }
        });

        function getCircles(zone){
            let deptt_code = $("#deptt_name").val();
            $('#circle option:not(:first)').remove();
            if (zone != '') {

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/registration_of_contractors/registration/get_circle_offices')?>",
                    data: {"deptt": deptt_code, "zone": zone},
                    beforeSend: function () {
                        $("#loading").attr("placeholder", "Please wait...");
                    },
                    success: function (res) {
                        if(res.status) {
                            var select = document.getElementById("circle");
                            var options = res.ret;

                            for(var i = 0; i < options.length; i++) {
                                var opt = options[i];
                                var el = document.createElement("option");
                                el.textContent = opt;
                                el.value = opt;
                                select.appendChild(el);

                                if (el.value == '<?= $circle ?>') {
                                    el.selected = true;
                                }
                            }
                        } else {
                            alert(res.msg);
                        }
                    }
                });
            }

        }

    function getOfficeDetails(val)
    {
        let deptt_code = $("#deptt_name").val();

        if(deptt_code == 'WRD') {
            $("#zone").val("Assam").change();
            $('#zone_div').fadeOut();
            if(document.getElementById("category").value == 'Class-II')
            {
                $('#circle_lab').html('Circle <span class="text-danger">*</span>');
            }
            else {
                $('#circle_lab').html('Circle');  
            }
        }

        if(deptt_code != '' && deptt_code != 'PWDB') {
            if(val == 'Class-II') {
                $("#category_of_regs").html(`<select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Contractor from other department" <?= ($category_of_regs === "Contractor from other department") ? 'selected' : '' ?>>Contractor from other department</option>
                                    <option value="Unemployed Diploma Engineer" <?= ($category_of_regs === "Unemployed Diploma Engineer") ? 'selected' : '' ?>>Unemployed Diploma Engineer</option>
                                </select>`);
            } else {
                $("#category_of_regs").html(`<select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Contractor from other department" <?= ($category_of_regs === "Contractor from other department") ? 'selected' : '' ?>>Contractor from other department</option>
                                    <option value="Unemployed Graduate Engineer" <?= ($category_of_regs === "Unemployed Graduate Engineer") ? 'selected' : '' ?>>Unemployed Graduate Engineer</option>
                                </select>`);
            }
        }
        else {
            if(val == 'Class-II') {
                $("#category_of_regs").html(`<select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Unemployed Diploma Engineer" <?= ($category_of_regs === "Unemployed Diploma Engineer") ? 'selected' : '' ?>>Unemployed Diploma Engineer</option>
                                </select>`);
            } else {
                $("#category_of_regs").html(`<select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Unemployed Graduate Engineer" <?= ($category_of_regs === "Unemployed Graduate Engineer") ? 'selected' : '' ?>>Unemployed Graduate Engineer</option>
                                </select>`);
            }
        }

        if(deptt_code != 'WRD') {   
        if(val != 'Class-II')
        {
            $('#circle').val('');
            $('#circle_div').fadeOut();
        }
        else {
            $('#circle_div').fadeIn('slow');
        }
    }
    }

    $(function() {
    $("#pan_card").keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $("#gst_no").keyup(function() {
        this.value = this.value.toUpperCase();
    });
});

    $(document).ready(function() {
    const exc_fields = ["category","zone","circle"];
    $('#myfrm input, #myfrm select').each(
        function(index){
            if(!exc_fields.includes($(this).attr('name'))) {  
            var input = $(this);
            input.addClass('ro');
            input.attr('readonly', true);
            }
        }
    );
});
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/upgradation/submit_personal_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Upgradation of Contractors
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
                    <fieldset class="border border-success" style="margin-top:0px">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Department Name <span class="text-danger">*</span> </label>
                                <select name="deptt_name" id="deptt_name" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="PHED" <?= ($deptt_name === "PHED") ? 'selected' : '' ?>>PHED</option>
                                    <option value="PWDB" <?= ($deptt_name === "PWDB") ? 'selected' : '' ?>>PWD (Building)</option>
                                    <!-- <option value="PWDR" <?= ($deptt_name === "PWDR") ? 'selected' : '' ?>>PWD (Roads)</option> -->
                                    <!-- <option value="IRG" <?= ($deptt_name === "IRG") ? 'selected' : '' ?>>Irrigation</option> -->
                                    <option value="WRD" <?= ($deptt_name === "WRD") ? 'selected' : '' ?>>Water Resource</option>
                                </select>
                                <?= form_error("deptt_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Class in which registration is sought <span class="text-danger">*</span> </label>
                                <div id="cat_div">
                                <select name="category" id="category" class="form-control" onchange="getOfficeDetails(this.value)">
                                    <option value="">Please Select</option>
                                    <?php if($category_reg === "Class-1(A)") {?>
                                        <option value="Class-1(A)" <?= ($category === "Class-1(A)") ? 'selected' : '' ?>>Class-I (A)</option>
                                    <?php } else if($category_reg === "Class-1(B)") {?>
                                        <option value="Class-1(A)" <?= ($category === "Class-1(A)") ? 'selected' : '' ?>>Class-I (A)</option>
                                        <option value="Class-1(B)" <?= ($category === "Class-1(B)") ? 'selected' : '' ?>>Class-I (B)</option>
                                    <?php } else if($category_reg === "Class-1(C)") {?>
                                        <option value="Class-1(A)" <?= ($category === "Class-1(A)") ? 'selected' : '' ?>>Class-I (A)</option>
                                        <option value="Class-1(B)" <?= ($category === "Class-1(B)") ? 'selected' : '' ?>>Class-I (B)</option>
                                        <option value="Class-1(C)" <?= ($category === "Class-1(C)") ? 'selected' : '' ?>>Class-I (C)</option>
                                    <?php } ?>
                                </select>
                                </div>
                                <?= form_error("category") ?>
                            </div>
                        </div>
                        <div class="row form-group" id="office_details">
                            <div class="col-md-6" id="zone_div">
                                <label>Zone <span class="text-danger">*</span> </label>
                                <select name="zone" id="zone" class="form-control" onchange="getCircles(this.value)">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("zone") ?>
                            </div>
                            <div class="col-md-6" id="circle_div">
                                <label id="circle_lab">Circle <span class="text-danger">*</span> </label>
                                <select name="circle" id="circle" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <h5 class="text-center mt-3 text-success"><u><strong>REGISTRATION DETAILS</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5">Details of the Applicant </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant Type <span class="text-danger">*</span> </label>
                                <select name="applicant_type" id="applicant_type" class="form-control">
                                    <!-- <option value="">Please Select</option> -->
                                    <option value="Individual" <?= ($applicant_type === "Individual") ? 'selected' : '' ?>>Individual</option>
                                    <option value="Proprietorship" <?= ($applicant_type === "Proprietorship") ? 'selected' : '' ?>>Proprietorship</option>
                                    <option value="Partnership firm" <?= ($applicant_type === "Partnership firm") ? 'selected' : '' ?>>Partnership firm</option>
                                    <option value="Company" <?= ($applicant_type === "Company") ? 'selected' : '' ?>>Company</option>
                                </select>
                                <?= form_error("applicant_type") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Category of Registration </label>
                                <select name="category_of_regs" id="category_of_regs" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Contractor from other department" <?= ($category_of_regs === "Contractor from other department") ? 'selected' : '' ?>>Contractor from other department</option>
                                    <option value="Unemployed Graduate Engineer" <?= ($category_of_regs === "Unemployed Graduate Engineer") ? 'selected' : '' ?>>Unemployed Graduate Engineer</option>
                                </select>
                                <?= form_error("category_of_regs") ?>
                            </div>
                        </div>
                        <div class="row form-group ind_div">
                            <div class="col-md-6">
                                <label>Name of the applicant <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?? '' ?>" maxlength="255" autocomplete="off"/>
                                <?= form_error("applicant_name") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Name of Father/ Husband <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_husband_name" id="father_husband_name" value="<?= $father_husband_name ?? ''?>" maxlength="255" autocomplete="off"/>
                                <?= form_error("father_husband_name") ?>
                            </div>
                            
                        </div>
                        <div class="row form-group ind_div">
                            <div class="col-md-2">
                                <label>Gender <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" id="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Others" <?= ($applicant_gender === "Others") ? 'selected' : '' ?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                             </div>
                            <div class="col-md-2">
                                    <label>Date of Birth <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control dp-dob" name="date_of_birth" id="date_of_birth" value="<?= $date_of_birth ?? ''?>" autocomplete="off" placeholder="dd-mm-yyyy"/>
                                    <?= form_error("date_of_birth") ?>
                            </div>
                            <div class="col-md-2">
                                    <label>Caste <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="caste" id="caste">
                                    <option value="">Select Caste</option>
                                    <option value="General" <?= ($caste === "General") ? 'selected' : '' ?>>General</option>
                                    <option value="OBC/MOBC" <?= ($caste === "OBC/MOBC") ? 'selected' : '' ?>>OBC/MOBC</option>
                                    <option value="ST" <?= ($caste === "ST") ? 'selected' : '' ?>>ST</option>
                                    <option value="SC" <?= ($caste === "SC") ? 'selected' : '' ?>>SC</option>
                                    </select>
                                    <?= form_error("caste") ?>
                            </div>
                            <div class="col-md-2">
                                    <label>Religion <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="religion" id="religion" >
                                    <option value="">Please Select</option>
                                    <option value="Buddhism" <?= ($religion === "Buddhism") ? 'selected' : '' ?>>Buddhism</option>
                                    <option value="Christianity" <?= ($religion === "Christianity") ? 'selected' : '' ?>>Christianity</option>
                                    <option value="Hinduism" <?= ($religion === "Hinduism") ? 'selected' : '' ?>>Hinduism</option>
                                    <option value="Islam" <?= ($religion === "Islam") ? 'selected' : '' ?>>Islam</option>
                                    <option value="Jainism" <?= ($religion === "Jainism") ? 'selected' : '' ?>>Jainism</option>
                                    <option value="Judaism" <?= ($religion === "Judaism") ? 'selected' : '' ?>>Judaism</option>
                                    <option value="Sikhism" <?= ($religion === "Sikhism") ? 'selected' : '' ?>>Sikhism</option>
                                    <option value="Zoroastrianism" <?= ($religion === "Zoroastrianism") ? 'selected' : '' ?>>Zoroastrianism</option>
                                    <option value="Others/Not Specified" <?= ($religion === "Others/Not Specified") ? 'selected' : '' ?>>Others/Not Specified</option>
                                    </select>
                                    <?= form_error("religion") ?>
                            </div>
                            <div class="col-md-2">
                                    <label>Nationality </label>
                                    <input type="text" class="form-control" name="nationality" id="nationality" value="Indian" autocomplete="off" readonly/>
                                    <?= form_error("nationality") ?>
                            </div>
                        </div>
                        <div class="row form-group org_div">
                            <div class="col-md-6" id="org_name_div">
                                <label>Organization Name (Firm/Company) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="org_name" id="org_name" value="<?= $org_name ?? ''?>" maxlength="255" autocomplete="off"/>
                                <?= form_error("org_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Authorised Signatory / Power of attorney (Firm/Proprietorship/Company) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="owner_director_name" id="owner_director_name" value="<?= $owner_director_name ?? ''?>" maxlength="255" autocomplete="off"/>
                                <?= form_error("owner_director_name") ?>
                            </div>

                        </div>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Mobile No. <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control ro" name="mobile" id="mobile" value="<?= $mobile_no?>" maxlength="10" autocomplete="off" readonly/>
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Land line No. (with STD code) </label>
                                <input type="text" class="form-control" name="land_line" id="land_line" value="<?= $land_line ?? '' ?>" maxlength="15" autocomplete="off"/>
                                <?= form_error("land_line") ?>
                            </div>
                            <div class="col-md-4">
                                <label>E-mail <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" id="email" value="<?= $email ?? '' ?>" maxlength="100" autocomplete="off"/>
                                <?= form_error("email") ?>
                            </div>
                            
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>PAN Card <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pan_card" id="pan_card" value="<?= $pan_card ?? ''?>" maxlength="10" autocomplete="off"/>
                                <?= form_error("pan_card") ?>
                            </div>
                            <div class="col-md-4">
                                <label>GST No.(e.g. 29AAICA3918J1ZE) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="gst_no" id="gst_no" value="<?= $gst_no ?? ''?>" maxlength="15" autocomplete="off"/>
                                <?= form_error("gst_no") ?>
                            </div>

                        </div>

                        <div class="row form-group org_div">
                            <div class="col-md-6">
                                    <label>Date of execution of deed <?php if($applicant_type != "Proprietorship") {?><span id="deed" class="text-danger">*</span><?php } ?> </label>
                                    <input type="text" class="form-control dp" name="date_of_deed" id="date_of_deed" value="<?= $date_of_deed ?? ''?>" autocomplete="off" placeholder="dd-mm-yyyy"/>
                                    <?= form_error("date_of_deed") ?>
                            </div>
                            <div class="col-md-6">
                                    <label>Validity expiry date <?php if($applicant_type != "Proprietorship") {?><span id="deed_val" class="text-danger">*</span><?php } ?> </label>
                                    <input type="text" class="form-control dp1" name="date_of_validity" id="date_of_validity" value="<?= $date_of_validity ?? ''?>" autocomplete="off" placeholder="dd-mm-yyyy"/>
                                    <?= form_error("date_of_validity") ?>
                            </div>
                        </div>
                        
                        <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h6">Bank Details </legend>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Bank Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?= $bank_name ?? ''?>" maxlength="30" autocomplete="off"/>
                                <?= form_error("bank_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Branch Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="branch_name" id="branch_name" value="<?= $branch_name ?? ''?>" maxlength="30" autocomplete="off"/>
                                <?= form_error("branch_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>IFSC Code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="<?= $ifsc_code ?? ''?>" maxlength="15" autocomplete="off"/>
                                <?= form_error("ifsc_code") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Account No. <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="account_no" id="account_no" value="<?= $account_no ?? ''?>" maxlength="20" autocomplete="off"/>
                                <?= form_error("account_no") ?>
                            </div>
                            
                        </div>
                        </fieldset>
                        <div class="row form-group">
                            <div class="col-md-4">
                                    <label>Date from which working as contractor </label>
                                    <input type="text" class="form-control dp" name="date_of_working_contractor" id="date_of_working_contractor" value="<?= $date_of_working_contractor ?? ''?>" autocomplete="off" placeholder="dd-mm-yyyy"/>
                                    <?= form_error("date_of_working_contractor") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Previous Registration No. </label>
                                <input type="text" class="form-control" name="prev_reg_no" id="prev_reg_no" value="<?= $prev_reg_no ?? ''?>" maxlength="100" autocomplete="off"/>
                                <?= form_error("prev_reg_no") ?>
                            </div>
                        </div>

                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/upgradation_of_contractors') ?>">
                        <i class="fa fa-angle-double-left"></i> Search Again
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>

<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?= $this->lang->line('otp_verification') ?>
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    <i class="fa fa-check"></i><?= $this->lang->line('VERIFY') ?>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-trash-o"></i><?= $this->lang->line('CANCEL') ?>
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->