<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Paylog extends My_Controller
{
	private $pay_type = array(
			1=>'后台充值',
			2=>'用户充值',
			3=>'预约付款',
			4=>'退款'
	);
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'paylog_model' );
		
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
		
		$order_sn && $where_str .= " and d.order_sn like '%{$order_sn}%'";
		$nickname && $where_str .= " and b.nickname like '%{$nickname}%'";
		$ptype && $where_str .= " and a.pay_type = '{$ptype}'";
		
		$date = $date ? $date : date('Y-m');
		if( ! is_date($date.'-01')){
			show_error('日期格式错误');
		}
		
		$table = 'w_paylog_'.substr($date, 0, 4);
		$where_str .= " and left(a.dateline,7)='{$date}'";
		
		$sql_arr ['data_sql'] = "SELECT a.*,b.nickname,c.uname,d.order_id,d.order_sn FROM $table a LEFT JOIN w_user b ON a.user_id = b.user_id
			LEFT JOIN w_admin c ON a.opera_id = c.admin_id 
			LEFT JOIN w_order d on a.order_id = d.order_id $where_str ORDER BY a.id DESC limit $page," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM $table a LEFT JOIN w_user b ON a.user_id = b.user_id
			LEFT JOIN w_admin c ON a.opera_id = c.admin_id LEFT JOIN w_order d on a.order_id = d.order_id $where_str";
		$data ['list'] = $this->paylog_model->get_page_list_by_sql ( $sql_arr );
		// 分页
		$config ['base_url'] = site_url ( 'order/paylog/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$data ['pay_type'] = $this->pay_type;
		$data['footerJs'] = array('DatePicker/WdatePicker.js');
		$this->template->display ( 'order/paylog/list.html', $data );
	}
	
	// 导出excel
	public function export($type = 0, $lx = 'xls') {
		$data = array ();
		$where_str = 'WHERE 1';

		$data ['date']	= $date = $this->input->get_post ( 'date' );
		$data ['order_sn'] = $order_sn = $this->input->get_post ( 'order_sn' );
		$data ['nickname'] = $nickname = $this->input->get_post ( 'nickname' );
		$data ['ptype'] = $ptype = $this->input->get_post ( 'ptype' );
		
		$order_sn && $where_str .= " and d.order_sn like '%{$order_sn}%'";
		$nickname && $where_str .= " and b.nickname like '%{$nickname}%'";
		$ptype && $where_str .= " and a.pay_type = '{$ptype}'";
		
		$date = $date ? $date : date('Y-m');
		if( ! is_date($date.'-01')){
			show_error('日期格式错误');
		}
		
		$table = 'w_paylog_'.substr($date, 0, 4);
		$where_str .= " and left(a.dateline,7)='{$date}'";
	
		$sql = "SELECT 	d.order_sn,b.nickname,a.gain,a.expense,a.balance,
				CASE a.pay_type WHEN '1' THEN '后台充值' WHEN '2' THEN '用户充值' WHEN '3' THEN '预约付款' WHEN '4' THEN '退款'
				WHEN '4' THEN '已退款' ELSE '未知类型' END AS pay_type,a.remark,a.dateline,c.uname FROM $table a 
				LEFT JOIN w_user b ON a.user_id = b.user_id
				LEFT JOIN w_admin c ON a.opera_id = c.admin_id 
				LEFT JOIN w_order d on a.order_id = d.order_id $where_str ORDER BY a.id DESC";
	
		$content_tpl = array (
				'order_sn' 	=> 	'订单号',
				'nickname' 	=> 	'用户',
				'gain' 		=> 	'收入',
				'expense'	=>	'支出',
				'balance'	=>	'余额',
				'pay_type'	=>	'类型',
				'remark'	=>	'备注',
				'dateline'	=>	'操作时间',
				'uname'		=>	'操作人'
		);
	
		$data = $this->paylog_model->get_all ( $sql );
		$first_title = '资金记录'.date('Ymd');
		$this->download_tpl ( $data, $first_title, $content_tpl );
	}
	
	private function download_tpl($data, $first_title, $content_tpl) {
		header ( "Content-type: text/html; charset=utf-8" );
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");
	
		$objPHPExcel->getActiveSheet()->setTitle($first_title);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $first_title)->mergeCells('A1:I1');
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
	
		$filename = '资金记录' . date ( 'YmdHis' ) . '.xls';
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="'.$filename.'"' );
		header ( 'Cache-Control: max-age=0' );
	
		$objWriter->save ( 'php://output' );
	}
}