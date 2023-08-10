<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Temp extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    // $this->load->library('AESrathi');
    //  $this->load->library('AESASP');
    $this->encryption_key = $this->config->item("encryption_key");
  }

public function index(){
  phpinfo();
}
  public function check_status()
  {
    $status_url = "https://auwssb.online/rtps.aspx"; //$status_url->status_url;
    if (empty($status_url)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "Status url not define",

        )));
      return;
    }

    $data = array(
      "app_ref_no" => '6C5E46000001', //$app_ref_no,//'NOC/05/143/2020'
      "mobile" => "9742447514" //$user_mobile //"9435347177"
    );
    $input_array = json_encode($data);
    $aes = new AES($input_array, $this->encryption_key);
    $enc = $aes->encrypt();
    //curl request

    $post_data = array('data' => $enc);
    $curl = curl_init($status_url);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    //   curl_setopt($curl, CURLOPT_CAINFO, FCPATH."assets/cacert.pem");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($curl, CURLOPT_SSLCERT, '/cacert.pem');
    $response = curl_exec($curl);
    // pre( $response);
    // print curl_errno($curl);
    // print curl_error($curl);
    // die;
    curl_close($curl);
    pre( $response);

    if ($response) {
      $response = json_decode($response);
    }
    // var_dump($response);die;
    // pre($response->data);
    //decryption
    if (isset($response->data) && !empty($response->data)) {
      $aes->setData($response->data);
      $dec = $aes->decrypt();
      $outputdata = json_decode($dec);
    }
