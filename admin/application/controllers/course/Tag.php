<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tag extends My_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'tag_model' );
		$this->load->model ( 'store_model' );
	}
	public function index($page = 1) {
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		
		// 显示分店自定义课程
		$where = ' WHERE 1';
		if ($this->_user ['store_id']) {
			$where .= " AND a.store_id = {$this->_user ['store_id']}";
		}
		$sql_arr ['data_sql'] = "SELECT a.*,b.name AS store_name FROM w_tag a LEFT JOIN w_store b ON a.store_id = b.store_id $where limit $page," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM w_tag a LEFT JOIN w_store b ON a.store_id = b.store_id $where";
		$data ['list'] = $this->tag_model->get_page_list_by_sql ( $sql_arr );
		
		// 分页
		$config ['base_url'] = site_url ( 'course/tag/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'course/tag/list.html', $data );
	}
	
	public function detail($tag_id = '') {
		$data = array ();
		if ($tag_id) {
			
			$sql = "select * from w_tag where tag_id = $tag_id";
			$store_id = $this->_user ['store_id'];
			if ($store_id) {
				$sql .= " and store_id = $store_id";
			}
			$data ['result'] = $this->tag_model->get_one ( $sql );
		}
		$data['store_list'] = $this->store_model->get_store_list($this->_user['store_id']);
		$this->template->display ( 'course/tag/detail.html', $data );
	}
	
	public function save($tag_id = '') {
		$data ['tag_name'] = trim ( $this->input->post ( 'tag_name' ) );
		if ($data ['tag_name'] != '') {
			if($this->_user['role_id'] == 1){
				$data ['store_id'] = $this->input->post ( 'store_id' );
			}else{
				$data ['store_id'] = $this->_user ['store_id'];
			}
			$this->tag_model->save ( $data, $tag_id );
		}
		redirect ( base_url () . 'course/tag' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->tag_model->update(array('disabled'=>$status) ,array('tag_id'=>$id));
			redirect(base_url().'course/tag');
		}
		else
		{
			show_error('参数错误');
		}
	}
	
	public function del($tag_id) {
		$tag_id = intval($tag_id);
		$tag_id && $this->tag_model->del(array('tag_id'=>$tag_id));
		redirect ( base_url () . 'course/tag' );
	}
}