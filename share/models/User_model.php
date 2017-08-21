<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends My_Model {

    public $model = 'user';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }

    //查找用户id
    public function get_user_id_by_openid($openid){
    	if($openid){
	    	$sql = "SELECT user_id FROM w_user WHERE openid = '{$openid}'";
	    	$res = $this->get_one($sql);
	    	return $res['user_id'];
    	}
    	return NULL;
    }
    
    //是否开通会员
    public function is_vip($openid){
    	if($openid){
	    	$sql = "SELECT is_open FROM w_user WHERE openid = '{$openid}'";
	    	$res = $this->get_one($sql);
	    	return $res['is_open'];
    	}
    	return NULL;
    }
}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */