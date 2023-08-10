
<h5 class="text-center bg-success p-2 rounded text-light">Application for Certified Copy of Registered Deed / পঞ্জীকৃত দলিলৰ প্ৰত্যায়িত নকলৰ বাবে আবেদন</h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </h6>
        <div class="row">
            <label class="col-6">Name of the Applicant/আবেদনকাৰীৰ নাম</label>
            <div class="col-6"><?= $attribute_details->applicant_name ?></div>

            <label class="col-6">Mobile Number/দূৰভাষ</label>
            <div class="col-6"><?= $attribute_details->mobile_number ?? 'N/A' ?></div>

            <label class="col-6">E-Mail/ই-মেইল</label>
            <div class="col-6"><?= $attribute_details->{'e-mail'} ?? 'N/A' ?></div>

            <label class="col-6">Address/ঠিকনা</label>
            <div class="col-6"><?= $attribute_details->address ?? 'N/A' ?></div>

            <label class="col-6">Relation/সম্পৰ্ক</label>
            <div class="col-6"><?= $attribute_details->relation ?? 'N/A' ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Details of the Registered Deed/পঞ্জীয়ন হোৱা দলিলৰ বিৱৰণ</h6>
        <div class="row">
            <label class="col-6">Reference related to the deed(Serial no./Deed no. with Year of Registration/Party name etc)</label>
            <div class="col-6"><?= $attribute_details->deed_serial_number ?? 'N/A' ?></div>

            <label class="col-6">Nature of Deed/পঞ্জীয়নৰ প্ৰকাৰ</label>
            <div class="col-6"><?= $attribute_details->nature_of_deed ?? 'N/A' ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1 text-light">Mode of service delivery & Office of Submission/সেৱা প্ৰদানৰ ধৰণ</h6>
        <div class="row">
            <label class="col-6">Select desired mode/পছন্দৰ পদ্ধতি নিৰ্বাচন কৰক</label>
            <div class="col-6"><?= $attribute_details->select_desired_mode ?? 'N/A' ?></div>

            <label class="col-6">Select Office for application submission/আবেদন জমা কৰিবলগীয়া কাৰ্য্যালয় নিৰ্বাচন কৰক</label>
            <div class="col-6"><?= $attribute_details->select_office ?? 'N/A' ?></div>
        </div>
    </div>
</div>

