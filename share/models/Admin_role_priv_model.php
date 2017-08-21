<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_role_priv_model extends My_Model {

    public $model = 'admin_role_priv';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    //获取指定角色权限列表
    public function get_role_priv($role_id = 0)
    {
    	if($role_id)
    		return $this->get_all("select role_id,model,ctrl from w_admin_role_priv where role_id = {$role_id}");
    	else
    		return false;
    }
    
    public function get_role_auth($role_id)
    {
    	$auth = array();
    	$query = $this->db->where(array('role_id'=>$role_id))->get('w_admin_role_priv');
    	$result = $query->result_array();
    	foreach ($result as $v)
    		$auth[$v['ctrl']][$v['act']] = 1;
    	return $auth;
    }
}

/* End of file admin_role_priv.php */
/* Location: ./application/models/admin_role_priv.php */