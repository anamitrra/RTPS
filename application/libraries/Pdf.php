<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH . "third_party/tcpdf/tcpdf.php";
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
    public function generate($htm, $fileNamePrefix = 'Sample',$option = 'I', $author = "NIC")
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetHeaderMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(10);
        $pdf->SetAuthor($author);
        //$pdf->setFooterData(array(0, 65, 0), array(0, 65, 127));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($htm, true, 0, true, 0);
        $pdf->lastPage();
        $filename = $fileNamePrefix . date("YmdHis", time()) . '.pdf';
        $pdf->Output($filename, $option);
    }
    public function save($htm, $fileNamePrefix = 'rtps', $author = "NIC")
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetHeaderMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(10);
        $pdf->SetAuthor($author);
        //$pdf->setFooterData(array(0, 65, 0), array(0, 65, 127));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($htm, true, 0, true, 0);
        $pdf->lastPage();
        
        $path = FCPATH.'storage/pdf';
        // Supply a filename including the .pdf extension
        $filename = $fileNamePrefix . date("YmdHis", time()) . '.pdf';
        // Create the full path
        $full_path = $path . '/' . $filename;        
        $pdf->Output($full_path, 'F');
        return $full_path;
    }
    public function base_64($htm, $fileNamePrefix = 'rtps', $author = "NIC")
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetHeaderMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(10);
        $pdf->SetAuthor($author);
        //$pdf->setFooterData(array(0, 65, 0), array(0, 65, 127));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($htm, true, 0, true, 0);
        $pdf->lastPage();

        $path = FCPATH.'storage/pdf';
        // Supply a filename including the .pdf extension
        $filename = $fileNamePrefix . date("YmdHis", time()) . '.pdf';
        // Create the full path
        $full_path = $path . '/' . $filename;
        return $pdf->Output($full_path, 'E');
    }
    public function to_string($htm, $fileNamePrefix = 'rtps', $author = "NIC")
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetHeaderMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(10);
        $pdf->SetAuthor($author);
        //$pdf->setFooterData(array(0, 65, 0), array(0, 65, 127));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($htm, true, 0, true, 0);
        $pdf->lastPage();

        $path = FCPATH.'storage/pdf';
        // Supply a filename including the .pdf extension
        $filename = $fileNamePrefix . date("YmdHis", time()) . '.pdf';
        // Create the full path
        $full_path = $path . '/' . $filename;
        return $pdf->Output($full_path, 'S');
    }
    
    public function get_pdf($htm, $dirName='', $fileName = 'rtps') {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetHeaderMargin(5);
        $pdf->SetTopMargin(5);
        $pdf->setFooterMargin(5);
        $pdf->SetAuthor('NIC');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($htm, true, 0, true, 0);
        $pdf->lastPage();        
        $dirPath = FCPATH.'storage/docs/'.$dirName;
        $filename = $fileName.'.pdf';
        $full_path = $dirPath . '/' . $filename;        
        $pdf->Output($full_path, 'F');
        // return $full_path;
    }//End of get_pdf()
}
