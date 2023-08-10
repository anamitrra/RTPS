<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
error_reporting(0);
class Exports extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("office/export_model");
        $this->load->library('excel');
        if ($this->session->userdata()) {
            if ($this->session->userdata('isAdmin') === TRUE) {
            } else {
              $this->session->sess_destroy();
              redirect('spservices/mcc/user-login');
            }
          } else {
            redirect('spservices/mcc/user-login');
          }
    }
    public function index()
    {
        $this->load->view("includes/office_includes/header", array("pageTitle" => " "));
        $this->load->view("office/report_download_view");
        $this->load->view("includes/office_includes/footer");
    }

    public function generate()
    {

        $status = $this->input->post('status');
        $community = $this->input->post('community');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        if (!empty($status) || !empty($community) || (!empty($start_date) && !empty($end_date))) {
            $applications = (array)$this->export_model->generate_report($status, $community, $start_date, $end_date);
            // pre($applications);
            // pre($applications->{'0'}->total);
            // $total = $applications->{'0'}->total;
            $total = count($applications);
            $limit = 5000;
            $download_filelink1 = '<h2 style="text-align:center">Download CSV</h2><hr><ol>';

            $last_page = ceil($total / $limit);
            $start = 0;
            $file_number = 0;
            for ($count = 0; $count < $last_page; $count++) {
                $file_number++;
                $object = new PHPExcel();
                $object->setActiveSheetIndex(0);
                $table_columns = array("Appl Ref. No", "Applicant name", "Contact No.", "Email", "Community", "Gender", "District", "Submission date", "Status");
                $column = 0;
                foreach ($table_columns as $field) {
                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column++;
                }
                // $excel_result = $this->export_model->generate_report($status, $community, $start_date, $end_date, $limit, $start);
                $excel_row = 2;
                foreach ($applications as $sub_row) {
                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $sub_row->service_data->appl_ref_no);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $sub_row->form_data->applicant_name);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $sub_row->form_data->mobile_number);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $sub_row->form_data->email_id);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $sub_row->form_data->community);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $sub_row->form_data->applicant_gender);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $sub_row->form_data->pa_district_name);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, format_mongo_date($sub_row->form_data->created_at ?? ''));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $sub_row->service_data->appl_status);

                    $excel_row++;
                }
                $start = $start + $limit;
                $object_writer = PHPExcel_IOFactory::createWriter($object, 'CSV');
                $file_name = 'applications-' . date("Y-m-d-H-i-s") . '-' . $file_number . '.csv';
                $object_writer->save('storage/csv/' . $file_name);
                $encrypt_file = base64_encode($file_name);
                $download_filelink1 .= '<li style="padding:5px;"><label><a class="btn btn-sm btn-success" href="' . base_url("spservices/office/download/$encrypt_file") . '" target="_blank">Download - ' . $file_name . ' &nbsp;<i class="fa fa-download"></i></a></label></li>';
            }

            $download_filelink1 .= '</ol>';
            // echo $download_filelink1;

            $this->load->view("includes/office_includes/header", array("pageTitle" => " "));
            $this->load->view("office/report_downloadlink_view", array('link' => $download_filelink1));
            // echo $download_filelink1;
            $this->load->view("includes/office_includes/footer");
        }
    }

    public function download($file)
    {
        $location = 'storage/csv/' . base64_decode($file);

        if (file_exists($location)) {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" .  substr($location, 12));
            readfile($location);
            //  unlink($file);
        } else {
            echo 'No File Found';
        }
    }
}
