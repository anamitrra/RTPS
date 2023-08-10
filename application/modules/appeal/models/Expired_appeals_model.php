<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use MongoDB\BSON\ObjectId;
class Expired_appeals_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("appeal_applications");
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
    public function appeals_filter($limit, $start, $temp, $col, $dir)
    {
        $filter = array();
        $arr = array();
        $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
        //pre($my_role);
//        switch ($my_role) {
//            case 'DPS':
//                $arr['dps_id']       = new ObjectId($this->session->userdata('userId')->{'$id'});
//                break;
//            case 'AA':
//                $arr['appellate_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
//                break;
//            case 'RA':
//                $arr['reviewing_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
//                break;
//            default:
//                $arr['system']="system admin";
//                break;
//        }
        $arr['appeal_expiry_status']=true;
        $arr['is_rejected']=false;
        $filter = $arr;
        //pre($filter);
        $col = "created_at";
        $dir = "asc";
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
        $filter = array();
        $arr = array();
        $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
        switch ($my_role) {
            case 'DPS':
                $arr['dps_id']       = new ObjectId($this->session->userdata('userId')->{'$id'});
                break;
            case 'AA':
                $arr['appellate_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                break;
            case 'RA':
                $arr['reviewing_id'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                break;
            default:
                $arr['system']="system admin";
                break;
        }
        $arr['appeal_expiry_status']=true;
        $arr['is_rejected']=false;
        $filter = $arr;
        //pre($filter);
        return $this->tot_search_rows($filter);
    }
}
