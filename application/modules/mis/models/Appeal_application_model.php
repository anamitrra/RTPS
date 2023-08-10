<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
class Appeal_application_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("appeal");
        $this->set_collection("appeal_applications");
    }
    /**
     * get_by_appl_ref_no
     *
     * @param mixed $appl_ref_no
     * @return void
     */
    public function get_by_appl_ref_no($appl_ref_no)
    {
        $filter['appl_ref_no'] = $appl_ref_no;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    /**
     * get_by_appeal_id
     *
     * @param mixed $appeal_id
     * @return void
     */
    public function get_by_appeal_id($appeal_id)
    {
        $filter['appeal_id'] = $appeal_id;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    public function get_ref_id_by_appeal_id($appeal_id){
        $filter['appeal_id'] = $appeal_id;
        $this->mongo_db->select(array('appeal_id'));
        $data= $this->mongo_db->where($filter)->find_one($this->table);
        if( !empty($data)){
            return $data->_id->{'$id'};
        }else{
            return array();
        }
        
    }
    public function get_with_related_by_appeal_id($appeal_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$appeal_id',$appeal_id)
                    )

                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'applications',
                    'localField'   => 'appl_ref_no',
                    'foreignField' => 'initiated_data.appl_ref_no',
                    'as'           => 'application_data'
                )
            ),
            array('$unwind' => '$application_data'),
            array('$unwind' => '$process_users'),
           array(
               '$lookup'  => array(
                   'from'         => 'users',
                   'localField'   => 'process_users.userId',
                   'foreignField' => '_id',
                   'as'           => 'process_users_data'
               )
           ),
            array('$unwind' => '$process_users_data'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'appeal_expiry_status' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'service_id' => 1,
                    'location_id' => 1,
                    'created_at' => 1,
                    'application_data' => 1,
                    'process_users' => 1,
                    'process_users_data' => 1,
                    'tentative_hearing_date'=>1,
                    'relief_sought_for'=>1,
                    'village'=>1,
                    "district"=>1,
                    "police_station"=>1,
                    "circle"=>1,
                    "post_office"=>1,
                    "pincode"=>1,
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data;
    }
    public function get_with_related($appl_ref_no)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$appl_ref_no',$appl_ref_no)
                    )

                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'applications',
                    'localField'   => 'appl_ref_no',
                    'foreignField' => 'initiated_data.appl_ref_no',
                    'as'           => 'application_data'
                )
            ),
            array('$unwind' => '$application_data'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'appeal_description' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'created_at' => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                    'application_details' => '$application_data',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
              // pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }

    /**
     * appeal
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @param int $appealType
     * @return void
     */
    public function appeals_search_rows($limit, $start, $keyword, $col, $dir,$appealType = 1)
    {
        $temp = array(
            '$or'=>[
              ['appl_ref_no' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['contact_number' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ],
            '$and' => [
                ['appeal_type' => $appealType]
            ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

    /**
     * appeals_tot_search_rows
     *
     * @param mixed $keyword
     * @param int $appealType
     * @return void
     */
    public function appeals_tot_search_rows($keyword,$appealType = 1)
    {
        $temp = array(
            '$or'=>[
              ['appl_ref_no' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ],
            '$and' => [
                ['appeal_type' => $appealType]
            ]
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }

    /**
     * appeals_filter
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @param int $appealType
     * @return void
     */
    public function appeals_filter($limit, $start, $temp, $col, $dir,$appealType = 1)
    {
        $filter = array();
        $arr = array();
        $user_type=!empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        $user_id=$this->session->userdata('userId')->{'$id'};
        $filter['$and'][] = ['appeal_type' => $appealType];
        $filter['$and'][] = ['appeal_expiry_status' => FALSE];
        if($user_type === 'PFC'){
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
        }else{
            $filter['$and'][] = ['process_users.userId'=>new ObjectId($user_id)];
        }
        $col = "created_at";
        $dir = "asc";
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    /**
     * appeals_filter_count
     *
     * @param int $appealType
     * @return void
     */
    public function appeals_filter_count($appealType = 1)
    {
        $user_type=!empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        $user_id=$this->session->userdata('userId')->{'$id'};
        $filter['$and'][] =  ['appeal_type' => $appealType];
        if($user_type === 'PFC'){
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
        }else{
            $filter['$and'][] = ['process_users.userId'=>new ObjectId($user_id)];
        }

        // $this->set_collection("appeal_applications");
//        $filter=['$and'=>[
//            ['process_users.userId'=>new MongoDB\BSON\ObjectId($user_id)],
//            ['appeal_type' => $appealType]
//        ]];

        return $this->tot_search_rows($filter);
    }
    public function get_appeal_details($doc_id)
    {
        $filter = array();
        $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
        $filter['_id'] = new ObjectId($doc_id);
        $current_user = "";
        switch ($my_role) {
            case 'DPS':
                $filter['dps_id']       = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "DPS";
                break;
            case 'AA':
                $filter['appellate_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "APPELLATE";
                break;
            case 'RA':
                $filter['reviewing_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "REVIEWER";
                break;
            default:
                break;
        }
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$and' => array($filter)
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'applications',
                    'localField'   => 'appl_ref_no',
                    'foreignField' => 'initiated_data.appl_ref_no',
                    'as'           => 'application_data'
                )
            ),
            array('$unwind' => '$application_data'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'current_user' => $current_user,
                    'process_users' => 1,
                    'created_at' => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                    'application_details' => '$application_data',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }


    public function get_appeal_details_for_everyone($doc_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => ['_id'=>new ObjectId($doc_id)]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'applications',
                    'localField'   => 'appl_ref_no',
                    'foreignField' => 'initiated_data.appl_ref_no',
                    'as'           => 'application_data'
                )
            ),
            array('$unwind' => '$application_data'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$process_users'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'process_users.userId',
                    'foreignField' => '_id',
                    'as'           => 'process_users_data'
                )
            ),
            array('$unwind' => '$process_users_data'),
//            array('$limit' => intval(1)),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'village'=>1,
                    'district' => 1,
                    'police_station' => 1,
                    'circle' => 1,
                    'post_office' => 1,
                    'pincode' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'date_of_hearing' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'created_at' => 1,
                    'process_users' => 1,
                    'process_users_data' => 1,
                    'application_data' => 1,
                    'ref_appeal_id'=>1,
                    'application_details' => '$application_data',
                    'relief_sought_for'=>1,
                    'tentative_hearing_date'=>1
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
//                pre($data);
        $data = (array)$data;

        return $data;
//        return $data[0];
    }

    public function get_appeal_details_for_everyone_old($doc_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => ['_id'=>new ObjectId($doc_id)]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'applications',
                    'localField'   => 'appl_ref_no',
                    'foreignField' => 'initiated_data.appl_ref_no',
                    'as'           => 'application_data'
                )
            ),
            array('$unwind' => '$application_data'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array('$limit' => intval(1)),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'created_at' => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                    'application_details' => '$application_data',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //        pre($data);
        $data = (array)$data;

        return $data[0];
    }


    public function get_with_related_by_appeal_id_no_application_data($appeal_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$appeal_id',$appeal_id)
                    )

                )
            ),
            array('$unwind' => '$process_users'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'process_users.userId',
                    'foreignField' => '_id',
                    'as'           => 'process_users_data'
                )
            ),
            array('$unwind' => '$process_users_data'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'date_of_hearing' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'appeal_expiry_status' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'service_id' => 1,
                    'location_id' => 1,
                    'created_at' => 1,
                    'process_users' => 1,
                    'process_users_data' => 1,
                    'relief_sought_for'=>1,
                    'tentative_hearing_date' => 1,
                    'village'=>1,
                    "district"=>1,
                    "police_station"=>1,
                    "circle"=>1,
                    "post_office"=>1,
                    "pincode"=>1,
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
//        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data;
//        return $data[0];
    }

    public function get_with_related_by_appeal_id_no_application_data_old($appeal_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$appeal_id',$appeal_id)
                    )

                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'appeal_expiry_status' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'service_id' => 1,
                    'location_id' => 1,
                    'created_at' => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
//        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }


    public function get_appeal_details_without_ref($doc_id)
    {
        $filter = array();
        if(!empty($this->session->userdata('role'))){
          $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
          $userObjId = new ObjectId($this->session->userdata('userId')->{'$id'});
        }

        $filter['_id'] = new ObjectId($doc_id);

        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$and' => array($filter)
                )
            ),
            array('$unwind' => '$process_users'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'process_users.userId',
                    'foreignField' => '_id',
                    'as'           => 'process_users_data'
                )
            ),
            array('$unwind' => '$process_users_data'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service_details'
                )
            ),
            array('$unwind' => '$service_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'locations',
                    'localField'   => 'location_id',
                    'foreignField' => '_id',
                    'as'           => 'location_details'
                )
            ),
            array('$unwind' => '$location_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'gender' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'date_of_hearing' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'created_at' => 1,
                    'process_users' => 1,
                    'process_users_data' => 1,
                    'relief_sought_for'=>1,
                    'tentative_hearing_date'=>1,
                    'service_details'=>1,
                    'location_details'=>1,
                    'village'=>1,
                    "district"=>1,
                    "police_station"=>1,
                    "circle"=>1,
                    "post_office"=>1,
                    "pincode"=>1,
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data;
//        return $data[0];
    }

    public function get_appeal_details_without_ref_old($doc_id)
    {
        $filter = array();
        $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
        $filter['_id'] = new ObjectId($doc_id);
        $current_user = "";
        switch ($my_role) {
            case 'DPS':
                $filter['dps_id']       = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "DPS";
                break;
            case 'AA':
                $filter['appellate_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "APPELLATE";
                break;
            case 'RA':
                $filter['reviewing_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                $current_user = "REVIEWER";
                break;
            default:
                break;
        }
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$and' => array($filter)
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'current_user' => $current_user,
                    'created_at' => 1,
                    'process_users' => 1,
                    'dps_details'       => '$dps_details' ,
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }

    public function get_with_related_by_appeal_id_without_ref($appeal_id)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$appeal_id',$appeal_id)
                    )

                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                )
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'appeal_id' => 1,
                    'appl_ref_no' => 1,
                    'ref_appeal_id' => 1,
                    'applicant_name' => 1,
                    'contact_number' => 1,
                    'contact_in_addition_contact_number' => 1,
                    'additional_contact_number' => 1,
                    'email_id' => 1,
                    'contact_in_addition_email' => 1,
                    'additional_email_id' => 1,
                    'address_of_the_person' => 1,
                    'name_of_service' => 1,
                    'date_of_application' => 1,
                    'date_of_appeal' => 1,
                    'name_of_PFC' => 1,
                    'ground_for_appeal' => 1,
                    'appeal_description' => 1,
                    'appeal_expiry_status' => 1,
                    'documents' => 1,
                    'process_status' => 1,
                    'appeal_type' => 1,
                    'service_id' => 1,
                    'location_id' => 1,
                    'created_at' => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewer_details' => '$reviewing_auth_details',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
