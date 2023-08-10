<?php

class Payment extends Rtps {
    private function pfcpayment($objId = null) {
        if(strlen($objId)) {
            $obj_id = $objId;
        } elseif($this->input->post('objid')) {
            $obj_id = $this->input->post('objid');
        }else {
            $obj_id = null;
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions(); 
            exit();
        }//End of if else
        
        /*if ($this->slug === "CSC") {
            $account = $this->config->item('csc_account');
            $mobile = $this->session->userdata('user')->mobileno;
        } else {
            $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
            $account = $user->account1;
            $mobile = $user->mobile;
        }*/

        $uniqid = uniqid();
        $DEPARTMENT_ID = $uniqid . time();
        $data = array();
        $data['service_charge'] = $this->config->item('service_charge');
        
        if ($this->slug === "CSC" || $this->slug === "PFC") {
            $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
            $data['printing_charge_per_page'] = $this->config->item('printing_charge');
            $data['rtps_trans_id'] = $this->input->post('rtps_trans_id');
            $data['no_printing_page'] = $this->input->post('no_printing_page');
            $data['no_scanning_page'] = $this->input->post('no_scanning_page');
            $data['pfc_payment'] = true;
            $ac1_amount = floatval($data['service_charge']);
        }
        if ($this->slug === "PFC") {
            if (!empty($data['no_printing_page']) && ( intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page']))) {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
            }
            if (!empty($data['no_scanning_page']) && intval($data['no_scanning_page']) < 0 || !is_numeric($data['no_printing_page'])) {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
            }
            if ($data['no_printing_page'] > 0) {
                // console.log("printing ::"+no_printing_page)
                $ac1_amount = $ac1_amount + intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
            }
            if ($data['no_scanning_page'] > 0) {
                // console.log("printing ::"+no_scanning_page)
                $ac1_amount = $ac1_amount + intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
            }
        }
        //echo "Here : "; pre($obj_id);
        if (strlen($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id));
            $transaction_data = $this->registered_deed_model->get_row($filter);
            //pre($transaction_data);
            if (empty($transaction_data)) {
                $this->my_transactions();
            }
            $data['rtps_trans_id'] = $transaction_data->rtps_trans_id;

            $dept_code = 'ARI';
            $office_code = "ARI000";

            $data['department_data'] = array(
                "DEPT_CODE" => $dept_code, //$user->dept_code,
                "OFFICE_CODE" => $office_code, //$user->office_code,
                "REC_FIN_YEAR" => "2022-2023", //dynamic
                "HOA1" => "",
                "FROM_DATE" => "01/04/2022",
                "TO_DATE" => "31/03/2099",
                "PERIOD" => "O", // O for ontimee payment
                "CHALLAN_AMOUNT" => "0",
                "DEPARTMENT_ID" => $DEPARTMENT_ID,
                "MOBILE_NO" => $transaction_data->mobile, //pfc no
                // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
                "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/payment_response/response'),
                "PARTY_NAME" => isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                "PIN_NO" => isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
                "ADDRESS1" => isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
                "ADDRESS2" => isset($transaction_data->address2) ? $transaction_data->address2 : "",
                "ADDRESS3" => isset($transaction_data->address3) ? $transaction_data->address3 : "781005"
            );
                
            $data['department_data']['MULTITRANSFER'] = "Y";
            $data['department_data']['NON_TREASURY_PAYMENT_TYPE'] = "02";

            if ($this->slug === "CSC") {
                $account1 = $this->config->item('csc_account');
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount) + floatval($this->rtps_convenience_fee);
                $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                $data['department_data']['ACCOUNT1'] = $account1;
                $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT2'] = $this->rtps_convenience_acc;
            } elseif ($this->slug === "PFC") {
                $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                $account1 = $user->account1;
                if ($account1 === $this->rtps_convenience_acc) {
                    $ac1_amount = $ac1_amount + intval($this->rtps_convenience_fee);
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $ac1_amount;
                    $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                    $data['department_data']['ACCOUNT1'] = $account1;
                } else {
                    $ac2_amount = $this->rtps_convenience_fee;
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount) + floatval($ac2_amount);
                    $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                    $data['department_data']['ACCOUNT1'] = $account1;
                    $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_acc;
                    $data['department_data']['ACCOUNT2'] = $ac2_amount;
                }//End of if else
            } else {
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['AC1_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT1'] = $this->rtps_convenience_acc;
            }//End of if else
            //pre($data);
            
            $res = $this->update_pfc_payment_amount($data);

            if ($res) {
                $this->load->view('iservices/basundhara/payment', $data);
            } else {
                $this->my_transactions();
            }
        } else {
            $this->my_transactions();
            return;
        }
    }
}