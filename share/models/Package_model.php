<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_model extends My_Model {

    public $model = 'package';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
	public function get_user_package($openid,$course_id=0,$coach_id=0)
	{
		if($openid){
			$sql = "SELECT a.package_id,a.package_num,a.userd_num,a.package_num-a.userd_num AS num,b.course_name,c.coach_name  
				FROM w_package a LEFT JOIN w_course b ON a.course_id = b.course_id
				LEFT JOIN w_coach c ON a.coach_id = c.coach_id WHERE a.openid = '{$openid}'";
			$course_id && $sql .= " AND a.course_id = $course_id";
			$coach_id && $sql .= " AND a.coach_id = $coach_id";
			return $course_id ? $this->get_one($sql) : $this->get_all($sql);
		}
	}  
}

/* End of file Package_model.php */
/* Location: ./application/models/Package_model.php */