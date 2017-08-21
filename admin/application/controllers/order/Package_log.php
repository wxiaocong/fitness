<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Package_log extends My_Controller
{
	private $pay_type = array(
			1=>'预约消费',
			2=>'套餐支付',
			3=>'充值套餐',
			4=>'取消订单',
			5=>'后台充值'
	);
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'package_log_model' );
		
	}
	
	public function index($page = 1)
	{
		$where_str = 'WHERE 1';
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);

		$data ['date']	= $date = $this->input->post ( 'date' );
		$data ['order_sn'] = $order_sn = $this->input->post ( 'order_sn' );
		$data ['nickname'] = $nickname = $this->input->post ( 'nickname' );
		$data ['ptype'] = $ptype = $this->input->post ( 'ptype' );
		
		$order_sn && $where_str .= " and b.order_sn like '%{$order_sn}%'";
		$nickname && $where_str .= " and f.nickname like '%{$nickname}%'";
		$ptype && $where_str .= " and a.pay_type = '{$ptype}'";
		
		$date = $date ? $date : date('Y-m');
		if( ! is_date($date.'-01')){
			show_error('日期格式错误');
		}
		
		$where_str .= " and left(a.dateline,7)='{$date}'";
		
		$sql_arr ['data_sql'] = "SELECT a.*,b.order_id,b.order_sn,d.course_name,e.coach_name,f.nickname FROM w_package_log a 
			LEFT JOIN w_order b ON a.order_id = b.order_id
			LEFT JOIN w_package c ON a.package_id = c.package_id
			LEFT JOIN w_course d ON c.course_id = d.course_id
			LEFT JOIN w_coach e ON c.coach_id = e.coach_id 
			LEFT JOIN w_user f ON a.openid = f.openid $where_str ORDER BY a.log_id DESC limit $page," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM w_package_log a
			LEFT JOIN w_order b ON a.order_id = b.order_id
			LEFT JOIN w_package c ON a.package_id = c.package_id
			LEFT JOIN w_course d ON c.course_id = d.course_id
			LEFT JOIN w_coach e ON c.coach_id = e.coach_id 
			LEFT JOIN w_user f ON a.openid = f.openid $where_str";
		$data ['list'] = $this->package_log_model->get_page_list_by_sql ( $sql_arr );
		// 分页
		$config ['base_url'] = site_url ( 'order/package_log/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$data ['pay_type'] = $this->pay_type;
		$data['footerJs'] = array('DatePicker/WdatePicker.js');
		$this->template->display ( 'order/package_log/list.html', $data );
	}
}