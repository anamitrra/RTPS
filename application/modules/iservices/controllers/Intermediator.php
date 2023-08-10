<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Intermediator extends Rtps
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->encryption_key = $this->config->item("encryption_key");
    $this->load->helper("minoritycertificate");
    $this->load->helper("appstatus");
  }

  public function index()
  {
  }
  public function vahan()
  {
    $data = array(
      // "data"=>"59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEA1rnb8fyfsV-ehtPzJfFvCbEbhR-Pp3nsC8bb0heNapR48Sp8_auA06ew7xpNLd_N9iij3RyhKKCqCD6964pJ6NeHStVAy9EnZyN3esCW988AzFnf0FATYVk6xmHqmaZOlUxGMwqkaPWO59p2SWSbmDGvDOsN1Ap-szuPulbYlFl9KUG9CJE5om-dhcMmqjEJnRp0UIFWf74lJY9staxR_"
      "data" => "59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEBbGqXC06udcAI_NHHmhJC1IExP7HZ4EzQPs3qG__NnCWVT7La5hhjUWdi0R26Kx_5wlUZj3wE-Igd0StXGOpm_ZDdo6o5F4mrk0Ho1IDScNb3_HXf0M3cDK_JuUb8r7s1Zk5kz6Eu315PhGHo07rQxkAUDE8CGxRhqQ80ILW5mFrbFpPEhKJBUVVxJpPT25GG7bupc4Fh4iV28umDst_hK"
    );

    //for 3 $str="59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEA1rnb8fyfsV-ehtPzJfFvCbEbhR-Pp3nsC8bb0heNapR48Sp8_auA06ew7xpNLd_N9iij3RyhKKCqCD6964pJ6NeHStVAy9EnZyN3esCW981XosynBSYsIzCLEGLHr9oAYO8Ly4DuoyL2VmloHajEC-CFXDu1nzkvJ_s1bZjS702lmTIauuPHG8Ths3QHBfEI5AFS_zIRnBVj0bfMPsezO";
    //for 5
    //  $str="dmEFygndWcyGrvojFd8opBHzk2Q7NZzMwUMSzHwEruXCZl1FqErPMWtWE5r_Sb2erkWPjtz8yFScPsd0KIJhtGQ1Td_bagqsGs5fmp1f2iF0avv7EXxSd1fa_gjY4bbzUM1EFmT_Qnh0JRSp6W6ZhrXeUIuJQkFnYCkn3gTzJByeDP2xjPaY17fPE_Ozl3rySM60j-8Q35Y3zjJ-Xz_8t0peq3UaqHbtojOFFv6UXA_yKSj-Eh2evrrXp3_TM9u3";
    //for 9
    //  $str="3Z0n2EpG4LdS1LKCCq8VgKouvtTIJO1KYPrmSJCSdvJu3Qx-uD9UA42M9dHQ1QXvebEadTblrMo7Ue7dCXeKTV7HUfKxhtEY2chYJdftK4o3OaQXp7e0XseDxPl0wPBtt-3VYvhvqrEgh6qNlYHUqRcqKMmTHnhcsYo6RGUAgzbpuQjA1FjXNuQpdFmsOeiIceJxInbN2VXCl-J0mBcFUZ0XSJb6-Ui15lH_RwGA7X3F0ODmcqkqdOuiA1K4q0jo";
    //for 2
    $str = "DphW0DwGrH3HDV7QQ_shs41jYEbYe-eO_7B3GAZRlf7AgIYNAu5Djf8N5sBAFM2ABO6xWNptX4cGY4UrXG-ZrTa5BDqhMW16h5-HcxnsEcCLLx5fsG_naRCe7ZnSui_lpXb1iwGnfzI-bT8LDK1WFyTYbMZahH0uI6xVZy53HWvhWRbG-gYy_wfOrngNsO2uYV7M0EfDhpNVfIq59Y96PmJ8egOZ30GwS_oTCQdAgD3nD-OUhnsUXMxx99Cv8d5Q";
    // $this->load->view('includes/frontend/header');
    // $this->load->view('vahan',$data);
    // $this->load->view('includes/frontend/footer');
    $target_url = "https://staging.parivahan.gov.in/vahanservice/vahan/ui/statevalidation/homepage.xhtml?data=" . $str;
    redirect($target_url);
    // https://staging.parivahan.gov.in/vahanservice/vahan/ui/eapplication/form_eAppCommonHome.xhtml?data=DphW0DwGrH3HDV7QQ_shs41jYEbYe-eO_7B3GAZRlf7AgIYNAu5Djf8N5sBAFM2ABO6xWNptX4cGY4UrXG-ZrTa5BDqhMW16h5-HcxnsEcCLLx5fsG_naRCe7ZnSui_lpXb1iwGnfzI-bT8LDK1WFyTYbMZahH0uI6xVZy53HWvhWRbG-gYy_wfOrngNsO2uYV7M0EfDhpNVfIq59Y96PmJ8egOZ30GwS_oTCQdAgD3nD-OUhnsUXMxx99Cv8d5Q

  }
  public function guidelines($service_id = null, $portal_no = null)
  {
    check_application_count_for_citizen();
    if (empty($service_id) || empty($portal_no)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      if (strpos($url, "admin") == false) {
        $admin_url = str_replace("iservices", "iservices/admin", $url);
        redirect($admin_url);
      }
    }
    $data = array("pageTitle" => "Guidelines");
    $data['service_id'] = $service_id;
    $data['portal_no'] = $portal_no;

    $guidelines = $this->portals_model->get_guidelines($service_id);
    if (property_exists($guidelines, "status") && !$guidelines->status) {
      exit("Service Under Maintenance");
      return;
    }
    if ($guidelines) {
      if ($portal_no === "12" || $portal_no === 12) {
        redirect(base_url("iservices/application/ngdrs/" . $service_id . "/" . $portal_no));
        return;
      }
      $data['service_name'] = $guidelines->service_name;
      $data['guidelines'] = isset($guidelines->guidelines) ? $guidelines->guidelines : array();
      $data['url'] = isset($guidelines->url) ? $guidelines->url : '';

      $this->load->view('includes/frontend/header');
      $this->load->view('guidelines', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
  }
  public function transactions()
  {
    $user = $this->session->userdata();
    if (!$user['isLoggedIn']) {
      redirect(base_url('iservices/login/logout'));
    }
    //redirect to new list
    $transView=$this->input->cookie('trans_view', TRUE);
    // pre($transView);
    if(empty($transView) || $transView === 'new'){
      redirect(base_url('iservices/myapplications/list'));
      return;
    }
    if (isset($user['role']) && !empty($user['role'])) {
      redirect(base_url('iservices/admin/my-transactions'));
    }
    $site_data = $this->getServiceAndSettings();

    $data['services_list'] = isset($site_data->services_list) ? $site_data->services_list : array();
    $data['settings'] = isset($site_data->settings) ? $site_data->settings : [];

    $this->load->model('spservices/minoritycertificates/minoritycertificates_model');
    $this->load->model('spservices/necertificates_model');
    $this->load->model('spservices/marriageregistration/marriageregistrations_model');
    $this->load->model('spservices/appointmentbooking/appointmentbookings_model');
    $this->load->model('spservices/mutationorder/mutationorders_model');
    $this->load->model('spservices/bhumiputra/caste_model');
    $this->load->model('spservices/prc/applications_model');
    $this->load->model('spservices/incomecertificate/income_registration_model');
    $this->load->model('spservices/trade_permit/tradePermit_model');
    $this->load->model('spservices/tradelicence/licence_model');

    $this->load->model('spservices/noncreamylayercertificate/Ncl_model');
    $this->load->model('spservices/employment_aadhaar_based/employment_model');


    $this->load->model('spservices/kaac/kaac_registration_model');


    $this->load->model('eodb/eodb_intermediator_model');
    $mobile = $user['mobile'];
    $data['intermediate_ids'] = $this->intermediator_model->get_my_transactions($mobile);
    // $data['intermediate_ids']=$this->intermediator_model->get_where(array('mobile'=>$mobile));
    $data['intermediate_ids'] = $this->prepare_transactions($data['intermediate_ids']);
    // pre($data['intermediate_ids']);

    //Trade Licence cerificate 
    $data["tradelicence"] = $this->licence_model->get_rows(array("form_data.mobile" => $mobile, "service_data.service_id" => "TRADE"));

    //Employment Exchange
    $employment_exchange_reg = $this->employment_model->get_rows(array("form_data.mobile_number" => $mobile, "form_data.service_id" => "EMP_A_REG"));

    $employment_exchange_renew = $this->employment_model->get_rows(array("form_data.mobile_number" => $mobile, "form_data.service_id" => "EMP_A_RENEW"));

    $employment_exchange_rereg = $this->employment_model->get_rows(array("form_data.mobile_number" => $mobile, "form_data.service_id" => "EMP_A_RE_REG"));

    $employment_exchange = array_merge((array)$employment_exchange_reg, (array)$employment_exchange_renew, (array)$employment_exchange_rereg);
    $data["employment_exchange"] = array_filter($employment_exchange);

    //registration of contractors
    $this->load->model('spservices/registration_of_contractors/employment_model');
    $reg_of_contractors_1 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_PWDB_1"));
    $reg_of_contractors_2 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_PWDB_2"));
    $reg_of_contractors_3 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_PHED_1"));
    $reg_of_contractors_4 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_PHED_2"));
    $reg_of_contractors_5 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_WRD_1"));
    $reg_of_contractors_6 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_WRD_2"));
    $reg_of_contractors_7 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_GMC_1"));
    $reg_of_contractors_8 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REG_GMC_2"));
    $reg_of_contractors = array_merge((array)$reg_of_contractors_1, (array)$reg_of_contractors_2, (array)$reg_of_contractors_3, (array)$reg_of_contractors_4, 
    (array)$reg_of_contractors_5, (array)$reg_of_contractors_6, (array)$reg_of_contractors_7, (array)$reg_of_contractors_8);
    $data["reg_of_contractors"] = array_filter($reg_of_contractors);
    //upgradation of contractors
    $upgr_of_contractors_1 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_PWDB_1"));
    $upgr_of_contractors_2 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_PWDB_2"));
    $upgr_of_contractors_3 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_PHED_1"));
    $upgr_of_contractors_4 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_PHED_2"));
    $upgr_of_contractors_5 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_WRD_1"));
    $upgr_of_contractors_6 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_UPGR_WRD_2"));
    $upgr_of_contractors = array_merge((array)$upgr_of_contractors_1, (array)$upgr_of_contractors_2, (array)$upgr_of_contractors_3, (array)$upgr_of_contractors_4, (array)$upgr_of_contractors_5, (array)$upgr_of_contractors_6);
    $data["upgr_of_contractors"] = array_filter($upgr_of_contractors);

    //renewal of contractors
    $renew_of_contractors_1 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_PHED_1"));
    $renew_of_contractors_2 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_PHED_2"));
    $renew_of_contractors_3 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_PWDB_1"));
    $renew_of_contractors_4 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_PWDB_2"));
    $renew_of_contractors_5 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_WRD_1"));
    $renew_of_contractors_6 = $this->employment_model->get_rows(array("form_data.mobile" => $mobile, "form_data.service_id" => "CON_REN_WRD_2"));
    $renewal_of_contractors = array_merge((array)$renew_of_contractors_1, (array)$renew_of_contractors_2, (array)$renew_of_contractors_3, (array)$renew_of_contractors_4, (array)$renew_of_contractors_5, (array)$renew_of_contractors_6);
    $data["renewal_of_contractors"] = array_filter($renewal_of_contractors);

    //Minority cerificates
    $data["minoritycertificates"] = $this->minoritycertificates_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "MCC"));

    //NE Cerificates 
    $data["necertificaties"] = $this->necertificates_model->get_rows(array("mobile" => $mobile, "service_id" => "NECERTIFICATE"));

    //Marriage Registrations
    $data["marriageregistrations"] = $this->marriageregistrations_model->get_rows(array("applicant_mobile_number" => $mobile, "service_id" => "MARRIAGE_REGISTRATION"));

    //Appointment Bookings
    $data["mutation_orders"] = $this->mutationorders_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "MUTATION_ORDER"));

    //Mutation Orders
    $data["mutationorders"] = $this->appointmentbookings_model->get_rows(array("form_data.mobile_number" => (int)$mobile, "service_data.service_id" => "APPOINTMENT_BOOKING"));

    $data["ncl"] = $this->Ncl_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "NCL"));

    $data["certifiedcopies"] = $this->necertificates_model->get_rows(array("mobile" => $mobile, "service_id" => "CRCPY"));
    $data["seniorcitizencertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "SCTZN"));


    $data["nextofkincertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "NOKIN"));

    $data["apdcl"] = $this->necertificates_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "apdcl1"));
    $data["prc"] = $this->applications_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "PRC"));
    $data["tp"] = $this->tradePermit_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "NC-TP"));
    // $data["caste"] = $this->caste_model->get_rows(array("form_data.mobile" => $mobile, "service_data.service_id" => "CASTE"));

    //for migration
    $data["migrationahsecs"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECMIGR"));
    //end for migration
    //for migration
    $data["changeinstitutes"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECCHINS"));
    //end for migration
    //newly added by Bishwajit for Gap Certificate
    $data["karbi_kbrcs"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "1879"));
    $data["karbi_krbcs"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "1880"));
    $data["karbi_farmers"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "1868"));
    // $data["gapahsecs"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECGAP"));

    //end for gap certificate

    // pre($data["migrationahsec"]);


    //KAAC 9 SERVICES
    $data["kaac_services_DCTH"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "DCTH"));
    $data["kaac_services_CCJ"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "CCJ"));
    $data["kaac_services_CCM"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "CCM"));
    $data["kaac_services_DLP"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "DLP"));
    $data["kaac_services_ITMKA"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "ITMKA"));
    $data["kaac_services_LHOLD"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "LHOLD"));
    $data["kaac_services_LRCC"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "LRCC"));
    $data["kaac_services_LVC"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "LVC"));
    $data["kaac_services_NECKA"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "NECKA"));

    //end KAAC 9 SERVICES

    //kaac Income Cetificate
    $data["kaac_services_income"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "ICERT"));
    //end Kaac Income Certificate

    //kaac NOC Trade Lisence
    $data["kaac_services_noctl"] = $this->kaac_registration_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.service_id" => "NOCTL"));
    //End

    //income certificate
    $data["incomecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "INC"));
    $data["delayedbirthregistration"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "PDBR"));

    $data["castecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "CASTE"));

    $data["delayeddeathcertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "PDDR"));

    $data["bakijai"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "BAKCL"));

    $data["buildingpermissioncertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "PPBP"));
    $data["eodb_transactions"] = $this->eodb_intermediator_model->get_rows(array("user_type" => "user", "mobile" => $mobile, "appl_status" => array("\$exists" => true, '$ne' => 'P')));
    $data["acmrnoc"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "ACMRNOC"));
    $data["acmr_cp_cme"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "CPCME"));

    $data["acmraddlregdegrees"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "ACMRREGAD"));

    $data["acmrprovisionalcertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "ACMRPRCMD"));

    $data["permanent_registration_mbbs"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "PROMD"));
    $data["acmrprovisionalcertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "ACMRPRCMD"));
    $data["offline_ack_apps"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "form_data.is_offline" => true));

    //Employment Exchange Non-Aadhaar
    $employment_exchange_reg_nonaadhaar = $this->employment_model->get_rows(array("form_data.mobile_number" => $mobile, "form_data.service_id" => "EMP_REG_NA"));
    $employment_exchange_nonaadhaar = array_merge((array)$employment_exchange_reg_nonaadhaar);
    $data["employment_exchange_nonaadhaar"] = array_filter($employment_exchange_nonaadhaar);

    $employment_exchange_rereg_nonaadhaar = $this->employment_model->get_rows(array("form_data.mobile_number" => $mobile, "form_data.service_id" => "EMP_REREG_NA"));
    $employment_exchange_renonaadhaar = array_merge((array)$employment_exchange_rereg_nonaadhaar);
    $data["employment_exchange_renonaadhaar"] = array_filter($employment_exchange_renonaadhaar);
    //Fetching list of RTPS services applied from SERVICE PLUS
    if (!$this->session->userdata('curl_executed_sp_rtps_p')) {
      $ref = modules::load('iservices/serviceplus/sprtps');
      $ref->get_sp_pending_applications('RTPS');
    }
    $data["serviceplus_transactions_rtps"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_role" => "CTZN", "user_data.sign_no" => $mobile, "form_data.portal" => "RTPS", "form_data.appl_status" => array('$ne' => 'D'), "form_data.base_service_id" => array('$nin' => [1859, 1860])));

    //Fetching list of EODB services applied from SERVICE PLUS
    if (!$this->session->userdata('curl_executed_sp_eodb_p')) {
      $ref = modules::load('iservices/serviceplus/sprtps');
      $ref->get_sp_pending_applications('EODB');
    }
    $data["serviceplus_transactions_eodb"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_role" => "CTZN", "user_data.sign_no" => $mobile, "form_data.portal" => "EODB", "form_data.appl_status" => array('$ne' => 'D')));

    ///For GMDA-Building Permission Service 
    // $ref = modules::load('spservices/buildingpermission/registration');
    // $sp_build_app = $ref->service_sp_list();
    // if ((is_array($sp_build_app)) && (count($sp_build_app) > 0)) {
    //   $data["spbuildingpermissioncertificate"] = $sp_build_app;
    // } else {
    //   $data["spbuildingpermissioncertificate"] = 0;
    // }
    $data["serviceplus_transactions_rtps"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_role" => "CTZN", "user_data.sign_no" => $mobile, "form_data.portal" => "RTPS", "form_data.appl_status" => array('$ne' => 'D')));

    //Fetching list of EODB services applied from SERVICE PLUS
    if (!$this->session->userdata('curl_executed_sp_eodb_p')) {
      $ref = modules::load('iservices/serviceplus/sprtps');
      $ref->get_sp_pending_applications('EODB');
    }
    $data["serviceplus_transactions_eodb"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_role" => "CTZN", "user_data.sign_no" => $mobile, "form_data.portal" => "EODB", "form_data.appl_status" => array('$ne' => 'D')));

    ///For GMDA-Building Permission Service 
    // $ref = modules::load('spservices/buildingpermission/registration');
    // $sp_build_app = $ref->service_sp_list();
    // if ((is_array($sp_build_app)) && (count($sp_build_app) > 0)) {
    //   $data["spbuildingpermissioncertificate"] = $sp_build_app;
    // } else {
    $data["spbuildingpermissioncertificate"] = 0;
    // }

    ///////////////////

    $data["ahsecregcardduplicatecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECDRC"));
    $data["ahsecadmitcardduplicatecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECDADM"));
    $data["ahsecmarksheetduplicatecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECDMRK"));
    $data["ahsecpasscerduplicatecertificate"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECDPC"));
    $data["ahsec_correction_rc"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECCRC"));
    $data["ahsec_correction_adm"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECCADM"));
    $data["ahsec_correction_marksheet"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECCMRK"));
    $data["ahsec_correction_pc"] = $this->necertificates_model->get_rows(array("form_data.user_type" => "user", "form_data.mobile" => $mobile, "service_data.service_id" => "AHSECCPC"));

    $data['pageTitle'] = "Transactions";

    $this->load->view('includes/frontend/header');
    // $this->load->view('transactions',$data);
    $this->load->view('mytransactions/transactions', $data);
    $this->load->view('includes/frontend/footer');
  }
  private function getServiceAndSettings()
  {
    $url = "localhost/site/api/siteapi/getServicesAndSettings";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($curl);



    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);

    if ($response) {
      $response = json_decode($response);
      if (isset($response->status) && $response->status) {
        return $response->data;
      } else {
        return array();
      }
    } else {
      return array();
    }
  }
  public function archived_transactions()
  {
    $user = $this->session->userdata();
    if ($this->is_admin()) {
      if ($user['role'] === "csc") {
        $apply_by = $user['userId'];
        $role = "csc";
      } else {
        $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        $role = "pfc";
      }
      $data['intermediate_ids'] = $this->intermediator_model->get_admin_archived_transactions($apply_by, $role);
      $data['pageTitle'] = "Archived Transactions";
      $this->load->view('includes/header');
      // $this->load->view('transactions',$data);
      $this->load->view('mytransactions/archived_transactions', $data);
      $this->load->view('includes/footer');
    } else {
      $data['intermediate_ids'] = $this->intermediator_model->get_my_archived_transactions($this->session->userdata('mobile'));
      $data['pageTitle'] = "Archived Transactions";
      $this->load->view('includes/frontend/header');
      // $this->load->view('transactions',$data);
      $this->load->view('mytransactions/archived_transactions', $data);
      $this->load->view('includes/frontend/footer');
    }


    // pre($data['intermediate_ids']);


  }
  private function prepare_transactions($data)
  {
    $service = array();
    $vahan_services = array();
    $noc_services = array();
    $sarathi_services = array();
    $basundhara_services = array();
    $other_services = array();
    $rtps_services = array();
    $eodb_services = array();
    if (!empty($data)) {
      foreach ($data as $trans) {
        if ($trans->portal_no === "1" || $trans->portal_no == 1) {
          array_push($noc_services, $trans);
        } else if ($trans->portal_no === "2" || $trans->portal_no == 2) {
          array_push($vahan_services, $trans);
        } else if ($trans->portal_no === "4" || $trans->portal_no == 4) {
          array_push($sarathi_services, $trans);
        } else if ($trans->portal_no === "5" || $trans->portal_no == 5) {
          array_push($basundhara_services, $trans);
        } else if ($trans->portal_no === "6" || $trans->portal_no == 6) {
          array_push($rtps_services, $trans);
        } else if ($trans->portal_no === "12" || $trans->portal_no == 12) {
          array_push($eodb_services, $trans);
        } else {
          array_push($other_services, $trans);
        }
      }
    }

    return array(
      "vahan_services" => $vahan_services,
      "noc_services" => $noc_services,
      "sarathi_services" => $sarathi_services,
      "basundhara_services" => $basundhara_services,
      'rtps_services' => $rtps_services,
      "other_services" => $other_services
    );
  }
  public function acknowledgement()
  {

    if (empty($_GET['app_ref_no'])) {
      redirect(base_url("iservices/transactions"));
    }
    if (!empty($this->session->userdata('role'))) {
      $is_kiosk = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $is_kiosk = false;
    }

    $app_ref_no = $_GET['app_ref_no'];
    if ($is_kiosk && ($is_kiosk === "PFC")) {
      $application_details = $this->intermediator_model->get_application_details(array("app_ref_no" => $app_ref_no, "applied_by" => new MongoDB\BSON\ObjectId($this->session->userdata("userId")->{'$id'}), "pfc_payment_status" => "Y"));
    } else if ($is_kiosk && ($is_kiosk === "CSC")) {
      $application_details = $this->intermediator_model->get_application_details(array("app_ref_no" => $app_ref_no, "applied_by" => $this->session->userdata('userId'), "pfc_payment_status" => "Y"));
    } else {
      $application_details = $this->intermediator_model->get_application_details(array("app_ref_no" => $app_ref_no, 'mobile' => $this->session->userdata('mobile')));
    }

    if (property_exists($application_details, "applied_by")) {
      if ($application_details->pfc_payment_status !== "Y") {
        redirect('iservices/transactions');
        return;
      }
    }
    // var_dump($application_details);die;
    if ($application_details->service_id) {
      $departmental_data = $this->portals_model->get_departmental_data($application_details->service_id);
      if( $departmental_data->portal_no !== "11"){
      if (isset($departmental_data->payment_required) && $departmental_data->payment_required) {
        if ($application_details->pfc_payment_status !== "Y") {
          redirect('iservices/transactions');
          return;
        }
      }
    }
    } else
      redirect('iservices/transactions');
    $data = array();
    $data['response'] = $application_details;
    $data['timeline_days'] = $departmental_data->timeline_days;
    $data['department_name'] = $departmental_data->department_name;
    $data['service_name'] = $departmental_data->service_name;

    $this->load->view('includes/frontend/header');
    $this->load->view('noc_ack1', $data);
    $this->load->view('includes/frontend/footer');
  }

  function generateRandomString($length = 7)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    return (int)$number;
  }

  public function generate_id()
  {
    $date = date('ydm');
    $str = "AS" . $date . "A" . $this->generateRandomString(7);
    return $str;
  }
  public function retry($rtps_trans_id, $service_id, $portal_no)
  {
    if ($rtps_trans_id && $service_id) {
      $user = $this->session->userdata();
      $guidelines = $this->portals_model->get_guidelines($service_id);
      $external_service_id = property_exists($guidelines, "external_service_id") ? $guidelines->external_service_id : $guidelines->service_id;

      $target_url = isset($guidelines->url) ? $guidelines->url : '';
      $input_array = array(
        "rtps_trans_id" => $rtps_trans_id,
        "user_id" => $this->session->userdata("mobile"), //$user['userId']->{'$id'},
        "mobile" => $this->session->userdata("mobile"),
        "service_id" => intval($external_service_id),
        "portal_no" => $portal_no,
        "process" => "I",
        "response_url" => base_url("iservices/get/response")
      );
      $input_array = json_encode($input_array);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      $data = array(
        "data" => $enc,
        "action" => $target_url
      );
      //  $url=$target_url."?data=".urlencode($enc);
      // redirect($url);
      $this->load->view("retry", $data);
    } else {
      redirect(base_url("iservices/transactions"));
    }
  }
  public function procced()
  {
    $service_id = $this->input->post('service_id');
    $service_name = $this->input->post('service_name');
    $portal_no = $this->input->post('portal_no');
    $target_url = $this->input->post('url');
    $user = $this->session->userdata();

    $service_details = $this->portals_model->get_guidelines($service_id);
    if (empty($service_details)) {
      return false;
    }

    $external_service_id = property_exists($service_details, "external_service_id") ? $service_details->external_service_id : $service_details->service_id;

    $date = date('ydm');
    $rtps_trans_id = $this->generate_id();
    A1:
    if ($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)) {
      $rtps_trans_id = $this->generate_id();
      goto A1;
    }


    $input_array = array(
      "rtps_trans_id" => $rtps_trans_id,
      "user_id" => $this->session->userdata("mobile"), //$user['userId']->{'$id'},
      "mobile" => $this->session->userdata("mobile"),
      "service_id" => intval($external_service_id),
      "portal_no" => $portal_no,
      "process" => "N",
      "response_url" => base_url("iservices/get/response")
    );
    $input_array = json_encode($input_array);
    $aes = new AES($input_array, $this->encryption_key);
    $enc = $aes->encrypt();
    // $url=base_url()."request/create?data=".urlencode($enc);//var_dump($url);die;
    $url = $target_url . "?data=" . urlencode($enc);
    //AS200226V0313962/AS
    // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
    $user_data = array(
      'user_id' => $user['userId']->{'$id'},
      'mobile' => $user['mobile'],
      'rtps_trans_id' => $rtps_trans_id,
      "service_name" => $service_name,
      "portal_no" => $portal_no,
      "service_id" => intval($service_id),
      "external_service_id" => $external_service_id,
      'target_url' => $url,
      'return_url' => base_url("iservices/get/response"),
      "status" => "",
      "payment_rtps_end" => true,
      "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
    );
    if (empty($user_data['user_id']) || empty($user_data['mobile'])) {
      $status["status"] = false;
      $status["message"] = "Invalid User Credentials";
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
    $res = $this->intermediator_model->insert($user_data);
    $status = array();
    if ($res) {
      $status["status"] = true;
      $status["url"] = $url;
      $status["encrypted_data"] = $enc;
      $status["message"] = "Need to redirect to third party urls";
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    } else {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
  }



  private function is_admin()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      return true;
    } else {
      return false;
    }
  }
  private function my_transactions()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      redirect(base_url('iservices/admin/my-transactions'));
    } else {
      redirect(base_url('iservices/transactions'));
    }
  }


  //delete appliated
  public function archive_application($rtps_trans_id)
  {
    //soft delete
    $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id);
    if ($this->is_admin()) {
      $fillter = array('rtps_trans_id' => $rtps_trans_id, "applied_by" => new ObjectId($this->session->userdata('userId')->{'$id'}));
    } else {
      $fillter = array('rtps_trans_id' => $rtps_trans_id, 'mobile' => $this->session->userdata('mobile'));
    }

    if (!empty($transaction_data)) {
      if ($transaction_data->status !== "S") {
        if (empty($transaction_data->app_ref_no)) {
          $result = $this->intermediator_model->update_row($fillter, array('is_archived' => true));
          if ($result) {
            $this->session->set_flashdata('message', 'Application Archived Successfuly');
            $this->session->set_flashdata('status_code', 'success');
          }
        }
      }
    }
    $this->my_transactions();
  }

  //delete appliated
  public function unarchive_application($rtps_trans_id)
  {
    //soft delete
    $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id);
    if ($this->is_admin()) {
      $fillter = array('rtps_trans_id' => $rtps_trans_id, "applied_by" => new ObjectId($this->session->userdata('userId')->{'$id'}));
    } else {
      $fillter = array('rtps_trans_id' => $rtps_trans_id, 'mobile' => $this->session->userdata('mobile'));
    }

    if (!empty($transaction_data)) {
      $result = $this->intermediator_model->update_row($fillter, array('is_archived' => false));
      if ($result) {
        $this->session->set_flashdata('message', 'Application Unarchived Successfuly');
        $this->session->set_flashdata('status_code', 'success');
      }
    }
    redirect(base_url('iservices/archived-transactions'));
  }

  public function detail($rtps_trans_id)
  {

    if ($rtps_trans_id) {
      $data['intermediate_ids'] = $this->intermediator_model->get_my_transactions_by_trans($rtps_trans_id);
      $data['intermediate_ids'] = $this->prepare_transactions($data['intermediate_ids']);
      $user = $this->session->userdata();
      if (isset($user['role']) && !empty($user['role'])) {
        $this->load->view('includes/header');
        $this->load->view('admin/mytransactions/transactions', $data);
        $this->load->view('includes/footer');
      } else {
        $this->load->view('includes/frontend/header');
        $this->load->view('mytransactions/transactions', $data);
        $this->load->view('includes/frontend/footer');
      }
    } else {
      exit("Invalid request");
    }
  }
}
