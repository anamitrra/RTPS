<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Egras_txn_log_assam_model extends Psql_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'schm_sp.egras_txn_log_assam';
    }

    public function get_payment_log($select = null,$whereArray = [],$limit = false, $offset = false, $countOnly = false){
        pre('test');
//        $a = $this->postgres->query('select count(user_name) from schm_sp.m_adm_sign
//join schm_sp.egras_txn_log_assam on egras_txn_log_assam.user_id = m_adm_sign.user_id
//join schm_sp.application_detail_pages on application_detail_pages.user_id = m_adm_sign.user_id')->result();
//        pre($a);
        $query = $this->postgres->from($this->table)
            ->join('schm_sp.m_adm_sign','schm_sp.m_adm_sign.user_id = schm_sp.egras_txn_log_assam.user_id')
            ->join('schm_sp.application_detail_pages','schm_sp.application_detail_pages.user_id = schm_sp.m_adm_sign.user_id')->limit(10)->get()->result();
        pre($query);
//        pre($this->postgres->get_compiled_select());
        if(isset($select))
            $query = $query->select($select);

        if($limit){
            if(isset($offset)){
                $query = $query->limit($limit,$offset);
            }else{
                $query = $query->limit($limit);
            }
        }
        $query = $query->limit(10);
        pre($this->postgres->get_compiled_select());
        pre($query->get());
        pre($this->postgres->get_compiled_select());
pre(count($query->get()->result()));
        if($offset)
            $query = $query->limit();

        if(!empty($whereArray)){
            $query = $query->get_where($whereArray);
        }else{
            $query = $query->get();
        }

        if($countOnly){
            return $query->num_rows();
        }else{
            return $query->result();
        }
    }
}