// pre( $outputdata );
    $data = array("pageTitle" => "Application Status");

    $data['result'] = $outputdata;


    if (!empty($this->session->userdata('role')) && ($this->session->userdata('role')->slug === "PFC" || $this->session->userdata('role')->slug === "SA")) {
      $this->load->view('includes/header');
      $this->load->view('status', $data);
      $this->load->view('includes/footer');
    } else {
      $this->load->view('includes/frontend/header');
      $this->load->view('status', $data);
      $this->load->view('includes/frontend/footer');
    }
  }

  public function check_mcrypt(){
  $data=$this->encrypt('orDxZaYnJAp5');
  pre($data); 

  // out put in local
  // a8c14b7e86039ae7351b39c8826d11c32157316623d3521196a3fdbe6c8ab821
  }
  function encrypt_openssl($msg, $key, $iv = null) {
    $iv_size = openssl_cipher_iv_length('AES-128-CBC');
    if (!$iv) {
      $iv = openssl_random_pseudo_bytes($iv_size);
    }
    $encryptedMessage = openssl_encrypt($msg, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encryptedMessage);
  }

  function encrypt($in_t)
  {
    $key = "kJFK3PaEYM4ksM7l";//$this->CLIENT_TOKEN;
    $pre = ":";
    $post = "@";
    $plaintext = rand(10, 99) . $pre . $in_t . $post . rand(10, 99);
    $iv = "0000000000000000";
    $pval = 16 - (strlen($plaintext) % 16);
    $ptext = $plaintext . str_repeat(chr($pval), $pval);
// pre( $ptext );
    // $dec = $this->encrypt_openssl($ptext,$key,$iv);
    // return  $dec ;
    // $dec = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $ptext, MCRYPT_MODE_CBC, $iv);
// pre($dec);
    // $ivsize = openssl_cipher_iv_length('AES-128-CBC');
// $iv = openssl_random_pseudo_bytes($ivsize);

$dec = openssl_encrypt(
  $ptext,
        'AES-128-CBC',
        $key,
        OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
        $iv
);
// pre($ciphertext);
    return bin2hex($dec);
  }



  public function enc(){
    $this->encryption_key="bbd8409829c6c214";
    $this->load->library('AESrathi');

    $urlData ="pur_cd=".$pur_cd."&portal_cd=".$portal_cd."&regn_no=".$regn_no;
    $urlData .= "&chassi_no=".$chassi_no."&mobileNo=".$mobile;
    $urlData .=  "&return_url=".$return_url;
    $urlData .= "&user_id=".$user['userId']->{'$id'};
    $urlData .= "&edist_trans_no=".$rtps_trans_id;
 
    $aes = new AESrathi($urlData, $this->encryption_key,128);
    $enc = $aes->encrypt();
    echo $enc;
  }
  public function dec(){
    $this->encryption_key="bbd8409829c6c214";
    $this->load->library('AESrathi');
    $enc='ÈAÙ\VÚÂóf$
    e­qåO ¡Ñ¶Î~C$©þhåRq
    -Z)äòû*<ÿW9®îfPô
    m´lÒ[\8õVU@¿hW!õIp³­Èë]³8ËV.ÃPäJKbOkï¾ão££ÌþååS#§l-9?«­§KÛ0Q>3Ú7rnÑ7³¨iÛ2Ï¶?gÒbëWyÅGlÙµxð£-ün!ºÃ®3G6qñÃX¥ùKzUö«';
    $aes = new AESASP($enc,"3sc3RLrpd17");
    $dec=$aes->decrypt();
    echo $dec;
  }
  function dec_i(){
    $str="jaMyOGzjhfBsP3ug5Qw/EsdsRBTgdCG4lYekiOvaODDxBCTVmY0aXJHaSWOprDOR9n2+G0jrdHguL8EskHWPFkhBGc61ilH1vua+dmvyfHS+GF6BqjMGjPdTV92jQrFi+Vty1rbdEmy7VWcGrnssBeDw57uRuMQRSAo0gierJT/DM3ft2N39Q8GV2mrxhdc4ySlsvBUKePsfPS3xE/zKtwR7P8POzIYTzU72h4uJ8GPA1HU0v/x85ri/AoqQ+dc2TeJAcSwelO7S1jkVYiev4I4R3BsmYwYcsfjnI+sobUeHSnTcqOHVfrS1KLxuaPjsl4jK4fQGi5QMXfdjmPo2cDns6SF2I63E2Ztmy2CDBN18Z/lLC+44aGNqvhJXcs92SmKNEQmI/QVZWM9ECCew58r/vv1I1bz5z05Nk35tvtxz8bexxY96oBDTWrLytkbKveXD6/qJVV3XkOIBcg/OuJ34QhO01mTArvtqBFgnGbOF3cd3b4fvceF74q56XLk9iV2LXj/ZVWVto2AwljVYFgCNCWXS22AYVN9fjfPsQKvouVG4b9f1UlnTCSgMXJ+laCUqYA7HzrV6bhGDjNUFD01sc0z+d6i5ptbOIsF6GnhRBSUJZijBbHGt+bm2ZDWoqjAFa7Iz5HAuGQpC+XmPvRwP5vPTe0iG2g/zuIfmBHtXrh1GJFT3+e9Sw+hPB4eds5c1/BJqBgtmarN7dqgTuGaxQrEuRnvGHerjRjHwCZ8eFJ8kq2iE8NUWcN+6BDH3SoJIoi+0nM88G58zi9kSmUBxcDsa1MMknFHi+VMo9SiXUiLqzjRHREBEao6UzyVqxs8ZRIrNfBnXZFFh9lpjbPN3S9asLPoDMKvUAZ3Qx+NXbtbiV7jlinTAAJ22Q0s+GZtTwoBiYXuFyoxTZYpwCZtCJmnlwy2Go9M4+2Iq/1LOby+y8EgXPWFu7URLBXy6";
    $input_array=array(
      "rtps_trans_id"=>"AS134",
      "user_id"=>"989898989889",
      "mobile"=>'989898989889',
      "service_id"=>'1',
      "portal_no"=>'9',
      "process"=>"N",
      "response_url"=>"iservices/get/response"
  );

  $input_array=json_encode($input_array);

    $aes = new AES($str,$this->encryption_key);
    $dec=$aes->decrypt();
    pre( $dec);
  }


  public function asp(){
    $input_array=array(
      "rtps_trans_id"=>"AS134",
      "user_id"=>"989898989889",
      "mobile"=>'989898989889',
      "service_id"=>'1',
      "portal_no"=>'5',
      "process"=>"N",
      "response_url"=>base_url("iservices/get/response")
  );

  $input_array=json_encode($input_array);pre(  $input_array);
  $aes = new AESASP($input_array, $this->encryption_key);
  $enc = $aes->encrypt();
  echo "ENc data:: ";
  var_dump($enc) ;


  echo "Dec data:: ";
  $aes1 = new AESASP( $enc, $this->encryption_key);
  $dec=$aes1->decrypt();
  var_dump($dec) ;
  
  }

  public function testtt(){
    $input_array=array(
      "rtps_trans_id"=>"AS134",
      "user_id"=>"989898989889",
      "mobile"=>'989898989889',
      "service_id"=>'1',
      "portal_no"=>'5',
      "process"=>"N",
      "response_url"=>base_url("iservices/get/response")
  );

  $plaintext=json_encode($input_array);
      $password = '3sc3RLrpd17';
      $method = 'AES-256-CBC';

      // Must be exact 32 chars (256 bit)
      $password = substr(hash('sha256', $password, true), 0, 32);
      echo "Password:" . $password . "\n";

      // IV must be exact 16 chars (128 bit)
      $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

      // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
      $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));

      // My secret message 1234
      $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $password, OPENSSL_RAW_DATA, $iv);

      echo 'plaintext=' . $plaintext . "\n";
      echo 'cipher=' . $method . "\n";
      echo 'encrypted to: ' . $encrypted . "\n";
      echo 'decrypted to: ' . $decrypted . "\n\n";
  }
  public function external_request(){

    $data=array(
      "trans_id"=>rand(10,null),
      "mobile"=>"9999999999",
      "service_id"=>"CASTE"
    );
    $aes = new AES(json_encode($data),$this->encryption_key);
    $enc=$aes->encrypt();
  
    $data['enc']=$enc;
    $this->load->view('includes/frontend/header');
    $this->load->view('external_request_page',$data);
      $this->load->view('includes/frontend/footer');
     
  }
  public function new(){
$cipher ="aes-256-cbc";
$encryption_key='1234567890123456';
$iv_size = openssl_cipher_iv_length($cipher);
$iv=$encryption_key;
$encrypted_data="jaMyOGzjhfBsP3ug5Qw/EsdsRBTgdCG4lYekiOvaODDxBCTVmY0aXJHaSWOprDOR9n2+G0jrdHguL8EskHWPFkhBGc61ilH1vua+dmvyfHS+GF6BqjMGjPdTV92jQrFi+Vty1rbdEmy7VWcGrnssBeDw57uRuMQRSAo0gierJT/DM3ft2N39Q8GV2mrxhdc4ySlsvBUKePsfPS3xE/zKtwR7P8POzIYTzU72h4uJ8GPA1HU0v/x85ri/AoqQ+dc2TeJAcSwelO7S1jkVYiev4I4R3BsmYwYcsfjnI+sobUeHSnTcqOHVfrS1KLxuaPjsl4jK4fQGi5QMXfdjmPo2cDns6SF2I63E2Ztmy2CDBN18Z/lLC+44aGNqvhJXcs92SmKNEQmI/QVZWM9ECCew58r/vv1I1bz5z05Nk35tvtxz8bexxY96oBDTWrLytkbKveXD6/qJVV3XkOIBcg/OuJ34QhO01mTArvtqBFgnGbOF3cd3b4fvceF74q56XLk9iV2LXj/ZVWVto2AwljVYFlNNIHfOeRlu4LlytmkMQlkDYboc3KIzCXujuuQw2wivDA3Nbggn7kc8GBtAHAC4Tgh4qLbH6W+Tj+cgALQ7Abbnr2rqDlyw8fDegnMMAqUpA8I1t3ILJ+0+8L8EdNjmqkpKWSnooGSCFAT1RmajcSpvJkfWboJ5BBhaPfgxA4Yl1rybccHjwzdZce/VLhLg8RsyekZTt4aeJ9rss44B6aRToylMOhDLAet1GA13//c3zLnSlqHETBv5P9PCLMAkYBW60u8VHHp0GxLL7Q+tDffYeVURkzClW2zoHce440T3QY66bZO5NRla90WRbqTCMtVWreX45lNioD3UGRaqvkoD7KZvDQRO17Mryg8lAiqKjo1sFPntuuWZfcMy/zInA/M4xQq9vGgkv43hOYbCcDs2SEXGU7MJS0+6gNwRGiZkc+dnt0up7tYGmnbytKG4Ig==
";
//Decrypt data
$decrypted_data = openssl_decrypt($encrypted_data, $cipher, $encryption_key, 0, $iv);

echo "Decrypted Text: " . $decrypted_data;
  }
  public function test_log(){
    $this->load->helper('log');
    // first param app_ref_no
    // second param data to log
    // third param optional collection name
    log_response("rtps-123",array("response_data"=>"whatever you have"));
    echo "done";
  }
  public function d_c(){
   $str=urldecode("AMNRaPa38i5fz39donI93tkbVjqRS7emrOfT%2BVzaokuBanQdqG8s0a1NXzt8zke5LVE%2F7UBH7472OmAPZ%2F635BxJjP8Ukyw5YcveLmHsZbx4MX2dfVDw1tHBEPYcl%2BaK%2F8QGNsCB2F2C8ZkNZOEqYrV0p0FJ12n3ldOBIgl9s98fw7UKCM2UZYp7LdOTVzexl8yK1XVvX7RQNbhAxkRjapX75tiPJID5zkKYpc35AezubKrmvzxqcy%2BNlqbZkwI9OOQRnc5OYnoclkk0rUGUzl23Obi4IDbSxvsK6A9jMAQ%3D");
  // $str=urldecode("AMNRaPa38i5fz39donI93tvVr1U4fRX4WJAcf%2BiWaWbpZcEoq4UxV%2FYFJWJkHEBLKLEK8675YfWHVjaDV%2BL59%2FbbPXsduGdkbynI3dawW0c4sJKWpJmbwzVunBc6NV9iugBt3pEpYA3wQvJ1xnowarPQrtksejEhGD1yJy4OdyK8ogQzN3Qj%2FOa04TLwjyIO%2FsrlDQGN8nLhgxEk22R3S%2BP2z3uW6fsBLjS3gPEB9xoGYOl8nU1RTQ9%2BaB8eAvhUIt4SUuGgOcd0E%2BRVzvLdNg%3D%3D");  
  $aes = new AES($str,$this->encryption_key);
    $enc=$aes->decrypt();
    pre( $enc);
    pre( json_decode ($enc));
  } 
  public function d_c1(){
    $str="AMNRaPa38i5fz39donI93n1lGKgCLPoMnQ8sxooOTiFnIRY/wxXV44ZzQEnWXDvmwtxYgpz3+
    np0GVJWjEk4rGlHO0R7ku3P5/sa67A/g6x74caMxJh26AtB+2Wwb10rS1/Zwzl/JcKWUFsBeb7n
    5ozDHRue3kO5jN/T7drZ7xim3CZSCx3le/HpbpKI/coVNN/ofzdm+5R3rE37iSLU9GeW6PiTI0Uz
    YPxBsfEYY9g=";
    $aes = new AES($str,$this->encryption_key);
    $enc=$aes->decrypt();
    pre( json_decode ($enc));
  }
  
  public function decrypt() {
        $key="1234567890123456";
        $iv="1234567890123456";
        $str="AMNRaPa38i5fz39donI93pJHu/CuT7JB+snt1dgou95v1iqs7huSSqyofXSNs+xzkbi6/mWeOSCBWI3HmAmAxC0YyJ01YcfGmUM1CbBCiz3SgP52fekjYr/GXCLW25mMST3wtBpwI+7fKbV1FTmwxXob+MGTvDiijpOTc/cZA0wYJnyYwwBUZDd6f+AdjWshmBPVSxZtsz8Yx7HlKzL/ZwEwLjy22kjkz1s77jNlUUnimKwvStYmotgIu1WJQAryPCBG35tGCndR0VxxDTIBo2xpQ0jpwoBYLJWRDQiTiuFpGZokBAUog8TtCtTVCoWpTb2arUU0lmVLJOR4CFX+IQ9D/XTwYuNthr/PXlogTf/KSLwv3UkTFxdxzp1QEqwPPMmaj5nrHi+7l9nM0Rr4k9BvxgaJZkZOtEoNqCVlsboiKyg0PsyCPYofmKooqwN2dUK3ZT9IUs5cBeuvA9h7h8V3sAHGVVpK5rEFJ4SHlr2pYc2s4UwGpL3OpF69NdyT7bfXQVd9jcBsYH5Ti5NEoNiHf17OJ75xArOe0tmuGtnvjzdimlOxh1+cXket/GtvDkEYYCHWHicwN+HGkI+OV0g8kMWyQpEcgyAiBhxoVbQeLCTBfJ4w+iRguTi/7vdSLDKRE1XaAvpK4wG/X//G5w==";
       $ret=openssl_decrypt( $str, "AES-256-CBC",$key, 0, $iv);
      
       return   trim($ret);
      //  $ret=openssl_encrypt( $str, "AES-256-CBC",$key, 0, $iv);
}


