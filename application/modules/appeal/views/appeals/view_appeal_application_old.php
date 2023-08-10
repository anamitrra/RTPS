<?php 
if(isset($appealApplication->dps_id) && isset($appealApplication->appellate_id) && isset($appealApplication->reviewing_id)){
    $dps= $this->users_model->get_by_doc_id($appealApplication->dps_id);
    $appalete= $this->users_model->get_by_doc_id($appealApplication->appellate_id);
    $review= $this->users_model->get_by_doc_id($appealApplication->reviewing_id);
}else{
    $dps= $appealApplication->dps_details;
    $appalete= $appealApplication->appellate_details;
    $review= $appealApplication->reviewer_details;
}

?><div class="row">
    <div class="col-md-2">
        <label for="applicationNumber">Appeal ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->appeal_id?></p>
    </div>
    <div class="col-md-2">
        <label for="applicationNumber">Application Number</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->appl_ref_no?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="nameOfThePerson">Name of the person</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->applicant_name?></p>
    </div>
    <div class="col-md-2">
        <label for="addressOfThePerson">Address of the person</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->address_of_the_person?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="contactNumber">Contact Number</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->contact_number?></p>
    </div>
    <div class="col-md-2">
        <label for="additionalContactNumber">Additional Contact Number</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->additional_contact_number?> <?php if($appealApplication->contact_in_addition_contact_number){ echo '<span class="badge badge-success">Active</span>';}?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="emailId">Email ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->email_id?></p>
    </div>
    <div class="col-md-2">
        <label for="additionalEmailId">Additional Email ID</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal">
            <?=$appealApplication->additional_email_id?>
            <?php if($appealApplication->contact_in_addition_email){ echo '<span class="badge badge-success">Active</span>';}?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="nameOfService">Name of service</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->name_of_service?></p>
    </div>
    <div class="col-md-2">
        <label for="nameOfPFC"> Name of the PFC/CSC (if application was submitted through PFC/CSC)</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->name_of_PFC ? $appealApplication->name_of_PFC->name : 'Not Available' ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="dateOfApplication">Date of application</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?= format_mongo_date($appealApplication->date_of_application,'d-m-Y') //date('d/m/Y') ?></p>
    </div>
    <div class="col-md-2">
        <label for="dateOfAppeal">Date of appeal</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=format_mongo_date($appealApplication->created_at,'d-m-Y')?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="groundForAppeal">Ground for Appeal</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->ground_for_appeal?></p>
    </div>
    <div class="col-md-2">
        <label for="appealDescription">Appeal Description/ Relief sought for</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal"><?=$appealApplication->appeal_description?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <label for="appealDescription">Attachments</label>
    </div>
    <div class="col-md-4">
        <p class="font-weight-normal">

            <?php
            if (property_exists($appealApplication, 'documents') && is_array($appealApplication->documents)) {
                $List = implode(', ', array_map(function ($v) {
                    return base_url($v);
                }, $appealApplication->documents));
                echo '<a href="#!" data-links="' . $List . '" class="btn btn-sm btn-outline-info" id="appealAttachments'.$appealApplication->appeal_id.'"> View </a>';
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
</div>
<?php
    if(isset($appealApplication->ref_appeal_id)){
?>

        <div class="row">
            <div class="col-md-2">
                <label>Reviewing Authority Name</label>
            </div>
            <div class="col-md-4">
                <p class="font-weight-normal"><?=$review->name?></p>
            </div>
            <div class="col-md-2">
                <label>Reviewing Authority Designation</label>
            </div>
            <div class="col-md-4">
                <p class="font-weight-normal"><?=$review->designation?></p>
            </div>
        </div>


<?php } ?>

<div class="modal fade" id="appealDocModal<?=$appealApplication->appeal_id?>" style="z-index: 9999!important;" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents attached with Appeal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="appealDocModalTBody<?=$appealApplication->appeal_id?>">
                <?php
                $counter = 0;
                if (count((array)$appealApplication->documents) && is_array($appealApplication->documents)) {
                    foreach ($appealApplication->documents as $doc) {
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
    const appealAttachments<?=$appealApplication->appeal_id?>Ref = $('#appealAttachments<?=$appealApplication->appeal_id?>')
    const appealDocModal<?=$appealApplication->appeal_id?>Ref = $('#appealDocModal<?=$appealApplication->appeal_id?>')
    const appealDocModalTBody<?=$appealApplication->appeal_id?>Ref = $('#appealDocModalTBody<?=$appealApplication->appeal_id?>')
    $(function(){
        appealAttachments<?=$appealApplication->appeal_id?>Ref.click(function() {
            var links = $(this).attr('data-links');
            var docs = links.split(',');
            // console.log(docs);
            appealDocModalTBody<?=$appealApplication->appeal_id?>Ref.empty();
            $(docs).each(function(key, val) {
                appealDocModalTBody<?=$appealApplication->appeal_id?>Ref.append('<a href = "'+val+'" class = "btn btn-sm btn-outline-warning mt-3"    target = "_blank" > View Attachment '+(key+1)+' </a><br/>');
                appealDocModal<?=$appealApplication->appeal_id?>Ref.modal('show');
            });
        })
    })
</script>