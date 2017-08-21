<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Coupon extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'coupon_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$data ['list'] = $this->coupon_model->get(NULL,pageSize,$page);
		
		// 分页
		$config ['base_url'] = site_url ( 'shop/coupon/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'shop/coupon/list.html', $data );
	}
	
	public function detail($coupon_id = '')
	{
		$data = array ();
		$data['footerJs'] = array(
				'DatePicker/WdatePicker.js',
				'coupon.js'
		);
		if ($coupon_id)
		{
			$whereArr = array (
					'coupon_id' => $coupon_id 
			);
			$result = $this->coupon_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		$this->template->display ( 'shop/coupon/detail.html', $data );
	}
	
	public function save($coupon_id = '') {
		$data ['no'] = trim ( $this->input->post ( 'no' ) );
		$data ['money'] = intval( $this->input->post ( 'money' ) );
		$data ['start_time'] = $this->input->post ( 'start_time' );
		$data['end_time'] =  $this->input->post ( 'end_time' );
		$data['profile'] =  $this->input->post ( 'profile' );
	
		$this->coupon_model->save ( $data, $coupon_id );
		redirect ( base_url () . 'shop/coupon' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->activity_model->update(array('disabled'=>$status) ,array('activity_id'=>$id));
			redirect(base_url().'shop/activity');
		}
		else
		{
			show_error('参数错误');
		}
	}	
	
	public function del($activity_id)
	{
		$activity_id = intval($activity_id);
		$activity_id && $this->activity_model->del(array('activity_id'=>$activity_id));
		redirect ( base_url () . 'shop/activity' );
	}

}