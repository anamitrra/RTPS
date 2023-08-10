<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Transactions extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->config->load('rtps_services');
        $this->load->helper("minoritycertificate");
    }

    public function index()
    {
        $user = $this->session->userdata();
        if (!isset($user['role']) || empty($user['role'])) {
            redirect(base_url('iservices/transactions'));
            return;
        }
         //redirect to new list
            $transView=$this->input->cookie('trans_view', TRUE);
            // pre($transView);
            if(empty($transView) || $transView === 'new'){
            redirect(base_url('iservices/myapplications/list'));
            return;
            }
        if ($user['role'] === "csc") {
            $apply_by = $user['userId'];
            $role = "csc";
        } else {
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            $role = "pfc";
        }

        // pre($apply_by);
        $this->load->model('spservices/minoritycertificates/minoritycertificates_model');
        $this->load->model('spservices/necertificates_model');
        $this->load->model('spservices/marriageregistration/marriageregistrations_model');
        $this->load->model('spservices/appointmentbooking/appointmentbookings_model');
        $this->load->model('spservices/mutationorder/mutationorders_model');
        // $this->load->model('spservices/incomecertificate/income_registration_model');
        $this->load->model('spservices/prc/applications_model');
        $this->load->model('spservices/trade_permit/tradepermit_model');
        $this->load->model('spservices/employment_aadhaar_based/employment_model');
        $this->load->model('eodb/eodb_intermediator_model');
        $this->load->model('spservices/noncreamylayercertificate/Ncl_model');
        $this->load->model('spservices/kaac/Kaac_registration_model');

        // $this->load->helper("minoritycertificate");
        $this->load->helper("appstatus");
        $filter = array(
            "applied_user_id" => $apply_by,
        );

        //Employment Exchange
        $employment_exchange_reg = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "EMP_A_REG","service_data.appl_status" => array('$ne' => 'D')));

        $employment_exchange_renew = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "EMP_A_RENEW","service_data.appl_status" => array('$ne' => 'D')));

        $employment_exchange_rereg = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "EMP_A_RE_REG","service_data.appl_status" => array('$ne' => 'D')));

        $employment_exchange = array_merge((array)$employment_exchange_reg, (array)$employment_exchange_renew, (array)$employment_exchange_rereg);
        $data["employment_exchange"] = array_filter($employment_exchange);

        //registration of contractors
        $reg_of_contractors_1 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_REG_PWDB_1"));
        $reg_of_contractors_2 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_REG_PWDB_2"));
        $reg_of_contractors_3 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_REG_PHED_1"));
        $reg_of_contractors_4 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_REG_PHED_2"));
        $reg_of_contractors = array_merge((array)$reg_of_contractors_1, (array)$reg_of_contractors_2, (array)$reg_of_contractors_3, (array)$reg_of_contractors_4);
        $data["reg_of_contractors"] = array_filter($reg_of_contractors);

         //upgradation of contractors
         $upgr_of_contractors_1 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_UPGR_PWDB_1"));
         $upgr_of_contractors_2 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_UPGR_PWDB_2"));
         $upgr_of_contractors_3 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_UPGR_PHED_1"));
         $upgr_of_contractors_4 = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CON_UPGR_PHED_2"));
         $upgr_of_contractors = array_merge((array)$upgr_of_contractors_1, (array)$upgr_of_contractors_2, (array)$upgr_of_contractors_3, (array)$upgr_of_contractors_4);
         $data["upgr_of_contractors"] = array_filter($upgr_of_contractors);

        //Minority cerificates
        $data["minoritycertificates"] = $this->minoritycertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "MCC", "service_data.appl_status" => array('$ne' => 'D')));

        //NE Cerificate 
        $data["necertificaties"] = $this->necertificates_model->get_rows(array("applied_by" => $apply_by, "service_id" => "NECERTIFICATE", "status" => array('$ne' => 'D')));

        //Marriage Registration
        $data["marriageregistrations"] = $this->marriageregistrations_model->get_rows(array("applied_by" => $apply_by, "service_id" => "MARRIAGE_REGISTRATION", "status" => array('$ne' => 'D')));

        //Appointment Booking
        $data["appointmentbookings"] = $this->appointmentbookings_model->get_rows(array("form_data.user_id" => $apply_by, "service_data.service_id" => "APPOINTMENT_BOOKING", "service_data.appl_status" => array('$ne' => 'D')));

        //Mutation Order
        $data["mutationorders"] = $this->mutationorders_model->get_rows(array("form_data.user_id" => $apply_by, "service_data.service_id" => "MUTATION_ORDER", "service_data.appl_status" => array('$ne' => 'D')));

        // $data['intermediate_ids']=$this->intermediator_model->get_where(array('applied_by'=>$apply_by));
        $data["certifiedcopies"] = $this->necertificates_model->get_rows(array("applied_by" => $apply_by, "service_id" => "CRCPY", "status" => array('$ne' => 'D')));
        $data["seniorcitizencertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "SCTZN", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));
        $data["delayedbirthregistration"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PDBR", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));
        $data["nextofkincertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NOKIN", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));
        $data["delayeddeathcertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PDDR", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));
        $data["incomecertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "INC", "service_data.appl_status" => array('$ne' => 'D')));
        $data["castecertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "CASTE", "service_data.appl_status" => array('$ne' => 'D')));

        $data["buildingpermissioncertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PPBP", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));

        $data["apdcl"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "apdcl1", "service_data.appl_status" => array('$ne' => 'D')));
        // $data["prc"] = $this->applications_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"PRC")); 

        $data["prc"] = $this->applications_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PRC", "service_data.appl_status" => array('$ne' => 'D')));

        $data["ncl"] = $this->Ncl_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NCL", "service_data.appl_status" => array('$ne' => 'D')));


        $data["tp"] = $this->tradepermit_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NC-TP", "service_data.appl_status" => array('$ne' => 'D')));

        $data["bakijai"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "BAKCL", "form_data.service_plus_data" => array("\$exists" => false), "service_data.appl_status" => array('$ne' => 'D')));

        //Employment Exchange
        //$data["employment_exchange"] = $this->employment_model->get_rows(array("service_data.applied_by" => $apply_by,"service_data.service_id" => "EMP_A_REG"));

        //$data["ncl"] = $this->Ncl_model->get_rows(array("form_data.mobile_number" => $mobile, "service_data.service_id" => "NCL"));

        $data["acmraddlregdegrees"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "ACMRREGAD", "service_data.appl_status" => array('$ne' => 'D')));

        $data["acmrprovisionalcertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "ACMRPRCMD", "service_data.appl_status" => array('$ne' => 'D')));

        $data["permanent_registration_mbbs"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PROMD","service_data.appl_status"=>array('$ne'=>'D')));

        $data["acmrnoc"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "ACMRNOC","service_data.appl_status"=>array('$ne'=>'D')));
    
        $data["offline_ack_apps"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.is_offline" => true,"service_data.appl_status"=>array('$ne'=>'D')));

        ///For GMDA-Building Permission Service 
        // $ref = modules::load('spservices/buildingpermission/registration');
        // $sp_build_app = $ref->service_sp_list();
        // if ((is_array($sp_build_app)) && (count($sp_build_app) > 0)) {
        //     $data["spbuildingpermissioncertificate"] = $sp_build_app;
        // } else {
            $data["spbuildingpermissioncertificate"] = 0;
        // }

        ///////////////////
        $data["acmr_cp_cme"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "CPCME", "service_data.appl_status" => array('$ne' => 'D')));

        // $data['intermediate_ids'] = $this->intermediator_model->get_admin_transactions($apply_by, $role);
        $data['intermediate_ids'] = $this->intermediator_model->get_admin_pending_transactions($apply_by, $role);
        $data['intermediate_ids'] = $this->prepare_transactions($data['intermediate_ids']);
        $data["eodb_transactions"] = $this->eodb_intermediator_model->get_rows(array("user_id" => $apply_by, "appl_status" => array("\$exists" => true, '$ne' => 'P')));

        //Fetching list of RTPS services applied from SERVICE PLUS
        if (!$this->session->userdata('curl_executed_sp_rtps_p')) {
            $ref = modules::load('iservices/serviceplus/sprtps');
            $ref->get_sp_pending_applications('RTPS');
        }
        $data["serviceplus_transactions_rtps"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_no" => $apply_by, "form_data.portal" => "RTPS", "form_data.appl_status" => array('$ne' => 'D')));

        //Fetching list of EODB services applied from SERVICE PLUS
        if (!$this->session->userdata('curl_executed_sp_eodb_p')) {
            $ref = modules::load('iservices/serviceplus/sprtps');
            $ref->get_sp_pending_applications('EODB');
        }
        $data["serviceplus_transactions_eodb"] = $this->eodb_intermediator_model->get_rows(array("user_data.sign_no" => $apply_by, "form_data.portal" => "EODB", "form_data.appl_status" => array('$ne' => 'D')));


         //Employment Exchange Non-Aadhaar
        $employment_exchange_rereg_nonaadhaar = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "EMP_REREG_NA", "service_data.appl_status" => array('$ne' => 'D')));
        $employment_exchange_renonaadhaar = array_merge((array)$employment_exchange_rereg_nonaadhaar);
        $data["employment_exchange_renonaadhaar"] = array_filter($employment_exchange_renonaadhaar);

        $data["ahsec_correction_rc"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "AHSECCRC", "service_data.appl_status" => array('$ne' => 'D')));
        $data["ahsec_correction_adm"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "AHSECCADM", "service_data.appl_status" => array('$ne' => 'D')));
        $data["ahsec_correction_marksheet"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "AHSECCMRK", "service_data.appl_status" => array('$ne' => 'D')));
        $data["ahsec_correction_pc"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "AHSECCPC", "service_data.appl_status" => array('$ne' => 'D')));
    
        // KAAC Services
        $data["kaac_noctl"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "NOCTL", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_income"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "ICERT", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_DCTH"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "DCTH", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_CCJ"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CCJ", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_CCM"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "CCM", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_DLP"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "DLP", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_LVC"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "LVC", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_LRCC"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "LRCC", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_LHOLD"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "LHOLD", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_ITMKA"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "ITMKA", "service_data.appl_status" => array('$ne' => 'D')));
        $data["kaac_NECKA"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "NECKA", "service_data.appl_status" => array('$ne' => 'D')));
        
        $data["kaac_farmer"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "FCERT", "service_data.appl_status" => array('$ne' => 'D')));
        // pre($data["kaac_farmer"]);
        $data["kaac_BRC"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "KBRC", "service_data.appl_status" => array('$ne' => 'D')));

        // pre($data["kaac_BRC"]);
        $data["kaac_RBRC"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "form_data.service_id" => "KRBC", "service_data.appl_status" => array('$ne' => 'D')));


        
        $data['pageTitle'] = "Transactions";

        $this->load->view('includes/header');
        // $this->load->view('admin/transactions',$data);
        $this->load->view('admin/mytransactions/transactions', $data);
        $this->load->view('includes/footer');
    }

    public function prepare_transactions($data)
    {
        $service = array();
        $vahan_services = array();
        $noc_services = array();
        $sarathi_services = array();
        $other_services = array();
        $basundhara_services = array();
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
                } else {
                    array_push($other_services, $trans);
                }
            }
        }
        return array(
            "vahan_services" => $vahan_services,
            "noc_services" => $noc_services,
            "sarathi_services" => $sarathi_services,
            "other_services" => $other_services,
            "basundhara_services" => $basundhara_services,
        );
    }
}
