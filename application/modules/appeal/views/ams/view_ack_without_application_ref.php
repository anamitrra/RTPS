<style>
    .btn-red{
        background-color: #dc3545;
    }
</style>
<script src="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>"></script>

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
            <div class="card mt-2 border-0 shadow">
                <div class="card-header bg-info">
                    <h5 class="card-title text-center text-white font-weight-bold m-0">Appeal Acknowledgement</h5>
                </div>
                <div class="card-body m-0">
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-sm btn-outline-primary font-weight-bold mx-1" data-toggle="modal" data-target="#appealDetailsViewModal" > View Appeal Details</button>

                    </div><br/>
                    <div id="ack" class="alert alert-light" role="alert">
                        <h4 class="alert-heading text-center mb-2">Thank you for applying!</h4>
                        <?php
                        if(property_exists($appealApplication[0],'appeal_expiry_status') && $appealApplication[0]->appeal_expiry_status){
                            ?>
                            <p>Your appeal is under consideration of the Appellate authority as you have submitted the appeal after 30 days from the date of expiry of the stipulated RTPS Delivery timeline or 30 days from the rejection of the application. Once the appeal is approved you will be notified via registered mobile number and email ID.</p>
                            <p>your Appeal ID is <b><?=$appealApplication[0]->appeal_id?></b></p>
                            <?php
                        }else{
                        ?>
                        <p>You have successfully appealed and your Appeal ID is <b><?=$appealApplication[0]->appeal_id?></b>.
                            <?php
                            }

                            ?>
                        </p>
                        <p> Date of Appeal : <b><?=format_mongo_date($appealApplication[0]->created_at,'d-m-Y g:i a')?></b> </p>
                        <ol class="font-weight-normal">
                            <li>Appellant Name : <?=$appealApplication[0]->applicant_name?></li>
                            <li>Appellant Address : <?=$appealApplication[0]->address_of_the_person?></li>
                            <li>Appellant Contact Number : <?=$appealApplication[0]->contact_number?></li>
                            <li>Appellant Email ID : <?= $appealApplication[0]->email_id !== '' ? $appealApplication[0]->email_id : 'NA' ?></li>
                            <li>Service Name : <?=$appealApplication[0]->name_of_service?></li>
                            <?php
                            $dpsName = '';
                            $dpsDesignation = '';
                            foreach ($appealApplication as $appealData){
                                if($appealData->process_users->role_slug === 'DPS'){
                                    $dpsName = $appealData->process_users_data->name;
                                    $dpsDesignation = $appealData->process_users_data->designation;
                                }
                            }
                            ?>
                            <li>DPS Name : <?=$dpsName?></li>
                            <li>DPS Designation : <?=$dpsDesignation?></li>
                            <li>Tentative Hearing Date : <?=empty($appealApplication[0]->tentative_hearing_date) ?:  format_mongo_date($appealApplication[0]->tentative_hearing_date, 'd-m-Y'); ?></li>
                                (*However this is subject to confirmation from the designated Official)
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
                    <a href="<?=base_url('appeal/dashboard')?>" class="btn btn-block btn-outline-secondary mb-2 font-weight-bold">
                        Back to Dashboard
                    </a>
                </div>
            </div>

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
