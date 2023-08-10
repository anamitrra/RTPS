<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feedback extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('feedback_model');
        $this->load->helper('captcha');
        
    }

    public function index()
    {
        // $data['cap'] = generate_n_store_captcha();
        // $data['settings'] = $this->settings_model->get_settings('feedback');
        // $this->render_view_new('feedback', $data);
    }

    public function feedback_action()
    {
        $lang = $this->lang;
        $settings = $this->settings_model->get_settings('feedback');

        $this->form_validation->set_rules('name', $settings->full_name->$lang, 'trim|required|regex_match[/^[a-zA-Z]+[a-zA-Z \.]+$/]');
        $this->form_validation->set_rules('email', $settings->email->$lang, 'trim|valid_email');
        $this->form_validation->set_rules('phone', $settings->phone->$lang, 'trim|required|exact_length[10]|is_natural');
        $this->form_validation->set_rules('address', $settings->address->$lang, 'trim');
        $this->form_validation->set_rules('feedback', $settings->feedback->$lang, 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|validate_captcha_portal[' . $settings->cap_err->$lang . ']');
        $this->form_validation->set_rules('rating', $settings->rating->$lang, 'trim|required|in_list[0,1,2,3,4,5]');


        // Custom error messages
        $this->form_validation->set_message('required', $settings->error_msg->$lang);
        $this->form_validation->set_message('valid_email', $settings->error_msg->$lang); 
        $this->form_validation->set_message('exact_length', $settings->error_msg->$lang); 
        $this->form_validation->set_message('is_natural', $settings->error_msg->$lang);
        $this->form_validation->set_message('regex_match', $settings->error_msg->$lang);
        // $this->form_validation->set_message('alpha', $settings->error_msg->$lang);
        $this->form_validation->set_message('in_list', $settings->error_msg->$lang);
        
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } 
        else {
            $data['name'] = trim($this->input->post('name', true));
            $data['phone'] = trim($this->input->post('phone', true));
            $data['feedback'] = trim($this->input->post('feedback', true));
            $email = trim($this->input->post('email', true));
            $address = trim($this->input->post('address', true));
            $data['rating'] = intval(trim($this->input->post('rating', true)));

            if (! empty($email)) {
               $data['email'] = $email;
            }
            if (! empty($address)) {
                $data['address'] = $address;
            }

            $this->feedback_model->insert($data);

            $this->session->set_flashdata('success', $settings->success_msg->$lang);
            redirect('/site/feedback');
        }

    }

    public function refresh_captcha()
    {
        if ($this->input->is_ajax_request()) {
            $cap = generate_n_store_captcha();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'captcha' => $cap,
                    'status' => true,
                )));
        }
    }
}
