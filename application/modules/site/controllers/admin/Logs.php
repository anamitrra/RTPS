<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Logs extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/dashboard_model");
    }

    public function index()
    {
        $data['daily'] = $this->dashboard_model->daily_count();   // portal user count
        $data['request'] = $this->dashboard_model->request_response();  // servicePlus user count

        // pre($data['request']);

        $final_data = [];

        //For matching user in both portal and sp
        foreach ($data['request'] as  $r) {
            foreach ($data['daily'] as  $d) {
                if ($r->date == $d->date) {
                    // $d->date;
                    // $d->count + $r->users;

                    $final_data[] = (object) ['date' => $r->date, 'count' => $r->count + $d->count];
                }
            }
        }

        //Finding remaining users in portal
        $pru = array_udiff($data['daily'], $final_data, function ($pdu, $fdu) {
            // echo "{$pdu->date}, {$fdu->date} <br>" ;
            return $pdu->date <=> $fdu->date;
        });

        //Finding remaining users in servicePlus
        $sru = array_udiff($data['request'], $final_data, function ($sdu, $fdu) {
            // echo "{$pdu->date}, {$fdu->date} <br>" ;
            return $sdu->date <=> $fdu->date;
        });


        $merged = array_merge($final_data, $pru, $sru);

        usort($merged, function ($d1, $d2) {
            return strtotime($d2->date) - strtotime($d1->date);
        });

        $final_merged = (count($merged) > 7) ? array_slice($merged, 0, 7) : $merged; // return the first 7 elements

        //pre($final_merged);
        $data['final'] = $final_merged;

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | User Logs"));
        $this->load->view("admin/logs", $data);
        $this->load->view("admin/includes/footer");
    }
}
