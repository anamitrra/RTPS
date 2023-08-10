<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


    require_once FCPATH.'/application/third_party/autoloader.php';
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Sign_controller extends Frontend
{
    /**
     * Test_app constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if (ENVIRONMENT != "development") {
            show_404();
        }
    }
    public function digitalsign()
    {
        $this->load->view('test/digital_sign');
    }
    public function digitalsign1_old()
    {
        //pre($this->input->get('doc'));
        $pdf_path=$this->input->get('doc');
        $b64_pdf=base64_encode(file_get_contents(FCPATH.$pdf_path));
        // $this->load->view('includes/frontend/header');
        $this->load->view('includes/header', array("pageTitle" => "Dashboard | Sign Order"));
        $this->load->view('test/digital_sign1',array('pdf_path'=>$pdf_path,'pdf_data'=>$b64_pdf));
        $this->load->view('includes/footer');

    }
    public function digitalsign1($encoded_filepath = null)
    {
        if($this->input->method() == 'post'){
            $pdf_path = 'data:application/pdf;base64,'.$this->input->post('base_64_pdf');
            $b64_pdf = $this->input->post('base_64_pdf');
        }else{
        // pre($encoded_filepath);
        $pdf_path= base64_decode($encoded_filepath);
            // $pdf_path= base_url($this->input->get('doc'));
            // pre($pdf_path);
            $b64_pdf=base64_encode(file_get_contents(FCPATH.$pdf_path));
        }
        // $this->load->view('includes/frontend/header');
        $this->load->view('includes/header', array("pageTitle" => "Dashboard | Sign Order"));
        $this->load->view('test/digital_sign1',array('pdf_path'=>$pdf_path,'pdf_data'=>$b64_pdf));
        $this->load->view('includes/footer');

    }
    public function date_cal()
    {
        echo date_cal('2021-02-15',8);
        
    }
    
    public function roles()
    {
        $this->load->model('roles_model');
        $roleCondition = ['$expr'=>[
            '$in' => ['$slug', ['DA','DPS','AA']]
        ]];
        $res=$this->roles_model->get_role_wise_permission($roleCondition);
        pre($res);
    }
    public function factory_reset()
    {
        // $modelCollectionArray = array(
        //     'appeal_process_model' => 'appeal_processes',
        //     'appeal_application_model' => 'appeal_applications',
        //     'sms_model' => 'sms',
        //     'email_model' => 'email',
        //     'mobile_otp_model' => 'mobile_otp',
        // );
        // foreach ($modelCollectionArray as $model => $collection) {
        //     $this->load->library('mongo_db');
        //     $this->mongo_db->delete_all($collection);
        //     echo $collection . '  Deleted <br/>';
        // }
    }
    //change mobile by application id
    public function change_mobile($mobile)
    {
        $this->load->model('applications_model');
        $filter = array('initiated_data.appl_ref_no' => 'RTPS-MRG/2020/12345');
        $data = array('initiated_data.attribute_details.mobile_number' => $mobile);
        $this->applications_model->update_where($filter, $data);
        exit(200);
    }
    //change submission date by application id
    public function change_submission_date($submission_date)
    {
        $this->load->model('applications_model');
        $filter = array('initiated_data.appl_ref_no' => 'RTPS-MRG/2020/12345');
        $data = array('initiated_data.submission_date' => new UTCDateTime(strtotime($submission_date) * 1000));
        //pre($data);
        $this->applications_model->update_where($filter, $data);
        exit(200);
    }
    public function view()
    {
        $this->load->model('applications_model');
        // $filter = array('initiated_data.appl_ref_no' => 'RTPS-CRCPY/2020/00008');
        // $application = $this->applications_model->first_where($filter);
        $filter = 'RTPS-CRCPY/2020/00008';
        $application = $this->applications_model->get_by_appl_ref_no($filter);
        $data = ['attribute_details' => $application->initiated_data->attribute_details];
        $this->load->view('includes/frontend/header');
        $this->load->view('applications/attribute_for_registered_deed', $data);
        $this->load->view('includes/frontend/footer');
    }
    public function test()
    {
        $this->load->model('appeal_process_model');
        $appeal = $this->appeal_process_model->find_latest_where(array('appeal_id' => '18ABA5B'));
        //pre(format_mongo_date($appeal->created_at));
        $lastProcessCreatedAtTimeStr = strtotime(format_mongo_date($appeal->created_at, 'd-m-Y H:i:s'));
        $today = date_create();
        date_sub($today, date_interval_create_from_date_string("60 days"));
        $sixtyDaysAgoTimeStr = strtotime(date_format($today, "d-m-Y H:i:s"));
        date_sub($today, date_interval_create_from_date_string("90 days"));
        $ninetyDaysAgoTimeStr = strtotime(date_format($today, "d-m-Y H:i:s"));
        var_dump($sixtyDaysAgoTimeStr < $lastProcessCreatedAtTimeStr);
        die('allow');
        var_dump($ninetyDaysAgoTimeStr < $lastProcessCreatedAtTimeStr);
        die('allow with reason and approval');
    }
    public function test_api()
    {
        $inputs = [
            "GrievanceReferenceNumber" => 900,
            "Name" => 'Carolyn Parker',
            "Gender" => 'F',
            "Address1" => '155 West Milton Avenue',
            "Address2" => 'Labore a qui invento',
            "Address3" => 'Illum ipsam ut temp',
            "Pincode" => '781001',
            "District" => '290',
            "State" => MY_STATE['state_code'],
            "Country" => MY_COUNTRY['country_code'],
            "EmailAddress" => 'hiranya.sarma44.hs@gmail.com',
            "PhoneNumber" => '9854856723',
            "MobileNumber" => '9854856723',
            "Language" => "E",
            "SubjectContent" => 'Description for demo purpose only',
            "DateOfReceipt" => date('m-d-Y'),
            "ComplainantIpAddress" => $_SERVER['REMOTE_ADDR'],
            "ExServicemen" => 'Y',
            "DefenceServices" => '1',
            "ServiceNumber" => '241'
        ];
        $this->load->library('CPGRMS_api_client', ['type' => 'register-grm']);
        $apiResponse = $this->cpgrms_api_client->post($inputs);
        pre(json_decode($apiResponse));
    }
    public function view_seeder()
    {
        $this->load->view('includes/frontend/header');
        $this->load->module('test')->view('test/seeder_view');
        $this->load->view('includes/frontend/footer');
    }
    public function seeder()
    {
        $variableData = [
            [
                'applicant_name' => 'Piyananda S.W',
                'mobile_number' => '9401250708',
            ],
            [
                'applicant_name' => 'Dilip Rabha',
                'mobile_number' => '8420308514',
            ],
            [
                'applicant_name' => 'F Ahmed',
                'mobile_number' => '8486593440',
            ],
            [
                'applicant_name' => 'Sheetal Sharma',
                'mobile_number' => '9854062479',
            ],
            [
                'applicant_name' => 'Sushmita Dutta',
                'mobile_number' => '9957033967',
            ],
            [
                'applicant_name' => 'Pranjal Baruah',
                'mobile_number' => '9864050750',
            ]
        ];
        $this->load->model('applications_model');
        $latestFiveApplications = $this->applications_model->get_latest(6);
        $counter = 0;
        foreach ($latestFiveApplications as $latestOneApplication) {
            echo ($latestOneApplication->initiated_data->appl_ref_no);
            echo '<br>';
            echo $variableData[$counter]['mobile_number'];
            echo '<br>-------------------------------------------------<br/>';
            $nowDb = date('d-m-Y H:i');
            $inputs = [
                'initiated_data.base_service_id' => '1205',
                'initiated_data.service_name'    => 'Application for Marriage Registration',
                'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
                'initiated_data.attribute_details.applicant_name' => $variableData[$counter]['applicant_name'],
                'initiated_data.attribute_details.mobile_number'  => $variableData[$counter]['mobile_number'],
                'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
            ];
            $filter = ['_id' => new ObjectId($latestOneApplication->{'_id'}->{'$id'})];
            $this->applications_model->update_where($filter, $inputs);
            $counter++;
        }
    }
    public function set_mobile_view()
    {
        $this->load->view('includes/frontend/header');
        $this->load->module('test')->view('test/set_mobile');
        $this->load->view('includes/frontend/footer');
    }
    public function set_mobile()
    {
        $mobileNumber = $this->input->post('mobile_number');
        $this->load->model('applications_model');
        $latestApplication = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{1};
        $nowDb = date('d-m-Y H:i');
        $inputs = [
            'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.mobile_number'  => $mobileNumber,
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
        pre($latestApplication->initiated_data->appl_ref_no . '--' . $latestApplication->initiated_data->base_service_id);
        //pre($latestApplication->initiated_data->appl_ref_no);
    }
    // distinct query and extract locations name and id
    public function restore_location()
    {
        $this->load->model('applications_model');
        pre($this->applications_model->get_distinct_locations());
        // find location_name in collection and remove same location from the array
        // store new location
    }
    public function sms_preview()
    {
        $this->load->model('sms_model');
        $smsList = $this->sms_model->get_latest(5);
        //        pre($smsList);
        foreach ($smsList as $sms) {
            echo '_______________________________________________________';
            echo '<p>Mobile No. ' . $sms->mobile . '</p>';
            echo '<p>OTP. ' . ($sms->otp ?? 'NA') . '</p>';
            echo '<p>Param. ' . ($sms->param ?? 'NA') . '</p>';
            echo '<p>Msg. ' . str_replace(['+', '%3A'], ' ', $sms->msg) . '</p>';
            echo '<p>sending_status. ' . str_replace(['+', '%3A'], ' ', $sms->sending_status) . '</p>';
            echo '<p>Created At. ' . format_mongo_date($sms->created_at, 'd-m-Y g:i a') . '</p>';
        }
    }
    public function set_applications()
    {
        $this->load->model('applications_model');
        $counter = 1;
        $latestApplication1 = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{2};
        $latestApplication2 = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{3};
        $latestApplication3 = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{4};
        $latestApplication4 = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{5};
        $latestApplication5 = $this->applications_model->get_where("initiated_data.base_service_id", "1396")->{6};
        echo 'Beyond 30days but less than 45 days so the appeal will be in expired mode<br>';
        echo ($latestApplication1->initiated_data->appl_ref_no);
        echo '<br>' . "9508133232" . '<br/>';
        $nowDb = '2020-08-04 12:05';
        $inputs = [
            //'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.applicant_name' => "Rahul Deka",
            'initiated_data.attribute_details.mobile_number'  => "9508133232",
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication1->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
        echo 'Beyond 45 days so the user can not make the appeal<br>';
        echo ($latestApplication2->initiated_data->appl_ref_no);
        echo '<br>' . "9508133232" . '<br/>';
        $nowDb = '2020-02-02 12:05';
        $inputs = [
            //'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.applicant_name' => "Rahul Deka",
            'initiated_data.attribute_details.mobile_number'  => "9508133232",
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication2->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
        echo '---------------------------------------------------<br>';
        echo ($latestApplication3->initiated_data->appl_ref_no);
        echo '<br>' . "9508133232" . '<br/>';
        $nowDb = '2020-10-02 12:05';
        $inputs = [
            //'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.applicant_name' => "Rahul Deka",
            'initiated_data.attribute_details.mobile_number'  => "9508133232",
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication3->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
        echo '---------------------------------------------------<br>';
        echo ($latestApplication4->initiated_data->appl_ref_no);
        echo '<br>' . "9508133232" . '<br/>';
        $nowDb = '2020-10-02 12:05';
        $inputs = [
            //'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.applicant_name' => "Rahul Deka",
            'initiated_data.attribute_details.mobile_number'  => "9508133232",
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication4->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
        echo '---------------------------------------------------<br>';
        echo ($latestApplication5->initiated_data->appl_ref_no);
        echo '<br>' . "9508133232" . '<br/>';
        $nowDb = '2020-10-02 12:05';
        $inputs = [
            //'initiated_data.service_name'    => 'Application for Marriage Registration',
            'initiated_data.submission_location' => 'Sub Registrar Office(Sub Registrar- Mangaldoi )',
            'initiated_data.attribute_details.applicant_name' => "Rahul Deka",
            'initiated_data.attribute_details.mobile_number'  => "9508133232",
            'initiated_data.submission_date'  =>  new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
        ];
        $filter = ['_id' => new ObjectId($latestApplication5->{'_id'}->{'$id'})];
        $this->applications_model->update_where($filter, $inputs);
    }

    public function change_mobile_view()
    {
        $this->load->view('includes/frontend/header');
        $this->load->module('test')->view('test/change_mobile');
        $this->load->view('includes/frontend/footer');
    }

    public function process_change_mobile(){
        $application_number = $this->input->post('application_number',true);
        $mobile_number = $this->input->post('mobile_number',true);
        if($application_number && $mobile_number){
            $this->load->model('applications_model');
            $this->applications_model->update_where(['initiated_data.appl_ref_no' => $application_number],['initiated_data.attribute_details.mobile_number' => $mobile_number]);
            exit('Success');
        }else{
            exit('Fail');
        }
    }
    public function process_change_mobile_email(){
        $application_number = $this->input->post('application_number',true);
        $mobile_number = $this->input->post('mobile_number',true);
        $email = $this->input->post('email',true);
        $submission_date = $this->input->post('submission_date',true);
        if($application_number && $mobile_number){
            $this->load->model('applications_model');
            if(!empty($submission_date)){
              $submission_date=new MongoDB\BSON\UTCDateTime(strtotime($submission_date) * 1000);
            //  var_dump($submission_date);die;
              $this->applications_model->update_where(['initiated_data.appl_ref_no' => $application_number],['initiated_data.attribute_details.mobile_number' => $mobile_number,'initiated_data.attribute_details.e-mail' => $email,'initiated_data.submission_date' => $submission_date]);
            }else {
              $this->applications_model->update_where(['initiated_data.appl_ref_no' => $application_number],['initiated_data.attribute_details.mobile_number' => $mobile_number,'initiated_data.attribute_details.e-mail' => $email]);
            }

            exit("Success");

        }else{
            exit('Fail');
        }
    }

    public function update_application(){
      $this->load->view('includes/frontend/header');
      $this->load->module('test')->view('test/update_application');
      $this->load->view('includes/frontend/footer');
    }

    public function update_appeal(){
      $this->load->view('includes/frontend/header');
      $this->load->module('test')->view('test/update_appeal');
      $this->load->view('includes/frontend/footer');
    }

    public function process_update_appeal(){
         $appeal_id = $this->input->post('appeal_id');
         $email = $this->input->post('email');
         $mobile_number = $this->input->post('mobile_number');
         $date_of_appeal = $this->input->post('date_of_appeal');
        if($appeal_id && $mobile_number){
            $this->load->model('appeal_application_model');
            if(!empty($date_of_appeal)){
                $date_of_appeal=new MongoDB\BSON\UTCDateTime(strtotime($date_of_appeal) * 1000);
                //  var_dump($submission_date);die;
                $this->appeal_application_model->update_where(['appeal_id' => $appeal_id],['contact_number' => $mobile_number,'email_id' => $email,'created_at' => $date_of_appeal]);
            }else {
                $this->appeal_application_model->update_where(['appeal_id' => $appeal_id],['contact_number' => $mobile_number,'email_id' => $email]);
            }

            exit("Success");

        }else{
            exit('Fail');
        }
    }
    public function sms(){
        pre($this->sms->send_otp('9401250708','9401250708','987789'));
    }

    public function view_excel_sms(){
        $this->load->module('test')->view('test/excel_sms');
    }
    public function process_excel_sms(){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($_FILES['excel_sms_file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        pre($sheetData);
        $dataArray = [];
        foreach($sheetData as $key => $row){
            if($key !== 0 && !empty($row[0]) && !empty($row[1])){
                $dataArray[] = ['mobile_number' => $row[0],'text' => $row[1]];
//                $this->sms->send($row[0], $row[1]); // number,sms
                $this->sms->send_otp($row[0],$row[0],$row[1]); // number,sms
            }
        }

        pre($dataArray);
    }

    function test_pdf_base(){
        $this->load->model('order_model');
        $latestHearingOrders = $this->order_model->last_where(['appeal_id' => '41B7293','order_type' => 'hearing-order','confirmed_hearing_date_process_id' => new ObjectId('606308ad0a090000a8001c78')]);
//pre($latestHearingOrders->templateContent->appellant);
        $this->load->library('pdf');
        $x = $this->pdf->to_string($latestHearingOrders->templateContent->appellant,'order');
//        $x = $this->pdf->base_64($latestHearingOrders->templateContent->appellant,'order');
        pre(base64_encode($x));
        $urlEncodedX = urlencode($x);
        redirect('appeal/test_app/new_test_pdf/?x='.$urlEncodedX);
    }

    function new_test_pdf($param){
        pre($param);
    }

    public function email_test(){
        pre($this->remail->process_email('hiranya.sarma44.hs@gmail.com', '', '', "Appeal Submitted", 'test info', ''));
    }



    public function update_mapping_id(){
        $this->mongo_db->select(array("appeal_id","appl_ref_no","service_id","location_id"));
       $appeal_applications= $this->mongo_db->get('appeal_applications');
       if($appeal_applications){
           foreach($appeal_applications as $appeal){
         
            if($appeal->service_id && $appeal->location_id){
                    $this->mongo_db->where(array("service_id"=>$appeal->service_id,"location_id"=>$appeal->location_id));
                    $official_details= (array) $this->mongo_db->get('official_details');
                    // pre(  $$appeal->appeal_id);
                    if( $official_details){
                        if( $official_details[0]->_id){
                            $this->mongo_db->set(array("official_mapping_id"=>new ObjectId($official_details[0]->_id->{'$id'})));
                            $this->mongo_db->where("appeal_id",$appeal->appeal_id);
                            $this->mongo_db->update("appeal_applications");
                        }
                    }
            }
           }
       }
     
    }


    public function check_files(){
        
        $filter=array(
              
                array(
                  '$lookup'  => array(
                      'from'         => 'appeal_processes',
                      'localField'   => 'appeal_id',
                      'foreignField' => 'appeal_id',
                      'as'           => 'appeal_process'
                  ),
              ),
              array('$unwind' => '$appeal_process'),
              array(
                '$project' => array(
                  'documents'=>  1,
                    'appeal_process.documents'       => 1,
                    'appeal_process.dps_hearing_order'       => 1,
                    'appeal_process.appellant_hearing_order'       => 1,
                    'appeal_process.approved_files'       => 1,
                    'appeal_id'=>1
                    )
                ),
  
  
            );
            
        
  
          $collection = 'appeal_applications';
          $data = $this->mongo_db->aggregate($collection, $filter);
       
          if($data){
               $log='';
              foreach($data as $appeal){
                // pre($appeal->appeal_process->documents);
                
                  if(isset($appeal->documents) && !empty($appeal->documents[0])){
                       $log .="Id : ".$appeal->appeal_id." - > "."path : ".$appeal->documents[0] ;
                    
                     //  pre(file_exists( $appeal->documents[0]));
                       if(file_exists($appeal->documents[0])){
                        $log .=" -> att exist ";
                       }else{
                        $log .=" -> att not exist ";
                       }
                     
                  }

                  if(isset($appeal->appeal_process->documents) && !empty($appeal->appeal_process->documents[0])){
                    $log .=" procces path : ".$appeal->appeal_process->documents[0] ;
                    if(file_exists($appeal->appeal_process->documents[0] )){
                     $log .=" -> procces att exist  ";
                    }else{
                     $log .=" -> procces att not exist ";
                    }
                     }

                     if(isset($appeal->appeal_process->appellant_hearing_order) && !empty($appeal->appeal_process->appellant_hearing_order[0])){
                        $log .=" ho path : ".$appeal->appeal_process->appellant_hearing_order[0] ;
                        if(file_exists($appeal->appeal_process->appellant_hearing_order[0] )){
                         $log .=" -> HO att exist  ";
                        }else{
                         $log .=" -> HO att not exist ";
                        }
                         }

                         if(isset($appeal->appeal_process->dps_hearing_order) && !empty($appeal->appeal_process->dps_hearing_order[0])){
                            $log .=" DPSHO path : ".$appeal->appeal_process->dps_hearing_order[0] ;
                            if(file_exists($appeal->appeal_process->dps_hearing_order[0] )){
                             $log .=" -> DPSHO att exist  ";
                            }else{
                             $log .=" -> DPSHO att not exist ";
                            }
                             }
                             if(isset($appeal->appeal_process->approved_files->disposalOrder) && !empty($appeal->appeal_process->approved_files->disposalOrder)){
                                $log .=" DO path : ".$appeal->appeal_process->approved_files->disposalOrder ;
                                if(file_exists($appeal->appeal_process->approved_files->disposalOrder )){
                                 $log .=" -> DO att exist  ";
                                }else{
                                 $log .=" -> DO att not exist ";
                                }
                                 }


                                 if(isset($appeal->appeal_process->approved_files->appellantHearingOrder ) && !empty($appeal->appeal_process->approved_files->appellantHearingOrder )){
                                    $log .=" AHO path : ".$appeal->appeal_process->approved_files->appellantHearingOrder ;
                                    if(file_exists($appeal->appeal_process->approved_files->appellantHearingOrder )){
                                     $log .=" -> AHO att exist  ";
                                    }else{
                                     $log .=" -> AHO att not exist ";
                                    }
                                     }
                                 if(isset($appeal->appeal_process->approved_files->dpsHearingOrder ) && !empty($appeal->appeal_process->approved_files->dpsHearingOrder )){
                                        $log .=" ADPSHO path : ".$appeal->appeal_process->approved_files->dpsHearingOrder ;
                                        if(file_exists($appeal->appeal_process->approved_files->dpsHearingOrder )){
                                         $log .=" -> ADPSHO att exist  ";
                                        }else{
                                         $log .=" -> ADPSHO att not exist ";
                                        }
                                 }
                     
                  
               $log .=" ".PHP_EOL ;
              // pre( $log);
                file_put_contents('./appeal_files_log'.date("j.n.Y").'.txt', $log, FILE_APPEND);
              }
              echo "created";
            //   pre("djjd");
          }
          
  
    }
}
