<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Grade extends My_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'grade_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$data ['list'] = $this->grade_model->get(NULL,pageSize,$page);
		// 分页
		$config ['base_url'] = site_url ( 'user/grade/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();		
		
		$this->template->display ( 'user/grade/list.html', $data );
	}
	
	public function detail($id = '')
	{
		$data = array ();
		
		if ($id)
		{
			$whereArr = array (
					'grade_id' => $id 
			);
			$result = $this->grade_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		$this->template->display ( 'user/grade/detail.html', $data );
	}
	
	public function save($id = '')
	{
		$data = $this->input->post ();
		
		$insertArr['grade_name'] = $data['grade_name'];
		
		if ($id) {
			$this->grade_model->save ( $data, $id );
		}else{
			show_error("没有该级别.");
		}
		redirect ( base_url () . 'user/grade' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->grade_model->update(array('disabled'=>$status) ,array('grade_id'=>$id));
			redirect(base_url().'user/grade');
		}
		else
		{
			show_error('参数错误');
		}
	}
	
	public function del($grade_id)
	{
		$grade_id = intval($grade_id);
		//查询该级别是否有用户
		$this->load->model ( 'user_model' );
		$num = $this->db->where(array('grade_id'=>$grade_id))->count_all_results('w_user');
		if($num){
			show_error("该级别下有会员,请先更改会员级别");
		}
		$grade_id && $this->grade_model->del(array('grade_id'=>$grade_id));
		redirect ( base_url () . 'user/grade' );
	}	
}