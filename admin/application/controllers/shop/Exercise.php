<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Exercise extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'exercise_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$data ['list'] = $this->exercise_model->get(NULL,pageSize,$page);
		// 分页
		$config ['base_url'] = site_url ( 'shop/exercise/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'shop/exercise/list.html', $data );
	}
	
	// 导出excel
	public function export($type = 0, $lx = 'xls') {
		$data = array ();
	
		$sql = "SELECT `no`,realname,phone,email,packageId,`datetime` FROM w_exercise WHERE realname != ''";
	
		$content_tpl = array (
				'no' 		=> 	'会员号',
    			'realname'	=>	'姓名',
    			'phone' 	=> 	'手机号',
    			'email' 	=> 	'邮箱',
    			'packageId'	=>	'套餐ID',
				'datetime'	=>	'时间'
		);
	
		$data = $this->exercise_model->get_all ( $sql );
		$first_title = '活动记录'.date('Ymd');
		$this->download_tpl ( $data, $first_title, $content_tpl );
	}
	
	private function download_tpl($data, $first_title, $content_tpl) {
		header ( "Content-type: text/html; charset=utf-8" );
		$this->load->library ( 'PHPExcel' );
    		$this->load->library ( 'PHPExcel/IOFactory' );
    		$objPHPExcel = new PHPExcel ();
    		$objPHPExcel -> getProperties() -> setTitle("export") -> setDescription("none");

    		$objPHPExcel->getActiveSheet()->setTitle($first_title);
    		$objPHPExcel->getActiveSheet()->setCellValue('A1', $first_title)->mergeCells('A1:F1');
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

    		$filename = '活动记录' . date ( 'YmdHis' ) . '.xls';
    		// 发送标题强制用户下载文件
    		header ( 'Content-Type: application/vnd.ms-excel' );
    		header ( 'Content-Disposition: attachment;filename="'.$filename.'"' );
    	header ( 'Cache-Control: max-age=0' );
	
    	$objWriter->save ( 'php://output' );
	}
}