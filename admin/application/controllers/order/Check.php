<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Check extends My_Controller
{
	public function __construct()
	{
		parent::__construct ();
		
	}
	
	//下载对账单页面
	public function index()
	{
		$data = array();
		$check_date = $this->input->get_post('check_date');
		$data['bill_type'] = $bill_type = $this->input->get_post('bill_type');
		
		$data['check_date'] = $check_date ? $check_date : date('Ymd',strtotime('-1 day'));
		
		if($check_date && $bill_type){
			$pay_config = array(
					'appid'			=>	$this->config->item ( 'appId' ),
					'mch_id'		=>	$this->config->item ( 'mchid' ),
					'key'			=>	$this->config->item ( 'key' )
			);
			$this->load->library('wxpay',$pay_config);
			$res = $this->wxpay->downloadBill($check_date, $bill_type);
				
			if( ! empty($res)){
				$res_arr = explode("\r\n", $res);
		
				if(count($res_arr) > 3){
					$data['table_title'] = explode(',',$res_arr[0]);
					for ($i = 1; $i < (count($res_arr)-3); $i++){
						$data['list'][] = explode('`', $res_arr[$i]);
					}
					$data['count_title'] = explode(',',$res_arr[count($res_arr) - 3]);
					$data['count_data'] = explode('`', $res_arr[count($res_arr) - 2]);
				}
			}
		}
		$data['footerJs'] = array('DatePicker/WdatePicker.js');
		$this->template->display ( 'order/check/index.html', $data );
	}
	
	public function download()
	{
		$check_date = $this->input->get_post('check_date');
		$bill_type = $this->input->get_post('bill_type');
		if($check_date && $bill_type){
			$pay_config = array(
					'appid'			=>	$this->config->item ( 'appId' ),
					'mch_id'		=>	$this->config->item ( 'mchid' ),
					'key'			=>	$this->config->item ( 'key' )
			);
			$this->load->library('wxpay',$pay_config);
			$res = $this->wxpay->downloadBill($check_date, $bill_type);
			
			$data = array();
			if( ! empty($res) ){
				$res_arr = explode("\r\n", $res);
				
				$data['table_title'] = explode(',',$res_arr[0]);
				for ($i = 1; $i < (count($res_arr)-3); $i++){
					$data['list'][] = explode('`', $res_arr[$i]);
				}
				$data['count_title'] = explode(',',$res_arr[count($res_arr) - 3]);
				$data['count_data'] = explode('`', $res_arr[count($res_arr) - 2]);
			}
			$this->template->display ( 'order/check/list.html', $data );
		}
	}
}