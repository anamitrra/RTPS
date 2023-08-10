<?php
    $obj_id = $dbrow->{'_id'}->{'$id'}; 
?>

<style type="text/css">
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

<main class="rtps-container">
    <input type="hidden" id="obj_id" value="<?= $obj_id ?>">
</main>
<script>
 $(window).on('load', function(){
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
    //$(window).load(function(){
        console.log('page loaded');
        $obj_id = $('#obj_id').val();
            let ackLocation='<?=base_url('spservices/applications/acknowledgement/')?>'+'<?=$obj_id?>';
                $.ajax({
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

				var add1 = houseNo +'/'+area+'/'+village_town+'/'+pin;
				var add2 = byLane+'/'+road+'/'+post_office+'/'+police_station+'/'+district;
                                    
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
                                    beforeSend:function(){
                                        $('#loading-image').show();
                                    }, 
                                    //url: "https://www.apdclrms.com/cbs/RestAPI/registerNewConn?subDiv="+999,
                                    url: "https://www.apdclrms.com/cbs/RestAPI/registerNewConn?subDiv="+sub_division,			
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
                                                    "message": msg
                                                },
                                        });                               
                                                Swal.fire({
                                                        title: 'Application submited successfully',
                                                        icon: 'success',
                                                        showCancelButton: false,
                                                        showConfirmButton: false,
                                                        timer:4000
                                                });
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
                                        }else{
                                                $('#loading-image').hide();
                                                Swal.fire({
                                                title:'Something went wrong please try again!',
                                                icon:'error',
                                                showCancelButton: false,
                                                showConfirmButton: false,
                                                timer:4000
                                                });
                                            window.location.replace(ackLocation)
                                        }
                                    },
                                    error:function(){
                                            Swal.fire({
                                                title:'Something went wrong please try again later!',
                                                icon:'error',
                                                showCancelButton: false,
                                                showConfirmButton: false,
                                                timer:4000
                                            });
                                        window.location.replace(ackLocation)
                                    }
                            });                                    
                        }
                });           
   // });
 });
</script>