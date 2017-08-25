<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Admin extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'admin_model' );
		$this->load->model ( 'admin_role_model' );
		$this->load->model ( 'store_model' );
	}
	
	public function index($page = 1)
	{
		//角色列表
		$data['admin_role'] = $this->admin_role_model->get_role();
		//分店列表
		$data['store_list'] = $this->store_model->get_store_list();
		
		$this->template->display ( 'sys/admin/list.html', $data );
	}
	
	public function getData($page = 1){
	
		$data['role_id'] = $role_id = intval($this->input->post('role_id'));
		$data['store_id'] = $store_id = intval($this->input->post('store_id'));
		$data['uname'] = $uname = $this->input->post('uname');
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		
		$where = "WHERE 1";
		$role_id && $where .= " AND a.role_id = {$role_id}";
		$store_id && $where .= " AND b.store_id = {$store_id}";
		$uname && $where .= " AND a.uname LIKE '%$uname%'";
		
		
		$sql = "SELECT
		a.admin_id,a.role_id,a.store_id,a.uname,a.name,a.disabled,b.name AS store_name,c.role_name
		FROM w_admin a LEFT JOIN w_store b ON a.store_id = b.store_id 
		LEFT JOIN w_admin_role c ON a.role_id = c.role_id $where limit $page," . pageSize;
		
		$data['list'] = $this->admin_model->get_all($sql);
		
		$this->template->display ( 'sys/admin/data.html', $data );
	
	}
	
	public function detail($id = '')
	{
		$data = array ();
		if ($id)
		{
			$whereArr = array (
					'admin_id' => $id 
			);
			$result = $this->admin_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		//角色列表
		$data['admin_role'] = $this->admin_role_model->get_role();
		//去掉教练角色
		unset($data['admin_role'][4]);
		//分店列表
		$data['store_list'] = $this->store_model->get_store_list();
		
		$data['footerJs'] = array('admin.js');
		$this->template->display ( 'sys/admin/detail.html', $data );
	}
	
	public function save($id = '')
	{
		$data = $this->input->post ();

		$this->admin_model->save ( $data, $id );

		redirect ( base_url () . 'sys/admin' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->admin_model->update(array('disabled'=>$status) ,array('admin_id'=>(int)$id));
			redirect(base_url().'sys/admin/index');
		}
		else
		{
			show_error('参数错误');
		}
	}
	
	public function del($admin_id)
	{
		exit();
		$admin_id = intval($admin_id);
		$admin_id && $this->admin_model->del(array('admin_id'=>$admin_id));		
		redirect ( base_url () . 'sys/admin' );
	}

}