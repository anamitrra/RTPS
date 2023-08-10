 <?php
    $obj_id = $dbrow->{'_id'}->{'$id'};  
    $rtps_trans_id = $dbrow->service_data->appl_ref_no;
 
    $district_name = $dbrow->form_data->district_name;
    $sub_division = $dbrow->form_data->sub_division;
    $appl_category = $dbrow->form_data->appl_category;
    $appl_load = $dbrow->form_data->appl_load;
    $no_of_days = $dbrow->form_data->no_of_days;
    $applicant_name = $dbrow->form_data->applicant_name;
    $fathers_name = $dbrow->form_data->fathers_name;
    $applicant_type = $dbrow->form_data->applicant_type;
    $gstn = $dbrow->form_data->gstn;
    $gmc = $dbrow->form_data->gmc;
    $assessment_id = $dbrow->form_data->assessment_id;
    $house_number = $dbrow->form_data->house_number;
    $by_lane = $dbrow->form_data->by_lane;
    $road = $dbrow->form_data->road;
    $area = $dbrow->form_data->area;
    $village_town = $dbrow->form_data->village_town;
    $post_office = $dbrow->form_data->post_office;
    $police_station = $dbrow->form_data->police_station;
    $district = $dbrow->form_data->district;
    $pin = $dbrow->form_data->pin;
    $mobile_number = $dbrow->form_data->mobile_number;
    $e_mail = $dbrow->form_data->e_mail;
    $pan_no = $dbrow->form_data->pan_no;
    $nearest_consumer_no = $dbrow->form_data->nearest_consumer_no ?? '';
    $premise_owner = $dbrow->form_data->premise_owner;
    $distance_pole_30 = $dbrow->form_data->distance_pole_30;
    $electricity_due = $dbrow->form_data->electricity_due;
    $existing_connection = $dbrow->form_data->existing_connection;
    $existing_cons_no = $dbrow->form_data->existing_cons_no ?? '';
    $existing_connected_load = $dbrow->form_data->existing_connected_load ?? '';

    $identity_attach = $dbrow->form_data->identity_attach ?? '';
    $identityFile = $dbrow->form_data->identityFile ?? '';
    $address_attach = $dbrow->form_data->address_attach ?? '';
    $addressFile = $dbrow->form_data->addressFile ?? '';
    $land_attach = $dbrow->form_data->land_attach ?? '';
    $selffAttestedFile = $dbrow->form_data->selffAttestedFile ?? '';
    $testReportFile = $dbrow->form_data->testReportFile ?? '';
    $scannedPhoto = $dbrow->form_data->scannedPhoto ?? '';
    $gmcFile = $dbrow->form_data->gmcFile ?? '';
    $nocFile = $dbrow->form_data->nocFile ?? '';

    $status = $dbrow->service_data->appl_status;
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
#loading-image {
    position:fixed;
    width:100%;
    left:0;right:0;top:0;bottom:0;
    background-color: rgba(255,255,255,0.7);
    z-index:9999;
    display:none;
}

@-webkit-keyframes spin {
	from {-webkit-transform:rotate(0deg);}
	to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
	from {transform:rotate(0deg);}
	to {transform:rotate(360deg);}
}

