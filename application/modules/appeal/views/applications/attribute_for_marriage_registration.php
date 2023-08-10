
<h5 class="text-center bg-success p-2 rounded text-light">Apply for Marriage Registration / বিবাহ পঞ্জীয়নৰ বাবে আবেদন </h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Marriage type/বিবাহৰ প্ৰকাৰ </label>
                <div class="pb-2"><?= $attribute_details->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Name of the Applicant/আবেদনকাৰীৰ নাম </label>
                <div class="pb-2"><?= $attribute_details->applicant_name ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">E-Mail/ই-মেইল</label>
                <div class="pb-2"><?= $attribute_details->{'e-mail'} ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Mobile Number/দূৰভাষ (ভ্ৰাম্যীন)</label>
                <div class="pb-2"><?= $attribute_details->mobile_number ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Office Details/কাৰ্য্যালয় বিৱৰণ</h6>
        <div class="row">
            <label class="col-6">Select Office/কাৰ্য্যালয় বাছনি কৰক</label>
            <div class="col-6 pb-2"><?= $attribute_details->select_office ?? 'N/A' ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Bride Details/কইনাৰ বিৱৰণ</h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Name of the Bride/কইনাৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->name_of_the_bride ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Father's Name/পিতাৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->bride_father_name ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Age/বয়স </label>
                <div class="pb-2"><?= $attribute_details->bride_age ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Occupation/বৃত্তি</label>
                <div class="pb-2"><?= $attribute_details->bride_occupation ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Bride Status/কইনাৰ স্থিতি</label>
                <div class="pb-2"><?= $attribute_details->bride_status ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Bride Present Address/কইনাৰ বৰ্তমান ঠিকনা </h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Country/দেশ</label>
                <div class="pb-2"><?= $attribute_details->bride_country_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">State/ৰাজ্য</label>
                <div class="pb-2"><?= $attribute_details->bride_state_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">District/জিলা</label>
                <div class="pb-2"><?= $attribute_details->bride_district_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Village/Town/গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_details->bride_village_or_town_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Police Station/থানা</label>
                <div class="pb-2"><?= $attribute_details->bride_police_station_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Post Office/ডাকঘৰ</label>
                <div class="pb-2"><?= $attribute_details->bride_post_office_present ?? 'N/A' ?></div>
            </div>
            <div class="col-12">
                <label class="mb-0">Pin Code/পিন</label>
                <div class="pb-2"><?= $attribute_details->bride_pin_code_present ?? 'N/A' ?></div>
            </div>
            <br>
            <div class="col-4">
                <label class="mb-0">Residency period at present address/বৰ্ত্তমান ঠিকনাত বসবাস কৰা সময় </label>
                   
                <div class="pb-2"><?= $attribute_details->bride_residency_period_at_present_address[0]->year ?? 'N/A' ?> Years  <?= $attribute_details->bride_residency_period_at_present_address[0]->month ?? 'N/A' ?> Months</div>         
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Bride Parmanent Address/কইনাৰ স্হায়ী ঠিকনা</h6>
        <div class="row">
            <label class="col-12">Permanent Address same as Present Adress/স্থায়ী ঠিকনা বৰ্ত্তমান ঠিকনাৰ নিচিনা</label>
            <div class="col-12"><?= $attribute_details->bride_permanent_address_same_as_present_adress ?? 'N/A' ?></div>
            <div class="col-4">
                <label class="mb-0">Country/দেশ</label>
                <div class="pb-2"><?= $attribute_details->bride_country_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">State/ৰাজ্য</label>
                <div class="pb-2"><?= $attribute_details->bride_state_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">District/জিলা</label>
                <div class="pb-2"><?= $attribute_details->bride_district_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Village/Town/গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_details->bride_village_or_town_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Police Station/থানা</label>
                <div class="pb-2"><?= $attribute_details->bride_police_station_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Post Office/ডাকঘৰ</label>
                <div class="pb-2"><?= $attribute_details->bride_post_office_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Pin Code/পিন</label>
                <div class="pb-2"><?= $attribute_details->bride_pin_code_parmanent ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Bridegroom Details/দৰাৰ বিৱৰণ </h6>
        <div class="row">
            <div class="col-6">
                <label class="mb-0">Name of the Bridegroom/দৰাৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->name_of_the_bridegroom ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Father's Name/পিতাৰ নাম</label>
                <div class="pb-2 text-uppercase"><?= $attribute_details->groom_father_name ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Age/বয়স</label>
                <div class="pb-2"><?= $attribute_details->groom_age ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Occupation/বৃত্তি</label>
                <div class="pb-2"><?= $attribute_details->groom_occupation ?? 'N/A' ?></div>
            </div>
            <div class="col-6">
                <label class="mb-0">Bridegroom Status/দৰাৰ স্থিতি</label>
                <div class="pb-2"><?= $attribute_details->groom_status ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Groom Present Address/বৰ্ত্তমান ঠিকনা</h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Country/দেশ</label>
                <div class="pb-2"><?= $attribute_details->groom_country_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">State/ৰাজ্য</label>
                <div class="pb-2"><?= $attribute_details->groom_state_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">District/জিলা</label>
                <div class="pb-2"><?= $attribute_details->groom_district_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Village/Town/গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_details->groom_village_town_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Police Station/থানা</label>
                <div class="pb-2"><?= $attribute_details->groom_post_office_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Post Office/ডাকঘৰ</label>
                <div class="pb-2"><?= $attribute_details->groom_police_station_present ?? 'N/A' ?></div>
            </div>
            <div class="col-12">
                <label class="mb-0">Pin Code/পিন</label>
                <div class="pb-2"><?= $attribute_details->groom_pin_code_present ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
            <label class="mb-0">Residency period at present address/বৰ্ত্তমান ঠিকনাত বসবাস কৰা সময় </label>
            <div class="pb-2"><?= $attribute_details->groom_residency_period_at_present_address[0]->year ?? 'N/A' ?> Years  <?= $attribute_details->groom_residency_period_at_present_address[0]->month ?? 'N/A' ?> Month</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Groom Permanent Address/দৰাৰ স্থায়ী ঠিকনা</h6>
        <div class="row">
            <label class="col-12">Permanent Address same as Present Address/বৰ্ত্তমান ঠিকনাৰ দৰে একে</label>
            <div class="col-12"><?= $attribute_details->groom_permanent_address_same_as_present_address ?? 'N/A' ?></div>
            <div class="col-4">
                <label class="mb-0">Country/দেশ</label>
                <div class="pb-2"><?= $attribute_details->groom_country_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">State/ৰাজ্য</label>
                <div class="pb-2"><?= $attribute_details->groom_state_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">District/জিলা</label>
                <div class="pb-2"><?= $attribute_details->groom_district_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Village/Town/গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_details->groom_village_town_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Police Station/থানা</label>
                <div class="pb-2"><?= $attribute_details->groom_police_station_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Post Office/ডাকঘৰ</label>
                <div class="pb-2"><?= $attribute_details->groom_post_office_parmanent ?? 'N/A' ?></div>
            </div>
            <div class="col-12">
                <label class="mb-0">Pin Code/পিন</label>
                <div class="pb-2"><?= $attribute_details->groom_pin_code_parmanent ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>