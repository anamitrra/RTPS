<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
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
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url("iservices/admin/dashboard"); ?>">Home</a></li>
        <li class="breadcrumb-item active"><a href="#!">Incomplete Transactions....</a></li>
      </ol>
    </div><!-- /.container-fluid -->
  </div>

  <div class="container">
    <div class="row">
      <a href="<?= base_url("iservices/myapplications/delivered") ?>" style="background: #ed3b3b;color: white;margin: auto;padding-left: 14px;padding-right: 14px;">Click here for delivered Applications</a>
    </div>
    <?php if ($this->session->userdata('message') <> '') { ?>
      <div class="row">
        <div class="col-md-12 mt-3 text-center">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success</strong> <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
    <?php } ?>


    <?php if ($this->session->userdata('errmessage') <> '') { ?>
      <div class="row">
        <div class="col-md-12 mt-3 text-center">
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Alert</strong> <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
    <?php } ?>
    <h5>This is the old version of your application list. Please <a class=" btn btn-outline-primary" href="<?=base_url('iservices/myapplications/switchview/new')?>">Switch<a/> To New Version</h5>
    <div class="row">
      <div class="col-sm-12 mx-auto">
        <?php if (!empty($intermediate_ids)) {
          if (!empty($intermediate_ids['vahan_services'])) {
            $this->load->view('admin/mytransactions/vahan_transactions', $intermediate_ids['vahan_services']);
          }
          if (!empty($intermediate_ids['noc_services'])) {
            $this->load->view('admin/mytransactions/noc_transactions', $intermediate_ids['noc_services']);
          }
          if (!empty($intermediate_ids['sarathi_services'])) {
            $this->load->view('admin/mytransactions/sarathi_transactions', $intermediate_ids['sarathi_services']);
          }
          if (!empty($intermediate_ids['basundhara_services'])) {
            $this->load->view('mytransactions/basundhara_transactions', $intermediate_ids['basundhara_services']);
          }
          if (!empty($intermediate_ids['other_services'])) {
            $this->load->view('admin/mytransactions/other_transactions', $intermediate_ids['other_services']);
          }
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
        if (!empty($seniorcitizencertificate)) {
          $this->load->view('spservices/applications_view/seniorcitizencertificate_view', $seniorcitizencertificate);
        }
        if (!empty($delayedbirthregistration)) {
          $this->load->view('spservices/applications_view/delayedbirthregistration_view', $delayedbirthregistration);
        }
        if (!empty($delayeddeathcertificate)) {
          $this->load->view('spservices/applications_view/delayeddeathcertifcate_view', $delayeddeathcertificate);
        }
        if (!empty($nextofkincertificate)) {
          $this->load->view('spservices/applications_view/nextofkincertifcate_view', $nextofkincertificate);
        }
        if (!empty($incomecertificate)) {
          $this->load->view('spservices/applications_view/incomecertificate_view.php', $incomecertificate);
        }
        if (!empty($castecertificate)) {
          $this->load->view('spservices/applications_view/castecertifcate_view', $castecertificate);
        }
        if (!empty($bakijai)) {

          $this->load->view('spservices/applications_view/bakijai_view', $bakijai);
        }

        if (!empty($prc)) {
          $this->load->view('spservices/prc/applicationlist', $prc);
        }

        if (!empty($ncl)) {
          $this->load->view('spservices/noncreamylayercertificate/transaction_list.php', $ncl);
        }

        if (!empty($tp)) {
          $this->load->view('spservices/trade_permit/applicationlist', $tp);
        }

        if (!empty($employment_exchange)) {
          $this->load->view('spservices/employment_aadhaar_based/applications_view', $employment_exchange);
        }
        if (!empty($acmrprovisionalcertificate)) {
          $this->load->view('spservices/applications_view/acmr_provisional_certificate', $acmrprovisionalcertificate);
        }

        if (!empty($acmraddlregdegrees)) {
          $this->load->view('spservices/applications_view/acmr_reg_of_addl_degrees_view', $acmraddlregdegrees);
        }

        if (!empty($serviceplus_transactions_rtps)) {
          //pre($eodb_transactions);
          $this->load->view('mytransactions/serviceplus_transactions_rtps', $serviceplus_transactions_rtps);
        }
        if (!empty($serviceplus_transactions_eodb)) {
          //pre($eodb_transactions);
          $this->load->view('mytransactions/serviceplus_transactions_eodb', $serviceplus_transactions_eodb);
        }
        if (!empty($spbuildingpermissioncertificate)) {
          $this->load->view('spservices/applications_view/buildingpermissioncertificate_view', $spbuildingpermissioncertificate);
        }

        if (!empty($acmr_cp_cme)) {
          $this->load->view('spservices/applications_view/acmr_cp_cme', $acmr_cp_cme);
        }
        if (!empty($acmrnoc)) {
          $this->load->view('spservices/applications_view/acmrnoc_view', $acmrnoc);
        }
        if (!empty($buildingpermissioncertificate)) {
          $this->load->view('spservices/applications_view/buildingpermissioncertificate_view', $buildingpermissioncertificate);
        }
        if (!empty($employment_exchange_renonaadhaar)) {
          $this->load->view('spservices/employment_nonaadhaar/applications_rereg_view', $employment_exchange_renonaadhaar);
        }
        if (!empty($eodb_transactions)) {
          //pre($eodb_transactions);
          $this->load->view('mytransactions/eodb_transactions', $eodb_transactions);
        }
        if (!empty($offline_ack_apps)) {
          $this->load->view('spservices/applications_view/offline_ack_view', $offline_ack_apps);
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
        // pre($kaac_noctl);
        if (!empty($kaac_noctl)) {
          $this->load->view('spservices/applications_view/kaac/kaac_noctl_view', $kaac_noctl);
        }
        if (!empty($kaac_income)) {
          $this->load->view('spservices/applications_view/kaac/kaac_income_view', $kaac_income);
        }
        if (!empty($kaac_DCTH)) {
          $this->load->view('spservices/applications_view/kaac/kaac_dcth_view', $kaac_DCTH);
        }
        if (!empty($kaac_CCJ)) {
          $this->load->view('spservices/applications_view/kaac/kaac_ccj_view', $kaac_CCJ);
        }
        if (!empty($kaac_CCM)) {
          $this->load->view('spservices/applications_view/kaac/kaac_ccm_view', $kaac_CCM);
        }
        if (!empty($kaac_DLP)) {
          $this->load->view('spservices/applications_view/kaac/kaac_dlp_view', $kaac_DLP);
        }
        if (!empty($kaac_LVC)) {
          $this->load->view('spservices/applications_view/kaac/kaac_lvc_view', $kaac_LVC);
        }
        if (!empty($kaac_LRCC)) {
          $this->load->view('spservices/applications_view/kaac/kaac_lrcc_view', $kaac_LRCC);
        }
        if (!empty($kaac_LHOLD)) {
          $this->load->view('spservices/applications_view/kaac/kaac_lhold_view', $kaac_LHOLD);
        }
        if (!empty($kaac_ITMKA)) {
          $this->load->view('spservices/applications_view/kaac/kaac_imtka_view', $kaac_ITMKA);
        }
        if (!empty($kaac_NECKA)) {
          $this->load->view('spservices/applications_view/kaac/kaac_necka_view', $kaac_NECKA);
        }
        if (!empty($kaac_farmer)) {
          $this->load->view('spservices/applications_view/kaac/kaac_farmer_view', $kaac_farmer);
        }
        if (!empty($kaac_BRC)) {
          $this->load->view('spservices/applications_view/kaac/kaac_brc_view', $kaac_BRC);
        }
        if (!empty($kaac_RBRC)) {
          $this->load->view('spservices/applications_view/kaac/kaac_rbrc_view', $kaac_RBRC);
        }
        ?>

      </div>
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
      return "Rejected";
      break;

    default:
      return "";
      break;
  }
}
?>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>


<script>
  $(document).ready(function() {
    $('.table').DataTable();
  });
</script>