public function update_address(){
  if(file_exists(FCPATH.'storage/assamese_address.json')){
    $files=file_get_contents(FCPATH.'storage/assamese_address.json');
    if($files){
      $files=json_decode($files);
      // pre( $files);
      foreach($files as $app){
        $this->mongo_db->where('rtps_trans_id',$app->ref_no);
        $this->mongo_db->set(array('applicant_details.0.address_line_1'=>$app->address_line_1,'applicant_details.0.address_line_2'=>$app->address_line_1));
        $this->mongo_db->update('intermediate_ids');
       
      }
      echo "updated";
    }
  }else{
    echo "no file found";
  }
 
}

public function pdf(){
  $html = $this->load->view('test_jama',[],true);

  // $html = $this->load->view('spservices/office/certificates/minority_certificate', array('data' => '', 'qr' => "xxx", 'certificate_no' => "xxyy"), true);
  // pre($html );
        $this->load->library('dpdf');
        
        $pdf_path = $this->dpdf->createPDF($html, "test", false);
        pre($pdf_path);
}


public function pdf1(){
  $html = $this->load->view('test_jama',[],true);
// pre($html);
        // $this->load->library('pdf');
        
        // $pdf_path = $this->pdf->generate($html, "test", false);

        $this->load->library('pdf');
        $fullPath = $this->pdf->get_pdf($html, 'NECERTIFICATE', str_replace('/', '-', "saheb"));
        $pathExplode = explode('storage', $fullPath);
        pre($pathExplode);
}

