<?php
    $old = $this->session->flashdata('old');
?>
<link href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css"/>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet"
      type="text/css"/>
<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">

<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>
<style>

    .btn-red {
        background-color: #dc3545;
    }

    #kvFileinputModal embed {
        height: 500px !important;
    }
</style>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Apply For Appeal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Apply for appeal</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card my-2 border-0 shadow">
                <div class="card-header bg-info">
                    <h4 class="text-white font-weight-bold text-center">Appeal Application Form</h4>
                </div>
                <form id="appealForm" method="POST" onsubmit="return checkFile()" action="<?= base_url('appeal/no-ref-process') ?>" enctype="multipart/form-data">
                    <div class="card-body">
                        <?php $applicationsLink = ($this->session->userdata('role') && in_array($this->session->userdata('role')->slug,['DA','PFC']) )? 'appeal/applications' : 'appeal/myapplications';?>
                        <a href="<?=base_url($applicationsLink)?>" class="btn btn-block btn-outline-success mt-2">Click here to view the existing applications in the system</a>
                        <?php
                        if ($this->session->flashdata('error') != null) {
                            ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applRefNo">Application Reference Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="applRefNo"
                                           name="applRefNo" placeholder="Enter Application Reference Number" required>
                                </div>
                            </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label for="">Do you have a Application Reference Number? <span class="text-danger">*</span></label>-->
<!--                            <br>-->
<!--                            <div class="form-check form-check-inline">-->
<!--                                <input class="form-check-input" type="radio" name="hasApplicationRefNum"-->
<!--                                       id="hasApplicationRefNumYes" value="yes" required data-parsley-errors-container="#hasRefErrorContainer">-->
<!--                                <label class="form-check-label" for="hasApplicationRefNumYes">Yes</label>-->
<!--                            </div>-->
<!--                            <div class="form-check form-check-inline">-->
<!--                                <input class="form-check-input" type="radio" name="hasApplicationRefNum"-->
<!--                                       id="hasApplicationRefNumNo" value="no" required data-parsley-errors-container="#hasRefErrorContainer">-->
<!--                                <label class="form-check-label" for="hasApplicationRefNumNo">No</label>-->
<!--                            </div>-->
<!--                            <div id="hasRefErrorContainer"></div>-->
<!--                        </div>-->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameOfThePerson">Name of the Appellant <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nameOfThePerson"
                                           name="nameOfThePerson" placeholder="Enter appellant name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="gender">Gender <span class="text-danger">*</span> </label>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale"
                                           value="male" required
                                           data-parsley-errors-container="#gender_error_container">
                                    <label class="form-check-label" for="genderMale">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                           value="female" required
                                           data-parsley-errors-container="#gender_error_container">
                                    <label class="form-check-label" for="genderFemale">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="genderTrans"
                                           value="others" required
                                           data-parsley-errors-container="#gender_error_container">
                                    <label class="form-check-label" for="genderTrans">Others</label>
                                </div>
                                <br>
                                <small class="text-danger" id="gender_error_container"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactNumber">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                           value="<?=$this->session->userdata('mobile') ?? ''?>" placeholder="Enter appellant contact number" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" <?=($this->session->userdata('role') !== null && in_array($this->session->userdata('role')->slug,['PFC','DA'])) ?: 'readonly'?> required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-inline mb-2">
                                        <label for="additionalContactNumber">Additional Contact Number</label>
                                        <input type="checkbox" name="contactInAdditionContactNumber"
                                               id="contactInAdditionContactNumber"
                                               class="ml-2" <?= isset($old['contactInAdditionContactNumber']) ? 'checked' : '' ?>>
                                        <label for="contactInAdditionContactNumber" class="ml-2">
                                            <small class="font-weight-normal">Contact in this number</small>
                                        </label>
                                        <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                           title="OTP and Notifications will be sent to this number"><i
                                                    class="fa fa-info-circle text-secondary"></i></a>
                                    </div>
                                    <input type="text" class="form-control" id="additionalContactNumber"
                                           name="additionalContactNumber" minlength="10" maxlength="10"
                                           pattern="^[6-9]\d{9}$"
                                           placeholder="Enter additional contact number if applicable"
                                           value="<?= $old['additionalContactNumber'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emailId">Email ID <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="emailId" name="emailId" placeholder="Enter appellant email id " required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-inline mb-2">
                                        <label for="additionalEmailId">Additional Email ID</label>
                                        <input type="checkbox" class="ml-2" name="contactInAdditionEmail"
                                               id="contactInAdditionEmail" <?= isset($old['contactInAdditionEmail']) ? 'checked' : '' ?>>
                                        <label for="contactInAdditionEmail" class="ml-2">
                                            <small class="font-weight-normal">contact in this email</small>
                                        </label>
                                        <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                           title="Notifications will be sent to this number"><i
                                                    class="fa fa-info-circle text-secondary"></i></a>
                                    </div>
                                    <input type="email" class="form-control" id="additionalEmailId"
                                           name="additionalEmailId"
                                           placeholder="Enter additional email id if applicable"
                                           value="<?= $old['additionalEmailId'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="addressOfThePerson">Address of the Appellant <span
                                                class="text-danger">*</span></label>
                                    <!-- <textarea type="text" class="form-control" id="addressOfThePerson"
                                              name="addressOfThePerson"
                                              required
                                              placeholder="Enter applicant's address"><?= $old['addressOfThePerson'] ?? '' ?></textarea> -->
                                </div>
                            </div>
                        </div>
                        <?php 
                            $appealApplicationPrevious=array();
                            $this->load->view('includes/address',$appealApplicationPrevious);
                        ?>

<input type="hidden" id="appeal_exp_date" name="appeal_exp_date" >

<div class="row">
                            <div class="col-md-6">
                                <label for="service">Service <span class="text-danger">*</span> </label>
                                <select class="select2 form-control" name="service" id="service" onchange="getServiceName()" required>
                                    <option value="">Choose One</option>
                                    <?php
                                    foreach ($serviceList as $service) {
                                        ?>
                                        <option data-service_timeline="<?=$service->service_timeline?>" value="<?= $service->{'_id'}->{'$id'} ?>"><?= $service->service_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="location">Location <span class="text-danger">*</span> </label>
                               <div id="location_block">
                               <select class="select2 form-control" name="location" id="location" required>
                                    <option value="">Choose One</option>
                                   
                                </select>
                               </div>
                            </div>
</div>

                        <div class="row py-3">

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nameOfService">Name of service <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nameOfService" name="nameOfService" placeholder="Enter name of service here" required>
                                </div>
                            </div> -->
                            <input type="hidden" class="form-control" id="nameOfService" name="nameOfService" placeholder="Enter name of service here" >
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfApplication">Date of application <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" id="dateOfApplication"
                                           name="dateOfApplication" placeholder="dd-mm-yyyy" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfAppeal">Date of appeal</label>
                                    <input type="text" class="form-control" id="dateOfAppeal" name="dateOfAppeal"
                                           value="<?= date('d-m-Y') ?>" readonly>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nameOfPFC">Name of the PFC/CSC <small>(if application was submitted through
                                            PFC/CSC)</small></label>
                                    <input type="text" class="form-control" id="nameOfPFC" name="nameOfPFC">
                                </div>
                            </div>
                        </div>
                        <div id="reasonOfDelayBlock">
                        </div>
                        <div class="row">
                          <div class="col-12">
                              <div class="form-group">
                                  <label for="groundForAppeal">Ground for Appeal <span
                                              class="text-danger">*</span></label>
                                  <select class="form-control select2" name="groundForAppeal" id="groundForAppeal" required>
                                      <option value="">Choose One</option>
                                      <option value="Denial of Service">Denial of Service</option>
                                      
                                      <!-- <option value="Delivery of Service beyond stipulated time limit">Delivery of Service beyond stipulated time limit</option> -->

                                      <option value="Delay in Service Delivery">Delay in Service Delivery</option>

                                      <option value="Refusal to Accept Application">Refusal to Accept Application</option>
                                  </select>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="appealDescription">Appeal Description<span
                                                class="text-danger">*</span></label>
                                    <textarea class="form-control" id="appealDescription" name="appealDescription"
                                              required placeholder="Write you appeal description here" maxlength="1500"></textarea>
                                    <small class="text-info">Maximum 1500 characters are allowed in description (<span  class="char-remaining">1500</span> Remaining )</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="reliefSoughtFor">Relief sought for <span
                                                class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reliefSoughtFor" name="reliefSoughtFor"
                                              required placeholder="Write Relief sought for here" maxlength="1500"></textarea>
                                    <small class="text-info">Maximum 1500 characters are allowed in description (<span  class="char-remaining">1500</span> Remaining )</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="appeal_attachments">Appeal Attachment (e.g. acknowledgement or receipt)<span class="text-danger">*</span></label>
                                    <div class="file-loading">
                                        <input id="appeal_attachments" name="appeal_attachments[]" type="file"
                                               multiple data-parsley-errors-container="#appealAttachmentErrorMsg">
                                    </div>
                                    <div id="appealAttachmentErrorMsg" style="color:red"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="is_attachement_uploaded"/>
                        <!-- <div class="row">
                            <div class="col-md-6">
                                <label for="service">Service <span class="text-danger">*</span> </label>
                                <select class="select2 form-control" name="service" id="service" onchange="getServiceName()" required>
                                    <option value="">Choose One</option>
                                    <?php
                                    foreach ($serviceList as $service) {
                                        ?>
                                        <option data-service_timeline="<?=$service->service_timeline?>" value="<?= $service->{'_id'}->{'$id'} ?>"><?= $service->service_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="location">Location <span class="text-danger">*</span> </label>
                               <div id="location_block">
                               <select class="select2 form-control" name="location" id="location" required>
                                    <option value="">Choose One</option>
                                   
                                </select>
                               </div>
                            </div>
                        </div> -->
                        <div id="officials-info-box" class="d-none">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="DPSName">DPS Name</label>
                                    <input type="text" class="form-control" id="DPSName" name="DPSName" value=""
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="DPSPosition">DPS Position</label>
                                    <input type="text" class="form-control" id="DPSPosition" name="DPSPosition"
                                           value="" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="appellateAuthorityName">Appellate Authority Name</label>
                                    <input type="text" class="form-control" id="appellateAuthorityName"
                                           name="appellateAuthorityName" value="" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="appellateAuthorityDesignation">Appellate Authority
                                        Designation</label>
                                    <input type="text" class="form-control" id="appellateAuthorityDesignation"
                                           name="appellateAuthorityDesignation" value=""
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-left">
                            <button type="submit" id="saveAndPreview" class="btn btn-outline-success font-weight-bold">
                                Submit Appeal
                            </button>
                            <a href="<?= base_url("appeal/userarea") ?>" class="btn btn-outline-warning font-weight-bold ml-2">Close</a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->session->unset_userdata("appeal_attachments");
?>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
    const appealAttachmentsRef = $('#appeal_attachments');
    const appealFormRef = $('#appealForm');
    var datepickerRef = $('.datepicker');
    // var hasApplicationRefNum = $('input[name="hasApplicationRefNum"]');
    var serviceRef = $('#service');
    var locationRef = $('#location');
    var dpsIdRef = $('#dps_id');
    var DPSNameRef = $('#DPSName');
    var DPSPositionRef = $('#DPSPosition');
    var appellateIdRef = $('#appellate_id');
    var appellateAuthorityNameRef = $('#appellateAuthorityName');
    var appellateAuthorityDesignationRef = $('#appellateAuthorityDesignation');
    var officialInfoBoxRef = $('#officials-info-box');
    var appealDescriptionRef = $('#appealDescription');
    var reliefSoughtForRef = $('#reliefSoughtFor');
    const fetchOfficialInfoUrl = "<?=base_url('appeal/fetch-official/(:service)/(:location)')?>";
    const fetchLocationByService = "<?=base_url('appeal/ams/get_locations_by_service')?>";

    $(function () {
        datepickerRef.datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
        });
        appealFormRef.parsley();
        // $('.select2').select2();
        //hasApplicationRefNum.click(function () {
        //    if ($(this).val() === 'yes') {
        //        Swal.fire({'title': 'Loading', 'html': 'Showing available application list.'})
        //        window.location.href = "<?//=base_url('appeal/myapplications')?>//";
        //    }
        //});
        serviceRef.change(fetchOfficial);
        locationRef.change(fetchOfficial);
        appealDescriptionRef.keyup(charRemaining);
        appealDescriptionRef.trigger('keyup');
        reliefSoughtForRef.keyup(charRemaining);
        reliefSoughtForRef.trigger('keyup');
        //appeal attachment File Upload
        appealAttachmentsRef.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 2048,//2mb
            msgSizeTooLarge: 'File "{name}"  exceeds maximum allowed upload size of 2MB. Please retry your upload!',
            minFileCount: 1,
            validateInitialCount: true,
            required: true,
            uploadExtraData: {
                "filename": "appeal_attachments"
            },
            allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function (event, files) {
            appealAttachmentsRef.fileinput("upload");
            $("#is_attachement_uploaded").val("Y");
        })
        .on('fileuploaderror', function(event, data, msg) {
            alert(msg);
        });

        appealFormRef.submit(function(){
          if($("#is_attachement_uploaded").val() ==="Y"){
            $("#appealAttachmentErrorMsg").html("");
            if(appealFormRef.parsley().validate()){
                Swal.fire({
                    html: '<h5>Processing...</h5>',
                    showConfirmButton: false,
                    allowOutsideClick: () => !Swal.isLoading(),
                    onOpen: function() {
                        Swal.showLoading();
                    }
                });
            }
            return  true;
          }else {
            $("#appealAttachmentErrorMsg").html("At least one appeal attachment required.");
            return  false;
          }


        });

        $("#dateOfApplication,#service").on('change',function(){
          let selected_date=$("#dateOfApplication").val(); //dd-mm-yyyy
        //   console.log(selected_date);
          var dateParts = selected_date.split("-");

        //  var timelime_period =  service_timeline;
        let service_timeline= $('#service option:selected').data('service_timeline')


        //  console.log("Service timeline is: ",service_timeline);

          // month is 0-based, that's why we need dataParts[1] - 1
          var selectedDateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
           var today = new Date();

        //    console.log("today",today);

          const diffTime = Math.abs(today - selectedDateObject);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        //   console.log(diffTime + " milliseconds");
        console.log(diffDays + " Days");
        // console.log("Day back ",diffDays)
        console.log("Service timeline is: ",service_timeline) 

        // console.log("Total day to check: ",diffDays + service_timeline + 30 )
        // 12-12-2022 + 45
        // var checkingDate = diffDays + service_timeline + 30
        // console.log("Checking date",checkingDate)

        var checkingDate =  service_timeline + 30

        // console.log("selectedDateObject",selectedDateObject)
        // console.log("checkingDate",checkingDate)


        const newDate = new Date();
        newDate.setDate(selectedDateObject.getDate() + checkingDate);
        const appeal_expiry_date = newDate.toISOString().split('T')[0]
        $("#appeal_exp_date").val(appeal_expiry_date);

        console.log("appeal_expiry_date ",appeal_expiry_date)


        // New
        var newCheckingDate = service_timeline + 30
        console.log("New checking date",newCheckingDate)

        console.log("...........")
        

          if(diffDays > newCheckingDate){
            $('#reasonOfDelayBlock').html(`<div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="reasonOfDelay">Reason of delay<span
                                    class="text-danger">*</span></label></label>
                        <textarea class="form-control" id="reasonOfDelay" name="reasonOfDelay"
                                  required placeholder="Write your reason delay apply" maxlength="1500"></textarea>
                    </div>
                </div>

            </div>`)
            console.log("show delay rease");
          }else {
            $('#reasonOfDelayBlock').html('');
          }
        })

        checkFile=function(){
          if($("#is_attachement_uploaded").val() ==="Y"){
            $("#appealAttachmentErrorMsg").html("");
            return  true;
          }else {
            $("#appealAttachmentErrorMsg").html("At least one appeal attachment required.");
            return  false;
          }

        }
        getServiceName=function(){
          let service=$('#service option:selected').text();
          $("#nameOfService").val(service);
          let service_id=$('#service option:selected').val();
          let service_timeline= $('#service option:selected').data('service_timeline')
         
            // console.log(service_timeline)
          $.get(fetchLocationByService+"/"+service_id, function () {
                Swal.fire({
                    title: 'Please wait',
                    text: 'Locations info ...',
                    showConfirmButton: false
                })
            }).done(function (response) {

                $("#location_block").html(response);
                Swal.close();
             

            }).fail(function () {
                officialInfoBoxRef.addClass('d-none');
                Swal.fire('Failed', 'Unable to process!!! Please try again.', 'error');
            });

        }
    })

    function fetchOfficial() {
        let serviceId = serviceRef.val();
        let locationId = locationRef.val();

        // console.log("Service id is",serviceId);

        if (serviceId.length && locationId.length) {
            let fetchOfficialInfoUrlMod = fetchOfficialInfoUrl.replace('(:service)', serviceId).replace('(:location)', locationId);
            $.get(fetchOfficialInfoUrlMod, function () {
                Swal.fire({
                    title: 'Please wait',
                    text: 'Retrieving DPS and Appellate info ...',
                    showConfirmButton: false
                })
            }).done(function (response) {
                if (response.status) {
                    DPSNameRef.val(response.dps_name);
                    DPSPositionRef.val(response.dps_designation);
                    appellateAuthorityNameRef.val(response.appellate_name);
                    appellateAuthorityDesignationRef.val(response.appellate_designation);
                    officialInfoBoxRef.removeClass('d-none');
                    Swal.close();
                } else {
                    officialInfoBoxRef.addClass('d-none');
                    Swal.fire('Failed', 'No matching officials found.', 'error')
                }

            }).fail(function () {
                officialInfoBoxRef.addClass('d-none');
                Swal.fire('Failed', 'Unable to process!!! Please try again.', 'error');
            });
        }
    }

    const charRemaining = function(){
        let currentLength = $(this).val().length;
        let maxLength     = $(this).attr('maxlength');
        let remaining     = $(this).val().length;
        $(this).parent().find('.char-remaining').text(maxLength - currentLength);
    }
</script>
