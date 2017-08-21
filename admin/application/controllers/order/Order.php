<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Order extends My_Controller {
	private $status_arr = array (
			'未支付',
			'已支付',
			'已取消',
			'已完成',
			'已退款' 
	);
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'course_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'order_model' );
	}
	public function index($page = 1) {
		$where_str = 'WHERE 1';
		
		$data ['store_id'] = $store_id = $this->input->post ( 'store_id' );		
		$data ['order_type'] = $order_type = $this->input->post ( 'order_type' );
		$data ['realname'] = $realname = $this->input->post ( 'realname' );
		$data ['coach_name'] = $coach_name = $this->input->post ( 'coach_name' );
		$data ['cstatus'] = $status = $this->input->post ( 'status' );
		$data ['date'] = $date = $this->input->post ( 'date' );
		$data ['time'] = $time = $this->input->post ( 'time' );
		
		$data ['store'] = $this->store_model->get_store_list ();		
		
		$store_id && $where_str .= " and a.store_id = {$store_id}";
		$status !== NULL && $status !== '' && $where_str .= " and a.status = '{$status}'";
		$date && $where_str .= " and a.date = '{$date}'";
		$time && $where_str .= " and a.time = '{$time}'";
		$realname && $where_str .= " and f.realname like '%{$realname}%'";
		$coach_name && $where_str .= " and c.coach_name like '%{$coach_name}%'";
		
		//没有分店id为充值订单
		$order_type ? $where_str .= " and a.store_id = 0" : $where_str .= " and a.store_id > 0";
		
		if ($this->_user['role_id'] == 4){ //教练
			$where_str .= " and a.coach_id = {$this->_user['uid']}";
		}elseif ($this->_user['role_id'] != 1){
			$where_str .= " and a.store_id = {$this->_user['store_id']}";
		}
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		
		$sql_arr ['data_sql'] = "SELECT
				  a.order_id,
				  a.order_sn,
				  a.num,
				  a.date,
				  a.time,
				  concat(a.date,' ',a.time) as date_time,
				  a.payment,
				  a.tel,
				  a.status,
				  b.name     AS store_name,
				  c.coach_name,
				  f.nickname,
				  f.realname,
				  e.course_name,
				  e.num as max_num
				FROM w_order a
				  LEFT JOIN w_store b
				    ON a.store_id = b.store_id
				  LEFT JOIN w_coach c
				    ON a.coach_id = c.coach_id
				  LEFT JOIN w_course e
				    ON a.course_id = e.course_id
				  LEFT JOIN w_user f
				    ON a.openid = f.openid
			  	$where_str
			ORDER BY a.order_id desc limit $page," . pageSize;
		
		$sql_arr ['count_sql'] = "SELECT count(order_id) AS cnt FROM (SELECT
			  a.order_id
			FROM w_order a
			  LEFT JOIN w_store b
			    ON a.store_id = b.store_id
			  LEFT JOIN w_coach c
			    ON a.coach_id = c.coach_id
			  LEFT JOIN w_coach_relate_course d
			    ON c.coach_id = d.coach_id
			  LEFT JOIN w_course e
			    ON e.course_id = d.course_id
			  LEFT JOIN w_user f
			    ON a.openid = f.openid
			  	$where_str
			GROUP BY a.order_id) t";
		//数据
		$data ['list'] = $this->order_model->get_page_list_by_sql ( $sql_arr );
		//不可取消时间
		$cannot_cancel_order_time = $this->order_model->cannot_cancel_order_time; 
		$data ['cannot_cancel_datetime'] =  date('Y-m-d H:i',strtotime("+$cannot_cancel_order_time hour"));
		// 分页
		$config ['base_url'] = site_url ( 'order/order/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$data ['status'] = $this->status_arr;
		$data ['footerJs'] = array (
				'DatePicker/WdatePicker.js' 
		);
		$this->template->display ( 'order/order/list.html', $data );
	}
	public function detail($order_id = '') {
		$data = array ();
		
		if ($order_id) {
			$data ['result'] = $this->order_model->get_order_detail ( $order_id );
			if($data['result']['opera_id']){
				if($data['result']['opera_role'] == '4'){//教练
					$this->load->model ( 'coach_model' );
					$data['result']['operaName'] = $this->coach_model->get_coach_by_id($data['result']['opera_id']);
				}else{
					$this->load->model ( 'admin_model' );
					$data['result']['operaName'] = $this->admin_model->get_admin_by_id($data['result']['opera_id']);
				}
			}
		}
		$data ['status'] = $this->status_arr;
		$this->template->display ( 'order/order/detail.html', $data );
	}
	
	// 导出excel
	public function export($type = 0, $lx = 'xls') {
		$data = array ();
		$where_str = 'WHERE 1';
		
		$data ['store_id'] = $store_id = $this->input->get_post ( 'store_id' );		
		$data ['order_type'] = $order_type = $this->input->get_post ( 'order_type' );
		$data ['realname'] = $realname = $this->input->get_post ( 'realname' );
		$data ['coach_name'] = $coach_name = $this->input->get_post ( 'coach_name' );
		$data ['cstatus'] = $status = $this->input->get_post ( 'status' );
		$data ['date'] = $date = $this->input->get_post ( 'date' );
		$data ['time'] = $time = $this->input->get_post ( 'time' );
		
		$store_id && $where_str .= " and a.store_id = {$store_id}";
		$status !== NULL && $status !== '' && $where_str .= " and a.status = '{$status}'";
		$date && $where_str .= " and a.date = '{$date}'";
		$time && $where_str .= " and a.time = '{$time}'";
		$realname && $where_str .= " and f.realname like '%{$realname}%'";
		$coach_name && $where_str .= " and c.coach_name like '%{$coach_name}%'";
		
		//没有分店id为充值订单
		$order_type ? $where_str .= " and a.store_id = 0" : $where_str .= " and a.store_id > 0";
		
		if ($this->_user['role_id'] == 4){ //教练
			$where_str .= " and a.coach_id = {$this->_user['uid']}";
		}elseif ($this->_user['role_id'] != 1){
			$where_str .= " and a.store_id = {$this->_user['store_id']}";
		}
	
		$sql = "SELECT f.nickname,f.realname,b.name AS store_name,e.course_name,c.coach_name,
		concat(a.num,'/',e.num) as num,concat(a.date,' ',a.time) as date_time,a.payment,a.tel,
		CASE a.status WHEN '0' THEN '未支付' WHEN '1' THEN '已支付' WHEN '2' THEN '已取消' WHEN '3' THEN '已完成'
 			WHEN '4' THEN '已退款' END AS `status`,f.num_deal,f.money_deal FROM w_order a
				  LEFT JOIN w_store b
				    ON a.store_id = b.store_id
				  LEFT JOIN w_coach c
				    ON a.coach_id = c.coach_id
				  LEFT JOIN w_course e
				    ON a.course_id = e.course_id
				  LEFT JOIN w_user f
				    ON a.openid = f.openid
			  	$where_str
			ORDER BY a.order_id desc";
	
		$content_tpl = array (
				'nickname' 	=> 	'客户',
				'realname' 	=> 	'姓名',
				'store_name' 		=> 	'分店',
				'course_name'	=>	'课程',
				'coach_name'	=>	'教练',
				'num'	=>	'人数',
				'date_time'	=>	'时间',
				'payment'	=>	'金额',
				'tel'		=>	'联系号码',
				'status'	=>	'状态',
				'num_deal'	=>	'成交次数',
				'money_deal'=>	'成交价格'
		);
	
		$data = $this->order_model->get_all ( $sql );
		$first_title = '订单列表'.date('Ymd');
		$this->download_tpl ( $data, $first_title, $content_tpl );
	}
	
	private function download_tpl($data, $first_title, $content_tpl) {
		header ( "Content-type: text/html; charset=utf-8" );
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");
	
		$objPHPExcel->getActiveSheet()->setTitle($first_title);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $first_title)->mergeCells('A1:L1');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
	
		$second_title = array_values ( $content_tpl );
		$second_key = array_keys ( $content_tpl );
		foreach ( $second_title as $k1 => $v1 ) {
			$row_index = chr ( ord ( 'A' ) + $k1 );
			$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( $row_index . '2', $v1 );
			$objPHPExcel->getActiveSheet ()->getStyle ( $row_index . '2' )->getFont ()->setBold ( true )->setSize ( 12 );
			$objPHPExcel->getActiveSheet()->getColumnDimension($row_index)->setWidth(16);
		}
		foreach ( $data as $k => $v ) {
			$num = $k + 3;
	
			foreach ( $second_key as $k2 => $v2 ) {
				$row2_chr = chr ( ord ( 'A' ) + $k2 );
				$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValueExplicit ( $row2_chr . $num, $v [$v2], PHPExcel_Cell_DataType::TYPE_STRING );
			}
		}
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
	
		$filename = '订单列表' . date ( 'YmdHis' ) . '.xls';
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="'.$filename.'"' );
		header ( 'Cache-Control: max-age=0' );
	
		$objWriter->save ( 'php://output' );
	}
}