public function seba_de(){
  $str='AMNRaPa38i5fz39donI93mdq9aMdRqOO7EmkpMErYK1XRDwinmXufRYIC1sEfksfHbEKTnmKPtpV3hbgEDO8yinvKYM2%2FDfZScuA70wU%2F7AaSstCM8%2BVAC%2B%2FmItVDGzc3fl5hSG8w1rUrSjIiCOplahLlYwzzsL06qz9DdSoE01UhsZ2p9p5y7ZOg%2FVUMawk4%2FcARLZgfajvhkxqFDHB4Kv2zXG0lCru3%2FZUKI9rqBRaV20Pz1d5Zi2Cu%2FJitKUB%2B3Qq%2BJqyiNWr%2FLkGuVbTrQ%3D%3D';
  $aes = new AES(urldecode( $str),$this->encryption_key);
  $enc=$aes->decrypt();
  pre(  $enc);
}




public function apdclTrackAPI($obj_id){
  $this->load->model('spservices/apdcl/registration_model');
  $dbrow = $this->registration_model->get_by_doc_id($obj_id);
 
  $applNo = $dbrow->form_data->application_no; 
  $subDiv = $dbrow->form_data->sub_division;
  //Track API
 $url = 'https://www.apdclrms.com/cbs/onlinecrm/applicationStatus?applNo='.$applNo.'&applType=NSC&subDiv='.$subDiv;
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $track = curl_exec($curl);
  curl_close($curl);
  $track = json_decode($track,true);
  //pre($track);
  foreach($track as $key)
  {
      $data = array(
        "applStatus" => $key['applStatus'],
        "applStatusId" => $key['applStatusId'],
        "bill_amount" => $key['bill_amount'],
        "billNo" => $key['billNo'], 
        "isBillPaid" => $key['isBillPaid'],
        "document" => $key['document'],
        "paymentLink" => $key['paymentLink'],
        "applView" => $key['applView'],
        "remarks" => $key['remarks'],
        "billDeskMsgUrl" => $key['billDeskMsgUrl'],
        "consNo" => $key['consNo'],
        "processing_time" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
      );
  }

  $processing_history = $dbrow->processing_history??array(); 
  $processing_history[] = $data;

    $data_to_update = [
      'processing_history'=> $processing_history
    ];
    
    $x = max(array_keys($processing_history))-1;
    $status = $processing_history[$x]->applStatusId;
   
    if($data['applStatusId'] != $status){
        $this->registration_model->update($obj_id,$data_to_update);
    }
      redirect('spservices/apdcl/registration/tracking/'.$obj_id);
}


