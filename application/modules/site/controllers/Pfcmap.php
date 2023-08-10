<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pfcmap extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
    }


    public function getPopupData()
    {
        switch ($this->lang) {
            case 'en':
                $post['name'] = 'Assam Secretariat';
                $post['address'] = 'Janata Bhawan, GS Rd, Dispur, Guwahati, Assam 781006';
                $post['meta_data'] = ['Details', 'Name', 'Address'];
                break;

            case 'bn':
                $post['name'] = 'আসাম সচিবালয়';
                $post['address'] = 'জনতা ভবন, জিএস রোড, দিসপুর, গুয়াহাটি, আসাম ৭৮১০০৬';
                $post['meta_data'] = ['বিবরণ', 'নাম', 'ঠিকানা'];
                break;

            default:
                $post['name'] = 'অসম সচিবালয়';
                $post['address'] = 'জনতা ভৱন, জিএছ ৰোড, দিচপুৰ, গুৱাহাটী, অসম ৭৮১০০৬';
                $post['meta_data'] = ['বিৱৰণ', 'নাম', 'ঠিকনা'];
                break;
        }

        // echo json_encode($post);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($post));
    }

    public function contact()
    {
        $post['regNo'] = 1;
        $post['Lattitude'] = 26.14359015;
        $post['longitude'] = 91.78981531913536;
        $post['HouseStatus'] = 'test';
        // echo json_encode([$post]);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([$post]));
    }
}
