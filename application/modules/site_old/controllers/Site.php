<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');
        $this->load->model('dept_model');
    }

    public function index()
    {
        // $this->load->view('theme1/index_temp');


        $this->load->model('cat_model');

        $data['recent_services'] = (array) $this->site_model->get_services(true, $this->lang);
        $data['services_list'] = (array) $this->site_model->get_services_list($this->lang);
        $data['categories'] = (array) $this->cat_model->get_all_categ($this->lang);
        $data['all_depts'] = $this->dept_model->get_all_depts($this->lang, false);
        $data['ac'] = $this->dept_model->get_all_depts($this->lang, true);
        $data['settings'] = $this->settings_model->get_settings('home_new');


        // Sadbhavana link in recent services @ 0
        // array_unshift($data['recent_services'], (object)[
        //     'name' => (object)[
        //         'en' => 'Sadbhavana',
        //         'as' => 'সদ্ভাবনা',
        //         'bn' => 'সদ্ভাবনা',
        //     ],
        //     'link' => 'sadbhavana/'
        // ]);


        // Check if Login error has occured
        $data['login_error'] = (stristr($this->agent->referrer(), 'loginSubmitForm.do') === false) ?
            false : true;


        $url = 'http://localhost/NIC/rtps/mis/api/portal/popular-services';
        // $url =  'https://rtps.assam.gov.in/mis/api/portal/popular-services';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // $error_msg = curl_error($curl);
            // pre($error_msg);
            $data['popular_services'] = array();
        } else {
            $json_res = json_decode($response, true)['data'];

            if (empty($json_res)) {
                $data['popular_services'] = array();
            } else {

                $service_ids = array_map(function ($val) {
                    return $val['service_id'];
                }, $json_res);


                $data['popular_services'] = (array) $this->site_model->get_popular_services($this->lang, $service_ids);

                // Sorting services based on count
                foreach (json_decode($response, false)->data as $val1) {
                    foreach ($data['popular_services'] as $val2) {

                        if ($val1->service_id == $val2->service_id) {
                            # add count

                            if (property_exists($val2, 'count')) {
                                $val2->count = ($val2->count > $val1->count) ? $val2->count : $val1->count;
                            } else {
                                $val2->count = $val1->count;
                            }
                        } elseif (property_exists($val2, 'service_id_aadhar') && $val1->service_id == $val2->service_id_aadhar) {
                            # add count

                            if (property_exists($val2, 'count')) {
                                $val2->count = ($val2->count > $val1->count) ? $val2->count : $val1->count;
                            } else {
                                $val2->count = $val1->count;
                            }
                        }
                    }
                }

                usort($data['popular_services'], function ($a, $b) {
                    if ($a->count == $b->count) return 0;
                    return ($a->count > $b->count) ? -1 : 1;
                });

                // pre($data['popular_services']);
            }
        }

        curl_close($curl);
        $this->render_view_new('home_new', $data);
    }
    public function about()
    {
        // $this->load->view('theme1/about_temp');

        $data = array();
        $data['about'] = $this->settings_model->get_settings('about');

        $this->render_view_new('about', $data);
    }
    public function faq()
    {
        $data = array();
        $data['faq'] = $this->settings_model->get_settings('faq');
        $this->render_view_new('faq', $data);
    }
    public function contact()
    {
        // $this->load->view('theme1/contact_temp');
        $data = array();
        $data['contact'] = $this->settings_model->get_settings('contact');
        $this->render_view_new('contact', $data);
    }
    public function kys($seo_url)
    {
        $this->load->model('Kys_model');
        $data = array();
        $data['kys'] = $this->Kys_model->get_by_seo_url($seo_url)->{'0'} ?? null;

        $data['settings'] = $this->settings_model->get_settings('kys');

        //  pre($data);
        $this->render_view_new('kys_new', $data);
    }

    public function transport_track_page()
    {
        $data = array();

        $data['transport_track'] = $this->settings_model->get_settings('transport_track');

        $this->render_view_new('transport_track', $data);
    }

    public function search()
    {
        $data = array();

        // Injection attacks prevention (XSS, HTML)
        $user_query = htmlspecialchars(trim($this->input->get('service_name', true)));

        // Escaping regex special characters in search_term
        $key = preg_quote($user_query, '/');

        if (!empty($key)) {
            $this->session->set_userdata(array("search_term" => $key));
            $this->session->set_userdata(array("user_query" => $user_query));
        }

        $data['search'] = (array) $this->site_model->get_search($this->session->userdata("search_term"), $this->lang);
        $data['settings'] = $this->settings_model->get_settings('search');

        $this->render_view_new('search', $data);
    }

    public function video()
    {
        $this->load->model('video_model');

        $data['settings'] = $this->settings_model->get_settings('videos');

        $empty_categ = array();

        foreach ($data['settings']->categories as $key => $category) {
            $category->videos = $this->video_model->get_videos_by_category($category->short_name, $this->lang);

            if (!count($category->videos)) {
                array_push($empty_categ, $key);
            }
        }

        // Only display categories which have videos
        foreach ($empty_categ as $key => $index) {
            unset($data['settings']->categories[$index]);
        }

        $this->render_view_new('video', $data);
    }

    public function docs()
    {
        $this->load->model('docs_model');

        $data['data'] = $this->settings_model->get_settings('documents');
        // pre($data['data']);

        $empty_categ = array();

        foreach ($data['data']->categories as $key => $category) {
            $category->docs = $this->docs_model->get_docs_by_category($category->short_name, $this->lang);

            foreach ($category->docs as $i => $doc) {
                $doc->realpath = realpath(FCPATH . $doc->path);
                $doc->type = empty($doc->realpath) ? 'N/A' : get_file_extension(mime_content_type($doc->realpath));
                $doc->size = empty($doc->realpath) ? '0 B' : format_bytes(filesize($doc->realpath));
            }

            if (!count($category->docs)) {
                array_push($empty_categ, $key);
            }
        }

        // Only display doc category which have documents
        foreach ($empty_categ as $key => $index) {
            unset($data['data']->categories[$index]);
        }

        $this->render_view_new('docs', $data);
    }

    public function artps_services($kiosk_type = '')
    {
        // Validate input
        if (in_array($kiosk_type, ['', 'pfc', 'csc'], TRUE)) {
            switch ($kiosk_type) {
                default:
                    $data['data'] = $this->settings_model->get_settings('artps_services');
                    $view_page = 'artps_services';
                    break;
                case 'pfc':
                case 'csc':
                    $data['settings'] = $this->settings_model->get_settings('kiosk_services');
                    $data['services'] = (array) $this->site_model->get_services_by_kiosk(strtoupper($kiosk_type), $this->lang);
                    $data['kiosk_type'] = $kiosk_type;
                    $view_page = 'kiosk_services';
                    break;
            }
        } else {
            $data['data'] = $this->settings_model->get_settings('artps_services');
            $view_page = 'artps_services';
        }

        $this->render_view_new($view_page, $data);
    }

    public function pfc_locations()
    {
        $data['settings'] = $this->settings_model->get_settings('pfc_locations');
        $data['pfcs'] = (array) $this->site_model->get_pfc_locations();
        $data['districts'] = (array) $this->site_model->get_pfc_districts();
        $this->render_view_new('pfc_locations', $data);
    }
    public function find_pfcs_by_district()
    {
        $data = (array) $this->site_model->get_pfcs_for_district($this->input->get('d', true));
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }


    // Temp Services
    public function all_services($kiosk_type = '')
    {
        // Validate input
        if (in_array($kiosk_type, ['', 'pfc', 'csc'], TRUE)) {
            $data['type'] = $kiosk_type;
        } else {
            $data['type'] = '';
        }

        $data['services'] = [
            0 => [
                'service_id' => '1336',
                'kiosk_availability' => 'ALL',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Animal Tax Payment under GMC Act 1971',
            ],
            1 => [
                'service_id' => '1542',
                'kiosk_availability' => 'ALL',
                'department' => 'Bodoland Territorial Area District',
                'service' => 'Application Form for Registration of Cooperative Society with Limited Liability under the Assam Cooperative Societies Act 2007 (BTAD)',
            ],
            2 => [
                'service_id' => '1329',
                'kiosk_availability' => 'ALL',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Trade License for Veterinary',
            ],
            3 => [
                'service_id' => '977',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Issuance Of Marriage Registration - Marriage Appointment',
            ],
            4 => [
                'service_id' => '3',
                'service_url' => 'https://rtps.assam.gov.in/iservices/vahan/3',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Duplicate Registration Certificate for Transport Vehicle',
            ],
            5 => [
                'service_id' => '977',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Registration of Documents in SRO under Registration Act, 1908 - Appointment for Deed',
            ],
            6 => [
                'service_id' => '1207',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Office Mutation - Mutation of Property Ownership',
            ],
            7 => [
                'service_id' => '1498',
                'kiosk_availability' => 'ALL',
                'department' => 'Welfare of Plain Tribes and Backward Classes',
                'service' => 'Issuance of Scheduled Caste Certificate',
            ],
            8 => [
                'service_id' => '1775',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Certified Copies of Chitha - KAAC',
            ],
            9 => [
                'service_id' => '1620',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Land Valuation Certificate - KAAC',
            ],
            10 => [
                'service_id' => '1639',
                'kiosk_availability' => 'PFC',
                'department' => 'Inland Water Transport',
                'service' => 'Renewal of Certificate of Survey',
            ],
            11 => [
                'service_id' => '1544',
                'kiosk_availability' => 'ALL',
                'department' => 'North Cachar Hills Autonomous Council',
                'service' => 'NOC for Immovable Properties in Rural Areas (NCHAC)',
            ],
            12 => [
                'service_id' => '1640',
                'kiosk_availability' => 'PFC',
                'department' => 'Inland Water Transport',
                'service' => 'Certificate of Competency',
            ],
            13 => [
                'service_id' => '1668',
                'kiosk_availability' => 'ALL',
                'department' => 'Secondary Education',
                'service' => 'Issuance of Migration Certificate, AHSEC',
            ],
            14 => [
                'service_id' => '1677',
                'kiosk_availability' => 'ALL',
                'department' => 'Skill, Employment and Entrepreneurship',
                'service' => 'Re-registration of Registration Card of employment seeker in Employment Exchange',
            ],
            15 => [
                'service_id' => '1492',
                'kiosk_availability' => 'ALL',
                'department' => 'Welfare of Plain Tribes and Backward Classes',
                'service' => 'Issuance of Non Creamy Layer Certificate',
            ],
            16 => [
                'service_id' => '1643',
                'kiosk_availability' => 'PFC',
                'department' => 'Inland Water Transport',
                'service' => 'Registration of Boats and Vessels',
            ],
            17 => [
                'service_id' => '1852',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Land Revenue Clearance Certificate - KAAC',
            ],
            18 => [
                'service_id' => '0002',
                'service_url' => 'https://rtps.assam.gov.in/iservices/guidelines/12/1',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'NOC for Re-classification',
            ],
            19 => [
                'service_id' => '1676',
                'kiosk_availability' => 'ALL',
                'department' => 'Skill, Employment and Entrepreneurship',
                'service' => 'Registration of employment seeker in Employment Exchange',
            ],
            20 => [
                'service_id' => '1650',
                'kiosk_availability' => 'ALL',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Holding Mutation',
            ],
            21 => [
                'service_id' => '2222',
                'service_url' => 'https://rtps.assam.gov.in/iservices/sarathi/guidelines/2222/4',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Driving License for Transport',
            ],
            22 => [
                'service_id' => '2223',
                'service_url' => 'https://rtps.assam.gov.in/iservices/sarathi/guidelines/2223/4',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Driving License for Non-Transport',
            ],
            23 => [
                'service_id' => '1264',
                'kiosk_availability' => '',
                'department' => 'Agriculture and Horticulture',
                'service' => 'Application for Retail Fertilizer Sale Point',
            ],
            24 => [
                'service_id' => '1686',
                'kiosk_availability' => 'ALL',
                'department' => 'General Administration',
                'service' => 'Issuance of Senior Citizen Certificate',
            ],
            25 => [
                'service_id' => '1866',
                'kiosk_availability' => 'CSC',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Issuance of Permission to Put Up Hoarding',
            ],
            26 => [
                'service_id' => '1804',
                'kiosk_availability' => '',
                'department' => 'Industries and Commerce',
                'service' => 'Add Unit of Common Application Form',
            ],
            27 => [
                'service_id' => '1131',
                'kiosk_availability' => '',
                'department' => 'Industries and Commerce',
                'service' => 'Application for Shed Allotment in various Industrial Estate',
            ],
            28 => [
                'service_id' => '1124',
                'kiosk_availability' => '',
                'department' => 'Industries and Commerce',
                'service' => 'Application for Land Allotment in various Industrial Estate',
            ],
            29 => [
                'service_id' => '1215',
                'kiosk_availability' => '',
                'department' => 'Co-operation',
                'service' => 'Registration of Cooperative Society with Limited Liability under the Assam Cooperative Societies Act 2007',
            ],
            30 => [
                'service_id' => '1179',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Registration in (Form I) under Rule 4 of the Assam Motor Transport Workers Rules, 1962',
            ],
            31 => [
                'service_id' => '1184',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Registration of Establishment as the Principal Employer in Form I under Contract Labour Regulation and Abolition Act 1970 and Rule 17 1 of the Assam Rules 1971',
            ],
            32 => [
                'service_id' => '1732',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Auto Renewal of License in Form VII under Rule 29 (2) of the Contract Labour (R &amp; A) Rules, 1971',
            ],
            33 => [
                'service_id' => '1844',
                'kiosk_availability' => '',
                'department' => 'Animal Husbandry and Veterinary',
                'service' => 'Valuation certificate of animal/bird for insurance',
            ],
            34 => [
                'service_id' => '1126',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC for one storied / multi-storied / high rise building under Assam Fire Service Rule 1989 (Form No I).',
            ],
            35 => [
                'service_id' => '1123',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in transport godowns and other godowns under Assam Fire Service Rule 1989 (Form No III).',
            ],
            36 => [
                'service_id' => '1119',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in Cinema Theatres / Multiplex etc. under Assam Fire Service Rule 1989 (Form No IV).',
            ],
            37 => [
                'service_id' => '1125',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in erecting temporary structures / circus / movable theatre / exhibitions under Assam Fire Service Rule 1989 (Form No VI).',
            ],
            38 => [
                'service_id' => '1128',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in the storage and handling of chemicals under Assam Fire Service Rule 1989 (Form No VIII).',
            ],
            39 => [
                'service_id' => '1503',
                'kiosk_availability' => 'ALL',
                'department' => 'Bodoland Territorial Area District',
                'service' => 'Registration of Establishment under the Assam Shops &amp; Establishment Act,1971 in Form O (BTAD)',
            ],
            40 => [
                'service_id' => '1628',
                'kiosk_availability' => 'CSC',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Cess Pool Service',
            ],
            41 => [
                'service_id' => '1508',
                'kiosk_availability' => 'ALL',
                'department' => 'Bodoland Territorial Area District',
                'service' => 'Renewal of Establishment under the Assam Shops &amp; Establishment Act, 1971 in Form O (BTAD)',
            ],
            42 => [
                'service_id' => '1396',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Issuance of Non-Encumbrance Certificate',
            ],
            43 => [
                'service_id' => '3',
                'service_url' => 'https://rtps.assam.gov.in/iservices/vahan/3',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Duplicate Registration Certificate for Non-transport Vehicle',
            ],
            44 => [
                'service_id' => '1054',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Certified Copy of Jamabandi or Records of Right/Chitha',
            ],
            45 => [
                'service_id' => '1104',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Issuance of Certified Copy of Registered Sale Deeds',
            ],
            46 => [
                'service_id' => '1546',
                'kiosk_availability' => 'ALL',
                'department' => 'North Cachar Hills Autonomous Council',
                'service' => 'Permanent Residential Certificate (PRC) in Rural Areas (NCHAC)',
            ],
            47 => [
                'service_id' => '9',
                'service_url' => 'https://rtps.assam.gov.in/iservices/vahan/9',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'No Objection Certificate for Transfer of Record of Registration to Other Registering Authority',
            ],
            48 => [
                'service_id' => '1651',
                'kiosk_availability' => 'ALL',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Assessment of Holding',
            ],
            49 => [
                'service_id' => '5',
                'service_url' => 'https://rtps.assam.gov.in/iservices/vahan/5',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Ownership Transfer for Non-Transport/Transport Vehicle',
            ],
            50 => [
                'service_id' => '1618',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Certified Copies of Jamabandi - KAAC',
            ],
            51 => [
                'service_id' => '1619',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Duplicate Copies of Land Patta - KAAC',
            ],
            52 => [
                'service_id' => '1323',
                'kiosk_availability' => 'ALL',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Issuance of Certificate for Dog Registration',
            ],
            53 => [
                'service_id' => '1545',
                'kiosk_availability' => 'ALL',
                'department' => 'North Cachar Hills Autonomous Council',
                'service' => 'Jamabandi Copy under Rural Areas (NCHAC)',
            ],
            54 => [
                'service_id' => '1205',
                'kiosk_availability' => 'PFC',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Issuance Of Marriage Registration - Application for Marriage Registration',
            ],
            55 => [
                'service_id' => '2',
                'service_url' => 'https://rtps.assam.gov.in/iservices/vahan/2',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Certificate of Fitness of Transport Vehicles',
            ],
            56 => [
                'service_id' => '1665',
                'kiosk_availability' => 'ALL',
                'department' => 'Secondary Education',
                'service' => 'Issuance of Migration Certificate, SEBA',
            ],
            57 => [
                'service_id' => '1622',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Non-Encumbrance Certificate - KAAC',
            ],
            58 => [
                'service_id' => '1773',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Income Certificate - KAAC',
            ],
            59 => [
                'service_id' => '1410',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Application for Office Partition of Land',
            ],
            60 => [
                'service_id' => '1409',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'Issuance of Certified Copy of Mutation Order',
            ],
            61 => [
                'service_id' => '0001',
                'service_url' => 'https://rtps.assam.gov.in/iservices/guidelines/11/1',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'No Objection Certificate for Transfer of Immovable Property',
            ],
            62 => [
                'service_id' => '1774',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Land Holding Certificate - KAAC',
            ],
            63 => [
                'service_id' => '1853',
                'kiosk_availability' => 'PFC',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Trace Map - KAAC',
            ],
            64 => [
                'service_id' => '0003',
                'service_url' => 'https://rtps.assam.gov.in/iservices/guidelines/13/1',
                'kiosk_availability' => 'ALL',
                'department' => 'Revenue and Disaster Management',
                'service' => 'NOC for Re-classification cum Transfer',
            ],
            65 => [
                'service_id' => '1675',
                'kiosk_availability' => 'ALL',
                'department' => 'Skill, Employment and Entrepreneurship',
                'service' => 'Renewal of Registration Card of employment seeker in Employment Exchange',
            ],
            66 => [
                'service_id' => '2703',
                'service_url' => 'https://rtps.assam.gov.in/iservices/sarathi/guidelines/2703/4',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Learner License for Transport',
            ],
            67 => [
                'service_id' => '2703',
                'service_url' => 'https://rtps.assam.gov.in/iservices/sarathi/guidelines/2703/4',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Learner License for Non-Transport',
            ],
            68 => [
                'service_id' => '2744',
                'service_url' => 'https://rtps.assam.gov.in/iservices/sarathi/guidelines/2744/4',
                'kiosk_availability' => 'ALL',
                'department' => 'Transport',
                'service' => 'Duplicate Driving License',
            ],
            69 => [
                'service_id' => '1120',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC In Respect Of Fire Safety Measures In Function Halls / Vivah Bhavan / Building Below 15 Mtrs Under Assam Fire Service Rule 1989 Form No V',
            ],
            70 => [
                'service_id' => '1158',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Application For Renewal Of Fire NOC Under Assam Fire Service Rule 1989 Form No XII',
            ],
            71 => [
                'service_id' => '1657',
                'kiosk_availability' => 'ALL',
                'department' => 'General Administration',
                'service' => 'Issuance of Next of Kin Certificate',
            ],
            72 => [
                'service_id' => '1868',
                'kiosk_availability' => '',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Issuance of Farmers Certificate - KAAC',
            ],
            73 => [
                'service_id' => '1178',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Registration of Establishment under the Assam Shops &amp; Establishment Act,1971 in FORM O Under Section 36 and RULE 45 of the Assam Rules',
            ],
            74 => [
                'service_id' => '1867',
                'kiosk_availability' => 'CSC',
                'department' => 'Guwahati Municipal Corporation',
                'service' => 'Renewal of Permission to Put Up Hoarding',
            ],
            75 => [
                'service_id' => '1091',
                'kiosk_availability' => '',
                'department' => 'Industries and Commerce',
                'service' => 'Common Application Form',
            ],
            76 => [
                'service_id' => '1854',
                'kiosk_availability' => 'ALL',
                'department' => 'Karbi Anglong Autonomous Council',
                'service' => 'Certified Copy of Mutation - KAAC',
            ],
            77 => [
                'service_id' => '1177',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Registration of Plantations in Form 13 under Section 2-A of the Plantations labour Rules 1956',
            ],
            78 => [
                'service_id' => '1176',
                'kiosk_availability' => '',
                'department' => 'Labour and Welfare',
                'service' => 'Registration of Establishment in Form I under Section 7 of the Buildings &amp; Other Construction Workers (R.E &amp; C.S) act, 1996 and Rule 23 (1) of the Assam Rules, 2007',
            ],
            79 => [
                'service_id' => '1843',
                'kiosk_availability' => '',
                'department' => 'Animal Husbandry and Veterinary',
                'service' => 'Post-mortem report',
            ],
            80 => [
                'service_id' => '1118',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC for storage and handling of LPG / CNG/ Oxygen / Hydrogen / Methane/Propane / Butane / Chlorine / Ammonia etc. under Assam Fire Service Rule 1989 (Form No II).',
            ],
            81 => [
                'service_id' => '1127',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in storage and handling of petroleum products / industry ( Class A, B &amp; C) under Assam Fire Service Rule 1989 (Form No VII).',
            ],
            82 => [
                'service_id' => '1130',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in the storage and handling of explosives under Assam Fire Service Rule 1989 (Form No IX).',
            ],
            83 => [
                'service_id' => '1111',
                'kiosk_availability' => '',
                'department' => 'Fire and Emergency Services',
                'service' => 'Fire NOC in respect of fire safety measures in the storage and handling of pharmaceutical products, chemical industries / storage of solvents etc under Assam Fire Service Rule 1989 (Form No X).',
            ],
            84 => [
                'service_id' => '1888',
                'kiosk_availability' => 'ALL',
                'department' => 'North Cachar Hills Autonomous Council',
                'service' => 'Trading License (NCHAC)',
            ],
            85 => [
                'service_id' => '1887',
                'kiosk_availability' => 'ALL',
                'department' => 'North Cachar Hills Autonomous Council',
                'service' => 'Trading Permit (NCHAC)',
            ],
            86 => [
                'service_id' => '1859',
                'kiosk_availability' => 'ALL',
                'department' => 'Skill, Employment and Entrepreneurship',
                'service' => 'Registration of employment seeker in Employment Exchange (AADHAR based)',
            ],
            87 => [
                'service_id' => '1860',
                'kiosk_availability' => 'ALL',
                'department' => 'Skill, Employment and Entrepreneurship',
                'service' => 'Re-registration of employment seeker in Employment Exchange (AADHAR based)',
            ],
        ];

        $this->load->view('theme1/temp_services', $data);
    }


    public function artps_services_api()
    {
        $this->load->model('artps_model');
        $data = $this->artps_model->get_all_services();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    public function citizen_registration()
    {
        $data['data'] = $this->settings_model->get_settings('citizen_reg');
        $this->render_view_new('citizen_reg', $data, array());
    }

    public function citizen_reg()
    {
        $this->load->view('theme1/citizen_reg_temp');
    }

    public function citizen_track()
    {
        $data['data'] = $this->settings_model->get_settings('citizen_track');
        $this->render_view_new('citizen_track', $data, array());
    }

    public function service_cat($cat_id)
    {
        $this->load->model('cat_model');
        $cat_id = intval($cat_id);

        $data['services'] = (array) $this->site_model->get_services_by_categ($cat_id, $this->lang);
        $data['settings'] = $this->settings_model->get_settings('service_category');

        $this->render_view_new('service_cat', $data);
    }

    /* Search using ELK search */

    public function elk_search($from = 0, $size = 10)
    {
        // Injection attacks prevention (XSS, HTML)

        $elk_query = htmlspecialchars(trim($this->input->get('query', true)));

        // print_r(['q' => $elk_query, 'server_info' => $_SERVER['SERVER_ADDR']]);

        if (empty($elk_query)) {
            redirect(
                empty($this->agent->referrer()) ? base_url('site') : $this->agent->referrer()
            );
        }

        $this->session->set_userdata(array("elk_query" => $elk_query));

        // fetch search results from ELK server

        $hosts = [
            // 'localhost:9200',
            '10.194.162.118:8080', // Host + Port
        ];
        $client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
        //$client = /Elasticsearch/ClientBuilder::create()->setHosts($hosts)->build();

        $params['index'] = 'rtps_*';
        $params['body'] = array(
            'query' => array(
                'multi_match' => array(
                    'query' => $this->session->userdata('elk_query'),
                    'type' => 'best_fields',
                    'fields' => [
                        "en.*",
                        "as.*",
                        "bn.*",
                    ],
                    'operator' => 'or',
                ),
            ),

            'highlight' => array(
                'pre_tags' => ['<em class="mark">'],
                'post_tags' => ['</em>'],
                'fields' => array(
                    "en.*" => new \stdClass(),
                    "as.*" => new \stdClass(),
                    "bn.*" => new \stdClass(),

                ),
            ),

            'fields' => [
                $this->lang . '.title',
                "url.*",
            ],
            '_source' => false,
            'track_total_hits' => true,
            'from' => $from,
            'size' => $size,
        );

        $data['search'] = $client->search($params);
        $data['settings'] = $this->settings_model->get_settings('search');
        $data['pagination'] = array(
            'from' => $from,
            'size' => $size,
            'display_info' => !empty($elk_query),
        );

        $this->render_view_new('elk_search', $data);
    }

    public function service_forms($service = '')
    {
        $data['settings'] = $this->settings_model->get_settings('service_forms');

        switch ($service) {
            case 'iwt':

                $this->render_view_new('iwt_form', $data);

                break;

            default:
                redirect($this->agent->referrer());
                break;
        }
    }
}
