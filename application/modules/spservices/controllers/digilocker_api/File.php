<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class File extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $x = base64_encode(file_get_contents('/opt05/app_data/rtps/preprod/signedFile/18/18500003/2023/02/21/135127/18500003_36526_13749_1676965126450.pdf'));

        // $x = file_get_contents('opt/app_data/rtps/prod/signedFile/18/18590007/2022/04/04/106371939/18590007_18867671_13859_1649084617323.pdf');
        pre($x);
    }
}