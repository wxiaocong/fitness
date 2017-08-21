<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Store extends My_Controller {
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'store_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'activity_model' );
	}
	
	public function index()
	{
		$data['headerCss'] = array('slide.css');
			
		$data['list'] = $this->store_model->get(array('disabled'=>'0'));
		$data['activity'] = $this->activity_model->one(array('where'=>array('disabled'=>'0')),1);
		
		$this->template->display('store/list.html',$data);
	}
	
	public function detail($store_id = 0)
	{
		$store_id = intval($store_id);
		if($store_id){
			$data = $this->store_model->one(array('where'=>array('store_id'=>$store_id)));
			if ( ! empty($data)){
				//分店课程
				$data['course_list'] = array_values($this->course_model->get_course_list($store_id));
				
				//分享
				$data['share'] = array(
					'title'	=>	trimall($data['name']),
					'desc'	=>	$data['addr'],
					'imgUrl'=>	$data['img1']
				);
				
				$data['headerCss'] = array('slide.css');
				$this->template->display('store/detail.html',$data);
				exit;
			}
		}
		show_error('不存在的分店');
	}
}
