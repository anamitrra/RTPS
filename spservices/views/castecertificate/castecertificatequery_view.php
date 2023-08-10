<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
$apiURL = "https://localhost/castapis/"; //For testing

//$startYear = date('Y') - 10;
//$endYear =  date('Y');
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

    $language = $dbrow->form_data->fillUpLanguage;

    $application_for = $dbrow->form_data->application_for;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $pan_no = isset($dbrow->form_data->pan_no) ? $dbrow->form_data->pan_no : "";
    $aadhar_no = isset($dbrow->form_data->aadhar_no) ? $dbrow->form_data->aadhar_no : "";
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email) ? $dbrow->form_data->email : "";
    $epic_no = isset($dbrow->form_data->epic_no) ? $dbrow->form_data->epic_no : "";;
    $dob = $dbrow->form_data->dob;
    $caste = $dbrow->form_data->caste;
    $subcaste = $dbrow->form_data->subcaste;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    //$husband_name = $dbrow->form_data->husband_name;
    // $religion = $dbrow->form_data->religion;

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

    $photo_type = isset($dbrow->form_data->photo_type) ? $dbrow->form_data->photo_type : "";
    $photo = isset($dbrow->form_data->photo) ? $dbrow->form_data->photo : "";
    $date_of_birth_type = isset($dbrow->form_data->date_of_birth_type) ? $dbrow->form_data->date_of_birth_type : "";
    $date_of_birth = isset($dbrow->form_data->date_of_birth) ? $dbrow->form_data->date_of_birth : "";
    $proof_of_residence_type = isset($dbrow->form_data->proof_of_residence_type) ? $dbrow->form_data->proof_of_residence_type : "";
    $proof_of_residence = isset($dbrow->form_data->proof_of_residence) ? $dbrow->form_data->proof_of_residence : "";
    $caste_certificate_of_father_type = isset($dbrow->form_data->caste_certificate_of_father_type) ? $dbrow->form_data->caste_certificate_of_father_type : "";
    $caste_certificate_of_father = isset($dbrow->form_data->caste_certificate_of_father) ? $dbrow->form_data->caste_certificate_of_father : "";
    $recomendation_certificate_type = isset($dbrow->form_data->recomendation_certificate_type) ? $dbrow->form_data->recomendation_certificate_type : "";
    $recomendation_certificate = isset($dbrow->form_data->recomendation_certificate) ? $dbrow->form_data->recomendation_certificate : "";
    $others_type = isset($dbrow->form_data->others_type) ? $dbrow->form_data->others_type : "";
    $others = isset($dbrow->form_data->others) ? $dbrow->form_data->others : "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type) ? $dbrow->form_data->soft_copy_type : "";
    $soft_copy = isset($dbrow->form_data->soft_copy) ? $dbrow->form_data->soft_copy : "";

    $photo_type_frm = set_value("photo_type");
    $date_of_birth_type_frm = set_value("date_of_birth_type");
    $proof_of_residence_type_frm = set_value("proof_of_residence_type");
    $caste_certificate_of_father_type_frm = set_value("caste_certificate_of_father_type");
    $recomendation_certificate_type_frm = set_value("recomendation_certificate_type");
    $others_type_frm = set_value("others_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $photo_frm = $uploadedFiles['photo_old'] ?? null;
    $date_of_birth_frm = $uploadedFiles['date_of_birth_old'] ?? null;
    $proof_of_residence_frm = $uploadedFiles['proof_of_residence_old'] ?? null;
    $caste_certificate_of_father_frm = $uploadedFiles['caste_certificate_of_father_old'] ?? null;
    $recomendation_certificate_frm = $uploadedFiles['recomendation_certificate_old'] ?? null;
    $others_frm = $uploadedFiles['others_old'] ?? null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

    $photo_type_db = $dbrow->form_data->photo_type ?? null;
    $date_of_birth_type_db = $dbrow->form_data->date_of_birth_type ?? null;
    $proof_of_residence_type_db = $dbrow->form_data->proof_of_residence_type ?? null;
    $caste_certificate_of_father_type_db = $dbrow->form_data->caste_certificate_of_father_type ?? null;
    $recomendation_certificate_type_db = $dbrow->form_data->recomendation_certificate_type ?? null;
    $others_type_db = $dbrow->form_data->others_type ?? null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;
    $photo_db = $dbrow->form_data->photo ?? null;
    $date_of_birth_db = $dbrow->form_data->date_of_birth ?? null;
    $proof_of_residence_db = $dbrow->form_data->proof_of_residence ?? null;
    $caste_certificate_of_father_db = $dbrow->form_data->caste_certificate_of_father ?? null;
    $recomendation_certificate_db = $dbrow->form_data->recomendation_certificate ?? null;
    $others_db = $dbrow->form_data->others ?? null;
    $soft_copy_db = $dbrow->form_data->soft_copy ?? null;

    $photo_type = strlen($photo_type_frm) ? $photo_type_frm : $photo_type_db;
    $date_of_birth_type = strlen($date_of_birth_type_frm) ? $date_of_birth_type_frm : $date_of_birth_type_db;
    $proof_of_residence_type = strlen($proof_of_residence_type_frm) ? $proof_of_residence_type_frm : $proof_of_residence_type_db;
    $caste_certificate_of_father_type = strlen($caste_certificate_of_father_type_frm) ? $caste_certificate_of_father_type_frm : $caste_certificate_of_father_type_db;
    $recomendation_certificate_type = strlen($recomendation_certificate_type_frm) ? $recomendation_certificate_type_frm : $recomendation_certificate_type_db;
    $others_type = strlen($others_type_frm) ? $others_type_frm : $others_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;
    $photo = strlen($photo_frm) ? $photo_frm : $photo_db;
    $date_of_birth = strlen($date_of_birth_frm) ? $date_of_birth_frm : $date_of_birth_db;
    $proof_of_residence = strlen($proof_of_residence_frm) ? $proof_of_residence_frm : $proof_of_residence_db;
    $caste_certificate_of_father = strlen($caste_certificate_of_father_frm) ? $caste_certificate_of_father_frm : $caste_certificate_of_father_db;
    $recomendation_certificate = strlen($recomendation_certificate_frm) ? $recomendation_certificate_frm : $recomendation_certificate_db;
    $others = strlen($others_frm) ? $others_frm : $others_db;
    $soft_copy = strlen($soft_copy_frm) ? $soft_copy_frm : $soft_copy_db;
} //End of if else //End of if else
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

        $('.number_input').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

        $('#aadhar_no').keyup(function() {
            if ($("#aadhar_no").val().length > 12) {
                $("#aadhar_no").val("");
                alert("Please! Enter upto only 12 digit");
            }
        });

        $(document).on("keyup", "#pan_no", function() {
            if ($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit");
            }
        });

        $('.pin_code').keyup(function() {
            if ($(".pin_code").val().length > 6) {
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

        $(document).on("change", "#app_for", function() {
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
        });

        $(document).on("change", "#caste", function() {
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.community_name = selectedVal; //alert(JSON.stringify(myObject));

                $.getJSON("<?= $apiURL ?>subcategory_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                        let selectOption = '';
                        $('.subcaste').empty().append('<option value="">Select a Sub-Caste</option>')
                        $('#check_caste').val("");
                        $.each(data.records, function(key, value) {
                            $('#check_caste').val("Yes");
                            selectOption += '<option value="' + value.sname + '(' + selectedVal + ')">' + value.sname + '</option>';
                        });

                        $('.subcaste').append(selectOption);
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        let selectOption = '';
                        $('.subcaste').empty().append('<option value="">Select a Sub-Caste</option>')
                    });
            }
        });

        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.district_name + '">' + value.district_name + '</option>';
            });
            $('#district').append(selectOption);
        });

        $(document).on("change", "#district", function() {
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.district_id = selectedVal; //alert(JSON.stringify(myObject));
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

        $(document).on("change", "#sub_division", function() {
            let selectedVal = $(this).val();
            if (selectedVal.length) { //alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_id = selectedVal; //alert(JSON.stringify(myObject));
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

        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Once you submitted, you won't able to revert this";
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
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });

        var photo = parseInt(<?= strlen($photo) ? 1 : 0 ?>);
        $("#photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: photo ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

        var dateOfBirth = parseInt(<?= strlen($date_of_birth) ? 1 : 0 ?>);
        $("#date_of_birth").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: dateOfBirth ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var proofOfResidence = parseInt(<?= strlen($proof_of_residence) ? 1 : 0 ?>);
        $("#proof_of_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: proofOfResidence ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#caste_certificate_of_father").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#recomendation_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#others").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/castecertificate/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <input id="check_caste" name="check_caste" value="" type="hidden" />
            <input name="photo_old" value="<?= $photo ?>" type="hidden" />
            <input name="caste_certificate_of_father_old" value="<?= $caste_certificate_of_father ?>" type="hidden" />
            <input name="recomendation_certificate_old" value="<?= $recomendation_certificate ?>" type="hidden" />
            <input name="proof_of_residence_old" value="<?= $proof_of_residence ?>" type="hidden" />
            <input name="others_old" value="<?= $others ?>" type="hidden" />
            <input name="date_of_birth_old" value="<?= $date_of_birth ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?= $service_name ?>
                </div>
                <div class="card-body" style="padding:5px">
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
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
                    <?php }
                    if ($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <?= (end($dbrow->processing_history)->remarks) ?? '' ?>
                                </div>
                            </div>
                            <span style="float:right; font-size: 12px">
                                Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                            </span>
                        </fieldset>
                    <?php } //End of if 
                    ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Language of the certificate /প্ৰমাণপত্ৰৰ ভাষা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Language/ ভাষা <span class="text-danger">*</span> </label>
                                <select name="language" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="English" <?= ($language === "English") ? 'selected' : '' ?>>English/ ইংৰাজী</option>
                                    <option value="Assamese" <?= ($language === "Assamese") ? 'selected' : '' ?>>Assamese/ অসমীয়া</option>
                                    <option value="Bodo" <?= ($language === "Bodo") ? 'selected' : '' ?>>Bodo/ বডো</option>
                                    <option value="Bengali" <?= ($language === "Bengali") ? 'selected' : '' ?>>Bengali/ বাংলা</option>
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
                                <select name="application_for" class="form-control" id="app_for" disabled>
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("application_for") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of the Applicant/আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" disabled />
                                <?= form_error("applicant_name") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($applicant_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" <?= (strlen($mobile) == 10) ? 'readonly' : '' ?> disabled />
                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?= $pan_no ?>" maxlength="10" type="text" disabled />
                                <?= form_error("pan_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>EPIC No./ ইপিআইচি নম্বৰ </label>
                                <input type="text" class="form-control" name="epic_no" id="epic_no" value="<?= $epic_no ?>" maxlength="255" disabled />
                                <?= form_error("epic_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ<span class="text-danger">*</span> </label>
                                <input type="dob" class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="255" disabled />
                                <?= form_error("dob") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" type="text" id="aadhar_no" disabled />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label>Father's Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother's Name/মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?= $mother_name ?>" maxlength="255" disabled />
                                <?= form_error("mother_name") ?>
                            </div>
                            <!-- <div class="col-md-6">
                                <label>Religion/ ধৰ্ম<span class="text-danger">*</span> </label>
                                <select name="religion" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Hindusim" <?= ($religion === "Hindusim") ? 'selected' : '' ?>>Hindusim</option>
                                    <option value="Islam" <?= ($religion === "Islam") ? 'selected' : '' ?>>Islam</option>
                                    <option value="Budhisim" <?= ($religion === "Budhisim") ? 'selected' : '' ?>>Budhisim</option>
                                    <option value="Christan" <?= ($religion === "Christan") ? 'selected' : '' ?>>Christan</option>
                                    <option value="Other" <?= ($religion === "Other") ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?= form_error("religion") ?>
                            </div> -->

                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label> Caste/Tribe/Community/ জাতি/জনজাতি/সম্প্ৰদায়<span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control" id="caste" disabled>
                                    <option value="<?= $caste ?>">
                                        <?= strlen($caste) ? $caste : "Caste/Tribe/Community" ?>
                                    </option>
                                    <?= form_error("caste") ?>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Caste/ উপ-জাতি<span class="text-danger">*</span> </label>
                                <select name="subcaste" class="form-control subcaste" disabled>
                                    <option value="<?= $subcaste ?>">
                                        <?= strlen($subcaste) ? $subcaste : "Caste/Tribe/Community" ?>
                                    </option>
                                    <?= form_error("subcaste") ?>
                                </select>
                                <?= form_error("subcaste") ?>
                            </div>
                        </div>

                        <!-- <div class="row form-group">

                            <div class="col-md-6"></div>
                        </div> -->
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address of the Applicant/ আবেদনকাৰীৰ ঠিকনা</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1/ ঠিকনা ৰেখা ১ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line_1" value="<?= $address_line_1 ?>" maxlength="100" disabled />
                                <?= form_error("address_line_1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনা ৰেখা ২ </label>
                                <input type="text" class="form-control" name="address_line_2" value="<?= $address_line_2 ?>" maxlength="100" disabled />
                                <?= form_error("address_line_2") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No/ ঘৰ নং<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="house_no" value="<?= $house_no ?>" maxlength="100" disabled />
                                <?= form_error("house_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control" disabled>
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control" id="district" disabled>
                                    <option value="<?= $district ?>">
                                        <?= strlen($district) ? $district : "Select District" ?>
                                    </option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা<span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control" id="sub_division" disabled>
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
                                <select name="circle_office" id="circle_office" class="form-control" disabled>
                                    <option value="<?= $circle_office ?>">
                                        <?= strlen($circle_office) ? $circle_office : "Circle Office" ?>
                                    </option>
                                </select>
                                <?= form_error("circle_office") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mouza/ মৌজা<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mouza" value="<?= $mouza ?>" maxlength="100" disabled />
                                <?= form_error("mouza") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village/ Town/ গাওঁ/চহৰ<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="village_town" value="<?= $village_town ?>" maxlength="100" disabled />
                                <?= form_error("village_town") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station/ আৰক্ষী থানা<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="police_station" value="<?= $police_station ?>" maxlength="100" disabled />
                                <?= form_error("police_station") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Post Office/ ডাকঘৰ<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="post_office" value="<?= $post_office ?>" maxlength="100" disabled />
                                <?= form_error("post_office") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিন কোড<span class="text-danger">*</span></label>
                                <input type="text" class="form-control pin_code" name="pin_code" value="<?= $pin_code ?>" maxlength="6" disabled />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            <li>2. Applicant's photo should be in JPEG format.</li>
                            <li>২. আবেদনকাৰীৰ ফটো jpeg formatত হ’ব লাগিব।</li>
                        </ul>
                    </fieldset>

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
                                            <td>Applicant's Photo <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant Photo" <?= ($photo_type === 'Applicant Photo') ? 'selected' : '' ?>>Applicant Photo</option>
                                                </select>
                                                <?= form_error("photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input name="photo" id="photo" type="file" />
                                                </div>
                                                <?php if (strlen($photo)) { ?>
                                                    <a href="<?= base_url($photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Date of Birth(One of Birth Certificate/Aadhar Card/PAN/Admit Card issued by any recognized Board of Applicant<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="date_of_birth_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Birth Certificate" <?= ($date_of_birth_type === 'Birth Certificate') ? 'selected' : '' ?>>Birth Certificate</option>
                                                    <option value="Aadhar Card" <?= ($date_of_birth_type === 'Aadhar Card') ? 'selected' : '' ?>>Aadhar Card</option>
                                                    <option value="PAN Card" <?= ($date_of_birth_type === 'PAN Card') ? 'selected' : '' ?>>PAN Card</option>
                                                    <option value="Admit Card issued by any recognized Board of Applicant" <?= ($date_of_birth_type === 'Admit Card issued by any recognized Board of Applicant') ? 'selected' : '' ?>>Admit Card issued by any recognized Board of Applicant</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="date_of_birth" name="date_of_birth" type="file" />
                                                </div>
                                                <?php if (strlen($date_of_birth)) { ?>
                                                    <a href="<?= base_url($date_of_birth) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Residence(One of Permanent Resident Certificate/Aadhar Card/EPIC/Land Document/Electricity Bill,Ration Card of Applicant or Parent<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_of_residence_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Resident Certificate" <?= ($proof_of_residence_type === 'Permanent Resident Certificate') ? 'selected' : '' ?>>Permanent Resident Certificate</option>
                                                    <option value="Aadhar Card" <?= ($proof_of_residence_type === 'Aadhar Card') ? 'selected' : '' ?>>Aadhar Card</option>
                                                    <option value="EPIC" <?= ($proof_of_residence_type === 'EPIC') ? 'selected' : '' ?>>EPIC</option>
                                                    <option value="Land Document" <?= ($proof_of_residence_type === 'Land Document') ? 'selected' : '' ?>>Land Document</option>
                                                    <option value="Electricity Bill,Ration Card of Applicant or Parent" <?= ($proof_of_residence_type === 'Electricity Bill,Ration Card of Applicant or Parent') ? 'selected' : '' ?>>Electricity Bill,Ration Card of Applicant or Parent</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_of_residence" name="proof_of_residence" type="file" />
                                                </div>
                                                <?php if (strlen($proof_of_residence)) { ?>
                                                    <a href="<?= base_url($proof_of_residence) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Caste certificate of father
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="caste_certificate_of_father_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Caste Certificate of Father" <?= ($caste_certificate_of_father_type === 'Caste Certificate of Father') ? 'selected' : '' ?>>Caste Certificate of Father</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="caste_certificate_of_father" name="caste_certificate_of_father" type="file" />
                                                </div>
                                                <?php if (strlen($caste_certificate_of_father)) { ?>
                                                    <a href="<?= base_url($caste_certificate_of_father) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Recommendation of authorized caste/tribe/community organization notified by State Government/ Existing caste certificate
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="recomendation_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Recommendation of authorized caste/tribe/community organization notified by State Government" <?= ($recomendation_certificate_type === 'Recommendation of authorized caste/tribe/community organization notified by State Government') ? 'selected' : '' ?>>Recommendation of authorized caste/tribe/community organization notified by State Government</option>
                                                    <option value="Existing caste certificate" <?= ($recomendation_certificate_type === 'Existing caste certificate') ? 'selected' : '' ?>>Existing caste certificate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="recomendation_certificate" name="recomendation_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($recomendation_certificate)) { ?>
                                                    <a href="<?= base_url($recomendation_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Any other document(Voter List,Affidavit,Existing Caste Certificate etc)</td>
                                            <td>
                                                <select name="others_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other supporting document" <?= ($others_type === 'Other supporting document') ? 'selected' : '' ?>>Other supporting document </option>
                                                </select>
                                                <?= form_error("others_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="others" name="others" type="file" />
                                                </div>
                                                <?php if (strlen($others)) { ?>
                                                    <a href="<?= base_url($others) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <?php if($this->slug == 'userrr') {?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?= ($soft_copy_type === 'Soft copy of the applicant form') ? 'selected' : '' ?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($soft_copy)) { ?>
                                                        <a href="<?= base_url($soft_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($dbrow->processing_history)) {
                                    foreach ($dbrow->processing_history as $key => $rows) {
                                        $query_attachment = $rows->query_attachment ?? ''; ?>
                                        <tr>
                                            <td><?= sprintf("%02d", $key + 1) ?></td>
                                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?></td>
                                            <td><?= $rows->action_taken ?></td>
                                            <td><?= $rows->remarks ?></td>
                                        </tr>
                                <?php } //End of foreach()
                                } //End of if else 
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>