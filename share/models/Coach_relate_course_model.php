<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coach_relate_course_model extends My_Model {

    public $model = 'coach_relate_course';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }


    public function get_course_by_coach($coach_id = 0){
    	$data = array();
    	if($coach_id){
    		$res =  $this->get_all("select course_id from w_coach_relate_course where coach_id = $coach_id");
    		if ( ! empty($res)){
    			foreach ($res as $val){
    				$data[] = $val['course_id'];
    			}
    		}
    	}
    	return $data;
    }
    
}

/* End of file coach_relate_course_model.php */
/* Location: ./application/models/coach_relate_course_model.php */