<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Doc_uri extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $postData = file_get_contents('php://input');
        $xml = simplexml_load_string($postData);
        $encodedPdf = '';
        $status = 0;
        $ts = (string)$xml->attributes()->{'ts'};
        $txn = (string)$xml->attributes()->{'txn'};
        $doc_uri = (string)$xml->DocDetails->URI;
        $doc_typ = substr($doc_uri, 18);
        $docType = substr($doc_typ, 0, strpos($doc_typ, "-"));
        $config = (array)$this->mongo_db->where(array('doctype' => $docType))->get('digilocker_service_settings');
        if (count($config)) {
            $hmac = getallheaders()['x-digilocker-hmac'];
            $hash = hash_hmac('sha256', $postData, $config[0]->api_key);
            $base64_hash = base64_encode($hash);
            if ($base64_hash === $hmac) {
                $uri = $doc_uri;
                $application_data = (array)$this->mongo_db->where(['uri' => $uri])->get('digilocker_uri');
                if (count($application_data)) {
                    $status = 1;
                    $encodedPdf = base64_encode(file_get_contents($application_data[0]->file_path));
                } else {
                    // $status = 0;
                    $response = $this->get_from_pg($uri);
                    $status = $response['status'];
                    $encodedPdf = $response['file'];
                }
            } else {
                $status = 0;
            }
        }
        else{
            echo 'doctype not avaiable';
        }
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        echo '<PullDocResponse xmlns:ns2="http://tempuri.org/">';
        echo '<ResponseStatus Status="' . $status . '" ts="' . $ts . '" txn="' . $txn . '">' . $status . '</ResponseStatus>';
        echo '<DocDetails>';
        echo '<DocContent>' . $encodedPdf . '</DocContent>';
        echo '<DataContent>';
        echo '</DataContent>';
        echo '</DocDetails>';
        echo '</PullDocResponse>';
        exit;
    }

    private function get_from_pg($uri)
    {
        $host        = "host=10.194.162.120"; //"host = 127.0.0.1";
        // $host        = "host = 127.0.0.1";
        $port        = "port=5432"; //"port = 5432";
        $dbname      = "dbname = rtps_preprod";
        // $dbname      = "dbname = rtps_prod";

        $credentials = "user = serviceplusrole password=Artps@p05tgres"; //"user = postgres password=admin";
        // $credentials = "user = postgres password=admin";

        $db = pg_connect("$host $port $dbname $credentials");
        if (!$db) {
            echo "Error : Unable to open database\n";
            return array();
        } else {
            // echo "Opened database successfully\n";
            $file = '';
            $status = 0;
            $query = "select * from schm_sp.application_cert where uri = '$uri'";
            $ret = pg_query($db, $query);
            // $row = pg_fetch_row($ret);
            while ($row = pg_fetch_row($ret)) {
                $status = 1;
                $file = base64_encode(file_get_contents($row[5]));
            }
            $data = ['file' => $file, 'status' => $status];
            return $data;
        }
    }
}
