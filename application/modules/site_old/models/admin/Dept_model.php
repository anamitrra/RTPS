 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dept_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('departments');
    }

    public function services_by_dept($lang = 'as')
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => array('online' => true)
            ),

            array(
                '$lookup' => array(
                    'from' => 'services',
                    // 'localField' => 'department_id',
                    // 'foreignField' => 'department_id',
                    'let' => array('department_id' => '$department_id', 'online' => '$online'),

                    'pipeline' => array(

                        array(

                            '$match' => array(

                                '$expr' => array(
                                    '$and' => array(
                                        array('$eq' => ['$department_id', '$$department_id'] ),
                                        array('$eq' => ['$online', true] ),
                                        
                                    )
                                )
                               
                            )
                        ),
                        array(
                            '$sort' => ['service_name.' . $lang => 1]
                        )

                    ),
                    'as' => 'services'
                ),
            ),

            array(
                '$project' => array(
                    '_id' => 0,
                    'department_name' => 1,
                    'department_id' => 1,
                    'department_short_name' => 1,
                    'icon' => 1,
                    'services.service_name' => 1,
                    'services.service_id' => 1,
                    'services.seo_url' => 1,
                ),
            ),

            array(
                '$sort' => array(
                    'department_name.' . $lang => 1
                )
            )

        ));
    }

    public function get_all_depts($lang='en')
    {
        $this->mongo_db->order_by(array('department_name.' . $lang => 'ASC'));
        
        return (array) $this->get_all([]);
    }

    public function add_new_dept($data)
    {
        return $this->insert($data);
    }

    public function get_dept_by_obID($ob_id)
    {
        return $this->get_by_doc_id($ob_id);
    }

    public function update_dept($object_id, $data)
    {
       return $this->update($object_id, $data);
    }

    public function get_dpt_id($dept_id)
    {
        return (array)$this->mongo_db->get_where($this->table, array('department_id' => $dept_id));
    }

    public function get_dpt_short($department_short_name)
    {
        return (array)$this->mongo_db->get_where($this->table, array('department_short_name' => $department_short_name));
    }

    public function get_dept_by_deptID($dept_id,$object_id)
    {
        $data=(array)$this->mongo_db->get_where($this->table, array('department_id' => $dept_id));
        if($data){
            if($data[0]->_id->{'$id'} === $object_id){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    public function get_dept_by_short($department_short_name,$old_department_short)
    {
      $data= (array)$this->mongo_db->get_where($this->table, array('department_short_name' => $department_short_name));
     // pre($data[0]->department_short_name);
      if($data){
        if($data[0]->department_short_name === $old_department_short){
            return true;
        }else{
            return false;
        }
    }else{
        return true;
    }

    }

}
