<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_log_model extends My_Model {

    public $model = 'package_log';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
    
        
    public function get_package_record($openid, $package_id){
    	$package_id = intval($package_id);
    	if($openid && $package_id){
    		$sql = "SELECT a.gain-a.expence AS pay,pay_type,a.balance_num,a.dateline,a.order_id,c.course_name,d.coach_name FROM w_package_log a 
				LEFT JOIN w_package b ON a.package_id = b.package_id
				LEFT JOIN w_course c ON c.course_id = b.course_id 
				LEFT JOIN w_coach d ON b.coach_id = d.coach_id
    			WHERE a.openid = '$openid' AND a.package_id = $package_id ORDER BY log_id DESC";
    		return $this->get_all($sql);
    	}
    }
}

/* End of file package_log_model.php */
/* Location: ./application/models/package_log_model.php */