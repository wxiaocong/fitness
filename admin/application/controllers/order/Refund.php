<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Refund extends My_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model( 'order_model' );
		$this->load->model( 'refund_model' );
		$this->load->model( 'return_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'paylog_model' );
	}
	
	//后台退款页面
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		 
		if($this->_user['role_id'] == 1){
			$data ['list'] = $this->refund_model->get(NULL,pageSize,$page,NULL,NULL,array('refund_id'=>'desc'));
		}else{
			$sql_arr ['data_sql'] = "SELECT a.*,b.order_id FROM w_refund a LEFT JOIN w_order b
				ON a.order_sn = b.order_sn WHERE b.store_id = {$this->_user['store_id']} ORDER BY refund_id desc";
			$sql_arr ['count_sql'] = "SELECT count(a.refund_id) AS cnt FROM w_refund a LEFT JOIN w_order b ON a.order_sn = b.order_sn WHERE b.store_id = {$this->_user['store_id']}";
			$data ['list'] = $this->order_model->get_page_list_by_sql ( $sql_arr );
		}
		
		// 分页 
		$config ['base_url'] = site_url ( 'order/refund/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'order/refund/list.html', $data);
	}
	
	
	public function apply($order_id = 0)
	{
		if($order_id){
			$order_info = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
			//已支付或已完成订单允许退款
			if( ! empty($order_info) && in_array($order_info['status'],array('1')) ){
				
				$order_info['no_reduce_fee_cancel_order_time'] = $this->order_model->no_reduce_fee_cancel_order_time;
				$order_info['cannot_cancel_order_time'] = $this->order_model->cannot_cancel_order_time; 
				
				$this->template->display ( 'order/refund/apply.html', $order_info );
			}else{
				show_error('该订单状态不允许退款');
			}
		}
	}
	
	//提交退款
	public function save(){
		$order_id = intval($this->input->post('order_id'));
		$refund_money = intval($this->input->post('refund_money')*100)/100;
		if($order_id){
			//开始事务
			$this->sys_log->prepare_log("后台取消订单退款",'order_refund');
			$this->db->trans_start ();
			
			$order_info = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
			if( empty($order_info) || $order_info['status'] != '1'){
				show_error('订单状态错误,不允许退款.');
			}
			if($order_info['payment'] < $refund_money){
				show_error('退款金额错误');
			}		
			$no_reduce_fee_cancel_order_time = $this->order_model->no_reduce_fee_cancel_order_time;
			$cannot_cancel_order_time = $this->order_model->cannot_cancel_order_time;
			$reduce_cancel_ratio = $this->order_model->reduce_cancel_ratio;
			
			if($order_info['date'].' '.$order_info['time'] < date('Y-m-d H:i',strtotime("+$cannot_cancel_order_time hour"))){
				show_error("课程开始前$cannot_cancel_order_time小时内,不能取消课程");
			}
// 			if($order_info['date'].' '.$order_info['time'] < date('Y-m-d H:i',strtotime("+$cannot_cancel_order_time hour"))){
				$reduce_cancel_cnt =  $this->order_model->get_reduce_cancel_cnt($order_info['openid']);
				if($reduce_cancel_cnt >= $this->order_model->no_reduce_fee_cancel_times && in_array($order_info['pay_type'], array('1','2','4','5')) && $order_info['payment']*$reduce_cancel_ratio < $refund_money){
					//金额支付
					show_error("退款金额超过可退款金额");
				}
// 			}		
			//写入退款单
			$refund_sn = 're_'.createOrderSn();
			$refund_data = array(
				'refund_sn'	=>	$refund_sn,
				'order_sn'	=>	$order_info['order_sn'],
				'admin_id'	=>	$this->_user['uid'],
				'status'	=>	'1'		
			);
			//用户信息
			$user_info = $this->user_model->one(array('where'=>array('openid'=>$order_info['openid'])));
			
			//取消订单
			$this->order_model->update(array('status'=>'2'),array('order_id'=>$order_id));
			$this->sys_log->add_log_msg('取消订单'.$this->db->affected_rows().'条',$this->_user['uid']);
			//更新排课
			$schedule_table= 'schedule_'.date('Y',strtotime($order_info['date']));
			$this->db->set('order_num',"order_num - {$order_info['num']}",false)->where(
				array(
					'course_id'	=>	$order_info['course_id'],
					'coach_id'	=>	$order_info['coach_id'],
					'date'		=>	$order_info['date'],
					'time'		=>	$order_info['time']
				)
			)->update($schedule_table);
			$this->sys_log->add_log_msg('更新排课'.$this->db->affected_rows().'条',$this->_user['uid']);
			switch ($order_info['pay_type']){
				case '1':	//团课余额支付退款
				case '2':	//团课支付接口	
				case '4':	//私教余额支付	
				case '5':	//私教接口支付	
					//写入退款单
					$refund_data['refund_fee'] = $refund_money;
					$this->refund_model->add($refund_data);
					$this->sys_log->add_log_msg('写入退款单'.$this->db->affected_rows().'条',$this->_user['uid']);
					//增加余额
					$this->db->set('balance',"balance + $refund_money",false)->where(array('openid'=>$order_info['openid']))->update('w_user');
					$this->sys_log->add_log_msg('增加余额'.$this->db->affected_rows().'条',$this->_user['uid']);
					//支付记录
					$pay_log_data = array(
						'user_id'	=>	$user_info['user_id'],
						'openid'	=>	$order_info['openid'],
						'pay_type'	=>	'4',
						'gain'	=> 	$refund_money,
						'balance'	=> 	$user_info['balance'] + $refund_money,
						'order_id'	=>	$order_id
					);
					$this->paylog_model->add($pay_log_data);
					break;
				case '3':		//套餐次数支付
				case '6':		//私教接口支付套餐	
					$this->load->model('package_model');
					$this->load->model('package_log_model');
					//套餐信息
					$package_info = $this->package_model->one(array('where'=>array('openid'=>$order_info['openid'],'course_id'=>$order_info['course_id'],'coach_id'=>$order_info['coach_id'])));
					//30小时外超过次数扣一半
					//减少套餐已使用次数
					if($reduce_cancel_cnt >= 2){
						$order_info['num'] /= 2;
					}
					//写入退款单
					$refund_data['package_num'] = $order_info['num'];
					$this->refund_model->add($refund_data);
					$this->sys_log->add_log_msg('写入退款单'.$this->db->affected_rows().'条',$this->_user['uid']);
					
					$this->db->set('userd_num',"userd_num - {$order_info['num']}",false)->where(array('openid'=>$order_info['openid']))->update('w_package');
					$this->sys_log->add_log_msg('减少套餐已使用次数'.$this->db->affected_rows().'条',$this->_user['uid']);
					//写入日志
					$package_log = array(
							'openid'	=>	$order_info['openid'],
							'package_id'=>	$package_info['package_id'],
							'order_id'	=>	$order_id,
							'pay_type'	=>	'4',
							'gain'		=>	$order_info['num'],
							'balance_num'=>	$package_info['package_num'] - $package_info['userd_num'] + $order_info['num']
					);
					$this->package_log_model->add($package_log);
					break;
			}
			if($this->db->trans_complete()){
				$this->sys_log->write_log();
			}
			redirect(base_url().'order/refund');
		}		
		show_error('缺少参数');
	}
}