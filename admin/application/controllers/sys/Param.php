<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Param extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'param_model' );
	}
	
	public function index()
	{
		$data = array();
		$this->template->display ( 'sys/param/list.html', $data );
	}
	
	public function getData($page = 1){
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$search = $this->input->post('search');
		$where = "WHERE 1";
		$search && $where .= " AND s_key LIKE '%{$search}%'";
		$sql = "SELECT * FROM w_setting $where ORDER BY id DESC LIMIT $page,".pageSize;
		$data['list'] = $this->param_model->get_all($sql);
		$this->template->display ( 'sys/param/data.html', $data );
		
	}
	
	public function detail($id = '')
	{
		$data = array ();
		
		if ($id)
		{
			$whereArr = array (
					'id' => $id 
			);
			$result = $this->param_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		$this->template->display ( 'sys/param/detail.html', $data );
	}
	
	public function save($id = '')
	{
		$data = $this->input->get_post (NULL, TRUE);
		echo $this->param_model->save ( $data, $id );
	}
	
	public function del($id)
	{
		$id = intval($id);
		$id && $this->param_model->del ( array (
				'id' => $id 
		) );
	}

}