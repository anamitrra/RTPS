<h5 class="text-center bg-success p-2 rounded text-light">Application form for Appointment - Deed Registration/Marriage / বিবাহ পঞ্জীয়ন /দলিল পঞ্জীয়নৰ বাবে সময় বিচাৰি আবেদন</h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </h6>
        <div class="row">
            <label class="col-6">Name of the Applicant/আবেদনকাৰীৰ নাম</label>
            <div class="col-6"><?= $attribute_details->applicant_name ?? 'N/A' ?></div>

            <label class="col-6">Father's Name/পিতাৰ নাম</label>
            <div class="col-6"><?= $attribute_details->father_name ?? 'N/A' ?></div>

            <label class="col-6">Mobile Number/দূৰভাষ</label>
            <div class="col-6"><?= $attribute_details->mobile_number ?? 'N/A' ?></div>

            <label class="col-6">E-Mail/ই-মেইল</label>
            <div class="col-6"><?= $attribute_details->{'e-mail'} ?? 'N/A' ?></div>

            <label class="col-6">Address of the Applicant/আবেদনকাৰীৰ ঠিকনা</label>
            <div class="col-6"><?= $attribute_details->address_of_the_applicant ?? 'N/A' ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Select Office for Application Submission/কাৰ্য্যালয় বাছনি কৰক, য'ত আবেদন পঠোৱা হ'ব</h6>
        <div class="row">
            <label class="col-6">Select Office/কাৰ্য্যালয় বাছনি কৰক</label>
            <div class="col-6"><?= $attribute_details->select_office ?? 'N/A' ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Appointment details/নিযুক্তিৰ বিৱৰণ</h6>
        <div class="row">
            <label class="col-6">Select Appointment Type/নিযুক্তিৰ প্ৰকাৰ বাছনি কৰক</label>
            <div class="col-6"><?= $attribute_details->appointment_type ?? 'N/A' ?></div>

            <label class="col-6">Date of appointment/নিযুক্তিৰ সময়</label>
            <div class="col-6"><?= $attribute_details->date_of_appointment ?? 'N/A' ?></div>
        </div>
    </div>
</div>