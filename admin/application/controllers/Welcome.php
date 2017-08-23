<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends My_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_model');
    }

    public function index()
    {
//     	$data['menu'] = $this->menu_model->get_all_menu("disabled = 'false'");
//     	//菜单树
//     	$ind = $menu = array();
//     	foreach ($this->menu_list as $v)
//     	{
//     		if($this->_user['role_id'] != 1 && ! isset($auth[$v['ctrl']]))	//菜单权限过滤
//     			continue;
//     		list($id, , $pid) = array_values($v);
//     		$ind[$id] = $v;
//     		isset($ind[$pid]) && $ind[$pid]['child'][$id] =& $ind[$id];
//     		$pid == 0 && $menu[$id] =& $ind[$id];
//     	}
        $this->template->display('welcome.html');
    }

    public function loginOut()
    {
       $this->session->sess_destroy();
       redirect(base_url());
    }

}