public function check_cin(){
  // pre($this->config->item('egras_grn_cin_url'));
  // $department_id="6374a0850c6fc1668587653";

  
  // $post_data = array('DEPARTMENT_ID' => $department_id,
  //   "OFFICE_CODE"=>"LRS338",
  //   "AMOUNT"=>50,
  //   "ACTION_CODE"=>"GETCIN",
  //   "SUB_SYSTEM"=>"");
  // $curl = curl_init($this->config->item('egras_grn_cin_url'));
  // curl_setopt($curl, CURLOPT_POST, true);
  // curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
  // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  // $response = curl_exec($curl);
  // pre( $response);
  // // print curl_errno($curl);
  // // print curl_error($curl);
  // // die;
  // curl_close($curl);
  // pre( $response);


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://assamegras.gov.in/challan/models/frmgetgrn.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('DEPARTMENT_ID' => '6470d9a04034e1685117344','OFFICE_CODE' => 'ARI000','AMOUNT' => '10','ACTION_CODE' => 'GETCIN','SUB_SYSTEM' => "ARTPS-SP|".base_url('iservices/temp/cin_response')),
    CURLOPT_HTTPHEADER => array(
      'Cookie: PHPSESSID=gknlnsekurnk47ukp71mtvtk1r'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  echo $response;

}

public function cin_response()
  {
    if (!empty($_POST)) {
      pre($_POST);
      if (!empty($_POST['DEPARTMENT_ID'])) {
        $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
        $STATUS = $_POST['STATUS'];
        $BANKCIN = $_POST['BANKCIN'];
        $this->registered_deed_model->update_row(array('query_department_id' => $DEPARTMENT_ID), array(
          "query_payment_response.BANKCIN" => $BANKCIN,
          "query_payment_response.STATUS" => $STATUS,
          "query_payment_response.TAXID" => $_POST['TAXID'],
          "query_payment_response.PRN" => $_POST['PRN'],
          "query_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
          'query_payment_status' => $STATUS
        ));
      }
    }

    // redirect(base_url('iservices/transactions'));
  }

public function checkgrn($app=true){ // TODO: need to check which are params to update
  //  pre($app->department_id);
      if($app){
        $OFFICE_CODE="ARI000";//$app->payment_params->OFFICE_CODE;
        
        $AMOUNT=10;//$am1+$am2;
        $department_id="6470d9a04034e1685117344";
        $string_field="DEPARTMENT_ID=".$department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
        // pre($string_field);
        $url = $this->config->item('egras_grn_cin_url');
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST,3);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $string_field);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
        curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($ch, CURLOPT_NOBODY,false);
        $result = curl_exec($ch);
        curl_close($ch);
        $res=explode("$",$result);//pre($res);
       pre( $res);
        
      }
       
  
    }


    //push payment status on external portal 


    public function push_rtps_payment_status($DEPARTMENT_ID)
  {
    if ($DEPARTMENT_ID) {
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $DEPARTMENT_ID));
     
      if ($application_details) {
        $encryption_key = $this->config->item("encryption_key");
        if (property_exists($application_details, 'pfc_payment_response') && !empty($application_details->pfc_payment_response)) {
          $params=array(
            "app_ref_no"=>$application_details->app_ref_no,
            "payment_data"=>$application_details->pfc_payment_response,
            "payment_status"=>$application_details->pfc_payment_response->STATUS
          );
          
      
         
          
         
          $input_array = json_encode($params);
          pre($input_array);
          $aes = new AES($input_array, $encryption_key);
          $enc = $aes->encrypt();
          // pre( $enc);
          //curl request
      
         
          if(in_array($svc_id,$stat_services)){
            $url="https://basundhara.assam.gov.in/rtpsmb/Epayment/updatePayment";
            $post_data = array('data' => $enc );
          }else{
            $url = $this->config->item('basundhara_push_payment_status_url');
            $post_data = array('data' => json_encode($enc) );
          }
          $curl = curl_init($url);
          // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
          $response = curl_exec($curl);
          curl_close($curl);
          if($response){
            $data_res=json_decode($response);
            if($data_res->responseType === 2 || $data_res->responseType === "2"){
              $result = $this->intermediator_model->add_param($application_details->rtps_trans_id, array(
                "payment_status_updated_on"=>true
              ));
            }
          }
        
        
        }
      }
    }
  }


  public function enc_pull(){
    $this->encryption_key="1234567890123456";
    $this->load->library('AES');

    $data=array(
      "app_ref_no"=>"3745649722"
    );
    $aes = new AES( json_encode( $data), $this->encryption_key,256);
    $enc = $aes->encrypt();
    echo $enc;
  }


}