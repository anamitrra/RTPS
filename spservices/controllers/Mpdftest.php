<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mpdftest extends frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('necertificates_model');
    }//End of __construct()

    public function index() {
        $this->load->library("ciqrcode");
        $params['data'] = base_url('spservices/necapi/verifycertificate/test');
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = 'storage/temps/test.png';
        $this->ciqrcode->generate($params);

        $html = '<html><head></head><body style="text-align:center; padding:10%; font-size:32px">'
                . 'PDF generation using MPDF is working fine<br><br><br>'
                . '<img src="' . FCPATH . 'storage/temps/test.png" style="width: 35mm; height: 35mm" />'
                . '</body></html>';

        require FCPATH . 'vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L']);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        $dirPath = 'storage/docs/' . $this->serviceId . '/';
        $fileName = 'test.pdf';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
            file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NEC only</body></html>');
        }
        $filePath = $dirPath . $fileName;
        $mpdf->Output($filePath, 'D');
    }//End of index()

    public function tcpdf($objId = null) {
        $this->load->library("ciqrcode");
        /* $params['data'] = base_url('spservices/necapi/verifycertificate/test');
          $params['level'] = 'H';
          $params['size'] = 10;
          $params['savename'] = 'storage/temps/test.png';
          $this->ciqrcode->generate($params);

          $html = '<html><head></head><body style="text-align:center; padding:10%; font-size:32px">'
          . 'PDF generation using MPDF is working fine<br><br><br>'
          .'<img src="'.FCPATH.'storage/temps/test.png" style="width: 35mm; height: 35mm" />'
          . '</body></html>';

          $this->load->library('pdf');
          $filePath = $this->pdf->get_pdf($html, 'NECERTIFICATE', 'test');
          $pcs = explode('storage', $filePath);
          echo '<a href="'.base_url('storage'.$pcs[1]).'" target="_blank">View</a>'; */

        $dbRow = $this->necertificates_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array("dbrow" => $dbRow);
            $html = $this->load->view('nec/generatecertificate_view', $data, true);
            $this->load->library('pdf');
            $fullPath = $this->pdf->get_pdf($html, 'NECERTIFICATE', str_replace('/', '-', $dbRow->rtps_trans_id));
            $pathExplode = explode('storage', $fullPath);
            $filePath = 'storage' . $pathExplode[1];
            echo '<a href="' . base_url($filePath) . '" target="_blank">View</a>';
        } else {
            die("Error");
        } //End of if else
    }//End of tcpdf()
}//End of Mpdftest