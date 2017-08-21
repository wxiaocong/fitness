<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends My_Controller {
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'tag_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'activity_model' );
		$this->load->model ( 'area_model' );
	}
	
	public function index($course_type = '1', $store_id = 0)
	{
		$data['course_type'] = $course_type;
		//分店课程(已分配教练)
		$sql = "select a.store_id,a.name,a.addr,a.city,a.latitude,a.longitude,a.img1,b.name as city_name from w_store a 
				left join w_area b on a.city = b.area_id where a.disabled = '0'";
		$store_list = $this->store_model->get_all($sql);
		//查找最近分店
		$distance = array();
		if( ! empty($store_list) ){
			foreach ($store_list as $v){
				$data['area'][$v['city']] = $v['city_name'];
				$data['store_list'][$v['city']][] = $v;
				
				if(empty($store_id) && $v['latitude'] > 0 && $v['longitude'] > 0){
					$distance[$v['store_id']] = getDistance($this->latitude,$this->longitude,$v['latitude'],$v['longitude']);
				}
			}
			if( empty($store_id) && ! empty($distance)){
				$min_dis = min($distance);
				$store_id = array_search($min_dis,$distance);
			}
		}else{
			show_error('没有设置分店');
		}
		
		if($store_id){
			$store_id_list = array_column($store_list, 'store_id');
			if( in_array($store_id, $store_id_list) ){
				$key = array_search($store_id, $store_id_list);
				$data['store_info'] =  $store_list[$key];
			}else{
				show_error('不存在的分店');
			}
		}else{
			//默认取第一个
			$data['store_info'] = reset($store_list);
			$store_id = $data['store_info']['store_id'];
		}
		
		//通用课程标签
		$sql = "select tag_id,tag_name from w_tag where disabled = '0'";
		$data['tag'] = $this->tag_model->get_all($sql);
		//分店教练
		$coach = array();
		$data['coach'] = $this->course_model->get_all_course($store_id,$course_type);
		
		$data['headerCss'] = array('slide.css');
		$data['footerJs'] = array(
				'course.js'
		);
		
		//分享
		$data['share'] = array(
			'title'	=>	trimall($data['store_info']['name']),
			'desc'	=>	trimall($data['store_info']['addr']),
			'imgUrl'=>	$data['store_info']['img1']
		);
		
		$data['activity'] = $this->activity_model->one(array('disabled'=>'0'),1);
		$this->template->display('course/list.html',$data);
	}
	
	public function detail($course_id = 0, $coach_id = 0){
		$coach_id = intval($coach_id);
		$course_id = intval($course_id);
		if( ! $course_id || ! $coach_id){
			$this->load->model ( 'user_model' );
			$user_info = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
			$coach_id = $user_info['bind_coach_id'];
			if(!$coach_id){
				//没有绑定跳至私教课程
				redirect(base_url().'course/index/2');
				exit;
			}
			//私教课程
			$course_list = $this->course_model->get_course_by_coach($coach_id,'2');
			if(empty($course_list)){
				//没有教练没有私教课程
				redirect(base_url().'course/index/2');
				exit;
			}
			$course_id = $course_list[0]['course_id'];
		}
		$data = $this->course_model->get_course_detail($course_id,$coach_id);
		if( ! empty($data['tag_name']) ){
			$data['tag'] = explode(',', $data['tag_name']);
		}
		$data['headerCss'] = array('slide.css');
		$data['activity'] = $this->activity_model->one(array('disabled'=>'0'),1);
		
		//分享
		$data['share'] = array(
			'title'	=>	$data['course_name'].'('.$data['coach_name'].')',
			'desc'	=>	trimall($data['introduce']),	
			'imgUrl'=>	$data['pic_persion']	
		);
		$this->template->display('course/detail.html', $data);
	}
}
