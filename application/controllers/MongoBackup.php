<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MongoBackup extends frontend
{
	public function __construct()
	{
		parent::__construct();
    }

    public function index(){
      $this->load->view("backup");
    }
    public function backup(){
        $database=$this->input->post("db");
        $path='C:/xampp/htdocs/rtps/mongodump';
        if(!empty($database) ){
          
            $current_dump_path=date('d-m-Y_H-i');
            $command = "mongodump --db " . $database . " --out " . $path."/".$current_dump_path;
            $results = shell_exec($command);
	    echo  "<ul><li>" . $command . "</li><li>".$results."</li></ul>";
        }else{
            echo "Empty Input";
        }
    }
    public function restore(){
   
        $database=$this->input->post("db");
        $path=$_POST['path'];
        if(!empty($database) && !empty($path) ){
        $command='mongorestore -d '.$database.' '.$path;
	    $results = shell_exec($command);
	    echo  "<ul><li>" . $command . "</li><li>".$results."</li></ul>";
        }else{
            echo "Empty Input";
        }
       
    }
}