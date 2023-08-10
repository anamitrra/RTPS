
<?php
// pre($appealApplication);


// foreach ($appealApplication as $x){

    // if($appealData->process_users->role_slug === 'DPS'){
    //     $dps = $appealData->process_users_data;
    // }

    // pre($x->appeal_id);


// }
// return;

// pre($active_user_info);

// return;

$this->lang->load('appeal');



foreach ($appealApplication as $appealData){
    if($appealData->process_users->role_slug === 'DPS'){
        $dps = $appealData->process_users_data;
    }
    if($appealData->process_users->role_slug === 'AA'){
        $appalete = $appealData->process_users_data;
    }
    if($appealData->process_users->role_slug === 'RA'){
        $review = $appealData->process_users_data;
    }
}

?><div class="row">
    <div class="col-md-2">
        <label for="applicationNumber">Appeal ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->appeal_id?></p>
    </div>
    <?php
    if(isset($appealApplication[0]->appl_ref_no)){
    ?>
        <div class="col-md-2">
            <label for="applicationNumber">Application Number</label>
        </div>
        <div class="col-md-4">
            <p class="font-weight-normal"><?=$appealApplication[0]->appl_ref_no?></p>
        </div>
    <?php
    }?>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="nameOfThePerson">Name of the person</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->applicant_name?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label for="addressOfThePerson"><u>Address of the Appellant</u></label>
    </div>
    <div class="col-md-2">
        <label for="village">Village</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->village?></p>
    </div>
    <div class="col-md-2">
        <label for="district">District</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->district?></p>
    </div>
    <div class="col-md-2">
        <label for="policeStation">Police Station</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->police_station?></p>
    </div>
    <div class="col-md-2">
        <label for="circle">Circle</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->circle??'NA'?></p>
    </div>
    <div class="col-md-2">
        <label for="postOffice">Post Office</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->post_office?></p>
    </div>
    <div class="col-md-2">
        <label for="postOffice">PIN</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->pincode?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="contactNumber">Contact Number</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->contact_number?></p>
    </div>
    <div class="col-md-2">
        <label for="additionalContactNumber">Additional Contact Number</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->additional_contact_number?> <?php if($appealApplication[0]->contact_in_addition_contact_number){ echo '<span class="badge badge-success">Active</span>';}?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="emailId">Email ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->email_id?></p>
    </div>
    <div class="col-md-2">
        <label for="additionalEmailId">Additional Email ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal">
            <?=$appealApplication[0]->additional_email_id?>
            <?php if($appealApplication[0]->contact_in_addition_email){ echo '<span class="badge badge-success">Active</span>';}?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="nameOfService">Name of service</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->name_of_service?></p>
    </div>
    <div class="col-md-2">
        <label for="nameOfPFC"> Name of the PFC/CSC <br> <small>(if application was submitted through PFC/CSC)</small></label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->name_of_PFC?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="dateOfApplication">Date of application</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?= format_mongo_date($appealApplication[0]->date_of_application,'d-m-Y') //date('d/m/Y') ?></p>
    </div>
    <div class="col-md-2">
        <label for="dateOfAppeal">Date of appeal</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=format_mongo_date($appealApplication[0]->created_at,'d-m-Y')?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="groundForAppeal">Ground for Appeal</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->ground_for_appeal?></p>
    </div>
    <div class="col-md-2">
        <label for="appealDescription">Appeal Description</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication[0]->appeal_description?></p>
    </div>
</div>
<div class="row">

    <div class="col-md-2">
        <label for="appealDescription">Appeal Relief sought for</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=empty($appealApplication[0]->relief_sought_for)? "":$appealApplication[0]->relief_sought_for?></p>
    </div>
    <?php
        if(($this->session->has_userdata('role') && $this->session->userdata('role')->slug === 'DPS') || !$this->session->has_userdata('role')){
    ?>
            <?php
            if(isset($appealApplication[0]->date_of_hearing)){
                ?>

                <div class="col-md-2">
                    <label for="appealDescription">Date of Hearing</label>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-normal"><?php echo format_mongo_date($appealApplication[0]->date_of_hearing, 'd-m-Y')?></p>
                </div>
                <?php
            }elseif (isset($appealApplication[0]->tentative_hearing_date)){
                ?>

                <div class="col-md-2">
                    <label for="appealDescription">Tentative Hearing Date</label>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-normal"><?php echo format_mongo_date($appealApplication[0]->tentative_hearing_date, 'd-m-Y');?></p>
                </div>
                <?php
            }
            ?>
    <?php
        }else{
    ?>
    <div class="col-md-2">
        <label for="appealDescription">Date of Hearing</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=format_mongo_date($appealApplication[0]->tentative_hearing_date, 'd-m-Y');?></p>
    </div>
            <?php
        }
    ?>
