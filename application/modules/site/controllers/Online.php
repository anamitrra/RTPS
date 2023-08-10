<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Online extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');
    }
    public function index($active = '')
    {
        $this->load->model('dept_model');

        $data['active'] =
            ($this->dept_model->tot_search_rows(['department_short_name' => trim($active)])) ? trim($active) : '';

        $data['service_by_dept'] = (array) $this->dept_model->services_by_dept($this->lang);
        $data['settings'] = $this->settings_model->get_settings('apply_online');

        $this->render_view_new('online_services', $data);
    }

    public function service_wise_data()
    {
        $data = array();
        $data['table_header'] = $this->settings_model->get_settings('service-wise-data');
        $this->render_view_new('service_wise_data', $data);
    }

    public function basundhara()
    {
        $data['settings'] = $this->settings_model->get_settings('basundhara');
        $data['services'] = (array) $this->site_model->get_basundhara_services($this->lang);

        $this->render_view_new('basundhara_services.php', $data);
    }

    public function utility()
    {

        $data['settings'] = $this->settings_model->get_settings('utility');
        $data['services'] = (array) $this->site_model->get_services_by_categ_n_subcateg(15, null, $this->lang);

        // Get sub-categories 
        $data['sub_categs'] = ((array) $this->mongo_db->command(array(
            'find' => 'service_categories',
            'filter' => ['cat_id' => 15],
            'projection' => ['_id' => 0, 'sub_categories' => 1],
        )))[0] ?? [];


        $this->render_view_new('utility.php', $data);
    }
    public function eodb()
    {
        $data['settings'] = $this->settings_model->get_settings('eodb');
        $data['services'] = (array) $this->site_model->get_services_by_categ_n_subcateg(8, null, $this->lang);

        // Only display those services having status of therir DEPT as online
        $data['services'] = array_filter($data['services'], function ($val) {
            return isset($val->dept);
        });

        // Get sub-categories
        $data['sub_categs'] = ((array) $this->mongo_db->command(array(
            'find' => 'service_categories',
            'filter' => ['cat_id' => 8],
            'projection' => ['_id' => 0, 'sub_categories' => 1],
        )))[0] ?? [];

        $this->render_view_new('eodb.php', $data);
    }

    public function citizenservice()
    {

        $data['settings'] = $this->settings_model->get_settings('citizen');
        // pre($data['settings']);

        $data['services'] = (array) $this->site_model->get_services_by_categ_n_subcateg(4, null, $this->lang);



        // Get sub-categories 
        $data['sub_categs'] = ((array) $this->mongo_db->command(array(
            'find' => 'service_categories',
            'filter' => ['cat_id' => 4],
            'projection' => ['_id' => 0, 'sub_categories' => 1],
        )))[0] ?? [];
        // pre($data['sub_categs']);

        $this->render_view_new('citizenservice.php', $data);
    }


    public function get_services_by_subcateg($categ, $subcateg = '')
    {
        $subcateg = strtolower(trim($subcateg));
        $curr_lang = $this->lang;

        $data = (array) $this->site_model->get_services_by_categ_n_subcateg(
            intval($categ),
            $subcateg === 'all' ? null : $subcateg,
            $this->lang
        );

        $data = array_map(function ($val) use ($curr_lang) {
            return (object)[
                'service_id' => $val->service_id,
                'service_name' => $val->service_name->$curr_lang,
                'cat_id' => $val->cat_id,
                'sub_cat' => $val->sub_cat ?? '',
                'seo_url' => $val->seo_url,
                'department_name' => $val->dept->department_name->$curr_lang,
            ];
        }, $data);

        // pre($data);
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
