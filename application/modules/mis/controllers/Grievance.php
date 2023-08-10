<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grievance extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("grievance/public_grievance_model",'public_grievance_model');
    }

    public function index(){
        $this->load->model('grievance/department_model','department_model');
        $this->load->model('grievance/district_model','district_model');
        $departmentList = $this->department_model->get_where([]);
        $districtList = $this->district_model->get_where([]);
        $data= [
            'departments'=>$departmentList,
            'districtList'=>$districtList,
        ];
        $this->load->view("includes/header", array("pageTitle" => "MIS | Grievance"));
        $this->load->view("grievance/index",$data);
        $this->load->view("includes/footer");
    }

    public function get_services($dept_id){
        $this->load->model('grievance/services_model','services_model');
        $services = $this->services_model->get_where_selected(['department_id' => $dept_id] ,['service_id','service_name']);
        echo json_encode($services);
    }

    public function get_records(){
        $serviceId = $this->input->post('serviceId');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $districtId = $this->input->post('district');
        $columns = array(
            0 => "RegistrationNumber",
            1 => "Name",
            2 => "grievanceCategory",
            3 => "DateOfReceipt",
            4 => "CurrentStatus",
        );
        $filterArray['serviceId'] = $serviceId;
        $filterArray['District'] = $districtId;
        if(isset($startDate) && isset($endDate)){
            $filterArray["DateOfReceipt"] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = ($this->input->post("order")[0]["dir"] === 'asc') ? 1 : -1;
        $totalData = $this->public_grievance_model->total_rows();
        if (empty($this->input->post("search")["value"])) {
            $records = $this->public_grievance_model->get_with_status($filterArray, [], $start, $limit, [$order => $dir]);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $searchArray = [
                'RegistrationNumber' => $search,
                'Name' => $search,
                'DateOfReceipt' => $search,
                'CurrentStatus' => $search,
            ];
            $records = $this->public_grievance_model->get_with_status($filterArray,$searchArray,$start,$limit,[$order => $dir]);
        }
        $totalFiltered = count((array)$this->public_grievance_model->get_with_status($filterArray));
//      pre($totalFiltered);
        $data = array();

        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $row) {
                $nestedData["#"] = ++$sl_no;
                $nestedData["RegistrationNumber"] = $row->registration_no;
                $nestedData["Name"] = $row->Name;
                $grievanceCategory = property_exists($row,'grievanceCategory') ? $row->grievanceCategory : 'NA';
                $nestedData["grievanceCategory"] = get_grievance_category_text_by_slug($grievanceCategory);
                $nestedData["DateOfReceipt"] = date('d-m-Y g:i a',intval(strval($row->DateOfReceipt))/1000);
                $nestedData["CurrentStatus"] = $row->grievance_data->CurrentStatus;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function counted_statistics(){
        // get total grievance received
        $this->load->model('grievance/public_grievance_model','public_grievance_model');
        $filterForCurrentYearGrievance["DateOfReceipt"] = [
            "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime( '01-01'.date('-Y')) * 1000)
        ];
        $current_year_grievances = $this->public_grievance_model->get_with_status($filterForCurrentYearGrievance);
        $current_year_grievance_count = count((array)$current_year_grievances);

        $under_process = 0;
        $pending = 0;
        $resolved = 0;
        foreach ($current_year_grievances as $grievance){
            switch ($grievance->grievance_data->CurrentStatus){
                case 'Grievance received':
                    $pending++;
                    break;
                case 'Case closed':
                    // TODO :: status confirm and update flag
                    $resolved++;
                    break;
                default:
                    $under_process++;
                break;
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'total'         => $current_year_grievance_count,
                'under_process' => $under_process,
                'pending'       => $pending,
                'resolved'      => $resolved,
            ]));
    }
}