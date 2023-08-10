<!-- <h1>Hello</h1> -->
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">


<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>

<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
.parsley-errors-list {
    color: red;
}

.mbtn {
    width: 100% !important;
    margin-bottom: 3px;
}

.blk {
    display: block;
}

/* search style */
label[for="search"],
label[for="el-search"] {
    transform: translateX(2rem);
}

[type="search"]:not(.dataTables_wrapper input, #appl-no) {
    border: 1px solid #362f2d;
    border-radius: 0.25rem;
    outline: none;
    background-color: rgba(199, 178, 153, 0.2);

    padding: 0.5rem 1rem 0.5rem 3rem;
    width: 55%;
}

.service-serach-btn {
    padding: 0.5rem 2rem;
    background-color: #362f2d;
    color: whitesmoke;
    font-size: 1rem;
    transform: translateX(-1.5rem);
    border-radius: 0.5rem;
}

.service-serach-btn:focus,
.service-serach-btn:hover {
    color: whitesmoke;
    box-shadow: none;
}

/* search style end */

/* For categorywise service list */
.service-categories>div {
    background-color: rgb(246 223 195 / 20%);
}

.service-categories h4 {
    color: #362f2d;
}

.service-categories img {
    align-self: flex-start;
}

/* For mobile phones: */
@media only screen and (max-width: 768px) {
    .service-categories {
        flex-direction: column;
        row-gap: 1em;
    }
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    border: 1px solid #ddd;
}

@media (max-width: 600px) {

    th,
    td {
        font-size: 12px;
    }
}
</style>

<?php
$lang = "en";
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('message') != '') {?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success</strong>
                <?php echo $this->session->userdata('message') != '' ? $this->session->userdata('message') : ''; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php }?>
        </div>
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('errmessage') != '') {?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning</strong>
                <?php echo $this->session->userdata('errmessage') != '' ? $this->session->userdata('errmessage') : ''; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php }?>
        </div>

    </div>
    <!-- Search Service, Login panel -->
    <div class="container-xxl">
        <div class="container">
            <div class="row">
                <div class="col-12 ">
                    <form action="<?=base_url('site/search')?>" method="get" id="service-search"
                        class="d-flex flex-column flex-md-row justify-content-md-center align-items-baseline">
                        <label for="search" class="search-icons d-none d-md-inline">
                            <img src="https://rtps.assam.gov.in/assets/site/theme1/images/esearch.webp"
                                alt="Serices icon" width="20" height="20">
                        </label>
                        <input class="mb-2 mb-md-0" autocomplete="off" type="search" name="service_name" list="services"
                            id="search" placeholder="Are you looking for a particular service?" required=""
                            title="Provide at least 3 characters">
                        <button class="btn align-self-stretch service-serach-btn" type="submit">
                            Search
                        </button>
                    </form>
                    <datalist id="services">
                        <?php foreach ($services_list as $service): ?>
                        <option value="<?=$service->service_name->en?>">
                            <?php endforeach;?>
                    </datalist>


                    <!-- Categorywise Services List -->

                    <section class="my-5 d-flex justify-content-evenly service-categories">

                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?=base_url('site/online/citizen')?>"
                                title="Click here to go to Citizen Services">
                                <div class="d-flex p-3">
                                    <img src="<?=base_url('assets/site/sewasetu/assets/images/citizen.png')?>"
                                        alt="citizen icon">
                                    <div class="flex-grow-1 text-start">
                                        <span class="d-inline-block mb-1 ms-3 fw-bold fs-4 text-danger" id="citizen">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Citizen Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?=base_url('site/online/eodb')?>"
                                title="Click here to go to Citizen Services">
                                <div class="d-flex p-3">
                                    <img src="<?=base_url('assets/site/sewasetu/assets/images/business.png')?>"
                                        alt="business icon">
                                    <div class="flex-grow-1 text-start">
                                        <span id="business"
                                            class="d-inline-block mb-1 ms-3 w-100 fw-bold fs-4 text-danger">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Business Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?=base_url('site/online/utility')?>"
                                title="Click here to go to Utility Services">
                                <div class="d-flex p-3">
                                    <img src="<?=base_url('assets/site/sewasetu/assets/images/utility.png')?>"
                                        alt="citizen icon">
                                    <div class="flex-grow-1 text-start">
                                        <span id="utility"
                                            class="d-inline-block mb-1 ms-3 w-100 fw-bold fs-4 text-danger">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Utility Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </section>

                    <!-- END -->




                </div>
            </div>
        </div>
    </div>
    <h5>This is the old version of your application list. Please <a class=" btn btn-outline-primary" href="<?=base_url('iservices/myapplications/switchview/new')?>">Switch<a/> To New Version</h5>
    <!--Search Bar end-->
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <?php if (!empty($intermediate_ids)) {
    if (!empty($intermediate_ids['vahan_services'])) {
        $this->load->view('mytransactions/vahan_transactions', $intermediate_ids['vahan_services']);
    }
    if (!empty($intermediate_ids['noc_services'])) {
        $this->load->view('mytransactions/noc_transactions', $intermediate_ids['noc_services']);
    }
    if (!empty($intermediate_ids['sarathi_services'])) {
        $this->load->view('mytransactions/sarathi_transactions', $intermediate_ids['sarathi_services']);
    }
    if (!empty($intermediate_ids['basundhara_services'])) {
        $this->load->view('mytransactions/basundhara_transactions', $intermediate_ids['basundhara_services']);
    }
    // if(!empty($intermediate_ids['rtps_services'])){
    //   $this->load->view('mytransactions/rtps_transactions',$intermediate_ids['rtps_services']);
    // }
}
//for migration ahsec
if (!empty($migrationahsecs)) {
    $this->load->view('spservices/migrationcertificateahsec/applications_view', $migrationahsecs);
}
if (!empty($changeinstitutes)) {
    $this->load->view('spservices/change_institute_ahsec/applications_view', $changeinstitutes);
}
if (!empty($karbi_kbrcs)) {
    $this->load->view('spservices/kaac/sec_group/applications_view_kbrc', $karbi_kbrcs);
}
if (!empty($karbi_krbcs)) {
    $this->load->view('spservices/kaac/sec_group/applications_view_krbc', $karbi_krbcs);
}
if (!empty($karbi_farmers)) {
    $this->load->view('spservices/kaac/fifth_group/applications_view', $karbi_farmers);
}
//end for migration ahsec

