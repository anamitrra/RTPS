<h5 class="text-center bg-success p-2 rounded text-light">ISSUE OF RECORDS OF RIGHT (ROR / JAMABANDI) / জমাবন্দীৰ বাবে আবেদন</h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Application for Issuance of RoR/জমাবন্দীৰ বাবে আবেদন</h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Applicant Name ( আবেদনকাৰীৰ নাম )</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->applicant_name ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Father's Name/পিতাৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->father_name ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Spouse Name/স্বামী/পত্নীৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->spouse_name ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Mobile Number/দূৰভাস নং:</label>
                <div class="pb-2"><?= $attribute_details->mobile_number ?></div>
            </div>
           
            <div class="col-4">
                <label class="mb-0">E-Mail/ই-মেইল</label>
                <div class="pb-2"><?= $attribute_details->{'e-mail'} ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Pan Number/PAN নং</label>
                <div class="pb-2"><?= $attribute_details->pan_number ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Location Details/ঠিকনা</h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Address 1/ঠিকনা-১</label>
                <div class="pb-2"><?= $attribute_details->address_1 ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Address 2/ঠিকনা-২</label>
                <div class="pb-2"><?= $attribute_details->address_2 ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">state/ৰাজ্য</label>
                <div class="pb-2"><?= $attribute_details->state ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">District/জিলা</label>
                <div class="pb-2"><?= $attribute_details->location_district ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Circle/ৰাজহচক্ৰ </label>
                <div class="pb-2"><?= $attribute_details->location_circle ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Revenue Village / Town/ৰাজহ গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_details->revenue_village_town ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Patta No/পট্টা নং</label>
                <div class="pb-2"><?= $attribute_details->patta_no ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Jamabandi/RoR to be issued in the name of/কাৰ নামত জমাবন্দী প্ৰদান কৰা হ'ব</label>
                <div class="pb-2"><?= $attribute_details->jamabandi_ror_issued_in_name ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Select where Application will be submitted/আবেদন কত দাখিল হব</h6>
        <div class="row">
            <label class="col-3">District/জিলা</label>
            <div class="col-3"><?= $attribute_details->application_district ?? 'N/A' ?></div>

            <label class="col-3">Circle/চক্ৰ</label>
            <div class="col-3"><?= $attribute_details->application_circle ?? 'N/A' ?></div>   
        </div>
    </div>
</div>