</div>
<?php
    if(isset($finalVerdictInfoForPreviousAppeal) && !empty((array)$finalVerdictInfoForPreviousAppeal)){
?>
        <div class="row">
            <div class="col-md-2">
                <label for="appealDescription">Date of Final Verdict</label>
            </div>
            <div class="col-md-4">
                <?=format_mongo_date($finalVerdictInfoForPreviousAppeal->created_at,'d-m-Y')?>
            </div>
            <div class="col-md-2">
                <label for="appealDescription">Description of Final Verdict</label>
            </div>
            <div class="col-md-4">
                <?=$finalVerdictInfoForPreviousAppeal->message?>
            </div>
        </div>
<?php
    }
?>

<div class="row">
    <div class="col-md-2">
        <label for="appealDescription">Attachments</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal">

            <?php
            if (property_exists($appealApplication[0], 'documents') && is_array($appealApplication[0]->documents)) {
                $List = implode(', ', array_map(function ($v) {
                    return base_url($v);
                }, $appealApplication[0]->documents));
                echo '<a href="#!" data-links="' . $List . '" class="btn btn-sm btn-outline-info" id="appealAttachments'.$appealApplication[0]->appeal_id.'"> View </a>';
            } ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="DPSName">DPS Name</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$dps->name?></p>
    </div>
    <div class="col-md-2">
        <label for="DPSPosition">DPS Position</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$dps->designation?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="appellateAuthorityName">Appellate Authority Name</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appalete->name?></p>
    </div>
    <div class="col-md-2">
        <label for="appellateAuthorityDesignation">Appellate Authority Designation</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appalete->designation?></p>
    </div>

    <div class="col-md-2">
        <label for="appellateAuthorityDesignation">Active Official User</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><b>Email:</b> <?=$active_user_info['email']?></p>
        <p class="font-weight-normal"><b>Name:</b> <?=$active_user_info['name']?></p>
        <p class="font-weight-normal"><b>Mobile:</b> <?=$active_user_info['mobile']?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <label for="appellateAuthorityName">Submission Location</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?= !empty($appealApplication[0]->location_details) ? $appealApplication[0]->location_details->location_name :"" ?></p>
    </div>
   
</div>
<?php
    if(isset($review)){
?>

        <div class="row">
            <div class="col-md-2">
                <label><?=$this->lang->line('reviewing_authority_label')?> Name</label>
            </div>
            <div class="col-md-4">
                <p class="font-weight-normal"><?=$review->name?></p>
            </div>
            <div class="col-md-2">
                <label><?=$this->lang->line('reviewing_authority_label')?> Designation</label>
            </div>
            <div class="col-md-4">
                <p class="font-weight-normal"><?=$review->designation?></p>
            </div>
        </div>


<?php } ?>

<div class="modal fade" id="appealDocModal<?=$appealApplication[0]->appeal_id?>" style="z-index: 9999!important;" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents attached with Appeal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="appealDocModalTBody<?=$appealApplication[0]->appeal_id?>">
                <?php
                $counter = 0;
                if (count((array)$appealApplication[0]->documents) && is_array($appealApplication[0]->documents)) {
                    foreach ($appealApplication[0]->documents as $doc) {
                        echo '<a href="' . base_url($doc) . '" class="btn btn-sm btn-outline-warning" target="_blank">View Attachment ' . ($counter + 1) . '</a><br/>';
                    }
                }
                ?>
            </div>
<!--            <div class="modal-footer justify-content-between">-->
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    const appealAttachments<?=$appealApplication[0]->appeal_id?>Ref = $('#appealAttachments<?=$appealApplication[0]->appeal_id?>')
    const appealDocModal<?=$appealApplication[0]->appeal_id?>Ref = $('#appealDocModal<?=$appealApplication[0]->appeal_id?>')
    const appealDocModalTBody<?=$appealApplication[0]->appeal_id?>Ref = $('#appealDocModalTBody<?=$appealApplication[0]->appeal_id?>')
    $(function(){
        appealAttachments<?=$appealApplication[0]->appeal_id?>Ref.click(function() {
            var links = $(this).attr('data-links');
            var docs = links.split(',');
            // console.log(docs);
            appealDocModalTBody<?=$appealApplication[0]->appeal_id?>Ref.empty();
            $(docs).each(function(key, val) {
                appealDocModalTBody<?=$appealApplication[0]->appeal_id?>Ref.append('<a href = "'+val+'" class = "btn btn-sm btn-outline-warning mt-3"    target = "_blank" > View Attachment '+(key+1)+' </a><br/>');
                appealDocModal<?=$appealApplication[0]->appeal_id?>Ref.modal('show');
            });
        })
    })
</script>
