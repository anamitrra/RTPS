<?php
?>

<style type="text/css">
legend {
    display: inline;
    width: auto;
}

ol li {
    font-size: 14px;
    font-weight: bold;
}
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST"
            action="<?= base_url('spservices/noncreamylayercertificate/registration/register') ?>"
            enctype="multipart/form-data">
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( অনা উচ্চস্তৰীয় স্তৰৰ প্রমান পত্রৰ বাবে আবেদন )
                </div>
                <div class="card-body" style="padding:5px">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } ?>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Supporting Document / সহায়ক নথি পত্ৰ</strong>

                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 20px">
                            <li>
                                <!-- Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity
                                bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice
                                [Mandatory]
                                / স্হায়ী বাসিন্দাৰ পত্ৰ । ২. যদি স্হায়ী বাসিন্দাৰ প্ৰমান পত্ৰ নাথাকে, তেন্তে ভোটাৰ
                                কাৰ্ড/বিদুৎ বিল/ বেঙ্ক পাছবুক/ পঞ্জীকৃত মাটিৰ নথি/ জমাবন্দী ( বাধ্যতামূলক ) -->
                                Permanent resident certificate or any other proof of residency [Mandatory] (স্হোনীয়
                                োচসন্দোৰ প্রমোন িত্র ো োচসন্দো সম্পবকজ চযকবনো প্রমোন িত্র [ বাধ্যতামূলক ])
                            </li>
                            <li>
                                OBC / MOBC certificate issued by competent authority [Mandatory]
                                / সংচিষ্ট কতৃজিক্ষৰ িৰো মিোৱো অনযোনয চিছিৰো মেণী / অনযোনয অচত চিছিৰো মেণীৰ প্রমোন িত্র (
                                বাধ্যতামূলক )
                            </li>
                            <li>
                                Income certificate of parents [Mandatory]
                                / পিতৃ মাতৃৰ আয়ৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                                <ol style="list-style:lower-alpha;  margin-left: 20px; margin-top: 0px">
                                    <li>Issued by the Circle Officer ( if the parents are agriculturist ) or / (ক) চক্ৰ
                                        বিষয়াৰ দ্বাৰা ( যদি পিতৃ মাতৃ খেতিয়ক হয় )</li>
                                    <li>Income certificate of parents issued by Controlling Authority / Treasury officer
                                        (if the parents are retired salaried person) or / (খ) কোষাগাৰ বিষয়াৰ দ্বাৰা,
                                        যদিহে আবেদনকাৰী পেঞ্চনধাৰী হয় ।</li>
                                    <!-- <li>Issued by Councillor/Mouzadar (in practice, in case of a non-salaried person) /
                                        (গ) কাউন্সিলৰ/মৌজাদাৰৰ দ্বাৰা, যদিহে আবেদনকাৰী দৰমহাবিহীন হয় ।</li> -->
                                </ol>
                            </li>
                            <li>
                                Other documents as per requirement (Voter card, Bank passbook, etc.) [Optional]
                                / অন্যান্য নথি যেনে- ভোটাৰ কাৰ্ড, বেঙ্ক পাছবুক
                            </li>
                        </ol>


                        <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                            <li>Statutory charges / স্হায়ী মাচুল : Rs. 30 / ৩০ টকা</li>
                            <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ol>
                    </fieldset>


                    <fieldset class=" border border-success" style="margin-top:40px">
                        <legend class="h5">Enter the digital OBC/MOBC certificate number</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                
                                <input type="text" class="form-control" name="cert_no" id="cert_no" value=""
                                    maxlength="255" />
                                <?= form_error("") ?>
                                <label style="font-size:12px;color:green" >Certificate format : XXXXXXXX-XXX-XXXXXXXX </label>
                            </div>
                            <div class="col-md-6">
                                <label>If digital OBC/MOBC Certificate not available <a href="http://localhost/rtps/spservices/castecertificate/registration">click here</a> to apply</label>
                                
                            </div>
                            
                        </div>
                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-check"></i> Validate
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>