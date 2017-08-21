<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Activity extends My_Controller {
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'activity_model' );
	
	}	
	public function index($activity_id = 0)
	{
		$activity_id = intval($activity_id);
		$data = array();
		if( ! $activity_id){
			//推荐活动
			$data = $this->activity_model->one(array('where'=>array('recommend'=>'1','disabled'=>'0')));
		}
		if( empty($data) ){	
			if( ! $activity_id){
				$data = $this->activity_model->one(array('order_by'=>array('activity_id'=>'DESC','disabled'=>'0')));
			}else{
				$data = $this->activity_model->one(array('where'=>array('activity_id'=>$activity_id,'disabled'=>'0')));
			}
		}
		
		//分享 取前三段中文
		preg_match('~[\x{4e00}-\x{9fa5}\,\，]+~u', $data['content'], $match);
		$data['share'] = array(
				'title'	=>	trimall($data['name']),
				'desc'	=>	$match[0],
				'imgUrl'=>	$data['slide_img']
		);
		
		$data['headerCss'] = array('slide.css');
		$this->template->display('activity/detail.html',$data);
	}
}
