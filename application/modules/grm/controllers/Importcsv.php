<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Importcsv extends frontend {

    //private $filePath = "D:\DATAS\GRM\grievance.csv";
    private $filePath = "D:\DATAS\GRM\pending_grievances.csv";
    
    public function __construct() {
        parent::__construct();
        $this->load->model('pending_grievances_model');
    }//End of __construct()
  
    public function index() {
        $word = "RTPS-";    
        if (file_exists($this->filePath)) {   
            $file_to_read = fopen($this->filePath, 'r');
            $lineNo = 0;
            $counter = 0;
            while (($line = fgetcsv($file_to_read)) !== FALSE) {
                if($lineNo){ //Skip the heading line
                    $GrievanceReferenceNumber = trim($line[0]);
                    $subContent = trim($line[3]);
                    $subjectContent = str_replace(array(".","'",",",")"), "", $subContent);
                    $registration_no = trim($line[4]);
                    $startPos = strpos($subjectContent, $word);
                    if($startPos) { //Only for matched row
                        $fromStr = trim(substr($subjectContent, $startPos-1, 50));
                        $rtps30 = trim(substr($subjectContent, $startPos, 30));
                        $explodeData = explode(" ", trim($rtps30));
                        $rtpsno = $explodeData[0];//$string = str_replace(".", "", $explodeData[0]);
                         echo "Ref No : <strong>{$rtpsno}</strong> from <i>{$fromStr}</i><br><br>";
                        $dbRow = $this->pending_grievances_model->get_row(["GrievanceReferenceNumber"=>$GrievanceReferenceNumber, "registration_no"=>$registration_no]);
                        if($dbRow) {
                            $objId = $dbRow->{"_id"}->{'$id'};
                            $data = array("rtps_ref_no"=>$rtpsno);
                            $this->pending_grievances_model->update($objId, $data);
                            echo "Ref No : <strong>{$rtpsno}</strong> from <i>{$fromStr}</i><br><br>";
                            $counter++;
                        }//End of if
                    }//End of if
                }//End of if
                $lineNo++;
            }//End of while
            fclose($file_to_read);
            echo "Total no. of records found : {$counter}";
        } else {
            echo "File does not exist at {$this->filePath}";
        }//End of if else            
    }//End of index()
    
    public function check() {
        $filter = array("rtps_ref_no" => ['$exists'=>true]); //Only for open applications
        $results = $this->pending_grievances_model->get_rows($filter);
        if($results) {
            $counter = 0;
            foreach ($results as $rows) {
                $objId = $rows->{"_id"}->{'$id'};
                $registration_no=$rows->registration_no;
                $explodeData = explode(" ", $rows->rtps_ref_no);
                $rtpsno = $explodeData[0];
                //echo $rtpsno.' FROM '.$rows->rtps_ref_no.' FROM '.$rows->SubjectContent.'<br><br>';
                echo "{$counter}. Ref No : <strong>{$rtpsno}</strong> from <i>{$rows->rtps_ref_no}</i><br><br>";
                $counter++;
            }//End of foreach()
            //echo "Toatal no. of records updated : ".$counter;
        }//End of if
    }//End of index()
    
    function pending() {
        $filter = array("current_status" => ['$ne'=>"Case closed"]); //Only for open applications
        $results = $this->pending_grievances_model->get_rows($filter);
        if($results) {
            $counter = 0;
            foreach ($results as $rows) {
                $objId = $rows->{"_id"}->{'$id'};
                $registration_no=$rows->registration_no;
                $rtps_ref_no=$rows->rtps_ref_no??'';
                $explodeData = explode(" ", $rtps_ref_no);
                $rtpsno = $explodeData[0];
                echo "Ref No : <strong>{$rtpsno}</strong> from <i>{$rtps_ref_no}</i> status <strong>{$rows->current_status}</strong><br><br>";
            }//End of foreach()
            //echo "Toatal no. of records updated : ".$counter;
        }//End of if
    }
}//End of Importcsv