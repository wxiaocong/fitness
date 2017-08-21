<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Order extends My_Controller {
	
	private $week = array('周日','周一','周二','周三','周四','周五','周六');
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
	//0未支付 1已支付 2已取消 3已完成 4已退款 5支付失败 6订单异常
	private $status_arr = array('未支付','已支付','已取消','已完成','已退款','支付失败','订单异常');
	private $cur_date = '';
	private $range_arr = array();
	private $pay_config = array();
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'store_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'schedule_model' );
		$this->load->model ( 'order_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'package_model' );
		$this->load->model ( 'paylog_model' );
	
		$this->cur_date = date('Y-m-d');
		$this->range_arr = range(0, 6);
		
		$this->pay_config = array(
			'appid'			=>	$this->config->item ( 'appId' ),
			'mch_id'		=>	$this->config->item ( 'mchid' ),
			'key'			=>	$this->config->item ( 'key' ),
			'sslcertPath'	=>	$this->config->item ( 'sslcertPath' ),
			'sslkeyPath'	=>	$this->config->item ( 'sslkeyPath' )
		);
	}
	
	public function index(){
		
		$data['status_arr'] = $this->status_arr;//状态
		$data['order'] = $this->order_model->get_order($this->openid,0,pageSize);
		$data['train_cnt'] = 0;//累计训练次数
		$data['train_minute'] = 0;//累计训练时长(分)
		$data['train_day'] = 0; //累计天数
		$tmp_train_day = array();
		
		$send_notice_class_time = $this->order_model->send_notice_class_time;//提前时间
		$confirm_failure_time = $this->order_model->confirm_failure_time + 1;//签到结束时间
		
		foreach ($data['order'] as &$val){
			if ($val['status'] == '3'){
				$data['train_cnt']++;
				$data['train_minute'] += 60;
				$tmp_train_day[] = $val['date'];
			}
			//是否可签到
			$val['can_sign'] = false;
			$order_time = $val['date'].' '.$val['time'];
			if(in_array($val['status'],array('1','3')) && $val['is_confirm'] == '0' &&
				strtotime($order_time) > strtotime(date('Y-m-d G:i',strtotime("-$confirm_failure_time hour"))) &&
				strtotime($order_time) <= strtotime(date('Y-m-d G:i',strtotime("+$send_notice_class_time minute")))){
				$val['can_sign'] = true;
			}
		}
		$data['train_day'] = count(array_flip(array_flip($tmp_train_day)));
		
		$data['headerCss'] = array('slide.css');
		$data['footerJs'] = array('order.js');
		$this->template->display('order/my_order.html',$data);
	}
	
	/**
	 * 订单详情
	 * $firstConfirm  0无操作 1 签到 2 超时 
	 */
	public function detail($order_id = 0, $firstConfirm = 0){
		$order_id = intval($order_id);
		if($order_id){
			$order_info = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
			$data = $this->course_model->get_course_detail($order_info['course_id'],$order_info['coach_id']);
			
// 			$data = $this->order_model->get_order($this->openid,$order_id);
			$data['order_id'] = $order_id;
			$data['is_confirm'] = $order_info['is_confirm'];
			$data['dateTime'] = $order_info['date'].' '.$order_info['time'];
			$data['orderTime'] = $data['dateTime'].'-'.date('H:00',strtotime($order_info['time'])+3600);
			
			$data['firstConfirm'] = $firstConfirm;
			$data['headerCss'] = array('slide.css');
			$this->template->display('order/detail.html', $data);
		}else{
			show_error('非法参数.');
		}
	}
	
	//训练表
	public function train($order_id = 0){
		$order_id = intval($order_id);
		if($order_id){
			$this->load->model ( 'train_model' );
			$data = $this->train_model->get_train_detail($order_id);
			$data['headerCss'] = array('slide.css');
			$this->template->display('order/train.html', $data);
		}else{
			show_error('非法参数.');
		}
		
	}
	
	/**
	 * 	时间
	 *	一个私教只能针对一个账号进行预约，一个账号下能预约最多不超多3人。
	 *	同一账号可以重复1人预约3次，也可只预约一次3人
	 */
	public function date($course_id = 0, $coach_id = 0, $choose_date = NULL)
	{
		$data['coach_id'] = $coach_id = intval($coach_id);
		$data['course_id'] = $course_id = intval($course_id);
		$choose_date = empty($choose_date) ? array(date('Y-m-d')) : $choose_date;
		
		if($course_id && $coach_id && count($choose_date) == 1 && is_date($choose_date[0])){
			
			//号
			$data['date_num'] = array(
				date('m.d'),
				date('m.d',strtotime(date("Y-m-d",strtotime("+1 day")))),
				date('m.d',strtotime(date("Y-m-d",strtotime("+2 day")))),
				date('m.d',strtotime(date("Y-m-d",strtotime("+3 day")))),
				date('m.d',strtotime(date("Y-m-d",strtotime("+4 day")))),
				date('m.d',strtotime(date("Y-m-d",strtotime("+5 day")))),
				date('m.d',strtotime(date("Y-m-d",strtotime("+6 day"))))
			);
			//星期
			$data['date_zn'] = array(
				$this->week[date('w',strtotime(date("Y-m-d",strtotime("+2 day"))))],
				$this->week[date('w',strtotime(date("Y-m-d",strtotime("+3 day"))))],
				$this->week[date('w',strtotime(date("Y-m-d",strtotime("+4 day"))))],
				$this->week[date('w',strtotime(date("Y-m-d",strtotime("+5 day"))))],
				$this->week[date('w',strtotime(date("Y-m-d",strtotime("+6 day"))))]
			);
			//课程人数限制
			$course_info = $this->course_model->get_course_price($course_id);
			//开课时间
			$data['ltime'] = $this->ltime;
			//教练上课时间
			$res = $this->schedule_model->get_choose_schedule($course_id, $coach_id, $choose_date);
			$data['schedule_time'] = empty($res) ? NULL : array_column($res, 'time');
			$data['schedule_order'] = array();
			
			if($course_info['course_type'] == '2'){
				//私教
				foreach ($res as $val){
					if($val['is_order'] == '1'){
						if($course_info['num'] <= $val['order_num']){
							//人数已满不能下单
							$data['schedule_order'][] = $val['time'];
						}else{
							//可一人3次或一次3人
							$openid =$this->order_model->get_order_by_schedule($val['course_id'],$val['coach_id'],$val['date'],$val['time']);
							if($openid && $openid != $this->openid){
								$data['schedule_order'][] = $val['time'];
							}
						}
					}
				}
			}else{
				//团课
				foreach ($res as $val){
					if($val['is_order'] == '1' && $course_info['num'] <= $val['order_num']){
						//人数已满不能下单
						$data['schedule_order'][] = $val['time'];
					}
				}
			}
			
			$data['headerCss'] = array('slide.css');			
			$data['footerJs'] = array('order.js');
			$this->template->display('order/date.html',$data);		
		}else{
			show_error("缺少参数");
		}
	}
	
	//切换日期
	public function get_schedule_by_date(){
		$course_id = intval($this->input->get('course_id'));
		$coach_id = intval($this->input->get('coach_id'));
		$data['date_num'] = $data_num = intval($this->input->get('data_num'));
		
		//课程人数限制
		$course_info = $this->course_model->get_course_price($course_id);
		//开课时间
		$data['ltime'] = $this->ltime;
		
		if($course_id && $coach_id && in_array($data_num, $this->range_arr)){
			
			$choose_date = array(date('Y-m-d',strtotime($this->cur_date)+24*60*60*$data_num));
			
			$res = $this->schedule_model->get_choose_schedule($course_id, $coach_id, $choose_date);
			$data['schedule_time'] = empty($res) ? NULL : array_column($res, 'time');
			$data['schedule_order'] = array();
			
			if($course_info['course_type'] == '2'){
				//私教
				foreach ($res as $val){
					if($val['is_order'] == '1'){
						if ($val['order_num'] == 0){
							continue;
						}elseif($course_info['num'] <= $val['order_num']){
							//人数已满不能下单
							$data['schedule_order'][] = $val['time'];
						}else{
							//可一人3次或一次3人
							$openid =$this->order_model->get_order_by_schedule($val['course_id'],$val['coach_id'],$val['date'],$val['time']);
							if($openid != $this->openid){
								$data['schedule_order'][] = $val['time'];
							}
						}
					}
				}
			}else{
				//团课
				foreach ($res as $val){
					if($val['is_order'] == '1' && $course_info['num'] <= $val['order_num']){
						//人数已满不能下单
						$data['schedule_order'][] = $val['time'];
					}
				}
			}
		}
		$this->template->display('order/date_schedule.html',$data);
	}
	
	
	public function confirm(){
		$course_id = intval($this->input->get('course_id'));
		$coach_id = intval($this->input->get('coach_id'));
		$date_num = intval($this->input->get('date_num'));
		$time_num = intval($this->input->get('time_num'));
		
		
		if($course_id && $coach_id && in_array($date_num, $this->range_arr) && isset($this->ltime[$time_num])){
		
			//获取确认订单基本信息
			$data = $this->coach_model->get_pre_order($course_id, $coach_id);
			
			if(empty($data)){
				show_error('未找到该课程.');
			}
			
			//已预约人数
			$schedule_info = $this->schedule_model->one(array('where'=>array(
				'course_id'	=>	$course_id,
				'coach_id'	=>	$coach_id,
				'date'		=>	date('Y-m-d',strtotime($this->cur_date)+24*60*60*$date_num),
				'time'		=>	$this->ltime[$time_num],
				'is_order'	=>	'1'					
			)));
			if ( ! empty($schedule_info)){
				$data['limit_num'] = $data['limit_num'] - $schedule_info['order_num'];
			}
			
			//私教课程查询用户套餐余额
			if($data['course_type'] == '2'){
				$data['package_info'] = $this->package_model->one(array('where'=>array('openid'=>$this->openid,'course_id'=>$course_id,'coach_id'=>$coach_id)));
			}
			//用户是否开通会员、优惠卷信息
			$data['is_vip'] = $this->user_model->is_vip($this->openid);
			$data['vip_discount'] = $this->user_model->vip_discount;
			//时间
			$data['date_num'] = $date_num;
			$data['time_num'] = $time_num;
			$data['str_date'] = date('m月d日',strtotime(date('Y-m-d',strtotime($this->cur_date)+24*60*60*$date_num)));
			
			$ltime_end = array_merge($this->ltime,array('17'=>'24:00'));	
			$data['str_time'] = $this->ltime[$time_num].'~'.$ltime_end[$time_num];
			
			$data['headerCss'] = array('slide.css');			
			$data['footerJs'] = array('order.js');
			$this->template->display('order/confirm.html',$data);
		}else{
			show_error('参数错误.');
		}
	}
	
	//查询是否有套餐、余额
	public function checkMoney(){
		$course_id = intval($this->input->get('course_id'));
		$coach_id = intval($this->input->get('coach_id'));
		$people_num = intval($this->input->get('people_num')); 
		if($course_id && $coach_id && $people_num){
			//订单信息
			$pre_order = $this->coach_model->get_pre_order($course_id,$coach_id);
			//用户信息
			$user_info = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
			
			if($pre_order['course_type'] == '2'){
				$package_info = $this->package_model->one(array('where'=>array('openid'=>$this->openid,'course_id'=>$course_id,'coach_id'=>$coach_id)));
				if( ! empty($package_info) && $package_info['package_num'] >= $people_num){
					echo '您有该课程套餐,点击确定将用剩余套餐次数支付订单.';
					exit;
				}
			}
			$pay_money = $pre_order['price']*$people_num;
			if($user_info['balance'] >= $pay_money){
				echo '您好，您当前余额为'.$user_info['balance'].'元,点击确定将用余额支付订单.';
				exit;
			}
			echo '';
		}
		
	}
	
	public function complate(){
		$course_id = intval($this->input->post('course_id'));
		$coach_id = intval($this->input->post('coach_id'));
		$date_num = intval($this->input->post('date_num'));
		$time_num = intval($this->input->post('time_num'));
		$people_num = intval($this->input->post('people_num')); 
		
		if($course_id && $coach_id && in_array($date_num, $this->range_arr) && isset($this->ltime[$time_num])){
			//开始事务
			$this->db->trans_start ();
			
			$pay_type = 0; //支付方式  1 团课余额支付 2 团课接口支付 3套餐次数支付 4私教余额支付  5私教接口支付 6私教接口支付套餐
			//订单信息
			$pre_order = $this->coach_model->get_pre_order($course_id,$coach_id);
			//用户信息
			$user_info = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
			//已预约人数
			$schedule_info = $this->schedule_model->one(array('where'=>array(
					'course_id'	=>	$course_id,
					'coach_id'	=>	$coach_id,
					'date'		=>	date('Y-m-d',strtotime($this->cur_date)+24*60*60*$date_num),
					'time'		=>	$this->ltime[$time_num],
					'is_order'	=>	'1'
			)));
			if( ! empty($schedule_info) && ($schedule_info['order_num'] + $people_num > $pre_order['limit_num']) ){
				echo json_encode(array('order_id'=>0,'msg'=>'超过该课程最大人数上限,请重新选择'));
				exit;
			}
			
			if($pre_order['course_type'] == '2'){
				//是否购买套餐
				$is_package = intval($this->input->post('is_package'));
				if($is_package){
					$pay_type = '6'; //私教接口支付套餐
					$pay_money = $pre_order['package_price'];
				}else{
					//不购买套餐查询套餐剩余
					$package_info = $this->package_model->one(array('where'=>array('openid'=>$this->openid,'course_id'=>$course_id,'coach_id'=>$coach_id)));
					if( ! empty($package_info) && $package_info['package_num'] >= $people_num){
						$pay_type = '3'; //套餐次数支付
						$pay_money = 0;
					}else{
						$pay_money = $pre_order['price']*$people_num;
						if($user_info['balance'] >= $pay_money){
							$pay_type = '4'; //私教余额支付
						}else{
							$pay_type = '5';//私教接口支付
						}
					}
				}
			}else{
				$pay_money = $pre_order['price']*$people_num;
				if($user_info['balance'] >= $pay_money){
					$pay_type = '1';
				}else{
					$pay_type = '2';
				}
			}
			
			$order_sn = createOrderSn();
			if( in_array($pay_type,array('2','5','6')) ){
				//预支付
				$data = array(
						'device_info'=>	'WEB',
						'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
						'body'		=>	'热炼健身-课程预约'.$order_sn,
						'trade_type'=>	'JSAPI',
						'notify_url'=>	base_url().'notice',
						'out_trade_no'=>$order_sn,
						'total_fee'	=> 	$pay_money*100,
						'openid'	=> $this->openid
				);
				$this->load->library('wxpay',$this->pay_config);
				$res = $this->wxpay->unifiedOrder($data);
			}
			
			if( in_array($pay_type,array('1','3','4')) || ( ! empty($res) && $res['return_code'] == 'SUCCESS') ){
				//写入订单
				$date = date('Y-m-d',strtotime($this->cur_date)+24*60*60*$date_num);
				$order_data = array(
						'order_sn'	=>	$order_sn,
						'openid'	=>	$this->openid,
						'store_id'	=>	$pre_order['store_id'],
						'course_id'	=>	$course_id,
						'coach_id'	=>	$coach_id,
						'num'		=>	$people_num,
						'date'		=>	$date,
						'time'		=>	$this->ltime[$time_num],
						'prepay_id'	=>	isset($res['prepay_id'])?$res['prepay_id']:'',
						'total'		=>	$pay_money,
						'payment'	=>	$pay_money,
						'pay_type'	=>	$pay_type,
						'status'	=>	'0'
				);
				
				if(in_array($pay_type,array('2','5','6'))){
					//保存订单
					$order_id = $this->order_model->add($order_data);
					//返回数据保存
					$this->load->model ( 'return_model' );
					$this->return_model->add(array('msg'=>json_encode($res)));
				}else{
					//保存订单
					$order_data['status'] = '1';
					$order_id = $this->order_model->add($order_data);
					switch ($pay_type){
						case '3'://剩余套餐支付
							$this->db->where('package_id',$package_info['package_id'])->set('userd_num',"userd_num + $people_num",false)->update('w_package');
							//写入日志
							$package_log = array(
								'openid'	=>	$this->openid,
								'package_id'=>	$package_info['package_id'],	
								'order_id'	=>	$order_id,
								'pay_type'	=>	'1',	
								'expence'	=>	$people_num,
								'balance_num'=>	$package_info['package_num']-$package_info['userd_num']-$people_num						
							);
							$this->load->model('package_log_model');
							$this->package_log_model->add($package_log);
							//绑定私教
							if(empty($user_info['bind_coach_id'])){
								$this->user_model->update(array('bind_coach_id'=>$coach_id), array('openid'=>$this->openid));
							}							
							break;
						case '4': //余额支付私教
							//绑定私教
							if(empty($user_info['bind_coach_id'])){
								$this->user_model->update(array('bind_coach_id'=>$coach_id), array('openid'=>$this->openid));
							}	
						default:
							//余额支付  团教私教
							$this->db->where('openid',$this->openid)->set('balance',"balance - $pay_money",false)->update('w_user');
							//支付记录
							$pay_log_data = array(
									'user_id'	=>	$user_info['user_id'],
									'openid'	=>	$this->openid,
									'pay_type'	=>	'3',//预约付款
									'expense'	=> 	$pay_money,
									'balance'	=> 	$user_info['balance'] - $pay_money,
									'order_id'	=>	$order_id
							);
							$this->paylog_model->add($pay_log_data);
					}
					//更新排课表
					$where_arr = array(
							'course_id'	=>	$course_id,
							'coach_id'	=>	$coach_id,
							'date'		=>	$date,
							'time'		=>	$this->ltime[$time_num]
					);
					$table = 'w_schedule_'.date('Y',strtotime($date));
					$this->db->where($where_arr)->set('is_order','1')->set('order_num',"order_num + $people_num",FALSE)->update($table);
				}
				$this->db->trans_complete ();
				
				$res['order_id'] = $order_id;
			}
			$res['pay_type'] = $pay_type;
			echo json_encode($res);
		}else{
			echo json_encode(array('order_id'=>0,'msg'=>'参数错误'));
		}
	}
	
	public function success($order_id = 0){
		$order_id = intval($order_id);
		if($order_id){
			$num = $this->db->where(array('order_id'=>$order_id))->count_all_results('w_order');
			$res = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
			if(empty($res)){
				show_error('订单不存在,请重新操作');
			}
			$data['order_id'] = $order_id;
			$data['tel']  = $res['tel'];
			$data['headerCss'] = array('slide.css');
			$data['footerJs'] = array('success.js');
			$this->template->display('order/success.html',$data);
		}else{
			show_error('参数错误');
		}
	}
	
	//保存联系手机号
	public function save_tel(){
		$order_id = intval($this->input->post('order_id'));
		$tel = trim($this->input->post('tel'));
		if($order_id){
			$this->order_model->update(array('tel'=>$tel),array('order_id'=>$order_id,'openid'=>$this->openid));
		}
	}
	
	public function cancel($order_id = 0){
		$order_id = intval($order_id);
		$order_info = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
		
		$cannot_cancel_order_time = $this->order_model->cannot_cancel_order_time;
		if(strtotime($order_info['date'].' '.$order_info['time']) < strtotime(date('Y-m-d H:i',strtotime("+$cannot_cancel_order_time hour")))){
			show_error("课程开始前$cannot_cancel_order_time小时后,不能取消课程");
		}
		if( empty($order_info) || $order_info['status'] != '1'){
			show_error('订单状态错误,不允许退款.');
		}
		
		$this->db->trans_start ();
		
		$this->order_model->update(array('status'=>'2'),array('order_id'=>$order_id));
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
		//用户信息
		$user_info = $this->user_model->one(array('where'=>array('openid'=>$order_info['openid'])));
		//退款
		//写入退款单
		$refund_sn = 're_'.createOrderSn();
		$refund_data = array(
			'refund_sn'	=>	$refund_sn,
			'order_sn'	=>	$order_info['order_sn'],
			'admin_id'	=>	0,
			'status'	=>	'1'		
		);
		$this->load->model ( 'refund_model' );
		switch ($order_info['pay_type']){
			case '1':	//团课余额支付退款
			case '2':	//团课支付接口	
			case '4':	//私教余额支付	
			case '5':	//私教接口支付	
				//写入退款单
				$refund_data['refund_fee'] = $refund_money = $order_info['payment'];
				$this->refund_model->add($refund_data);
				//增加余额
				$this->db->set('balance',"balance + $refund_money",false)->where(array('openid'=>$order_info['openid']))->update('w_user');
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
				$reduce_cancel_cnt =  $this->order_model->get_reduce_cancel_cnt($order_info['openid']);
				if($reduce_cancel_cnt >= 2){
					$order_info['num'] /= 2;
				}
				//写入退款单
				$refund_data['package_num'] = $order_info['num'];
				$this->refund_model->add($refund_data);
				
				$this->db->set('userd_num',"userd_num - {$order_info['num']}",false)->where(array('openid'=>$order_info['openid']))->update('w_package');
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
		$this->db->trans_complete();
		redirect(base_url().'index.php/order');
	}
}
