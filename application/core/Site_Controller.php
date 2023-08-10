<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     N3Cr0N (N3Cr0N@list.ru)
 * @copyright  2019 N3Cr0N
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @link       <URI> (description)
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 * @filesource
 *
 */

class Site_Controller extends MY_Controller
{
    //
    public $CI;

    /**
     * An array of variables to be passed through to the
     * view, layout, ....
     */
    protected $data = array();

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->helper('cookie');

        // Turn off error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', '0');

        // Send HTTP Security headers
        send_security_headers();

        // Redirect users to sewasetu.assam.gov.in if come from rtps.assam.gov.in
        $this->redirect_users_to_sewasetu();


        //Visitor count started on 17-8-2021
        if (is_new_visitor()) {
            $this->settings_model->update_visitors_count();
        }

        $this->lang = "en";    // Default language is "AS"

        $this->getData();

        // Logging user access

        $input_data = array();
        // $input_data['request_uri'] = $this->input->server('REQUEST_URI');
        $input_data['timestamp'] = date("Y-m-d H:i:s");
        // $input_data['client_ip'] = $this->input->server('REMOTE_ADDR');    // $_SERVER['HTTP_X_FORWARDED_FOR']

        $input_data['mongo_date'] = new MongoDB\BSON\UTCDateTime(strtotime("now") * 1000);
        // $input_data['client_ip'] = $this->input->server('X-Forwarded-For');

        $input_data['client_ip'] =  getallheaders()['X-Forwarded-For'] ?? $this->input->server('REMOTE_ADDR');

        $input_data['client_user_agent'] = $this->agent->agent_string();
        $input_data['referer_page'] = $this->agent->referrer();

        $this->mongo_db->insert('user_portal', $input_data);
    }

    public function getData()
    {
        // Get site's language from URL
        $site_lang = trim($this->input->get('lang', TRUE));

        if (!empty($site_lang) && in_array($site_lang, ['en', 'as', 'bn'], TRUE)) {
            $this->lang = $site_lang;

            set_custom_cookie('rtps_language', $this->lang);
        } else {
            if (empty($this->input->cookie('rtps_language', TRUE))) {

                // If no lang cookie found, then default langiage is ENG
                $this->lang = 'en';
            } else {
                $this->lang = $this->input->cookie('rtps_language', TRUE);
            }
        }
    }

    public function render_view_new($page_name = '', $data = array())
    {
        $this->load->view('theme1/includes/header', array(
            'header' => $this->settings_model->get_settings('header'),
        ));

        if (!empty($page_name)) {
            $this->load->view('theme1/' . $page_name, $data);
        } else {
            $this->load->view('theme1/index', $data);
        }

        $this->load->view('theme1/includes/footer', array('footer' => $this->settings_model->get_settings('footer')));
    }

    private function redirect_users_to_sewasetu()
    {
        $parsed_url = parse_url(current_url());
        $url = 'https://sewasetu.assam.gov.in' . ($parsed_url['path'] ?? '/site');

        // pre([$parsed_url, $url]);

        if (stristr($parsed_url['host'], 'rtps.assam.gov.in')) {

            $html = "
                <!DOCTYPE html>
                <html lang=\"en\">
                <head>
                    <meta charset=\"UTF-8\">
                    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                    <meta http-equiv=\"refresh\" content=\"4; url={$url}\" >
                    <title>RTPS | ASSAM</title>

                    <style>
                        body {
                            font-family: system-ui, sans-serif;
                            text-rendering: optimizeSpeed;
                            background-color: whitesmoke;
                        }
                        h3 {
                            text-align: center;
                            padding: 1.5em;
                            background-color: #dbe7eb;
                            color: #23128c;
                            border-radius: 1.5em;
                        }
                    </style>

                </head>
                <body>
                        <h3>RTPS Portal has been moved to Sewa Setu Portal. So, you are now being redirected to <a href=\"https://sewasetu.assam.gov.in/site\">Sewa Setu Portal</a> in a few seconds...</h3>
                </body>
                </html>

            ";

            $this->output->set_output($html);
            $this->output->_display();
            exit;
        }
    }
}
