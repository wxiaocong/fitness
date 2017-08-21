<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Record extends My_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'train_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'order_model' );
	}
	public function index($page = 1) {
		
		$data ['store_id'] = $store_id = $this->input->post ( 'store_id' );
		$data ['coach_name'] = $coach_name = $this->input->post ( 'coach_name' );
		$data ['date'] = $date = $this->input->post ( 'date' );
		
		$where_str = "WHERE a.status in('1','3','4')";
		
		if($this->_user['role_id'] == '2'){
			//分店管理员
			$where_str .= " AND d.store_id = {$this->_user['store_id']}";
			$coach_name && $where_str .= " AND b.coach_id like '%{$coach_name}%'";
		}elseif($this->_user['role_id'] != '1'){
			//教练
			$where_str .= " AND b.coach_id = {$this->_user['uid']}";
		}else{
			$data ['store'] = $this->store_model->get_store_list ();
			
			$store_id && $where_str .= " AND d.store_id = {$store_id} ";
			$coach_name && $where_str .= " AND b.coach_id like '%{$coach_name}%'";
		}
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		
		$sql_arr ['data_sql'] = "SELECT a.*,b.course_name,c.coach_name,d.nickname FROM w_order a LEFT JOIN w_course b ON a.course_id = b.course_id
				LEFT JOIN w_coach c ON a.coach_id = c.coach_id LEFT JOIN w_user d ON a.openid = d.openid $where_str ORDER BY a.order_id desc limit $page," . pageSize;
		
		$sql_arr ['count_sql'] = "SELECT count(a.order_id) as cnt FROM w_order a LEFT JOIN w_course b ON a.course_id = b.course_id
				LEFT JOIN w_coach c ON a.coach_id = c.coach_id LEFT JOIN w_user d ON a.openid = d.openid $where_str";
		
		//数据
		$data ['list'] = $this->train_model->get_page_list_by_sql ( $sql_arr );
		
		// 分页
		$config ['base_url'] = site_url ( 'order/train/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$data ['footerJs'] = array (
				'DatePicker/WdatePicker.js' 
		);
		$this->template->display ( 'order/train/list.html', $data );
	}
	public function detail($order_id = '') {
		$data = array ();
		
		if ($order_id) {
			$data = $this->train_model->get_train_detail ( $order_id );
		}
		$data['order_id'] = $order_id;
		$this->template->display ( 'order/train/detail.html', $data );
	}
	
	public function save($order_id = 0, $train_id = 0)
	{
		$order_id = intval($order_id);
		if($order_id){
			$data = $this->input->post ();
		
			$insert_data = array('order_id'=>$order_id);
			foreach ($data as $key=>$val){
				if ($key != 'goals'){
					$insert_data[$key] = json_encode($val);
				}else{
					$insert_data[$key] = $val;
				}
			}
			
			$this->train_model->save ( $insert_data, $train_id);
		
			redirect ( base_url () . 'order/train' );
		}else{
			show_error('缺少参数');
		}
	}
}