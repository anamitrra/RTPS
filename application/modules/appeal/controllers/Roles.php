<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Roles extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('roles_model');
        $this->load->library('form_validation');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    public function index()
    {
        $access_list = $this->roles_model->get_all_access_list();
        $this->load->view('includes/header', array('pageTitle' => 'roles'));
        $this->load->view('roles/roles_list', array("access_list" => $access_list));
        $this->load->view('includes/footer');
    }

    public function get_records()
    {
        $columns = array(
            "0" => "sl_no",
            "1" => "role_id",
            "2" => "role_name",
            "3" => "action",
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int)$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->roles_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->roles_model->all_rows($limit, $start, $order, $dir);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->roles_model->roles_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->roles_model->roles_tot_search_rows($search);
            $totalFiltered = count((array)$totalFiltered);
        }
        $data = array();

        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //pre($rows);
                $objId = $rows->{"_id"}->{'$id'};
                $btns = '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="editRole"><span class="fa fa-edit" aria-hidden="true"></span></a>';
                $nestedData["sl_no"] = $sl;
                $nestedData["role_name"] = (isset($rows->role_name)) ? $rows->role_name : "";
                $nestedData["role_slug"] = (isset($rows->slug)) ? $rows->slug : "";
                $nestedData["action"] = $btns;
                $data[] = $nestedData;
                $sl++;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }

    public function get_role_info()
    {
        $role_id = $this->input->post("role_id", TRUE);
        $role = $this->roles_model->get_role_info($role_id);
        $access_list = $this->roles_model->get_all_access_list();
        $array_access_list = (array)$access_list;
        foreach ($role->permissions_array as $val) {
            //print_r($val->{'_id'}->__toString());
            foreach ($array_access_list as $key => $value) {
                if ($val->{'_id'}->__toString() == $value->{'_id'}->{'$id'}) {
                    $array_access_list[$key]->checked = True;
                }
            }
        }
//        pre($array_access_list);
        if (isset($role)) {
            $json_data = array(
                "status" => true,
                "data" => $role,
                "permissions" => $array_access_list
            );
        } else {
            $json_data = array(
                "status" => false,
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }

    public function add()
    {
        $this->_rules();
        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
            $arr = $this->input->post('roles', true);
            if (!empty($arr)) {
                $role_ids = array_map(function ($val) {
                    return new ObjectId($val);
                }, $arr);
            } else {
                $role_ids = [];
            }
    //pre($this->input->post());
            $data = array(
                'role_name' => $this->input->post('role_name', true),
                'slug' => $this->input->post('role_slug', true),
                'permissions' => $role_ids,
            );
    
            $result = $this->roles_model->insert($data);
            //pre($result);
            if (isset($result['_id']->{'$id'})) {
                $json_data = array(
                    "status" => true
                );
            } else {
                $json_data = array(
                    "status" => false
                );
            }
    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($json_data));
        }
       
    }

    public function update()
    {
        if(empty($this->input->post('role_id', true))){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => "No role found"
            )));
        }else{
            $role_id = $this->input->post('role_id', true);
            if(preg_match('/^[0-9a-f]{24}$/i', $role_id) === 0){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "No role found"
                )));
            }
            $this->form_validation->set_rules('role_name', ' ', 'trim|required|xss_clean|alpha');
            if ($this->form_validation->run() == false) {
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => validation_errors()
                )));
            }else{
                $arr = $this->input->post('editroles', true);
                if (!empty($arr) && $arr != "") {
                    $role_ids = array_map(function ($val) {
                        return new ObjectId($val);
                    }, $arr);
                }
                $data = array(
                    'role_name' => $this->input->post('role_name', true),
                    'permissions' => $role_ids ?? [],
                );
                
                $result = $this->roles_model->update($role_id, $data);
                if ($result->getMatchedCount()) {
                    $json_data = array(
                        "status" => true
                    );
                } else {
                    $json_data = array(
                        "status" => false
                    );
                }
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($json_data));
            }
        }
       
        
    }

    public function update_action()
    {
        $this->form_validation->set_rules('role_name', ' ', 'trim|required|xss_clean|alpha');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', 'Invalid Input');
            redirect(site_url('roles'));
        }else{
            if ($this->form_validation->run() == false) {
                $this->update($this->input->post('roleId', true));
            } else {
                $data = array(
                    'role_name' => $this->input->post('role_name', true),
                );
    
                $this->roles_model->update($this->input->post('roleId', true), $data);
                $this->session->set_flashdata('message', 'Update Record Success');
                redirect(site_url('roles'));
            }
        }
       
    }

    public function delete($id)
    {
        $row = $this->roles_model->get_by_id($id);

        if ($row) {
            $this->roles_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('roles'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('roles'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('role_name', ' ', 'trim|required|xss_clean|alpha');
        $this->form_validation->set_rules('role_slug', ' ', 'trim|required|xss_clean|alpha');

        $this->form_validation->set_rules('roleId', 'roleId', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

;

/* End of file Roles.php */
/* Location: ./application/controllers/Roles.php */
