
<h5 class="text-center bg-success p-2 rounded">APPLICATION FORM FOR OFFICE MUTATION / কাৰ্য্যালয় নামজাৰীৰ বাবে আবেদন </h5>
<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">Applicant Name/আবেদনকাৰীৰ নাম </label><br>
                <div class="pb-2"><?= $attribute_detais->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Father's Name/পিতাৰ নাম</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Spouse Name/স্বামী/পত্নীৰ নাম</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Mobile Number/দূৰভাষ (ভ্ৰাম্যীন)</label>
                <div class="pb-2"><?= $attribute_detais->mobile_number ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">E-Mail/ই-মেইল</label>
                <div class="pb-2"><?= $attribute_detais->{'e-mail'} ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Pan</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Location Details/মাটিৰ অৱস্থিতি </h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">District/জিলা</label><br>
                <div class="pb-2"><?= $attribute_detais->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Circle/ৰাজহ চক্ৰ</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Revenue Village / Town/ৰাজহ গাওঁ/চহৰ</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Patta No/পট্টাৰ নং</label>
                <div class="pb-2"><?= $attribute_detais->mobile_number ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Dag No/দাগ নং</label>
                <div class="pb-2"><?= $attribute_detais->{'e-mail'} ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Transfer Type/হস্তান্তৰৰ প্ৰকাৰ</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Deed No ( If Any )/দলিল নং (যদি আছে)</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Deed Value ( If Any )/দলিলৰ মূল্য (যদি আছে)</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Deed Date ( If Any )/দলিলৰ তাৰিখ (যদি আছে)</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Select the location of the applied land (আপুনি আবেদন কৰা মাটি খিনিৰ অৱস্হান বাচনি কৰক)</label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">fees </label>
                <div class="pb-2"><?= $attribute_detais->pan ?? 'N/A' ?></div>
            </div>
            
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Buyer's Details/ক্ৰেতাৰ বিৱৰণ </h6>
        <div class="row">
            <label class="col-12">Please Note : Preferred language for Applicant Name, Guardian Name in this form is (Assamese / Bengali)/অনুগ্ৰহ কৰি মন কৰকঃ আবেদনকাৰী/অভিভাৱকৰ নাম অসমীয়া বা বঙালীত লিখক</label>
            <div class="col-12">
                <label>Buyer/ক্ৰেতা </label>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Co-Pattadar/এজমালী পট্টাদাৰ</th>
                            <th>Applicant Name/আবেদনকাৰীৰ নাম</th>
                            <th>Gender/লিংগ</th>
                            <th>Guardian Name/অভিভাবকৰ নাম </th>
                            <th>Relation/সম্পৰ্ক</th>
                            <th>Mobile/দূৰভাষ</th>
                            <th>DOB (if minor)/জন্মৰ তাৰিখ ( নাবালক হলে )</th>
                            <th>Address/ঠিকনা</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($attribute_detais->detailed_land_schedule as $key => $land) { ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?= $land->land_area ?? 'N/A' ?></td>
                                <td><?= $land->daag_number ?? 'N/A' ?></td>
                                <td><?= $land->patta_number ?? 'N/A' ?></td>
                                <td><?= $land->patta_type ?? 'N/A' ?></td>
                                <td><?= $land->patta_type ?? 'N/A' ?></td>
                                <td><?= $land->patta_type ?? 'N/A' ?></td>
                                <td><?= $land->patta_type ?? 'N/A' ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Land Area Details/মাটি'ৰ তপশীল/কালি </h6>
        <div class="row">
            <label class="col-12">Total Land Area of This Dag/এই দাগৰ মুঠ কালি</label>
            <div class="col-2">
                <label class="mb-0">Bigha ( বিঘা )</label><br>
                <div class="pb-2"><?= $attribute_detais->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Katha ( কঠা )</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Lessa ( লেছা )</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Ganda (গন্দা) </label>
                <div class="pb-2"><?= $attribute_detais->mobile_number ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Krantik (ক্ৰান্তিক) </label>
                <div class="pb-2"><?= $attribute_detais->{'e-mail'} ?? 'N/A' ?></div>
            </div>

            <label class="col-12">Land Area to be Mutated/নামজাৰী হ'ব লগা মাটি'ৰ কালি</label>
            <div class="col-2">
                <label class="mb-0">Bigha ( বিঘা )</label><br>
                <div class="pb-2"><?= $attribute_detais->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Katha ( কঠা )</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Lessa ( লেছা )</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Ganda (গন্দা) </label>
                <div class="pb-2"><?= $attribute_detais->mobile_number ?? 'N/A' ?></div>
            </div>
            <div class="col-2">
                <label class="mb-0">Krantik (ক্ৰান্তিক) </label>
                <div class="pb-2"><?= $attribute_detais->{'e-mail'} ?? 'N/A' ?></div>
            </div>            
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Seller's Details/বিক্ৰীদাৰৰ তথ্য</h6>
        <div class="row">
            <div class="col-12">
                <label>Seller/বিক্ৰীদাৰৰ নাম</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Select Pattadar/পট্টাদাৰ বাছনি কৰক</th>
                            <th>Pattadar land area to be mutated/পট্টাদাৰৰ কিমান মাটি নামজাৰী হ'ব </th>
                            <th>Mobile Number/দূৰভাষ নং</th>
                            <th>E-Mail/ই-মেইল</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($attribute_detais->detailed_land_schedule as $key => $land) { ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?= $land->land_area ?? 'N/A' ?></td>
                                <td><?= $land->daag_number ?? 'N/A' ?></td>
                                <td><?= $land->patta_number ?? 'N/A' ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="text-center bg-secondary p-1">Select where Application will be submitted/আবেদন ক'ত দাখিল কৰা হ'ব</h6>
        <div class="row">
            <div class="col-4">
                <label class="mb-0">District/জিলা</label><br>
                <div class="pb-2"><?= $attribute_detais->marriage_type ?? 'N/A' ?></div>
            </div>
            <div class="col-4">
                <label class="mb-0">Circle/চক্ৰ</label>
                <div class="pb-2"><?= $attribute_detais->name_of_the_applicant ?? 'N/A' ?></div>
            </div>
        </div>
    </div>
</div>