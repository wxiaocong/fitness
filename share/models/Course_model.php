<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends My_Model {

    public $model = 'course';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }

    public function save($data,$course_id = '')
    {
        if ($course_id) {
            $this->update($data, array('course_id' => $course_id));
        }else{
            $this->add($data);
        }
    }
    
    //获取课程可分配教练
    public function get_course_coach($course_id)
    {
    	$sql = "SELECT b.coach_id,b.coach_name  FROM w_course a LEFT JOIN w_coach b ON a.store_id = b.store_id
			WHERE a.course_id = $course_id AND b.disabled = '0'";
    	return $this->get_all($sql);
    }

    //获取课程分店
    public function get_store_by_course($course_id)
    {
    	$sql = "SELECT store_id FROM w_course WHERE course_id = {$course_id}";
    	return $this->get_one($sql);
    }
    
    //获取课程列表
    public function get_course_list($store_id = 0)
    {
    	$data = array();
    	$sql = "select course_id,course_name from w_course where disabled = '0'";
    	$store_id && $sql .= " and store_id = {$store_id}";
    	$res = $this->get_all($sql);
    	foreach ($res as $val){
    		$data[$val['course_id']] = $val['course_name'];
    	}
    	return $data;
    }
    
    //获取教练课程
    public function get_course_by_coach($coach_id = 0, $course_type = '0'){
    	$coach_id = intval($coach_id);
    	if($coach_id){
	    	$sql = "SELECT
				  course_id,course_name,package_price,package_num
				FROM w_course
				WHERE course_id IN(SELECT
				              course_id
				            FROM w_coach_relate_course
				            WHERE coach_id = {$coach_id})";
	    	if($course_type != '0'){
	    		$sql .= " AND course_type = '{$course_type}'";
	    	}
	    	return $this->get_all($sql);
    	}
    	return NULL;
    }
    
    //获取所有教练课程列表
    public function get_all_course($store_id = 0, $course_type = '1'){
    	if($store_id){
	    	$where_str = " WHERE a.disabled = '0' and c.course_type = '{$course_type}' and c.course_id > 0 and c.store_id > 0";
	    	$store_id && $where_str .= " AND c.store_id = {$store_id}";
	    	$sql = "SELECT
					  a.coach_id,
					  a.store_id,
					  a.coach_name,
					  a.pic_persion,
	    			  c.course_id,
					  c.course_name,
					  c.summary,
					  c.price,
					  c.package_num,
					  c.package_price,
					  c.tag_id,
					  GROUP_CONCAT(e.tag_name) AS tag_name
					FROM w_coach a
	    			  LEFT JOIN w_store d ON a.store_id = d.store_id
					  LEFT JOIN w_coach_relate_course b ON a.coach_id = b.coach_id
					  LEFT JOIN w_course c ON c.course_id = b.course_id
					  LEFT JOIN w_tag e ON FIND_IN_SET(e.tag_id,c.tag_id)
					$where_str GROUP BY c.course_id,a.coach_id";
	    	return $this->get_all($sql);
    	}
    }
    
    //获取教练课程详情
    public function get_course_detail($course_id, $coach_id = 0){
    	$course_id = intval($course_id);
    	$coach_id = intval($coach_id);
    	if($course_id && $coach_id){
	    	$sql = "SELECT
					  a.course_id,
					  a.course_name,
					  a.course_type,
					  a.video,
					  a.pic,
					  a.introduce,
					  a.notice,
					  a.price,
					  b.name        AS store_name,
					  b.addr,
					  b.addr_link,
					  c.coach_id,
					  c.coach_name,
					  c.pic_persion,
					  c.profile,
					  group_concat(e.tag_name) as tag_name
					FROM w_course a
					  LEFT JOIN w_store b
					    ON a.store_id = b.store_id
					    LEFT JOIN `w_coach_relate_course` d on a.`course_id`= d.`course_id`
  						LEFT JOIN w_coach c ON c.`coach_id`= d.`coach_id`  
  						LEFT JOIN w_tag e ON FIND_IN_SET(e.tag_id,a.tag_id)
	    		WHERE a.course_id = $course_id AND c.coach_id = $coach_id GROUP BY a.course_id";
	    	return $this->get_one($sql);
    	}
    }
    
    
    //获取分店标签过滤课程
    public function get_course_filter($store_id, $tag_id, $course_type = '1'){
    	if($store_id && $tag_id && $course_type){
    		$sql = "SELECT course_id,course_name FROM w_course 
    			WHERE store_id = {$store_id} AND course_type = '{$course_type}' AND FIND_IN_SET($tag_id,tag_id) AND disabled = '0'";
    		return $this->get_all($sql);
    	}
    	return NULL;
    }
    
    //获取课程价格
    public function get_course_price($course_id = 0){
    	if( ! empty($course_id) ){
    		$sql = "SELECT course_type,price,num FROM w_course WHERE course_id = $course_id";
    		return $this->get_one($sql);
    	}
    	return NULL;
    }
}

/* End of file store_model.php */
/* Location: ./application/models/store_model.php */