<style>
    .btn-red{
        background-color: #dc3545;
    }
</style>
<script src="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>"></script>

<div class="container">
    <div class="card mt-2 border-0 shadow">
        <div class="card-header bg-info">
            <h5 class="card-title text-center text-white font-weight-bold m-0">Appeal Acknowledgement</h5>
        </div>
        <div class="card-body m-0">
            <div class="d-flex justify-content-start">
                 <button class="btn btn-sm btn-outline-primary font-weight-bold mx-1" data-toggle="modal" data-target="#appealDetailsViewModal" > View Appeal Details</button>
                <button class="btn btn-sm btn-outline-info font-weight-bold mx-1" data-toggle="modal" data-target="#rtpsApplicationViewModal" >View RTPS Application Details</button>

            </div><br/>
            <div id="ack" class="alert alert-success" role="alert">
                <h4 class="alert-heading text-center mb-2">Thank you for applying!</h4>
                <?php
                    if(property_exists($appealApplication,'appeal_expiry_status') && $appealApplication->appeal_expiry_status){
                ?>
                    <p>Your appeal is under consideration of the Appellate authority as you have submitted the appeal after 30 days from the date of expiry of the stipulated RTPS Delivery timeline or 30 days from the rejection of the application. Once the appeal is approved you will be notified via registered mobile number and email ID.</p>
                    <p>Your Application reference No. is <b><?=$appealApplication->appl_ref_no?></b> and your Appeal Request ID is <b><?=$appealApplication->appeal_id?></b></p>
                <?php
                    }else{
                ?>
                <p>You have successfully appealed for the RTPS Application reference No. <b><?=$appealApplication->appl_ref_no?></b> and your Appeal ID is <b><?=$appealApplication->appeal_id?></b>.
                <?php
                    }

                ?>
                </p>
                <p> Date of Appeal : <b><?=format_mongo_date($appealApplication->created_at,'d-m-Y g:i a')?></b> </p>
                <ol class="font-weight-normal">
                    <li>Appellant Name : <?=$appealApplication->applicant_name?></li>
                    <li>Appellant Address : <?=$appealApplication->address_of_the_person?></li>
                    <li>Appellant Contact Number : <?=$appealApplication->contact_number?></li>
                    <li>Appellant Email ID : <?= $appealApplication->email_id !== '' ? $appealApplication->email_id : 'NA' ?></li>
                    <li>Service Name : <?=$appealApplication->name_of_service?></li>
                    <li>Department Name : <?=$appealApplication->application_details->initiated_data->department_name?></li>
                    <li>DPS Name : <?=$appealApplication->dps_details->name?></li>
                    <li>DPS Designation : <?=$appealApplication->dps_details->designation?></li>
                </ol>
                <hr>
                <p class="mb-0">Note : </p>
               <ol class="font-weight-normal">
                   <li>(a) The intimation for hearing will be given in due course of time.</li>
                   <li>(b) Appellant can track the appeal status using the ARTPS no. & Appeal number at the
                       Portal or by calling at Call Center Number <span title="test data">(18009854856723)</span></li>
               </ol>
                <p class="mb-0">Please download this acknowledgement for your future reference.</p>
            </div>
            <div id="elementH"></div>
            <button type="button" class="btn btn-block btn-outline-success mb-2 font-weight-bold" onclick="downloadAck()">
                Download Acknowledgement
            </button>
            <a href="<?=base_url('appeal/logout')?>" class="btn btn-block btn-outline-secondary mb-2 font-weight-bold">
                Close
            </a>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="appealDetailsViewModal" tabindex="-1" role="dialog" aria-labelledby="appealDetailsViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appealDetailsViewModalLongTitle">Appeal Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplication]);
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rtpsApplicationViewModal" tabindex="-1" role="dialog" aria-labelledby="rtpsApplicationViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rtpsApplicationViewModalLongTitle">RTPS Application Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("applications/view_application", array('data' => $applicationData->initiated_data, 'execution_data' => $applicationData->execution_data));
                ?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('assets/plugins/jspdf/jspdf.min.js')?>"></script>
<script>
    function downloadAck() {
        var doc = new jsPDF();
        var printableHtml = $('#ack').html();
        printableHtml = printableHtml.replace(' <hr>\n' +
            '                <p class="mb-0">Please download this acknowledgement for your future reference.</p>','');
        printableHtml = printableHtml.replace('Thank you for applying!','Appeal Acknowledgement');

        var specialElementHandlers = {
            '#elementH': function (element, renderer) {
                return true;
            }
        };
        doc.fromHTML(printableHtml, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        // Save the PDF
        doc.save('acknowledgement.pdf');
    }

</script>