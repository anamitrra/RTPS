<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Psql_Model extends CI_Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->postgres = $this->load->database('service_plus',true);
//        ['service_plus'] = $this->load->database('service_plus',true)
//        $this->db->setDatabase('service_plus');
    }

    public function my_get_where($selectString = '*',$whereArray = [],$limit = 0,$offset = 0){
//        pre( $this->load->database('service_plus',true));

        $query = $this->postgres->select($selectString);
        $whereInConditions = [];
        $whereConditions = [];
        foreach ($whereArray as $field => $value ){
            if(is_array($value)){
                $whereInConditions[][$field] = $value;
            }else{
                $whereConditions[$field] = $value;
            }
        }

        if(!empty($whereInConditions)){
            foreach ($whereInConditions as  $whereInCondition){
                foreach ($whereInCondition as $field => $arrayValue){
                    $query = $query->where_in($field,$arrayValue);
                }
            }
        }

        if(!empty($whereConditions)){
            $query = $query->where($whereConditions);
        }
//        pre($query->from($this->table)->get_compiled_select());

        if($limit){
            $query = $query->limit($limit,$offset);
        }

        return $query->get($this->table)->result();
    }

    public function my_insert($inputArray,$multi = false){
        if($multi){
            return $this->postgres->insert_batch($this->table,$inputArray);
        }else{
            return $this->postgres->insert($inputArray,$this->table);
        }
    }

}