//newly added by Bishwajit for Gap Certificate
if (!empty($gapahsecs)) {
    $this->load->view('spservices/gappermissioncertificateahsec/applications_view', $gapahsecs);
}
//end for Gap certificate ahsec

if (!empty($minoritycertificates)) {
    $this->load->view('spservices/minoritycertificates/applications_view', $minoritycertificates);
}
if (!empty($necertificaties)) {
    $this->load->view('spservices/applications_view/necertificates_view', $necertificaties);
}
if (!empty($marriageregistrations)) {
    $this->load->view('spservices/applications_view/marriageregistrations_view', $marriageregistrations);
}
if (!empty($appointmentbookings)) {
    $this->load->view('spservices/applications_view/appointmentbookings_view', $appointmentbookings);
}
if (!empty($mutationorders)) {
    $this->load->view('spservices/mutationorder/applications_view', $mutationorders);
}
if (!empty($certifiedcopies)) {
    $this->load->view('spservices/applications_view/registereddeed', $certifiedcopies);
}
if (!empty($tradelicence)) {
    $this->load->view('spservices/applications_view/tradelicence_view', $tradelicence);
    if (!empty($intermediate_ids['vahan_services'])) {
        $this->load->view('mytransactions/vahan_transactions', $intermediate_ids['vahan_services']);
    }
    if (!empty($intermediate_ids['noc_services'])) {
        $this->load->view('mytransactions/noc_transactions', $intermediate_ids['noc_services']);
    }
    if (!empty($intermediate_ids['sarathi_services'])) {
        $this->load->view('mytransactions/sarathi_transactions', $intermediate_ids['sarathi_services']);
    }
    if (!empty($intermediate_ids['basundhara_services'])) {
        $this->load->view('mytransactions/basundhara_transactions', $intermediate_ids['basundhara_services']);
    }
    // if(!empty($intermediate_ids['rtps_services'])){
    //   $this->load->view('mytransactions/rtps_transactions',$intermediate_ids['rtps_services']);
    // }
}
//for migration ahsec
if (!empty($migrationahsecs)) {
    $this->load->view('spservices/migrationcertificateahsec/applications_view', $migrationahsecs);
}
//end for migration ahsec

            //newly added
            //KAAC 9 SERVICES Bishwajit
            if (!empty($kaac_services_DCTH)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_dcth', $kaac_services_DCTH);
            }
            if (!empty($kaac_services_CCJ)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_ccj', $kaac_services_CCJ);
            }
            if (!empty($kaac_services_CCM)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_ccm', $kaac_services_CCM);
            }
            if (!empty($kaac_services_DLP)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_dlp', $kaac_services_DLP);
            }
            if (!empty($kaac_services_ITMKA)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_itmka', $kaac_services_ITMKA);
            }
            if (!empty($kaac_services_LHOLD)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_lhold', $kaac_services_LHOLD);
            }
            if (!empty($kaac_services_LRCC)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_lrcc', $kaac_services_LRCC);
            }
            if (!empty($kaac_services_LVC)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_lvc', $kaac_services_LVC);
            }
            if (!empty($kaac_services_NECKA)) {
                $this->load->view('spservices/kaac/applications/kaac_applications_view_necka', $kaac_services_NECKA);
            }

            //END KAAC 9 SERVICES

            // KAAC Income Certificate Bishwajit
            if (!empty($kaac_services_income)) {
                $this->load->view('spservices/kaacincomecertificate/kaac_applications_view_income', $kaac_services_income);
            }
            //end KAAC Income Certificate

            // KAAC NOC Trade License Bishwajit
            if (!empty($kaac_services_noctl)) {
                $this->load->view('spservices/kaac_noc_trade_license/kaac_applications_view_noctl', $kaac_services_noctl);
            }
            //end KAAC NOC Trade License 

