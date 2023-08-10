<style>
    /* body {visibility:hidden;} */
    .print {
        visibility: visible;
    }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">

<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-body" style="border: 2px solid #ccc; margin: 2px; padding: 10px;">
                <div style="text-align: center; border-bottom: 2px solid #ccc; padding: 10px">
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase"><u>Office of the <?= isset($response->form_data->ulb) ? $response->form_data->ulb : '' ?>,Assam</u>
                    </div>
                    <div style="font-size: 16px; font-weight: bold; margin: 5px auto"><u>TRADE LICENSE </u></div>
                    <div style="font-size: 16px; font-weight: bold;">As per section 229(1) of Assam Municipal Act 1956 </div>
                    <hr style="height:1px;border:none;color:#333;background-color:#333;">
                    </hr>
                    <div style="float: left; border: none; font-weight: bold;">UBIN:<?= isset($response->form_data->ubin) ? $response->form_data->ubin : '' ?></div>
                    <div style="float: right; border: none; font-weight: bold;">UAIN:<?= isset($response->service_data->appl_ref_no) ? ($response->service_data->appl_ref_no) : '' ?>
                    </div><br>
                    <hr style="height:1px;border:none;color:#333;background-color:#333;">
                    </hr>


                    <div class="row">
                        <p style="text-align:left; line-height: 24px">
                            Licence issued in the name of <strong><?= (isset($response->form_data->applicant_name)) ? $response->form_data->applicant_name : '' ?></strong>,
                            Owner Name <strong><?= (isset($response->form_data->owner_name)) ? $response->form_data->owner_name : '' ?></strong>,
                            Name of Trade <strong><?= (isset($response->form_data->trade_name)) ? $response->form_data->trade_name : '' ?></strong>,
                        </p>
                    </div>
                    <div class="row">

                        <h10 class="h10"><b><u>Address:</u></b></h10>
                    </div>
                    <div class="row">
                        <p1 style="text-align:justify; line-height: 24px">
                            <style>
                                p1 {
                                    text-indent: 3em;
                                }
                            </style>
                            Name of Father/Husband: <strong><?= (isset($response->form_data->father_name2)) ? $response->form_data->father_name2 : '' ?></strong>,
                            Ward No:<strong><?= (isset($response->form_data->ward_no2)) ? $response->form_data->ward_no2 : '' ?></strong>,
                        </p1>
                    </div>
                    <div class="row">
                        <p1 style="text-align:justify; line-height: 24px">
                            Town/Village:<strong><?= (isset($response->form_data->district)) ? $response->form_data->district : '' ?></strong>,
                            PO:<strong><?= (isset($response->form_data->post_office)) ? $response->form_data->post_office : '' ?></strong>,
                            PS:<strong><?= (isset($response->form_data->police_station)) ? $response->form_data->police_station : '' ?></strong>,
                        </p1>
                    </div>
                    <div class="row">
                        <p1 style="text-align:justify; line-height: 24px">
                            District:<strong><?= (isset($response->form_data->district)) ? $response->form_data->district : '' ?></strong>,
                        </p1>
                    </div> <br>
                    <div class="row">
                        <p style="text-align:justify; line-height: 24px">
                            The Business Establishment owned by Shri/Smt. <strong><?= (isset($response->form_data->business_ownership)) ? $response->form_data->business_ownership : '' ?></strong>,
                            at Ward No:<strong><?= (isset($response->form_data->ward_no2)) ? $response->form_data->ward_no2 : '' ?></strong>,
                        </p>
                    </div>
                    <div class="row">
                        <p style="text-align:justify; line-height: 24px">
                            by <strong><?= (isset($response->form_data->holding_no)) ? $response->form_data->holding_no : '' ?></strong>,
                            Road Holding no. of the House/Room<strong><?= (isset($response->form_data->holding_no)) ? $response->form_data->holding_no : '' ?></strong>,
                            Owned By<strong><?= (isset($response->form_data->holding_no)) ? $response->form_data->holding_no : '' ?></strong>,
                            is hereby issued License no <strong><?= (isset($response->service_data->certificate_no)) ? $response->service_data->certificate_no : '' ?></strong>,
                        </p>
                    </div> <br>
                    <div class="row">
                        <p style="text-align:justify; line-height: 24px">
                            Date of Isssue of License <strong><?= isset($response->service_data->submission_date) ? format_mongo_date($response->service_data->submission_date) : '' ?></strong>, <br>
                            The License is valid till <strong><?= (isset($response->form_data->applicant_name)) ? $response->form_data->applicant_name : '' ?></strong>, <br>
                            <b>Fees: </b><strong><?= (isset($response->form_data->submission_date)) ? $response->service_data->submission_date : '' ?></strong>,
                        </p>
                    </div>
                    <div class="row" style="float: right;">
                        <p style="font-weight: bold; "> Executive Officer/Chairperson <strong></strong><br>
                            <b> ULB Name <strong><?= (isset($response->form_data->ulb)) ? $response->form_data->ulb : '' ?></strong></b> <br><br>
                        </p>
                    </div><br><br><br>
                    <p>
                        (This trade license has been issued on the basis of self declaration by the applicant on 'trust and approved' system verification will be done at a later stage.The applicant will be held responsible for any misrepresentation of facts.)
                    </p>


                </div>
                <div class="row">
                    <p style="position: relative;left: 290px;"> (***This is a computer generated email and it does not require a signature.***) </p>

                </div>
            </div>


        </div>
        <button type="button" id="btnPrint" class="btn btn-primary mb-2">
            <i class="fas fa-print-right"></i> Print
        </button>
    </div>
</div>
</div>
</div>