<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
class Ams_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
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
                    'gender'=>1,
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

    public function get_with_related_by_appeal_id_old($appeal_id)
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
                    'application_details' => '$application_data',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
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
     * @return void
     */
    public function appeals_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or'=>[
              ['applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * appeals_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function appeals_tot_search_rows($keyword)
    {
        $temp = array(
            '$or'=>[
              ['applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
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
     * @return void
     */
    public function appeals_filter($limit, $start, $temp, $col="created_at", $dir="asc")
    {

        $mobile=$this->session->userdata('mobile');
        $filter=['$and'=>[
            ['contact_number'=>$mobile]
        ]];

        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }
    /**
     * appeals_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function appeals_filter_count()
    {
        $mobile=$this->session->userdata('mobile');
        $filter=['$and'=>[
            ['contact_number'=>$mobile]
        ]];

        return $this->tot_search_rows($filter);
    }

        /**
     * applications_filter
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_filter($limit, $start, $col, $dir)
    {
        $this->set_collection("applications");
        $mobile=$this->session->userdata('mobile');

        $filter["initiated_data.attribute_details.mobile_number"]= strval($mobile);
        //pre($filter);
        return $this->search_rows($limit, $start, $filter, $col, $dir);

    }
    /**
     * applications_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function applications_filter_count()
    {
        $this->set_collection("applications");
        $mobile=$this->session->userdata('mobile');
        $filter["initiated_data.attribute_details.mobile_number"]= $mobile;

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

    public function applications_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * applications_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows($keyword)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }
}