//END KAAC SERVICES

if (!empty($minoritycertificates)) {
    $this->load->view('spservices/minoritycertificates/applications_view', $minoritycertificates);
}
if (!empty($necertificaties)) {
    $this->load->view('spservices/applications_view/necertificates_view', $necertificaties);
}
if (!empty($marriageregistrations)) {
    $this->load->view('spservices/applications_view/marriageregistrations_view', $marriageregistrations);
}
if (!empty($appointmentbookings)) {
    $this->load->view('spservices/applications_view/appointmentbookings_view', $appointmentbookings);
}
if (!empty($mutationorders)) {
    $this->load->view('spservices/mutationorder/applications_view', $mutationorders);
}
if (!empty($certifiedcopies)) {
    $this->load->view('spservices/applications_view/registereddeed', $certifiedcopies);
}
if (!empty($tradelicence)) {
    $this->load->view('spservices/applications_view/tradelicence_view', $tradelicence);
    if (!empty($intermediate_ids['vahan_services'])) {
        $this->load->view('mytransactions/vahan_transactions', $intermediate_ids['vahan_services']);
    }
    if (!empty($intermediate_ids['noc_services'])) {
        $this->load->view('mytransactions/noc_transactions', $intermediate_ids['noc_services']);
    }
    if (!empty($intermediate_ids['sarathi_services'])) {
        $this->load->view('mytransactions/sarathi_transactions', $intermediate_ids['sarathi_services']);
    }
    if (!empty($intermediate_ids['basundhara_services'])) {
        $this->load->view('mytransactions/basundhara_transactions', $intermediate_ids['basundhara_services']);
    }
    // if(!empty($intermediate_ids['rtps_services'])){
    //   $this->load->view('mytransactions/rtps_transactions',$intermediate_ids['rtps_services']);
    // }
}
if (!empty($minoritycertificates)) {
    $this->load->view('spservices/minoritycertificates/applications_view', $minoritycertificates);
}
if (!empty($necertificaties)) {
    $this->load->view('spservices/applications_view/necertificates_view', $necertificaties);
}
if (!empty($marriageregistrations)) {
    $this->load->view('spservices/applications_view/marriageregistrations_view', $marriageregistrations);
}
if (!empty($appointmentbookings)) {
    $this->load->view('spservices/applications_view/appointmentbookings_view', $appointmentbookings);
}
if (!empty($mutationorders)) {
    $this->load->view('spservices/mutationorder/applications_view', $mutationorders);
}
if (!empty($certifiedcopies)) {
    $this->load->view('spservices/applications_view/registereddeed', $certifiedcopies);
}
if (!empty($tradelicence)) {
    $this->load->view('spservices/applications_view/tradelicence_view', $tradelicence);
}
if (!empty($intermediate_ids['other_services'])) {
    $this->load->view('mytransactions/other_transactions', $intermediate_ids['other_services']);
}
if (!empty($seniorcitizencertificate)) {
    $this->load->view('spservices/applications_view/seniorcitizencertificate_view', $seniorcitizencertificate);
}
if (!empty($nextofkincertificate)) {
    $this->load->view('spservices/applications_view/nextofkincertifcate_view', $nextofkincertificate);
}
if (!empty($delayeddeathcertificate)) {
    $this->load->view('spservices/applications_view/delayeddeathcertifcate_view', $delayeddeathcertificate);
}
if (!empty($apdcl)) {
    $this->load->view('spservices/applications_view/apdcl', $apdcl);
}
if (!empty($prc)) {
    $this->load->view('spservices/prc/applicationlist', $prc);
}
if (!empty($tp)) {
    $this->load->view('spservices/trade_permit/applicationlist', $tp);
}
if (!empty($ncl)) {
    $this->load->view('spservices/noncreamylayercertificate/transaction_list.php', $ncl);
}
if (!empty($caste)) {
    $this->load->view('spservices/bhumiputra/applicationlist', $caste);
}
if (!empty($incomecertificate)) {
    $this->load->view('spservices/applications_view/incomecertificate_view.php', $incomecertificate);
}
if (!empty($delayedbirthregistration)) {
    $this->load->view('spservices/applications_view/delayedbirthregistration_view', $delayedbirthregistration);
}
if (!empty($castecertificate)) {
    $this->load->view('spservices/applications_view/castecertifcate_view', $castecertificate);
}
if (!empty($bakijai)) {
    $this->load->view('spservices/applications_view/bakijai_view', $bakijai);
}
if (!empty($buildingpermissioncertificate)) {
    $this->load->view('spservices/applications_view/buildingpermissioncertificate_view', $buildingpermissioncertificate);
}
if (!empty($acmr_cp_cmecertificate)) {
    $this->load->view('spservices/applications_view/acmr_cp_cmecertificate_view', $acmr_cp_cmecertificate);
}

