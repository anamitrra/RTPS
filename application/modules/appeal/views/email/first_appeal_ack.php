<style>
    .text-center{
        text-align: center;
    }
    .box-container{
        border: 1px solid black;
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .box{
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
    }
</style>
<div class="box-container">
   <div class="box">
       <h1 class="text-center">Thank you for applying!</h1>
       <?php
       if(property_exists($appealApplication[0],'appeal_expiry_status') && $appealApplication[0]->appeal_expiry_status){
           ?>
           <p>Your appeal is under consideration of the Appellate authority as you have submitted the appeal after 30 days from the date of expiry of the stipulated RTPS Delivery timeline or 30 days from the rejection of the application. Once the appeal is approved you will be notified via registered mobile number and email ID.</p>
           <p>Your Application reference No. is <b><?=$appealApplication[0]->appl_ref_no?></b> and your Appeal Request ID is <b><?=$appealApplication[0]->appeal_id?></b></p>
           <?php
       }else{
           ?>
           <p>You have successfully appealed for the RTPS Application reference No. <b><?=$appealApplication[0]->appl_ref_no?></b> and your Appeal ID is <b><?=$appealApplication[0]->appeal_id?></b>.
           </p>
           <?php
       }

       ?>
       <p>Date of Appeal : <b><?=format_mongo_date($appealApplication[0]->created_at,'d-m-Y g:i a')?></b></p>
       <ol class="font-weight-normal">
           <li>Appellant Name : <?=$appealApplication[0]->applicant_name?></li>
           <li>Appellant Address : <?=$appealApplication[0]->address_of_the_person?></li>
           <li>Appellant Contact Number : <?=$appealApplication[0]->contact_number?></li>
           <li>Appellant Email ID : <?= $appealApplication[0]->email_id !== '' ? $appealApplication[0]->email_id : 'NA' ?></li>
           <li>Service Name : <?=$appealApplication[0]->name_of_service?></li>
           <li>Department Name : <?=$appealApplication[0]->application_data->initiated_data->department_name?></li>
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
</div>
