<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Digilocker_service_settings extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('digilockermaster/doctype_service_settings');
    }

    public function index($obj_id = null)
    {
        $data["dbrow"] = $this->doctype_service_settings->get_row(array("_id" => new ObjectId($obj_id)));
        $data["settings_data"] = $this->doctype_service_settings->get_rows();

        $this->load->view('includes/frontend/header');
        $this->load->view('digilockermaster_view/service_settings_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit()
    {

        $service_name = $this->input->post('service_name');
        $api_key = $this->input->post('api_key');
        $doc_type = $this->input->post('doc_type');
        $db = $this->input->post('db');
        $collection = $this->input->post('collection');
        $multi_service = $this->input->post('multi_service');
        $parameter_values = $this->input->post('parameter_values');
        $mis_service_name = $this->input->post('mis_service_name');

        $api_use = ($this->input->post('api_use') == 'Yes') ? true : false;
        $api_url = $this->input->post('api_url');
        $udf_parameters = $this->input->post('udf_parameters');
        $db_field_name = $this->input->post('db_field_name');
        $api_parameter_label = $this->input->post('api_parameter_label');
        $digilocker_api_parameters = $this->input->post('digilocker_api_parameters');


        $udfs = [];
        for ($i = 0; $i < count($udf_parameters); $i++) {
            $udfs[$db_field_name[$i]] = $udf_parameters[$i];
        }


        $data = [
            "serice_name" => $service_name,
            "api_key" => $api_key,
            "doctype" => $doc_type,
            "udfs" => $udfs,
            // "multi_services" => $multi_services,
            "db" => $db,
            "collection" => $collection,
            "useAPI" => $api_use,
            // "api_parameters" => $api_params,
            // "api_url" => $api_url
        ];

        $multi_services = [];
        if ($multi_service == 'Yes') {
            for ($i = 0; $i < count($parameter_values); $i++) {
                $multi_services[$parameter_values[$i]] = $mis_service_name[$i];
            }
            $data['multi_services'] = $multi_services;
        }

        $api_params = [];
        if ($api_use) {
            for ($i = 0; $i < count($api_parameter_label); $i++) {
                $api_params[$api_parameter_label[$i]] = $digilocker_api_parameters[$i];
            }
            $data['api_parameters'] = $api_params;
            $data['api_url'] = $api_url;
            $data['useAPI'] = $api_use;
        }

        // pre($data);
        pre($this->input->post());

        $this->mongo_db->insert('digilocker_service_settings', $data);

    }
}
