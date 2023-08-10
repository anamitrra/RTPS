
<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;


class Dpdf
{
    function createPDF($html, $filename = '', $paper = 'A4', $orientation = 'landscape', $folder=null, $param=false)
    {
        $options = new Options();
        $options->set('chroot', realpath(''));
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        
        if($param){
            $storage_location = 'storage/' . $folder . '/';
        }else{
            $storage_location = 'storage/' . $folder . date("Y") . '/' . date("m") . '/' . date("d") . '/';
        }
        $pathname = FCPATH . $storage_location;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        
        $output = $dompdf->output();
        $file_path = $storage_location . $filename.'.pdf';
        file_put_contents($pathname . $filename.'.pdf', $output);
        // if ($download) {
        //     $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
        // } else {
        //     $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
        // }
        return $file_path;
    }
}
