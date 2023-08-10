<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Servicelist extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/roles_model');
        $this->load->library('form_validation');
    }

    private function get_services(){
        
     
        $kiosk_type = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        $this->mongo_db->switch_db('portal');
        $filter=array(
            'online'=>true,
            'kiosk_availability'=>['$in'=>['ALL',$kiosk_type]]
        );
        $collection = 'services';
        $this->mongo_db->select(array('service_id','service_name.en','service_url','seo_url'));
        // $this->mongo_db->limit(5);
        $data=$this->mongo_db->get_data_like($filter, $collection);
        $this->mongo_db->switch_db('iservices');
        return $data;
    
    }
    public function index(){
        $services=$this->get_services();
     
        $data['service_list']=$services;
     
        $this->load->view('includes/header', array('pageTitle' => 'Services'));
        $this->load->view('admin/services/all_service',$data);
        $this->load->view('includes/footer');
    }
    public function index_old($service='')
    {
        

        $service=[];
        $service[]=array("service_name"=>"Duplicate Registration Certificate for Non-transport Vehicle","url"=>base_url("iservices/admin/vahan/3"));
        $service[]=array("service_name"=>"Duplicate Registration Certificate for Transport Vehicle","url"=>base_url("iservices/admin/vahan/3"));
        $service[]=array("service_name"=>"Ownership Transfer for Non-Transport/Transport Vehicle","url"=>base_url("iservices/admin/vahan/5"));
        $service[]=array("service_name"=>"No Objection Certificate for Transfer of Record of Registration to Other Registering Authority","url"=>base_url("iservices/admin/vahan/9"));
        $service[]=array("service_name"=>"Certificate of Fitness of Transport Vehicles","url"=>base_url("iservices/admin/vahan/2"));
        $service[]=array("service_name"=>"Address change in RC","url"=>base_url("iservices/admin/vahan/4"));
        $service[]=array("service_name"=>"Hypothecation Addition ","url"=>base_url("iservices/admin/vahan/6"));
        $service[]=array("service_name"=>"Hypothecation Termination  ","url"=>base_url("iservices/admin/vahan/7"));

        $service[]=array("service_name"=>"NOC for Gift, Lease and Mortgage ","url"=>base_url("iservices/admin/guidelines/11/1"));
        $service[]=array("service_name"=>"NOC for Re Classification ","url"=>base_url("iservices/admin/guidelines/12/1"));
        $service[]=array("service_name"=>"NOC for Re Classification cum transfer ","url"=>base_url("iservices/admin/guidelines/13/1"));
        $service[]=array("service_name"=>"NOC for Composite service   ","url"=>base_url("iservices/admin/guidelines/14/1"));
        
        $service[]=array("service_name"=>"Learner Licence for Transport   ","url"=>base_url("iservices/admin/sarathi/guidelines/2703/4"));
        $service[]=array("service_name"=>"Learner Licence for Non-Transport   ","url"=>base_url("iservices/admin/sarathi/guidelines/2703/4"));
        $service[]=array("service_name"=>"Diving Licence for Transport ","url"=>base_url("iservices/admin/sarathi/guidelines/2222/4"));
        $service[]=array("service_name"=>"Diving Licence for Non-Transport ","url"=>base_url("iservices/admin/sarathi/guidelines/2223/4"));
        $service[]=array("service_name"=>"Duplicate Driving License ","url"=>base_url("iservices/admin/sarathi/guidelines/2744/4"));
        $service[]=array("service_name"=>"Renewal of Driving License for transport","url"=>base_url("iservices/admin/sarathi/guidelines/2745/4"));
        $service[]=array("service_name"=>"Renewal of Driving License for Non Transport ","url"=>base_url("iservices/admin/sarathi/guidelines/2746/4"));

        $service[]=array("service_name"=>"MUTATION BY INHERITANCE","url"=>base_url("iservices/basundhara/guidelines/231/5"));
        $service[]=array("service_name"=>"MUTATION BY DEED ","url"=>base_url("iservices/basundhara/guidelines/232/5"));
        $service[]=array("service_name"=>"Allotment Certificate to Periodic Patta ","url"=>base_url("iservices/basundhara/guidelines/235/5"));
        $service[]=array("service_name"=>"STRIKING OUT NAME ","url"=>base_url("iservices/basundhara/guidelines/238/5"));
        $service[]=array("service_name"=>"NAME CORRECTION ","url"=>base_url("iservices/basundhara/guidelines/236/5"));
        $service[]=array("service_name"=>"AREA CORRECTION ","url"=>base_url("iservices/basundhara/guidelines/237/5"));
        $service[]=array("service_name"=>"MOBILE UPDATION  ","url"=>base_url("iservices/basundhara/guidelines/240/5"));
        $service[]=array("service_name"=>"Certified Copy of Jamabandi or Records of Right/Chitha  ","url"=>base_url("iservices/basundhara/guidelines/242/5"));
        // $service[]=array("service_name"=>"Field Partition  ","url"=>base_url("iservices/basundhara/guidelines/233/5"));
        // $service[]=array("service_name"=>"Reclassification of land less than one bigha   ","url"=>base_url("iservices/basundhara/guidelines/234/5"));
         $service[]=array("service_name"=>"Settlement of Occupancy Tenant   ","url"=>base_url("iservices/basundhara/guidelines/243/5"));
         $service[]=array("service_name"=>"Settlement of AP Transferred Land from Original AP Holder   ","url"=>base_url("iservices/basundhara/guidelines/244/5"));
         $service[]=array("service_name"=>"Settlement of Hereditary Land of Tribal Communities   ","url"=>base_url("iservices/basundhara/guidelines/245/5"));
         $service[]=array("service_name"=>"Settlement of Khas and Ceiling Surplus Land   ","url"=>base_url("iservices/basundhara/guidelines/246/5"));
         $service[]=array("service_name"=>"Settlement of PGR VGR Land   ","url"=>base_url("iservices/basundhara/guidelines/247/5"));
         $service[]=array("service_name"=>"Settlement of special cultivators  ","url"=>base_url("iservices/basundhara/guidelines/248/5"));
         $service[]=array("service_name"=>"e-Khajana   ","url"=>base_url("iservices/basundhara/guidelines/249/5"));
        
        $service[]=array("service_name"=>"Water connection ( GMDW&SB)   ","url"=>base_url("iservices/admin/guidelines/901/9"));
        $service[]=array("service_name"=>"Online Application for Water Connection  ","url"=>base_url("iservices/admin/guidelines/701/7"));
        $service[]=array("service_name"=>"Online Payment of Bill  ","url"=>base_url("iservices/admin/guidelines/702/7"));
        $service[]=array("service_name"=>"Property Assessment   ","url"=>base_url("iservices/admin/guidelines/1101/11"));
        $service[]=array("service_name"=>"Property Tax payment   ","url"=>base_url("iservices/admin/guidelines/1102/11"));


        $service[]=array("service_name"=>"Issuance of Caste Certificate ","url"=>base_url("spservices/castecertificate/registration"));
        $service[]=array("service_name"=>"Issuance of Income Certificate ","url"=>base_url("spservices/income-certificate"));
        $service[]=array("service_name"=>"Application for new Low Tension connection (APDCL) ","url"=>base_url("spservices/apdcl_form"));
        $service[]=array("service_name"=>"Issuance of Certified Copy of Registered Sale Deeds","url"=>base_url("spservices/registereddeed"));
        $service[]=array("service_name"=>"Issuance of Non-Encumbrance Certificate","url"=>base_url("spservices/necertificate"));
        $service[]=array("service_name"=>"Issuance Of Marriage Registration - Application for Marriage Registration","url"=>base_url("spservices/marriageregistration/"));
        $service[]=array("service_name"=>"Issuance of Senior Citizen Certificate","url"=>base_url("spservices/senior-citizen-certificate"));
        $service[]=array("service_name"=>"Permission for Delayed Birth Registration","url"=>base_url("spservices/delayed-birth-registration"));
        $service[]=array("service_name"=>"Issuance of Bakijai Clearance Certificate","url"=>base_url("spservices/bakijai-clearance-certificate"));
        $service[]=array("service_name"=>"Issuance of Next of Kin Certificate","url"=>base_url("spservices/nextofkin/registration"));
        $service[]=array("service_name"=>"Permission for Delayed Death Registration","url"=>base_url("spservices/delayeddeath/registration"));


        $data['service_list']=$service;
     
        $this->load->view('includes/header', array('pageTitle' => 'Services'));
        $this->load->view('admin/services/all_service',$data);
        $this->load->view('includes/footer');
    }
    public function item($service=''){
        echo "not available";die;
        $this->load->view('includes/header', array('pageTitle' => 'Services'));
        $this->load->view('admin/services/list',array('service'=>$service));
        $this->load->view('includes/footer');
    }



}
