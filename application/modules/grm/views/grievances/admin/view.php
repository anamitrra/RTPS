
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Grievances</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url('grm/dashboard')?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url('grm/my-grm')?>">My Grievances</a></li>
                        <li class="breadcrumb-item"><a href="#">Grievances Details</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <?php
                if(!empty($grievanceStatus)){
            ?>

                    <div class="card">
                        <div class="card-header p-0" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                    Grievance Status
                                </button>

                                <button class="float-right btn btn-sm btn-outline-success mt-1 mr-3"> Refresh Status</button>
                            </h2>
                        </div>
                        <div class="card-body">

                            <div id="status-box" class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td width="25%">Registration Number</td>
                                        <td width="25%"><span id="resRegistrationNumber"><?=$grievanceStatus->RegistrationNumber?></span></td>
                                        <td width="25%">Name</td>
                                        <td><span id="resName"><?=$grievanceStatus->Name?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Date Of Receipt</td>
                                        <td><span id="resDateOfReceipt"><?=format_mongo_date($grievanceStatus->DateOfReceipt,'d-m-Y')?></span></td>
                                        <td>Receiving Organization</td>
                                        <td><span id="resReceivingOrg"><?=$grievanceStatus->ReceivingOrg?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Grievance Details</td>
                                        <td><span id="resGrievanceDetails"><?=$grievanceStatus->GrievanceDetails?></span></td>
                                        <td>Grievance Document</td>
                                        <td><span id="resGrievanceDocument"><?=isset($grievanceStatus->grievance_doc_name) ? '<a class="btn btn-sm btn-outline-warning font-weight-bold" href="'.base_url('storage/uploads/grievance/attachments/'.$grievanceStatus->grievance_doc_name).'" target="_blank">View</a>' : 'NA'?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Current Status</td>
                                        <td><span id="resCurrentStatus"><?=$grievanceStatus->CurrentStatus?></span></td>
                                        <td>Date Of Action</td>
                                        <td><span id="resDateOfAction"><?=format_mongo_date($grievanceStatus->DateOfAction,'d-m-Y')?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Reason</td>
                                        <td><span id="resReason"><?=$grievanceStatus->Reason ?? 'NA'?></span></td>
                                        <td>Remark</td>
                                        <td><span id="resRemark"><?=$grievanceStatus->Remark?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Reply Document</td>
                                        <td><span id="resReplyDocument"><?=isset($grievanceStatus->reply_doc_name) ? '<a href="'.base_url('storage/uploads/grievance/reply_document/'.$grievanceStatus->reply_doc_name).'" class="btn btn-sm btn-outline-warning" target="_blank">View</a>' : 'NA' ?></span></td>
                                        <td>Rating</td>
                                        <td><span id="resRating"><?=$grievanceStatus->Rating ?? 'NA'?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Rating Text</td>
                                        <td><span id="resRatingText"><?=$grievanceStatus->RatingText ?? 'NA'?></span></td>
                                        <td>To Org</td>
                                        <td><span id="resToOrg"><?=$grievanceStatus->ToOrg ?? 'NA'?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Officer Name</td>
                                        <td><span id="resOfficerName"><?=$grievanceStatus->OfficerName?></span></td>
                                        <td>Officer Designation</td>
                                        <td><span id="resOfficerDesignation"><?=$grievanceStatus->OfficerDesignation?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Officer Address</td>
                                        <td colspan="3"><span id="resOfficerAddress"><?=$grievanceStatus->OfficerAddress?></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

            <?php
                }
            ?>

            <div class="card">
                <div class="card-header p-0" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                            Grievance Details
                        </button>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Name</label>
                        </div>
                        <div class="col-md-4">
                            <p><?=$grievanceDetails->Name?></p>
                        </div>
                        <div class="col-md-2">
                            <label for="">Gender</label>
                        </div>
                        <div class="col-md-4">
                            <?php
                                switch ($grievanceDetails->Gender){
                                    case 'M':
                                        $gender = 'Male';
                                        break;
                                    case 'F':
                                        $gender = 'Female';
                                        break;
                                    case 'O':
                                        $gender = 'Others';
                                        break;
                                    default :
                                        $gender = '--';
                                        break;
                                }
                            ?>
                            <p><?=$gender?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Country</label>
                        </div>
                        <div class="col-md-4">
                            <p><?=MY_COUNTRY['country_name']?></p>
                        </div>
                        <div class="col-md-2">
                            <label for="">State</label>
                        </div>
                        <div class="col-md-4">
                            <p><?=MY_STATE['state_name']?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">District</label>
                        </div>
                        <div class="col-md-4">
                            <p><?=MY_COUNTRY['country_name']?></p>
                        </div>
                        <div class="col-md-2">
                            <label for="">State</label>
                        </div>
                        <div class="col-md-4">
                            <p><?=MY_STATE['state_name']?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>