//        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }
    public function checkValidApplication($mobile,$appeal_id){
      $collection = 'appeal_applications';
      $data = $this->mongo_db->get_where($collection,array('contact_number'=>$mobile,'appeal_id'=>$appeal_id));
      // pre($data);
      $data = (array)$data;
      $response=false;
      if(!empty($data)){
        $response= true;
      }
      return $response;
    }
    public function appeal_timeline_check()
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match'=>[
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 30 //if crossed 30 days
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                    ]
                ]
            ),

            array(
                '$project' => array(
                    'appeal_id'=>1,
                    'appl_ref_no'=>1,
                    'days_passed'=>[
                        '$toInt' => [
                            '$divide' => [
                                [
                                    '$subtract' => [$current_time, '$created_at']
                                ],
                                86400000 // equivalent to one day
                            ]
                        ]
                    ],
                )
                )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return FALSE;
        }
    }
    public function service_wise_appeal_count()
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service_details'
                )
            ),
            array(
                '$group' => array(
                    '_id' => '$service_id',
                    'service_name' => ['$first' => '$service_details.service_name'],
                    'total' => array('$sum' => 1),
                )
            ),
            // array(
            //     '$project'=>[
            //         'service_details.service_name'=>1
            //     ]
            // )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }


    public function first_appeal_total_count($service_id)
    {
        $appealType = 1;
        $filter['$and'][] =  ['appeal_type' => $appealType];
        if($service_id){
            $filter['$and'][] =  ['service_id' => new ObjectId($service_id)];
        }
        return $this->tot_search_rows($filter);
    }

    public function first_appeal_disposal_count($service_id)
    {
        $appealType = 1;
        $filter['$and'][] =  ['appeal_type' => $appealType];
        $filter['$and'][] = ['process_status'=>'resolved'];
        if($service_id){
            $filter['$and'][] =  ['service_id' => new ObjectId($service_id)];
        }
        return $this->tot_search_rows($filter);
    }
    public function first_appeal_pending_count($service_id)
    {
        $appealType = 1;
        $filter['$and'][] =  ['appeal_type' => $appealType];
        $filter['$and'][] = ['$expr' => [
            '$ne' => [
                '$process_status', 'resolved'
            ]
        ]];
        if($service_id){
            $filter['$and'][] =  ['service_id' => new ObjectId($service_id)];
        }
        return $this->tot_search_rows($filter);
    }
    public function get_service_doc_id($service_id){
        $filter['service_id'] = $service_id;
        return $this->mongo_db->where($filter)->find_one("services");
        
       
    }
    public function get_districts(){
            return $this->mongo_db->get('districts');
    }   
}
