<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Area extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'area_model' );
		$this->load->model ( 'store_model' );
	}
	
	public function index($page = 1)
	{
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		 
		$sql_arr['data_sql'] = "SELECT a.*,b.name AS pname FROM w_area a LEFT JOIN w_area b ON a.pid = b.area_id limit $page," . pageSize;
		$sql_arr['count_sql'] = "SELECT count(1) as cnt FROM w_area";
		$data ['list'] = $this->area_model->get_page_list_by_sql($sql_arr);
		// 分页
		$config ['base_url'] = site_url ( 'sys/area/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'sys/area/list.html', $data );
	}
	
	public function detail($area_id = '')
	{
		$data = array ();
		
		if ($area_id)
		{
			$whereArr = array (
					'area_id' => $area_id
			);
			$result = $this->area_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		//一级城市
		$data['city'] = $this->area_model->one(array('where'=>array('pid'=>'0')),1);
		$this->template->display ( 'sys/area/detail.html', $data );
	}
	
	public function get_area_by_city($city_id = 0){
		$city_id = intval($city_id);
		if($city_id){
			$res = $this->area_model->one(array('where'=>array('pid'=>$city_id)),1);
			echo json_encode($res);
		}
	}
	
	public function save($id = '')
	{
		$data = $this->input->post ();
		$this->area_model->save ( $data, $id );
		redirect ( base_url () . 'sys/area' );
	}
	
	public function del($id)
	{
		//查询地区是否有分店
		$id = intval($id);
		if($this->store_model->search_area_store_cnt($id)){
			$this->area_model->del ( array (
					'area_id' => $id 
			) );
			redirect ( base_url () . 'sys/area' );
		}else{
			show_error('该地区有分店,请先删除分店.');
		}
	}

}