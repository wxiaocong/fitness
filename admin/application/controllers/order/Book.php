<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Book extends My_Controller
{
	//课程分段时间
	private $ltime = array('8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00');
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'store_model' );
		$this->load->model ( 'tag_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'schedule_model' );
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'package_model' );
		$this->load->model ( 'order_model' );
	}
	
	public function index($page = 1)
	{
		$data['store_list'] = $this->store_model->get_store_list($this->_user['store_id']);

// 		$init_store_id = $this->_user['role_id'] == 1 ? array_shift((array_keys($data['store_list']))) :  $this->_user['store_id'];
		if($this->_user['role_id'] == 1){
			$key_store = array_keys($data['store_list']);
			$init_store_id = array_shift($key_store);
		}else{
			$init_store_id = $this->_user['store_id'];
		}
		
		
		$data['tag_list'] = $this->tag_model->get_tag_by_store($init_store_id);
		
		$data['headerCss'] = array('../js/artDialog/css/ui-dialog.css');
		$data['footerJs'] = array('artDialog/dist/dialog.js','DatePicker/WdatePicker.js','book.js');
		$this->template->display ( 'order/book/list.html', $data );
	}
	
	//获取分店课程标签
	public function get_store_tag($store_id = 0)
	{
		if($this->_user['role_id'] != 1 || $store_id == 0){
			return;
		}
		echo json_encode($this->tag_model->get_tag_by_store($store_id));
	}
	
	//获取分店课程列表
	public function get_course()
	{
		$store_id = intval($this->input->post('store_id'));
		$course_type = intval($this->input->post('course_type'));
		$tag_id = intval($this->input->post('tag_id'));
		
		$course_type || $course_type = '1' ; //默认普通
		
		if($store_id && $tag_id){
			echo json_encode($this->course_model->get_course_filter($store_id, $tag_id, $course_type));
		}
	}
	
	//获取课程价格
	public function get_course_price_coach($course_id = 0){
		$data = $this->course_model->get_course_price($course_id);
		if($this->_user['role_id'] > 2){
			//教练
			$data['coach'] = $this->coach_model->get_coach_by_course($course_id, $this->_user['store_id'], $this->_user['uid']);
		}elseif ($this->_user['role_id'] == '2'){
			//分店管理员
			$data['coach'] = $this->coach_model->get_coach_by_course($course_id, $this->_user['store_id']);
		}else{
			$data['coach'] = $this->coach_model->get_coach_by_course($course_id);
		}
		echo json_encode($data);
	}
	
	//获取各时间点课程预约情况
	public function get_date_schedule(){
		$course_id = intval($this->input->post('course_id'));
		$coach_id = intval($this->input->post('coach_id'));
		$date = $this->input->post('date');
		
		if($course_id && $coach_id && is_date($date)){
			$this->schedule_model->set_table(substr($date, 0, 4));
			$schedule_table = 'w_schedule_'.substr($date, 0, 4);
			$sql = "SELECT a.time,a.order_num,b.num FROM $schedule_table a LEFT JOIN w_course b ON a.course_id = b.course_id
				WHERE a.course_id = {$course_id} AND a.coach_id = {$coach_id} AND a.date = '{$date}' ";
			$res = $this->schedule_model->get_all($sql);
			$res1 = $res2 = array();
			if(!empty($res)){
				foreach ($res as $val){
					$res1[$val['time']] = $val;
				}
			}
			foreach ($this->ltime as $v){
				$res2[] = array(
					'time'	=>	$v.'-'.(intval($v)+1).':00',
					'data'	=>	isset($res1[$v]) ? ($res1[$v]['order_num']>=$res1[$v]['num'] ? '已满' : $res1[$v]['order_num'].'/'.$res1[$v]['num']) : '空'
				);
			}
			echo json_encode($res2);
		}
	}
	
	//查找预约信息
	public function get_order_num(){
		$course_id = intval($this->input->post('course_id'));
		$coach_id = intval($this->input->post('coach_id'));
		$date = $this->input->post('date');
		$time = $this->input->post('time');
		
		if($course_id && $date && $time){
			$this->schedule_model->set_table(date('Y',strtotime($date)));
			//排课
			$table = 'w_schedule_'.date('Y',strtotime($date));
			$sql = "SELECT id,is_order FROM $table WHERE course_id = {$course_id} AND coach_id = {$coach_id} AND date = '{$date}' AND time='{$time}'";
			$res = $this->schedule_model->get_one($sql);
			if(empty($res)){
				echo json_encode(array('status'=>'0','msg'=>'没有排课,无法预约'));
			}else{
				//已预约，查询已预约人数
				$num = 0;
				if($res['is_order'] == '1'){
					$num = $this->order_model->get_order_num_by_course_time($course_id,$date,$time);
				}
				echo json_encode(array('status'=>'1','num'=>$num));
			}
		}		
	}
	
	//查找用户
	public function search_user_info(){
		$no = $this->input->post('no');
		$phone = $this->input->post('phone');
		$nickname = $this->input->post('nickname');
		
		$no && $like['no'] = $no;
		$phone && $like['phone'] = $phone;
		$nickname && $like['nickname'] = $nickname;
		
		if ( ! empty($like)){
			$res = $this->user_model->one(array('like'=>$like),1);
			if(count($res) == 1){
				$data = $res[0];
				//健身次数
				$this->load->model ( 'order_model' );
				$data['fitness_time'] = $this->db->where(array('openid'=>$data['openid']))->count_all_results('w_order');
				//套餐信息
				$data['package_info'] = $this->package_model->get_user_package($data['openid']);
				$this->template->display ( 'order/book/user_info.html', $data );
			}
		}
	}
	
	//提交预约
	public function submit_order(){
		$course_id = intval($this->input->post('course'));
		$coach_id = intval($this->input->post('coach'));
		$num = intval($this->input->post('num'));
		$date = trim($this->input->post('date'));
		$time = intval($this->input->post('time'));
		$user_id = intval($this->input->post('user_id'));
		$payType = intval($this->input->post('payType'));
		
		if($course_id && $coach_id && $num && $user_id && is_date($date) && isset($this->ltime[$time])){
			$course_info = $this->course_model->one(array('where'=>array('course_id'=>$course_id)));
			$user_info = $this->user_model->one(array('where'=>array('user_id'=>$user_id)));
			if(empty($course_info)){
				echo json_encode(array('status'=>'0','msg'=>'课程不存在'));
				exit;
			}
			if(empty($user_info)){
				echo json_encode(array('status'=>'0','msg'=>'用户不存在'));
				exit;
			}
			if($date < date('Y-m-d') || ( $date == date('Y-m-d') && str_replace(':', '', $this->ltime[$time]) <= date('Hi'))){
				echo json_encode(array('status'=>'0','msg'=>'时间已过,无法预约'));
				exit;
			}
			//开始事务
			$this->sys_log->prepare_log("后台预约下单", 'order_book');
			$this->db->trans_start ();
			
			if($course_info['course_type'] == '2' && $payType){	//私教次数支付
				//套餐信息
				$package_info = $this->package_model->get_user_package($user_info['openid'],$course_id,$coach_id);
				if($package_info['package_num']-$package_info['userd_num'] < $num){
					echo json_encode(array('status'=>'0','msg'=>'套餐次数不足，无法预约'));
					exit;
				}	
				$pay_type = '3'; //套餐次数支付
				$pay_money = 0;
			}else{
				$pay_type = $course_info['course_type'] == '2' ? '4' : '1';
				$pay_money = $course_info['price'] * $num;
			}
			
			//写入订单
			$order_sn = createOrderSn();
			$order_data = array(
				'order_sn'	=>	$order_sn,
				'openid'	=>	$user_info['openid'],
				'store_id'	=>	$course_info['store_id'],
				'course_id'	=>	$course_id,
				'coach_id'	=>	$coach_id,
				'num'		=>	$num,
				'date'		=>	$date,
				'time'		=>	$this->ltime[$time],
				'total'		=>	$pay_money,
				'payment'	=>	$pay_money,
				'pay_type'	=>	$pay_type,
				'status'	=>	'1',
				'time_end'	=>	date('YmdHis'),
				'opera_role'=>	$this->_user['role_id'],
				'opera_id'	=>	$this->_user['uid']
			);
			$order_id = $this->order_model->add($order_data);
			$this->sys_log->add_log_msg('写入订单'.$this->db->affected_rows().'条',$this->_user['uid']);
			switch ($pay_type){
				case '1':					
				case '4':
					//余额支付  团教私教
					$this->db->where('openid',$user_info['openid'])->set('balance',"balance - $pay_money",false)->update('w_user');
					$this->sys_log->add_log_msg('扣除余额'.$this->db->affected_rows().'条',$this->_user['uid']);
					//支付记录
					$pay_log_data = array(
							'user_id'	=>	$user_info['user_id'],
							'openid'	=>	$user_info['openid'],
							'pay_type'	=>	'3', //预约付款
							'expense'	=> 	$pay_money,
							'balance'	=> 	$user_info['balance'] - $pay_money,
							'order_id'	=>	$order_id
					);
					$this->load->model('paylog_model');
					$this->paylog_model->add($pay_log_data);
					break;
				case '3':
					//剩余套餐支付
					$package_where = array(
					'openid'	=>	$user_info['openid'],
					'course_id'	=>	$course_id,
					'coach_id'	=>	$coach_id
					);
					$this->db->where($package_where)->set('userd_num',"userd_num + $num",false)->update('w_package');
					$this->sys_log->add_log_msg('增加套餐已使用次数'.$this->db->affected_rows().'条',$this->_user['uid']);
					//写入日志
					$package_log = array(
							'openid'	=>	$user_info['openid'],
							'order_id'	=>	$order_id,
							'course_id'	=>	$course_id,
							'coach_id'	=>	$coach_id,
							'expence'	=>	$num,
							'balance_num'=>	$package_info['num']-$num
					);
					$this->load->model('package_log_model');
					$this->package_log_model->add($package_log);
					break;
			}

			//更新排课表
			$where_arr = array(
				'course_id'	=>	$course_id,
				'coach_id'	=>	$coach_id,
				'date'		=>	$date,
				'time'		=>	$this->ltime[$time]
			);
			$table = 'w_schedule_'.date('Y',strtotime($date));
			$this->schedule_model->set_table(date('Y',strtotime($date)));
			$this->db->where($where_arr)->set('is_order','1')->set('order_num',"order_num + $num",FALSE)->update($table);
			
			$this->sys_log->add_log_msg('更新排课表'.$this->db->affected_rows().'条',$this->_user['uid']);
			
			if($this->db->trans_complete()){
				$this->sys_log->write_log();
			}
			
			echo json_encode(array('status'=>'1','msg'=>'预约成功','order_id'=>$order_id));
		}else{
			echo json_encode(array('status'=>'0','msg'=>'缺少必要参数'));
		}
	}
}