if (!empty($employment_exchange)) {
    $this->load->view('spservices/employment_aadhaar_based/applications_view', $employment_exchange);
}

if (!empty($eodb_transactions)) {
    //pre($eodb_transactions);
    $this->load->view('mytransactions/eodb_transactions', $eodb_transactions);
}
if (!empty($serviceplus_transactions_rtps)) {
    // pre($serviceplus_transactions_rtps);
    $this->load->view('mytransactions/serviceplus_transactions_rtps', $serviceplus_transactions_rtps);
}
if (!empty($serviceplus_transactions_eodb)) {
    // pre($serviceplus_transactions_eodb);
    $this->load->view('mytransactions/serviceplus_transactions_eodb', $serviceplus_transactions_eodb);
}

if (!empty($acmrnoc)) {
    $this->load->view('spservices/applications_view/acmrnoc_view', $acmrnoc);
}
if (!empty($acmraddlregdegrees)) {
    $this->load->view('spservices/applications_view/acmr_reg_of_addl_degrees_view', $acmraddlregdegrees);
}
if (!empty($acmrprovisionalcertificate)) {
    $this->load->view('spservices/applications_view/acmr_provisional_certificate', $acmrprovisionalcertificate);
}
if (!empty($permanent_registration_mbbs)) {
    $this->load->view('spservices/applications_view/acmr_permanent_registration_mbbs', $permanent_registration_mbbs);
}
if (!empty($acmr_cp_cme)) {
    $this->load->view('spservices/applications_view/acmr_cp_cme', $acmr_cp_cme);
}
if (!empty($spbuildingpermissioncertificate)) {
    $this->load->view('spservices/applications_view/buildingpermissioncertificate_view', $spbuildingpermissioncertificate);
}
if (!empty($employment_exchange_nonaadhaar)) {
    $this->load->view('spservices/employment_nonaadhaar/applications_reg_view', $employment_exchange_nonaadhaar);
}
if (!empty($employment_exchange_renonaadhaar)) {
    $this->load->view('spservices/employment_nonaadhaar/applications_rereg_view', $employment_exchange_renonaadhaar);
}
if (!empty($offline_ack_apps)) {
    $this->load->view('spservices/applications_view/offline_ack_view', $offline_ack_apps);
}
if (!empty($ahsecregcardduplicatecertificate)) {
    $this->load->view('spservices/applications_view/ahesc_reg_card_dup_certificate_view', $ahsecregcardduplicatecertificate);
}
if (!empty($ahsecadmitcardduplicatecertificate)) {
    $this->load->view('spservices/applications_view/ahesc_admit_card_dup_certificate_view', $ahsecadmitcardduplicatecertificate);
}
if (!empty($ahsecmarksheetduplicatecertificate)) {
    $this->load->view('spservices/applications_view/ahesc_marksheet_dup_certificate_view', $ahsecmarksheetduplicatecertificate);
}
if (!empty($ahsecpasscerduplicatecertificate)) {
    $this->load->view('spservices/applications_view/ahesc_pass_cert_dup_certificate_view', $ahsecpasscerduplicatecertificate);
}