#loading-image::after {
    content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:40px;height:40px;
    border-style:solid;
    border-color:black;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">   
 
 //convert base64 files to binary
 const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
                const byteCharacters = atob(b64Data);
                const byteArrays = [];

                for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                        const slice = byteCharacters.slice(offset, offset + sliceSize);
                        const byteNumbers = new Array(slice.length);
                        for (let i = 0; i < slice.length; i++) {
                                byteNumbers[i] = slice.charCodeAt(i);
                        }
                        const byteArray = new Uint8Array(byteNumbers);
                        byteArrays.push(byteArray);
                }
                        const blob = new Blob(byteArrays, {type: contentType});
                        return blob;
}

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
        
    $(document).on("click", "#finalSubmitBtn", function(){
            $obj_id = $('#obj_id').val();
            let ackLocation='<?=base_url('spservices/applications/acknowledgement/')?>'+'<?=$obj_id?>';
            let rejectLoc = '<?=base_url('iservices/transactions')?>';
            Swal.fire({
                title: 'Are you sure?',
                text: 'Once you submitted, you will not be able to revert this',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                   $.ajax({
                            beforeSend:function(){
                                    $('#loading-image').show();
                            },        
                            url : '<?= base_url("spservices/apdcl/registration/getDbData/") ?>'+$obj_id,
                            type: 'GET',
                            success:function(result){
                               const data = JSON.parse(result);
                               var obj = data.ObjectId;
                              // Encode the file using the FileReader API
                                const contentType = 'application/pdf,image/jpg,image/jpeg,image/png';
                                const contentType2 = 'image/jpg,image/jpeg,image/png';

                                const identity = data.identityFile;
                                const address = data.addressFile;
                                const self = data.selffAttestedFile;
                                const test = data.testReportFile;
                                const photo = data.scannedPhoto;
                                const gmc = data.gmcFile;
                                const noc = data.nocFile;

                                const blob1 = b64toBlob(identity, contentType);
                                const blob2 = b64toBlob(address, contentType);
                                const blob3 = b64toBlob(self, contentType);
                                const blob4 = b64toBlob(test, contentType);
                                const blob5 = b64toBlob(photo, contentType2);
                                const blob6 = b64toBlob(gmc, contentType);
                                const blob7 = b64toBlob(noc, contentType);

								 	var DBdata = new FormData();
                                    var sub_division = data.sub_division;									
                                    var houseNo = data.house_number;
                                    var byLane = data.by_lane;
                                    var road = data.road;
                                    var area = data.area;
                                    var village_town = data.village_town;
                                    var post_office = data.post_office;
                                    var police_station = data.police_station;
                                    var district = data.district;
                                    var pin = data.pin;

									var add1 = houseNo +','+area+','+village_town+','+pin;
									var add2 = byLane+','+road+','+post_office+','+police_station+','+district;
                                    
                                    DBdata.append('appl_category',data.appl_category);
                                    DBdata.append('appl_load',data.appl_load);                                              
                                    DBdata.append('applicant_name',data.applicant_name);
                                    DBdata.append('applicant_fname',data.fathers_name);
                                    DBdata.append('applicant_add1',add1);
                                    DBdata.append('applicant_add2',add2);
                                    DBdata.append('mobile_no',data.mobile_number);
                                    if(data.e_mail != ""){
                                        DBdata.append('email_id',data.e_mail);
                                    }
                                    if(data.pan_no != ""){
                                        DBdata.append('pan_no',data.pan_no);
                                    }
                                    if(data.gstn != ""){
                                        DBdata.append('gstn',data.gstn);
                                    }
                                    DBdata.append('no_of_days',data.no_of_days);
                                    DBdata.append('under_gmc',data.gmc);
                                    if(data.assessment_id != ""){
                                        DBdata.append('gmc_holding_no',data.assessment_id);		
                                    }							
                                    DBdata.append('applicant_type',data.applicant_type);		
                                    DBdata.append('premise_owner',data.premise_owner);
                                    DBdata.append('distance_pole_30',data.distance_pole_30);
                                    DBdata.append('electricity_due',data.electricity_due);
                                    DBdata.append('existing_connection',data.existing_connection);
                                    if(data.existing_cons_no != ""){
                                        DBdata.append('existing_cons_no',data.existing_cons_no);
                                    }
                                    if(data.existing_connected_load != ""){
                                        DBdata.append('existing_connected_load',data.existing_connected_load);
                                    }                                              
                                    if(data.nearest_consumer_no != ""){
                                        DBdata.append('nearest_cons_no',data.nearest_consumer_no);
                                    }
                                    DBdata.append('identity_attach',data.identity_attach);
                                    DBdata.append('address_attach',data.address_attach);
                                    DBdata.append('land_attach',data.land_attach);
                                    DBdata.append('identityFile',blob1);
                                    DBdata.append('addressFile',blob2);
                                    DBdata.append('selffAttestedFile',blob3);
                                    DBdata.append('testReportFile',blob4);
                                    DBdata.append('scannedPhoto',blob5);
                                    if(data.gmcFile != "")
                                    {                                                   
                                            DBdata.append('gmcFile',blob6);
                                    }
                                    if(data.nocFile != "")
                                    {
                                            DBdata.append('nocFile',blob7);
                                    }
                                    DBdata.append('eodba_txn_no',obj);
                                    DBdata.append('updated_by','RTPS');
                                    
                                    $.ajax({
                                            url: "https://www.apdclrms.com/cbs/RestAPI/registerNewConn?subDiv="+sub_division,
                                            //url: "https://www.apdclrms.com/cbs/RestAPI/registerNewConn?subDiv="+999,			
                                            type: 'POST',
                                            data: DBdata,
                                            processData : false,
                                            contentType : false,
                                            cache: false,
                                            timeout : 1000000,
                                            async : true,
                                            complete:function(){
                                                    $('#loading-image').hide();
                                            },
                                            success:function(response) 
                                            {			
                                                var txnNo = response.txnNo;
                                                var appNo = response.applNo;
                                                var msg = response.message;
                                                  
                                                if(response.message == 'success'){ 
                                                    $.ajax({
                                                            url: '<?= base_url("spservices/apdcl/registration/db_update") ?>',
                                                            type: "POST",
                                                            dataType: "JSON",
                                                            data: {
                                                                "txnNo":txnNo,
                                                                "appNo":appNo,
                                                                "message":msg
                                                            },
                                                    });                               
                                                        Swal.fire({
                                                            title: 'Application submited successfully',
                                                            icon: 'success',
                                                            showConfirmButton: true,
                                                            confirmButtonText: 'OK'
                                                            //timer:10000
                                                        })
                                                        window.location.replace(ackLocation)
                                                }
                                                else if(response.message == 'rejected'){ 
                                                    $.ajax({
                                                            url: '<?= base_url("spservices/apdcl/registration/db_update") ?>',
                                                            type: "POST",
                                                            dataType: "JSON",
                                                            data: {
                                                                "txnNo":txnNo,
                                                                "appNo":appNo,
                                                                "message":msg
                                                            },
                                                    });                               
                                                        $('#loading-image').hide();    
                                                        Swal.fire({
                                                            title: 'Rejected',
                                                            text: 'Your application for new electricity connection is rejected. You have outstanding due in your premises. Please contact your nearest sub-division. Kindly note down your application number :  for reference.',
                                                            icon: 'error',
                                                            showCancelButton: true,
                                                            showConfirmButton: true
                                                        });
                                                        window.location.replace(ackLocation)
                                                }
                                                else{
                                                        $('#loading-image').hide();        
                                                        Swal.fire({
                                                                title:'Something went wrong please try again!',
                                                                icon:'error',
                                                                showCancelButton: true,
                                                                showConfirmButton: true,
                                                                //timer:4000
                                                            });
                                                            window.location.replace(rejectLoc)
                                                }
                                            },
                                            
                                            error:function(){
                                                    Swal.fire({
                                                            title:'Something went wrong please try again later!',
                                                            icon:'error',
                                                            showCancelButton: true,
                                                            showConfirmButton: true,
                                                            //timer:4000
                                                    });
                                                    window.location.replace(rejectLoc)
                                            }
                                        });                                    
                            }
                   });
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container-fluid my-2" style="width:80%">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <h5>Application for new Low Tension connection (APDCL)</h5>
                   <input type="hidden" id="obj_id" id="obj_id" value="<?= $obj_id ?>">
            </div>
            <div class="card-body" style="padding:5px">

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant / আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width: 50%">Name of the Applicant / আবেদনকাৰীৰ নাম: <strong><?=$applicant_name?></strong> </td>
                                <td style="width: 50%">Father's Name / পিতৃৰ নাম: <strong><?=$fathers_name?></strong> </td>
                            </tr> 
                            <tr>
                                <td>Mobile Number / দুৰভাষ (মবাইল): <strong><?= $mobile_number?></strong> </td>
                                <td>Email Id / ইমেইল: <strong><?= $e_mail?></strong> </td>
                            </tr> 
                            <tr>
                                <td>PAN No. / পেন নং: <strong><?= $pan_no?></strong> </td>
                                <td></td>
                            </tr> 
                        </tbody>
                    </table>
                </fieldset>
                <div class="text-center" id="loading-image" style="display:none"></div>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applied to / প্ৰয়োগ কৰা হৈছে </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width: 50%">District / জিলা: <strong><?=$district_name?></strong> </td>
                                <td style="width: 50%">Sub-division / মহকুমা: <strong><?=$sub_division?></strong> </td>
                            </tr>
                            <tr>
                                <td>Applied Category / প্ৰয়োগ কৰা শ্ৰেণী: <strong><?=$appl_category?></strong> </td>
                                <td>Applied Load(KW) / প্ৰয়োগ কৰা লোড: <strong><?=$appl_load?></strong> </td>
                            </tr>
                            <tr>
                                <td>No. of Days / দিনৰ সংখ্যা: <strong><?=$no_of_days?></strong> </td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td>Type of the Applicant / আবেদনকাৰীৰ প্ৰকাৰ: 
                                    <?php if($applicant_type == 'prePaidConsumer') { ?><strong>Pre Paid Consumer</strong> <?php }
                                    else if($applicant_type == 'postPaidConsumer') { ?><strong>Post Paid Consumer</strong> <?php } ?>                                    
                                </td>
                                <td>GSTN(If available) / জি.এছ.টি.এন.(যদি উপলব্ধ): <strong><?=$gstn?></strong> </td>
                            </tr>
                            <tr>
                                <td>Wheather the applicant is under GMC<br>আবেদনকাৰী জিএমচিৰ অধীনত আছে নেকি: 
                                        <?php if($gmc == 'N') { ?><strong>No</strong> <?php }
                                        else if($gmc == 'Y') { ?><strong>Yes</strong> <?php } ?>
                                </td>
                                <td>Assessment Id / মূল্যায়ন আইডি: <strong><?=$assessment_id?></strong> </td>
                            </tr>
                
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Desired Connection Details / বিচৰা সংযোগৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                    <td style="width: 50%">House No. / ঘৰৰ নং: <strong><?=$house_number?></strong> </td>
                                    <td style="width: 50%">By lane / উপ-পথ: <strong><?=$by_lane?></strong> </td>
                            </tr>
                            <tr>
                                    <td>Road / পথ: <strong><?=$road?></strong> </td>
                                    <td>Area / অঞ্চল: <strong><?=$area?></strong> </td>
                            </tr> 
                            <tr>
                                    <td>Village-Town / গাওঁ-নগৰ: <strong><?=$village_town?></strong> </td>
                                    <td>Post Office / ডাকঘৰ: <strong><?=$post_office?></strong> </td>
                            </tr>     
                            <tr>
                                    <td>Police Station / থানা: <strong><?=$police_station?></strong> </td>
                                    <td>District / জিলা: <strong><?=$district?></strong></td>
                            </tr>
                            <tr>
                                    <td>PIN Code / পিন নং: <strong><?=$pin?></strong> </td>
                                    <td><?php if(strlen($nearest_consumer_no)){ ?>
                                        Nearest Consumer No. / নিকটতম উপভোক্তা নং: <strong><?= $nearest_consumer_no?></strong> 
                                        <?php } ?>
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Premises Details / চৌহদৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                    <td style="width: 50%">Whether the applicant is the owner of the premises<br>আবেদনকাৰী চৌহদৰ গৰাকী নে : 
                                            <?php if($premise_owner == 'N') { ?><strong>No</strong> <?php }
                                            else if($premise_owner == 'Y') { ?><strong>Yes</strong> <?php } ?>
                                    </td>
                                    <td style="width: 50%">Whether distance of premises from pole is less than 30m<br>মেৰুৰ পৰা চৌহদৰ দূৰত্ব 30 মিটাৰতকৈ কম হয় নে : 
                                            <?php if($distance_pole_30 == 'N') { ?><strong>No</strong> <?php }
                                            else if($distance_pole_30 == 'Y') { ?><strong>Yes</strong> <?php } ?>
                                    </td>
                            </tr>
                            <tr>
                                    <td>Is there any electricity due outstanding in premises<br>চৌহদত কোনো বিদ্যুৎ বকেয়া আছে নেকি : 
                                            <?php if($electricity_due == 'N') { ?><strong>No</strong> <?php }
                                            else if($electricity_due == 'Y') { ?><strong>Yes</strong> <?php } ?>
                                    </td>
                                    <td>If there is any existing connection of the premises<br>চৌহদৰ কোনো বিদ্যমান সংযোগ আছে নেকি : 
                                            <?php if($existing_connection == 'N') { ?><strong>No</strong> <?php }
                                            else if($existing_connection == 'Y') { ?><strong>Yes</strong> <?php } ?>
                                    </td>
                            </tr>
                            <tr>
                                    <td><?php if(strlen($existing_cons_no)){ ?>
                                            Consumer Number / উপভোক্তা নং: <strong><?= $existing_cons_no?></strong> 
                                        <?php } ?>
                                    </td>
                                    <td><?php if(strlen($existing_connected_load)){ ?>
                                        Consumer Load / উপভোক্তা লোড: <strong><?= $existing_connected_load?></strong>
                                        <?php } ?>
                                    </td>
                            </tr> 
                            
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">ATTACHED ENCLOSURE(S) </legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50%;">Type of Enclosure</th>
                                <th class="text-center" style="width: 25%;">Enclosure Document</th>
                                <th class="text-center" style="width: 25%;">File/Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Proof of Identity</td>
                                <?php if($identity_attach == 'voter') { ?><td class="text-center">Voter ID Card</td> <?php }
                                 else if($identity_attach == 'passport') { ?><td class="text-center">Passport</td> <?php }
                                 else if($identity_attach == 'driving') { ?><td class="text-center">Driving License</td> <?php }
                                 else if($identity_attach == 'ration') { ?><td class="text-center">Ration Card</td> <?php }
                                 else if($identity_attach == 'bpl') { ?><td class="text-center">BPL Card</td> <?php }
                                 else if($identity_attach == 'pan') { ?><td class="text-center">PAN Card</td> <?php }
                                 else if($identity_attach == 'aadhar') { ?><td class="text-center">AADHAR Card</td> <?php }
                                 else if($identity_attach == 'photoId') { ?><td class="text-center">Photo identity Certificate from local competent authority</td> <?php }?>
                                <td><?php if(strlen($identityFile)){ ?>
                                        <a href="<?=base_url($identityFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php } ?>
                                </td>
                                <input type="hidden" value="<?= $identityFile ?>" id="identityFile">
                            </tr>
                            <tr>
                                <td>Residential Address Proof</td>
                                <?php if($address_attach == 'passbook') { ?><td class="text-center">Bank Passbook</td> <?php }
                                 else if($address_attach == 'telephone') { ?><td class="text-center">Telephone Bill</td> <?php }
                                 else if($address_attach == 'driving') { ?><td class="text-center">Driving License</td> <?php }
                                 else if($address_attach == 'ration') { ?><td class="text-center">Ration Card</td> <?php }
                                 else if($address_attach == 'bpl') { ?><td class="text-center">BPL Card</td> <?php }
                                 else if($address_attach == 'existingBill') { ?><td class="text-center">Existing Electricity Bill</td> <?php }
                                 else if($address_attach == 'aadhar') { ?><td class="text-center">AADHAR Card</td> <?php }
                                 else if($address_attach == 'photoId') { ?><td class="text-center">Photo identity Certificate from local competent authority</td> <?php }?>
                                <td><?php if(strlen($addressFile)){ ?>
                                        <a href="<?=base_url($addressFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Proof of the legal occupation of the premises</td>
                                <?php if($land_attach == 'holdingNo') { ?><td class="text-center">Holding No</td> <?php }
                                 else if($land_attach == 'leaseAgreement') { ?><td class="text-center">Lease Agreement</td> <?php }
                                 else if($land_attach == 'rentAgreement') { ?><td class="text-center">Rent Agreement</td> <?php }
                                 else if($land_attach == 'sale') { ?><td class="text-center">Sale Deed</td> <?php  }?>
                                <td><?php if(strlen($selffAttestedFile)){ ?>
                                        <a href="<?=base_url($selffAttestedFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Test report from authorized electrical contractor / supervisor</td>
                                <td></td>
                                <td><?php if(strlen($testReportFile)){ ?>
                                        <a href="<?=base_url($testReportFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Latest Passport Size Photo of the Applicant</td>
                                <td></td>
                                <td><?php if(strlen($scannedPhoto)){ ?>
                                        <a href="<?=base_url($scannedPhoto)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if(strlen($gmcFile)){ ?>
                            <tr>
                                <td>Affidavit/NOC/Self declaration by the consumer if not having Assessment Id</td>
                                <td style="font-weight:bold"></td>
                                <td>
                                        <a href="<?=base_url($gmcFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($nocFile)){ ?>
                            <tr>
                                <td>Affidavit from land owner indicating no objection to give connection to the applicant and indemnity bond (Optional) from the owner, if the applicant is not the owner of the land</td>
                                <td style="font-weight:bold"></td>
                                <td>
                                        <a href="<?=base_url($nocFile)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/apdcl/registration/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                    <a class="btn btn-warning" id="" href="<?= base_url('spservices/apdcl/payment/verify/'.$obj_id) ?>">
                        <i class="fa fa-save"></i> Make Payment
                    </a>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>                    
            </div>
        </div>
    </div>
</main>