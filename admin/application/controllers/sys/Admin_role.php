<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_role extends My_Controller {

public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'admin_role_model' );
		$this->load->model ( 'admin_role_priv_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		 
		$data ['list'] = $this->admin_role_model->get(NULL,pageSize,$page);
		// 分页
		$config ['base_url'] = site_url ( 'sys/admin_role/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'sys/admin_role/list.html', $data );
	}
	
	public function priv($id = '')
	{
		if((int)$id)
		{
			$whereArr = array (
					'role_id' => $id
			);
		}
		else
			return false;
		$data = $ind = array();
		$data['role_id'] = $id;
		$this->load->model ( 'menu_model' );
		$menu_list = $this->menu_model->get_all_menu("disabled = 'false'");
		//菜单树		
		foreach ($menu_list as $v)
		{
			list($mid, , $pid) = array_values($v);
			$ind[$mid] = $v;
			if(isset($ind[$pid])) $ind[$pid]['child'][$mid] =& $ind[$mid]; //构造索引
			if($pid == 0) $data['tree'][$mid] =& $ind[$mid]; //转存根节点组
		}
		$auth = $this->admin_role_priv_model->get_role_priv((int)$id); //已有权限
		foreach ($auth as $v)
			$data['ctrl'][$v['ctrl']] = true;
		$data['footerJs'] = array('role_priv.js');
		$this->template->display ( 'sys/admin_role/priv_list.html', $data );		
	}
	
	public function detail($id = '')
	{
		$data = array ();
		
		if ($id)
		{
			$whereArr = array (
					'role_id' => $id 
			);
			
			$result = $this->admin_role_model->one ( array (
					'where' => $whereArr 
			) );
			
			$data ['result'] = $result;
		}
		$this->template->display ( 'sys/admin_role/detail.html', $data );
	}
	
	public function save($id = '')
	{
		$data = $this->input->post ();
		$this->admin_role_model->save ( $data, $id );

		redirect ( base_url () . 'sys/admin_role' );
	}
	
	public function save_priv($id = '')
	{
		$data = $this->input->post ();
		$data = array_values($data['data']);
		$insert_arr = $mb = $menu = array();
		//菜单
		foreach ($this->menu_list as $v)
			$menu[$v['menu_id']] = $v;
		foreach ($data as $v)
		{
			if( ! isset($menu[$v['menu_id']]))
				continue;
			$mb = array(
					'role_id' => $id,
					'model' => $menu[$v['menu_id']]['model'],
					'ctrl'	=> $menu[$v['menu_id']]['ctrl']
			);
			$insert_arr[] = $mb; 
// 			if(isset($v['save']))
// 			{
// 				$mb['act'] = 'save';
// 				$insert_arr[] = $mb; //修改
// 			}
// 			if(isset($v['del']))
// 			{
// 				$mb['act'] = 'del';
// 				$insert_arr[] = $mb; //删除
// 			}
		}
		$auth = $this->admin_role_priv_model->get_role_priv((int)$id); //已有权限
		$result = array_diff_assoc2_deep($insert_arr, $auth); //更改数据处理
		if(!empty($result['add']))	//新增权限
			$this->db->insert_batch('admin_role_priv', $result['add']);
		if(!empty($result['del']))	//失去权限
		{
			foreach ($result['del'] as $v)
				$this->db->delete('admin_role_priv', $v);
		}
	
		redirect ( base_url () . 'sys/admin_role/priv/' . $id );
	}	
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->admin_role_model->update(array('disabled'=>$status) ,array('role_id'=>(int)$id));
			redirect(base_url().'sys/admin_role/index');
		}
		else
		{
			show_error('参数错误');
		}
	}	
	
	public function del($role_id)
	{
		return false;
		$role_id = intval($role_id);
		$role_id && $this->admin_role_model->del(array('role_id'=>$role_id));
		redirect ( base_url () . 'sys/admin_role' );
	}

	//二维数组比较
	private function array_diff_assoc2_deep($array1, $array2) {
		if(empty($array1))
			return $result['del'] = $array2;
		if(empty($array2))
			$result['add'] = $array1;
		$arr1 = $arr2 = array();
		foreach ($array2 as $v)
			$arr2[] = md5(serialize($v));
		foreach ($array1 as $k=>$v)
		{
			$arr1[] = $tmp = md5(serialize($v));
			if(in_array($tmp, $arr2))
				unset($array1[$k]);	//已拥有
		}
		foreach ($array2 as $k=>$v)
		{
			if(in_array(md5(serialize($v)), $arr1))
				unset($array2[$k]);	//失去权限
		}
		$result['add'] = $array1;
		$result['del'] = $array2;
		return $result;
	}

}