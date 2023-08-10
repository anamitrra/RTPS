
<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Test extends Rtps
{

    public function index()
    {

        $html = $this->load->view('test', array(), true);

        $this->load->library('dpdf');
        
        $pdf_path = $this->dpdf->createPDF($html, 't', false);
        pre($pdf_path);
    }
}
