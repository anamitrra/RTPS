
<h5 class="text-center bg-success p-2 rounded text-light">Application for Non-encumbrance Certificate / বোজমুক্ত প্ৰমাণ পত্ৰৰ বাবে আবেদন</h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Name of the Applicant/আবেদনকাৰীৰ নাম </label>
                <div class="pb-2"><?= $attribute_details->applicant_name ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Fathers Name/পিতাৰ নাম </label>
                <div class="pb-2"><?= $attribute_details->father_name ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Mobile Number/দূৰভাষ (ভ্ৰাম্যীন)</label>
                <div class="pb-2"><?= $attribute_details->mobile_number ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">E-Mail/ই-মেইল</label>
                <div class="pb-2"><?= $attribute_details->{'e-mail'} ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Address of the applicant/আবেদনকাৰীৰ ঠিকনা</label>
                <div class="pb-2"><?= $attribute_details->address_of_the_applicant ?? 'N/A' ?></div>
            </div>    
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Select Office for Application Submission/কাৰ্য্যালয় বাছনি কৰক, য'ত আবেদন দাখিল হ'ব</h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Office/কাৰ্য্যালয়</label>
                <div class="pb-2"><?= $attribute_details->office ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Particulars of land/মাটি'ৰ বিৱৰণ</h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Select Circle/চক্ৰ বাছনি কৰক</label>
                <div class="pb-2"><?= $attribute_details->circle ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Select Village/গাওঁ বাছনি কৰক</label>
                <div class="pb-2"><?= $attribute_details->village ?? 'N/A' ?></div>
            </div>
            <?php
                if(property_exists($attribute_details,'detailed_land_schedule')){
            ?>
                    <div class="col-12">
                        <label>Detailed land schedule/মাটি'ৰ তপশীল</label>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Land_area</th>
                                <th>Daag_number</th>
                                <th>Patta_number</th>
                                <th>Patta_type</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($attribute_details->detailed_land_schedule as $key => $land) { ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td><?= $land->land_area ?? 'N/A' ?></td>
                                    <td><?= $land->daag_number ?? 'N/A' ?></td>
                                    <td><?= $land->patta_number ?? 'N/A' ?></td>
                                    <td><?= $land->patta_type ?? 'N/A' ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Other Details/অন্য তথ্য</h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Records to be searched from/কেতিয়াৰ পৰা তথ্য লাগে</label>
                <div class="pb-2"><?= $attribute_details->records_to_be_searched_from ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Records to be searched to/কেতিয়ালৈ তথ্য লাগে </label>
                <div class="pb-2"><?= $attribute_details->records_to_be_searched_to ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Reference no of the land document to be uploaded/মাটি'ৰ তথ্যৰ নাম</label>
                <div class="pb-2"><?= $attribute_details->land_document_reference_no ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Year on which the document is registered/তথ্য নিবন্ধিত বছৰ</label>
                <div class="pb-2"><?= $attribute_details->document_registered_year ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Mode of service delivery/সেৱা প্ৰদানৰ প্ৰকাৰ</h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Select desired mode/প্ৰকাৰ বাছনি কৰক</label>
                <div class="pb-2"><?= $attribute_details->desired_mode ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>