if (!empty($ahsec_correction_rc)) {
    $this->load->view('spservices/applications_view/ahsec_correction_rc_view', $ahsec_correction_rc);
}
if (!empty($ahsec_correction_adm)) {
    $this->load->view('spservices/applications_view/ahsec_correction_adm_view', $ahsec_correction_adm);
}
if (!empty($ahsec_correction_marksheet)) {
    $this->load->view('spservices/applications_view/ahsec_correction_marksheet_view', $ahsec_correction_marksheet);
}
if (!empty($ahsec_correction_pc)) {
    $this->load->view('spservices/applications_view/ahsec_correction_pc_view', $ahsec_correction_pc);
}
if (!empty($reg_of_contractors)) {
    $this->load->view('spservices/registration_of_contractors/applications_view', $reg_of_contractors);
}
if (!empty($upgr_of_contractors)) {
    $this->load->view('spservices/registration_of_contractors/applications_view_upgr', $upgr_of_contractors);
}
if (!empty($renewal_of_contractors)) {
    $this->load->view('spservices/registration_of_contractors/renewal_applications_view', $renewal_of_contractors);
}
?>
        </div>
    </div>
</div>
<?php
function get_status($str)
{
    switch ($str) {
        case 'S':
            return "Success";
            break;
        case 'P':
            return "Pending";
            break;
        case 'Y':
            return "Success";
            break;
        case 'N':
            return "Failed";
            break;
        case 'F':
            return "Failed";
            break;
        case 'A':
            return "Aborted ";
            break;
        case 'R':
            return "Pending";
            break;

        default:
            return "";
            break;
    }
}
?>
<script src="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.js')?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function(event) {

    // Load service category counts
    window.fetch("<?=base_url('site/categorywise_services_api')?>")
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            document.querySelector('span#citizen').textContent = data.data.total_citizen;
            document.querySelector('span#utility').textContent = data.data.total_utility;
            document.querySelector('span#business').textContent = data.data.total_business;

        })
        .catch(error => alert(error));
});
</script>