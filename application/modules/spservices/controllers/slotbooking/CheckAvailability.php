<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class CheckAvailability extends Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('slotbooking/time_slot_model');
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper("role");
        $this->load->library('user_agent');
        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index()
    {
        // pre($this->input->post());
        $booking_date = $this->input->post('booking_date');
        $time_slot = $this->input->post('time_slot');
        $office = $this->input->post('office');
        $counter = 0;
        $max_limit = 4;
        $sts = 0;
        $filter = [
            'booking_date' => $booking_date,
            'office' => $office,
        ];
        $timeSlots = (array)$this->mongo_db->where($filter)->get('time_slots_txn');
        if (count($timeSlots)) {
            foreach ($timeSlots as $val) {
                if ($time_slot == $val->time_slot) {
                    $counter = $counter + 1;
                }
            }
        }
        if ($counter < $max_limit) {
            $data = array(
                "booking_date" => $this->input->post("booking_date"),
                "time_slot" => $this->input->post("time_slot"),
                "service_id" => $this->input->post('service'),
                "office" => $this->input->post("office"),
                "ref_no" => $this->input->post('ref_no'),
                "txn_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );
            $inserted_entries = $this->check_entry($data);
            if ($inserted_entries == 0) {
                $this->time_slot_model->insert($data);
            }
            $sts = 1;
            $msg = 'Slot available. You can go for booking.';
        } else {
            $sts = 0;
            $msg = 'Sorry, Selected slot is not available. Please select another slot.';
        }
        echo json_encode(['status' => $sts, 'msg' => $msg]);
    }

    public function check_available($pdata)
    {
        $booking_date = $pdata['booking_date'];
        $time_slot = $pdata['time_slot'];
        $office = $pdata['employment_exchange'];
        $rtps_ref_no = $pdata["rtps_ref_no"];
        $service_id = $pdata["service_id"];

        $counter = 0;
        $max_limit = 4;
        $sts = 0;
        $filter = [
            'booking_date' => $booking_date,
            'office' => $office,
        ];
        $timeSlots = (array)$this->mongo_db->where($filter)->get('time_slots_txn');
        if (count($timeSlots)) {
            foreach ($timeSlots as $val) {
                if ($time_slot == $val->time_slot) {
                    $counter = $counter + 1;
                }
            }
        }

        if ($counter < $max_limit) {
            $data = array(
                "booking_date" => $booking_date,
                "time_slot" => $time_slot,
                "service_id" => $service_id,
                "office" => $office,
                "ref_no" =>  $rtps_ref_no,
                "txn_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $inserted_entries = $this->check_entry($data);
            if ($inserted_entries == 0) {
                $this->time_slot_model->insert($data);
            }
            $sts = 1;
            $msg = 'Slot available. You can go for booking.';
        } else {
            $sts = 0;
            $msg = 'Sorry, Selected slot is not available. Please select another slot.';
        }
        return (['status' => $sts, 'msg' => $msg]);
    }

    public function check_entry($data)
    {
        unset($data['txn_time']);
        $entries = (array)$this->mongo_db->where($data)->get('time_slots_txn');
        return count($entries);
    }

    public function release_slots($ref_no = null)
    {
        $booked_slots = (array)$this->mongo_db->where(['ref_no' => $ref_no])->order_by('_id', 'desc')->get('time_slots_txn');
        if (count($booked_slots)) {
            foreach ($booked_slots as $key => $val) {
                if ($key != array_key_first($booked_slots)) {
                    $this->deleteEntry($val->_id->{'$id'});
                }
            }
        }
    }

    public function deleteEntry($id)
    {
        $this->mongo_db->where(['_id' => new ObjectId($id)])->delete('time_slots_txn');
    }
}
