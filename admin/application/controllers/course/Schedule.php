<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Schedule extends My_Controller
{
	//课程分段时间
	private $ltime = array(
		'1'	=>	'8:00',
		'2'	=>	'9:00',			
		'3'	=>	'10:00',
		'4'	=>	'11:00',
		'5'	=>	'12:00',
		'6'	=>	'13:00',
		'7'	=>	'14:00',
		'8'	=>	'15:00',
		'9'	=>	'16:00',
		'10'=>	'17:00',
		'11'=>	'18:00',
		'12'=>	'19:00',
		'13'=>	'20:00',
		'14'=>	'21:00',
		'15'=>	'22:00',
		'16'=>	'23:00'
	); 
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'schedule_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'coach_model' );
	}
	
	public function index($store_id=0,$course_id=0,$coach_id=0,$start_data='')
	{
		$data['cur_store_id'] = $store_id;
		$data['cur_course_id'] = $course_id;
		$data['cur_coach_id'] = $coach_id;
		$data['cur_start_data'] = $start_data;
		
		$data['store_list'] = $this->store_model->get_store_list($this->_user['store_id']);
		if($store_id){
			$data['course_list'] = $this->course_model->get_course_list($store_id);
			if($course_id){
				$data['coach_list'] = $this->coach_model->get_coach_by_course($course_id);
			}
		}
		$data['footerJs'] = array('DatePicker/WdatePicker.js','schedule.js');
		$this->template->display ( 'course/schedule/list.html', $data );
	}

	//保存课程时间安排
	public function save(){
		$store_id = intval($this->input->post('store_id'));
		$course_id = intval($this->input->post('course_id'));
		$coach_id = intval($this->input->post('coach_id'));

		if($this->_user['role_id'] != 1){
			//分店管理员仅可安排该分店
			if($this->_user['store_id'] != $store_id){
				show_error ( '权限不足');
			}
		}
		
		$start_date = $this->input->post('start_date');
		$is_date = strtotime($start_date)?strtotime($start_date):false;
		if($is_date===false){
			show_error("非法日期格式.");
		}
		if( empty($course_id) || empty($coach_id) ){
			show_error('缺少参数!');
		}
		
		$data = array(
			'course_id'	=>	strval($course_id),
			'coach_id'	=>	strval($coach_id)					
		);
		
		//表单课程
		$new_data = $choose_date = array();
		for( $i = 1; $i <= 7; $i++){
			$param = 'k'.$i.'time';
			$choose = $this->input->post($param);
			
			$tmp_date = date('Y-m-d',strtotime($start_date) + 24*60*60*($i-1));	
			if( strtotime($tmp_date) < strtotime(date("Y-m-d")) ){
				continue;
			}
			$choose_date[] = $data['date'] = $tmp_date;
			if( ! empty($choose)){	
				foreach ($choose as $val){
					$data['time'] = $this->ltime[$val];
					$new_data[] = $data;
				}
			}
		}
		//获取该时间段已选择课程
		$choose_schedule = $this->schedule_model->get_choose_schedule($course_id,$coach_id,$choose_date,'course_id,coach_id,date,time');
		//比较差异
		$result = array_diff_assoc2_deep($new_data,$choose_schedule);
		if(!empty($result['add'])){	//新增
			//是否同一表
			$year = $insert = array();
			foreach ($choose_date as $val){
				$year[] = date('Y',strtotime($val));
			}
			$year = array_unique($year);
			
			foreach ($result['add'] as $value){
				$value['opera_id'] = $this->_user['uid'];
				$value['opera_time'] = date('Y-m-d H:i:s');
				$insert[date('Y',strtotime($value['date']))][] = $value;
			}
			$this->db->insert_batch('schedule_'.$year[0], $insert[$year[0]]);
			if(count($year) > 1){
				//跨年
				$this->db->insert_batch('schedule_'.$year[1], $insert[$year[1]]);
			}
			
		}
		if(!empty($result['del']))	//去除
		{
			foreach ($result['del'] as $v){
				$table = 'schedule_'.date('Y',strtotime($v['date']));
				$this->db->delete($table, $v);
			}
		}
		redirect ( base_url () . 'course/schedule/index/' . $store_id . '/' . $course_id . '/' . $coach_id . '/' . strtotime($start_date));
	}
	
	//获取分店课程教练列表
	public function get_coach_list($course_id = 0){
		$store_id = $coach_id = 0;
		if ($this->_user['role_id'] != 1){
			$store_id = $this->_user['store_id'];
		}
		if($this->_user['role_id'] == 4){ //教练
			$coach_id = $this->_user['uid'];
		}
		echo json_encode($this->coach_model->get_coach_by_course($course_id,$store_id,$coach_id));
	}
	//获取分店教练课程列表
	public function get_course_list($store_id = 0){
		echo json_encode($this->course_model->get_course_list($store_id));
	}
	
	//获取一周已选课程时间列表
	public function get_choose_schedule(){
// 		$store_id = intval($this->input->get('store_id'));
		$course_id = intval($this->input->get('course_id'));
		$coach_id = intval($this->input->get('coach_id'));
		
		$start_date = $this->input->get('start_date');
		
		$result =  array(
			'status'	=>	'0',
			'data'		=>	array(),
			'msg'		=>	''				
		);
		
		$is_date = strtotime($start_date)?strtotime($start_date):false;
		if($is_date===false){
			$result['msg'] = '非法日期格式.';
			echo json_encode($result);
			exit;
		}
		if($coach_id){
			$choose_date = array();//所选日期后7天
			$return_data = array();//返回数据
			for ($i = 0; $i < 7; $i++){
				$choose_date[] = date('Y-m-d',strtotime($start_date) + 24*60*60*$i);
			}
			$res = $this->schedule_model->get_choose_schedule($course_id,$coach_id,$choose_date);
			if( ! empty($res)){
				foreach ($res as $val){
					$key = (strtotime($val['date']) - strtotime($start_date))/(24*60*60);
					$val['ktime'] = array_search($val['time'],$this->ltime);
					$return_data[$key][] = $val;
				}
			}
			echo json_encode($return_data);
		}else{
			$result['msg'] = '缺少参数!';
			echo json_encode($result);
			exit;
		}
	}

}