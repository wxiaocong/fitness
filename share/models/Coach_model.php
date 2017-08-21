 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coach_model extends My_Model {

    public $model = 'coach';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    public function save($data,$coach_id = '')
    {
        if ($coach_id) {
        	if( ! $data['passwd'])
        		unset($data['passwd']);
        	else
        		$data['passwd'] = get_check_pwd(md5($data['passwd']));
        	$this->update($data, array('coach_id' => $coach_id));
        }else{
        	if($this->check_uname_repeat($data['uname'])){
        		show_error('教练已存在.');
        	}
        	$data['passwd'] = get_check_pwd(md5($data['passwd']));
            $this->add($data);
        }
    }
    
    public function get_md5_user($userName){
    	$sql = "SELECT * FROM w_coach WHERE md5(uname) = '{$userName}'";
    	return $this->get_one($sql);
    }
    
	//检查用户名是否重复
    public function check_uname_repeat($uname = ''){
    	return $this->db->where(array('uname'=>$uname))->count_all_results('coach');
    }
    
    //获取分店教练列表
    public function get_coach_by_store($store_id = 0){
    	$store_id = intval($store_id);
    	if($store_id){
    		$sql = "select coach_id,coach_name from w_coach where store_id = $store_id and disabled = '0'";
    		return $this->get_all($sql);
    	}
    	return null;
    }
    
    //获取课程教练
    public function get_coach_by_course($course_id = 0, $store_id = 0, $coach_id = 0){
    	$course_id = intval($course_id);
    	if($course_id){
    		$sql = "SELECT a.coach_id,a.coach_name FROM w_coach a LEFT JOIN w_coach_relate_course b ON a.coach_id = b.coach_id
					LEFT JOIN w_course c ON c.course_id = b.course_id WHERE c.course_id = {$course_id} AND a.disabled = '0'";
    		$coach_id && $sql .= " AND a.coach_id = $coach_id";
    		$store_id && $sql .= " AND a.store_id = $store_id";
    		return $this->get_all($sql);
    	}
    	return null;
    }
    
	//获取确认订单基本信息
	public function get_pre_order($course_id = 0, $coach_id = 0){
		if($coach_id){
			$sql = "SELECT
					  a.coach_id,
					  a.coach_name,
					  a.store_id,
					  d.price,
					  b.name     AS store_name,
					  b.addr,
					  d.course_id,
					  d.num as limit_num,
					  d.course_name,
					  d.course_type,
					  d.package_num,
					  d.package_price  
					FROM w_coach a
					  LEFT JOIN w_store b
					    ON a.store_id = b.store_id
					  LEFT JOIN w_coach_relate_course c
					    ON a.coach_id = c.coach_id
					  LEFT JOIN w_course d
					    ON c.course_id = d.course_id
					WHERE a.coach_id = {$coach_id} and d.course_id = {$course_id}";
			return $this->get_one($sql);
		}
		return NULL;
	}
	
	//根据教练查找分店id
	public function get_store_by_coach($coach_id = 0){
		$coach_id = intval($coach_id);
		if($coach_id){
			$res =  $this->get_one("SELECT store_id FROM w_coach WHERE coach_id = {$coach_id}");
			return isset($res['store_id']) ? $res['store_id'] : 0;
		}
		return 0;
	}
	
	//查找教练
	public function get_coach_by_id($coach_id = 0){
		$coach_id = intval($coach_id);
		if($coach_id){
			$res = $this->get_one("SELECT coach_name FROM w_coach WHERE coach_id = {$coach_id}");
			return empty($res) ? '' : $res['coach_name'];
		}
	}
}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */