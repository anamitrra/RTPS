<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Authorise extends Frontend
{
    public function restriction()
    {
        $this->load->config('methods');
        if (!empty($this->session->userdata('role'))) {
            $a = $this->session->userdata('role')->slug;
        //   echo $a;die;

            $method = $this->router->fetch_class() . '/' . $this->router->fetch_method();


            $authorise = $this->config->item($a);
            // echo($method);die;
            $access_denied_url="appeal/dashboard/access_denied";
            switch ($a) {

                case 'DA':
                    if (!in_array($method, $authorise)) {
                       // echo $method;die;
                        redirect($access_denied_url);
                    }

                    break;
                case 'PFC':
                    if (!in_array($method, $authorise)) {
                        //echo $method;die;
                        redirect($access_denied_url);
                    }

                    break;
                case 'DPS':
                    if (!in_array($method, $authorise)) {
                        //echo $method;die;
                        redirect($access_denied_url);
                    }
                case 'RA':
                    if (!in_array($method, $authorise)) {
                       // echo $method;die;
                        redirect($access_denied_url);
                    }
                    break;
                case 'AA':
                    if (!in_array($method, $authorise)) {
                        //echo $method;die;
                        redirect($access_denied_url);
                    }
                    break;
                case 'MOC':
                    if (!in_array($method, $authorise)) {
                        //echo $method;die;
                        redirect($access_denied_url);
                    }
                    break;
                case 'RR':
                    if (!in_array($method, $authorise)) {
                        //echo $method;die;
                        redirect($access_denied_url);
                    }
                    break;
                case 'ADMIN':
                    if (!in_array($method, $authorise)) {
                      //  echo $method;die;
                        redirect($access_denied_url);
                    }
                    break;

                    case 'DEPT_ADMIN':
                        if (!in_array($method, $authorise)) {
                          //  echo $method;die;
                            redirect($access_denied_url);
                        }
                        break;

                default:
                    break;

                    // case 'mis':
                    //     $config['mongo_db']['active'] = 'mis';
                    //     break;
                    // case 'grm':
                    //     $config['mongo_db']['active'] = 'grievances';
                    //     break;
                    // case 'iservices':
                    //     $config['mongo_db']['active'] = 'expr';
                    //     break;
                    // case 'appeal':
                    //     $config['mongo_db']['active'] = 'appeal';
                    //     break;
                    // case 'service_plus':
                    //     $config['mongo_db']['active'] = 'service_plus';
                    //     break;
                    // default:
                    //     $config['mongo_db']['active'] = 'default';
                    // break;
            }
        }

        // if(!in_array($this->session->userdata('role')->slug,['DA','RR','RA','MOC','AA','DPS'])){
        //     redirect('appeal/dashboard');
        // }
        // echo $a.' '.$method;  
        // die();
    }
}
