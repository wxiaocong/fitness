<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends My_Controller {
	//课程分段时间
	public $ltime = array(
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
        parent::__construct();
        $this->load->model('store_model');
        $this->load->model('order_model');
        $this->load->model('plan_model');
    }

    public function index($page = 1)
    {
    	$where_str = "WHERE (a.status='3' OR a.status='1') AND a.pay_type<'7'";
    	
    	$data ['store_id'] = $store_id = $this->input->post ( 'store_id' );
    	$data ['course_type'] = $course_type = $this->input->get_post ( 'course_type' );
    	$data ['course_name'] = $course_name = $this->input->post ( 'course_name' );
    	$data ['coach_name'] = $coach_name = $this->input->post ( 'coach_name' );
    	$data ['date'] = $date = $this->input->post ( 'date' );
    	$data ['time'] = $time = $this->input->post ( 'time' );
    	
    	$store_id && $where_str .= " and a.store_id = {$store_id}";
    	$date && $where_str .= " and a.date = '{$date}'";
    	$time && $where_str .= " and a.time = '{$time}'";
    	$course_name && $where_str .= " and c.course_name like '%{$course_name}%'";
    	$coach_name && $where_str .= " and d.coach_name like '%{$coach_name}%'";
    	$where_str .= $course_type == '2' ? " and c.course_type = '2'" : " and c.course_type = '1'";
    	
    	if ($this->_user['role_id'] == 4){ //教练
    		$where_str .= " and a.coach_id = {$this->_user['uid']}";
    	}elseif ($this->_user['role_id'] != 1){
    		$where_str .= " and a.store_id = {$this->_user['store_id']}";
    	}
    	
    	$page < 1 && $page = 1;
    	$page = pageSize * ($page - 1);
    	
    	$sql_arr ['data_sql'] = "SELECT b.name AS store_name,c.course_name,d.coach_name,SUM(a.num) AS num,c.num AS max_num,
    			a.course_id,a.coach_id,a.date,a.time,SUM(a.total) AS total,SUM(a.payment) AS payment,e.plan_id FROM w_order a
				LEFT JOIN w_store b ON a.store_id = b.store_id
				LEFT JOIN w_course c ON a.course_id = c.course_id
				LEFT JOIN w_coach d ON a.coach_id = d.coach_id
				LEFT JOIN w_plan e ON a.course_id = e.course_id
	    			AND a.coach_id = e.coach_id AND a.date = e.date AND a.time = e.time
				$where_str
				GROUP BY a.course_id,a.coach_id,a.date,a.time 
    			ORDER BY a.order_id desc limit $page," . pageSize;
    	$sql_arr ['count_sql'] = "SELECT count(1) AS cnt FROM w_order a
				LEFT JOIN w_store b ON a.store_id = b.store_id
				LEFT JOIN w_course c ON a.course_id = c.course_id
				LEFT JOIN w_coach d ON a.coach_id = d.coach_id
				$where_str
				GROUP BY a.course_id,a.coach_id,a.date,a.time";
    	//数据
    	$data ['list'] = $this->order_model->get_page_list_by_sql ( $sql_arr );
    	
    	// 分页
    	$config ['base_url'] = site_url ( 'report/order/index' );
    	$config ['total_rows'] = $data ['list'] ['totalNum'];
    	$this->pagination->initialize ( $config );
    	$data ['pages'] = $this->pagination->create_links ();
    	
    	$data ['store_list'] = $this->store_model->get_store_list ();		
    	$data['footerJs'] = array('DatePicker/WdatePicker.js');
        $this->template->display('report/order/list.html', $data);
    }
    
    public function detail($course_id,$coach_id,$date,$time){
    	if($course_id && $coach_id && $date && $time){
	    	$sql = "SELECT a.total,a.payment,a.tel,b.nickname,a.date,a.time,a.is_confirm,b.no FROM w_order a 
	    	LEFT JOIN w_user b ON a.openid = b.openid WHERE a.course_id = {$course_id} && a.coach_id = {$coach_id}
	    	AND a.date = '{$date}' AND a.time = '{$time}' AND (a.status='3' OR a.status='1') AND a.pay_type<'7'";
	    	$data['list'] = $this->order_model->get_all($sql);
	    	$this->template->display('report/order/detail.html', $data);
    	}else{
    		show_error('参数错误');
    	}
    }
    
    public function plan($course_id,$coach_id,$date,$time){
    	$where = array(
    		'course_id'	=>	$course_id,
    		'coach_id'	=>	$coach_id,
    		'date'		=>	$date,
    		'time'		=>	$time					
    	);
    	$data = $this->plan_model->one(array('where'=>$where));
    	if(empty($data)){
    		$data = $where;
    	}
    	$data['footerJs'] = array(
    			'../assets/ueditor/ueditor.config.js',
    			'../assets/ueditor/ueditor.all.js',
    			'activity.js'
    	);
    	$this->template->display('report/order/plan.html', $data);
    }

    public function save($course_id,$coach_id,$date,$time,$plan_id=0){
    	$data = array(
    		'course_id'	=>	$course_id,
    		'coach_id'	=>	$coach_id,
    		'date'		=>	$date,
    		'time'		=>	$time,				
    		'content'	=>	$this->input->post('content')
    	);
    	$this->plan_model->save($data,$plan_id);
    	redirect(base_url().'report/order/index?course_type=2');
    }
    
    // 导出excel
    public function export($type = 0, $lx = 'xls') {
    	$data = array ();
    	$where_str = "WHERE (a.status='3' OR a.status='1') AND a.pay_type<'7'";
    	
    	$data ['store_id'] = $store_id = $this->input->post ( 'store_id' );
    	$data ['course_type'] = $course_type = $this->input->get_post ( 'course_type' );
    	$data ['course_name'] = $course_name = $this->input->post ( 'course_name' );
    	$data ['coach_name'] = $coach_name = $this->input->post ( 'coach_name' );
    	$data ['date'] = $date = $this->input->post ( 'date' );
    	$data ['time'] = $time = $this->input->post ( 'time' );
    	
    	$store_id && $where_str .= " and a.store_id = {$store_id}";
    	$date && $where_str .= " and a.date = '{$date}'";
    	$time && $where_str .= " and a.time = '{$time}'";
    	$course_name && $where_str .= " and c.course_name like '%{$course_name}%'";
    	$coach_name && $where_str .= " and d.coach_name like '%{$coach_name}%'";
    	$where_str .= $course_type == '2' ? " and c.course_type = '2'" : " and c.course_type = '1'";
    	
    	if ($this->_user['role_id'] == 4){ //教练
    		$where_str .= " and a.coach_id = {$this->_user['uid']}";
    	}elseif ($this->_user['role_id'] != 1){
    		$where_str .= " and a.store_id = {$this->_user['store_id']}";
    	}
    
    	$sql = "SELECT b.name AS store_name,if(a.is_confirm='0','否','是') as is_confirm,c.course_name,d.coach_name,concat(SUM(a.num),'/',c.num) AS num,
    			a.date,a.time,SUM(a.total) AS total,SUM(a.payment) AS payment FROM w_order a
				LEFT JOIN w_store b ON a.store_id = b.store_id
				LEFT JOIN w_course c ON a.course_id = c.course_id
				LEFT JOIN w_coach d ON a.coach_id = d.coach_id
				LEFT JOIN w_plan e ON a.course_id = e.course_id
	    			AND a.coach_id = e.coach_id AND a.date = e.date AND a.time = e.time
				$where_str
				GROUP BY a.course_id,a.coach_id,a.date,a.time 
    			ORDER BY a.order_id desc";
    
    	$content_tpl = array (
    			'store_name' 	=> 	'分店',
    			'is_confirm'	=>	'是否签到',
    			'course_name' 	=> 	'课程',
    			'coach_name' 	=> 	'教练',
    			'num'	=>	'预约人数',
    			'date'	=>	'日期',
    			'time'			=>	'时间',
    			'payment'		=>	'金额'
		);
    
    			$data = $this->order_model->get_all ( $sql );
    			$first_title = '订单统计'.date('Ymd');
    					$this->download_tpl ( $data, $first_title, $content_tpl );
    }
    
    private function download_tpl($data, $first_title, $content_tpl) {
    header ( "Content-type: text/html; charset=utf-8" );
		$this->load->library ( 'PHPExcel' );
    		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
    		$objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");
    
    		$objPHPExcel->getActiveSheet()->setTitle($first_title);
    		$objPHPExcel->getActiveSheet()->setCellValue('A1', $first_title)->mergeCells('A1:H1');
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
    
    $filename = '订单统计' . date ( 'YmdHis' ) . '.xls';
    // 发送标题强制用户下载文件
    header ( 'Content-Type: application/vnd.ms-excel' );
    header ( 'Content-Disposition: attachment;filename="'.$filename.'"' );
    header ( 'Cache-Control: max-age=0' );
    
    $objWriter->save ( 'php://output' );
    }
    
}