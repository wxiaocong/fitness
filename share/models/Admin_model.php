<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends My_Model {

    public $model = 'admin';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    public function save($data,$id = '')
    {
        if ($id) {
        	if( ! $data['passwd'])
        		unset($data['passwd']);
        	else
        		$data['passwd'] = get_check_pwd(md5($data['passwd']));
        	$this->update($data, array('admin_id' => $id));
        }else{
        	if($this->check_uname_repeat($data['uname'])){
        		show_error('用户已存在.');
        	}
        	$data['passwd'] = get_check_pwd(md5($data['passwd']));
            $this->add($data);
        }
    }
    
    //检查用户名是否重复
    public function check_uname_repeat($uname = ''){
    	return $this->db->where(array('uname'=>$uname))->count_all_results('admin');
    }
    
    //查找管理员
    public function get_admin_by_id($admin_id = 0){
    	$admin_id = intval($admin_id);
    	if($admin_id){
    		$res = $this->get_one("SELECT uname,name FROM w_admin WHERE admin_id = {$admin_id}");
    		if(empty($res)){
    			return '';
    		}else{
    			return $res['uname'].':'.$res['name'];
    		}
    	}
    }
    

    public function get_md5_user($userName){
    	$sql = "SELECT * FROM w_admin WHERE md5(uname) = '{$userName}'";
    	return $this->get_one($sql);
    }
}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */