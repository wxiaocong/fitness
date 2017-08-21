<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class User extends My_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'user_model' );
		$this->load->model ( 'grade_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'package_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		$where_str = 'WHERE 1';

		$data ['realname'] = $realname = $this->input->post ( 'realname' );
		$data ['nickname'] = $nickname = $this->input->post ( 'nickname' );
		$data ['no'] = $no = $this->input->post ( 'no' );
		$data ['grade_id'] = $grade_id = $this->input->post ( 'grade_id' );
		
		$realname && $where_str .= " AND a.realname like '%$realname%'";
		$nickname && $where_str .= " AND a.nickname like '%$nickname%'";
		$no && $where_str .= " AND a.no like '%$no%'";
		$grade_id && $where_str .= " AND a.grade_id = $grade_id";
		
		$sql_arr ['data_sql'] = "SELECT a.*,b.grade_name,c.coach_name FROM w_user a LEFT JOIN w_grade b ON a.grade_id = b.grade_id
			LEFT JOIN w_coach c on a.bind_coach_id = c.coach_id $where_str ORDER BY user_id DESC limit $page," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM w_user a $where_str";
		
		$data ['list'] = $this->user_model->get_page_list_by_sql ( $sql_arr );
		// 分页
		$config ['base_url'] = site_url ( 'user/user/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();		

		$data['grade'] = $this->grade_model->get_grade_list();		
		$this->template->display ( 'user/user/list.html', $data );
	}
	
	public function detail($id = '')
	{
		$data = array ();
		
		if ($id)
		{
			$whereArr = array (
					'user_id' => $id 
			);
			$result = $this->user_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}else{
			show_error("没有该用户");
		}
		$data['grade'] = $this->grade_model->get_grade_list();
		//教练
		if($this->_user['role_id'] == '1'){	//管理员
			$data['coach_list'] = $this->coach_model->one(array('where'=>array('disabled'=>'0')),1);
		}else{	//其他角色只能绑定自己店教练
			$data['coach_list'] = $this->coach_model->one(array('where'=>array('store_id'=>$this->_user['store_id'],'disabled'=>'0')),1);
		} 
		//套餐信息
		$data['package'] = $this->package_model->get_user_package($result['openid']);
		$this->template->display ( 'user/user/detail.html', $data );
	}
	
	public function save($id = '')
	{
		$data = $this->input->post ();
		
		$insertArr['realname'] = $data['realname'];
		$insertArr['bind_coach_id'] = $data['bind_coach_id'];
// 		$insertArr['balance'] = $data['balance'];
		$insertArr['height'] = $data['height'];
		$insertArr['weight'] = $data['weight'];
		$insertArr['fat'] = $data['fat'];
		$insertArr['bones'] = $data['bones'];
		
		if ($id) {
			
			$this->sys_log->prepare_log("修改会员信息", 'user_edit');
			$this->db->trans_start ();
			
// 			$res = $this->user_model->get_one("SELECT balance FROM w_user WHERE user_id = {$id}");
			$this->user_model->update($insertArr, array('user_id' => $id));
			$this->sys_log->add_log_msg('更新会员信息'.$this->db->affected_rows().'条',$this->_user['uid']);
// 			if($insertArr['balance'] != $res['balance']){
// 				//写入资金变动记录表
// 				$this->load->model ( 'paylog_model' );
// 				$this->paylog_model->add(
// 				array(
// 						'user_id'	=>	$id,
// 						'pay_type'	=>	'后台充值',
// 						'gain'		=>	$insertArr['balance'] > $res['balance'] ? $insertArr['balance'] - $res['balance'] : 0,
// 						'expense'	=>	$insertArr['balance'] < $res['balance']	? $res['balance'] - $insertArr['balance'] : 0,
// 						'balance'	=>	$insertArr['balance'],
// 						'remark'	=>	'',
// 						'opera_id'	=>	$this->_user['uid']
// 				));
// 			}
			if($this->db->trans_complete()){
				$this->sys_log->write_log();
			}
		}else{
			show_error("没有该用户.");
		}
		redirect ( base_url () . 'user/user' );
	}
	
	//会员充值页面
	public function pay($user_id=0){
		//管理员可充值
		if($this->_user['role_id'] > 2){
			show_error("无权操作");
		}
		if($user_id){
			$data['result'] = $this->user_model->one(array('where'=>array('user_id'=>$user_id)));
			if($data['result']['bind_coach_id']){
				$data['coach_name'] = $this->coach_model->get_coach_by_id($data['result']['bind_coach_id']);
				//教练私教课程
				$data['course_list'] = $this->course_model->get_course_by_coach($data['result']['bind_coach_id'],'2');
			}
			$this->template->display ( 'user/user/pay.html', $data );
		}else{
			show_error("缺少参数");
		}
	}
	
	//会员充值
	public function do_pay($user_id=0){
		//管理员可充值
		if($this->_user['role_id'] > 2){
			show_error("无权操作");
		}
		if($user_id){
			$user_info = $this->user_model->one(array('where'=>array('user_id'=>$user_id)));
			if(empty($user_info)){
				show_error('用户不存在');
			}
			$pay_type = intval($this->input->post('pay_type'));
			$num_deal = intval($this->input->post('num_deal'));
			$money_deal = floatval($this->input->post('money_deal'));
			
			$this->db->trans_start ();
			
			if($user_info['num_deal'] != $num_deal || $user_info['money_deal'] != $money_deal){
				$this->user_model->update(array('num_deal'=>$num_deal,'money_deal'=>$money_deal),array('user_id'=>$user_id));
			}
			if($pay_type == '1'){//充值余额
				$money = $this->input->post('money')*100/100;
				
				$this->sys_log->prepare_log("后台会员充值",'user_pay');
				
				$this->db->set('balance',"balance + {$money}",false)->where('user_id',$user_id)->update('w_user');
				
				$this->sys_log->add_log_msg("会员{$user_info['nickname']}充值{$money}元",$this->_user['uid']);
				
				$this->load->model ( 'paylog_model' );
				//充值记录
				$pay_log_data = array(
						'user_id'	=>	$user_info['user_id'],
						'openid'	=>	$user_info['openid'],
						'pay_type'	=>	'1',
						'gain'		=> 	$money,
						'balance'	=> 	$user_info['balance'] + $money
				);
				$this->paylog_model->add($pay_log_data);
			}elseif ($pay_type == '2'){
				$course_id = intval($this->input->post('course_id'));
				$package_num = $this->input->post('package_num')*100/100;
				if($course_id && $user_info['bind_coach_id'] && $package_num){
					$this->sys_log->prepare_log("后台会员充值套餐",'user_pay');
					//是否有套餐
					$package_data = array(
							'openid'		=>	$user_info['openid'],
							'course_id'		=>	$course_id,
							'coach_id'		=>	$user_info['bind_coach_id']
					);
					$package_info = $this->package_model->one(array('where'=>$package_data));//已有套餐信息
					if(empty($package_info)){
						//无套餐新增套餐
						$package_data['package_num'] = $package_num;
						$package_id = $this->package_model->add($package_data);
						$balance_num = $package_num;
					}else{
						//有套餐，增加次数
						$package_id = $package_info['package_id'];
						$this->db->where('package_id',$package_id)->set('package_num',"package_num + {$package_num}",false)->update('w_package');
						$balance_num = $package_info['package_num'] - $package_info['userd_num'] + $package_num; //剩余次数
					}
					$this->sys_log->add_log_msg("会员{$user_info['nickname']}充值{$package_num}次course_id:{$course_id},coach_id:{$user_info['bind_coach_id']}",$this->_user['uid']);
					//套餐充值记录
					$package_log = array(
							'openid'	=>	$user_info['openid'],
							'package_id'=>	$package_id,
							'order_id'	=>	0,
							'pay_type'	=>	'5',
							'gain'		=>	$package_num,
							'balance_num'	=>	$balance_num
					);
					$this->load->model ( 'package_log_model' );
					$this->package_log_model->add($package_log);
				}else{
					show_error('会员参数错误');
				}
			}else{
				show_error('未知类型');
			}
			if($this->db->trans_complete()){
				$this->sys_log->write_log();
			}
			redirect(base_url().'user/user/detail/'.$user_id);
		}else{
			show_error("参数错误");
		}
	}
}