<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class My_Controller extends CI_Controller
{
	
	var $_user = '';
	var $menu_list = '';
	
	public function __construct()
	{
		parent::__construct ();		
		date_default_timezone_set ( 'PRC' );
		
		$this->check_auth();
		$this->load->driver('cache');
		$this->load->library ( 'Account' );
		// 检查登陆
		$user = $this->account->checkLogin ();
		
		$this->_user = $user;
		
		// 角色
		$role_id = $this->session->userdata ( 'role_id' );
		//权限
		$auth = $this->session->userdata ( 'auth' );
		
		//菜单 优先缓存
		$this->menu_list = $this->cache->file->get('menu');
		if(empty($this->menu_list))
		{
			$this->load->model('menu_model');
			$this->menu_list = $this->menu_model->get_all_menu("disabled = 'false'");
			$this->cache->file->save('menu', $this->menu_list, file_cache_time);
		}
		//菜单树
		$ind = $menu = array();
		foreach ($this->menu_list as $v)
		{
			if($user['role_id'] != 1 && ! isset($auth[$v['ctrl']]))	//菜单权限过滤
				continue;
			list($id, , $pid) = array_values($v);
			$ind[$id] = $v;
			isset($ind[$pid]) && $ind[$pid]['child'][$id] =& $ind[$id];
			$pid == 0 && $menu[$id] =& $ind[$id];
		}
		$uri_string = $this->uri->uri_string ();
		
		$this->template->assign ( 'uri_string', $uri_string );		
		$this->template->assign ( 'menu', $menu );		
		$this->template->assign ( 'role_id', $role_id );		
		$this->template->assign ( 'username', $user ['username'] );		
		$this->template->assign ( 'user_data', $user );		
		$this->template->assign ( 'pageSize', pageSize );
		$this->template->assign ( 'pic_persion', $user ['pic_persion'] );
		$this->template->assign ( 'store_id', $user ['store_id'] );
				
		$this->template->assign ( 'ci', $this );
	}
	
	//操作权限检查
	protected function check_auth()
	{
		$role_id = $this->session->userdata('role_id');
		if($role_id == 1)
			return true;
		$uriArr = $this->uri->segment_array();
		if(in_array($uriArr[1], array('welcome','pwd'))){
			return true;
		}
// 		$uriArr3 = (isset($uriArr[3]) && $uriArr[3] != 'detail') ? $uriArr[3] : 'index';
// 		if( ! in_array($uriArr3,array('index','save','del')) )
// 			return true;
		$auth = $this->session->userdata('auth');

		if( empty($auth[$uriArr[1].'/'.$uriArr[2]]) )
			show_error ( '权限不足');
	}


	//获取系统参数
	public function get_all_param()
	{
		$param = $this->cache->file->get('param');
		if( ! $param)
		{
			$this->load->model('param_model');
			$param = $this->param_model->get_all_param();
			$this->cache->file->save('param', $param, file_cache_time);
		}
		return $param;
	}
}

/* End of file MY_Controler.php */
/* Location: ./application/controllers/MY_Controler.php */