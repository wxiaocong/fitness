
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends CI_Controller {
	private $pay_config = array();
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model ( 'return_model' );
        $this->load->model ( 'order_model' );
        $this->load->model ( 'user_model' );
        $this->load->model ( 'paylog_model' );
        $this->load->model ( 'schedule_model' );
        $this->load->model ( 'course_model' );
        $this->load->model ( 'package_model' );
        $this->load->model ( 'package_log_model' );
        
        $this->pay_config = array(
        		'appid'			=>	$this->config->item ( 'appId' ),
        		'mch_id'		=>	$this->config->item ( 'mchid' ),
        		'key'			=>	$this->config->item ( 'key' ),
        		'sslcertPath'	=>	$this->config->item ( 'sslcertPath' ),
        		'sslkeyPath'	=>	$this->config->item ( 'sslkeyPath' )
        );
        $this->load->library('wxpay',$this->pay_config);
    }

    //通知
    public function index()
    {
    	$xml = file_get_contents("php://input");
    	$res = $this->wxpay->xml2array($xml);
    	if(! empty($res['return_code'])){
    		//保存返回数据
    		$this->return_model->add(array('msg'=>json_encode($res)));
    		//返回成功处理
    		if( $res['return_code'] == 'SUCCESS' && $res['appid'] == $this->config->item ( 'appId' )
    				&& $res['mch_id'] == $this->config->item ( 'mchid' ) && $res['result_code'] == 'SUCCESS'){
    			//验证签名
    			if( ! $this->wxpay->validate($res)){
    				$this->return_model->add(array('msg'=>json_encode(array('return_code'=>'FAIL','return_msg'=>'签名错误'))));
    				echo $this->wxpay->array2xml(array('return_code'=>'FAIL','return_msg'=>'签名错误'));
    				exit;
    			}
    			//开始事务
    			$this->db->trans_start ();
    			//查询未支付订单
    			$order_where = array('order_sn'=>$res['out_trade_no'],'status'=>'0');
    			$order_info = $this->order_model->one(array('where'=>$order_where));
    			if( ! empty($order_info) ){
    				if($order_info['payment']*100 != $res['total_fee'] || $order_info['openid'] != $res['openid']){
    					//更新订单异常，客服处理
    					$this->order_model->update(array('status'=>'6'),array('order_sn'=>$res['out_trade_no']));
    				}else{
    					//用户信息
    					$user_info = $this->user_model->one(array('where'=>array('openid'=>$res['openid'])));
    					$pay = $order_info['total']; //
    					$data = array(
    							'transaction_id'=>$res['transaction_id'],
    							'time_end'	=>	$res['time_end'],
    							'status'	=>	'1'
    					);
    					//更新订单
    					$affect = $this->order_model->update($data,$order_where);
    					if($affect){
    						switch ($order_info['pay_type']){
    							case '5':	//私教充值支付
    								if($user_info['bind_coach_id'] != $order_info['coach_id']){	//绑定私教
    									$this->user_model->update(array('bind_coach_id'=>$order_info['coach_id']), array('openid'=>$order_info['openid']));
    								}
    							case '2':	//团课支付  
    								//充值记录
    								$pay_log_data = array(
    										'user_id'	=>	$user_info['user_id'],
    										'openid'	=>	$res['openid'],
    										'pay_type'	=>	'3',
    										'gain'		=> 	$pay,
    										'expense'	=>	$pay,
    										'balance'	=> 	$user_info['balance'],
    										'order_id'	=>	$order_info['order_id']
    								);
    								$this->paylog_model->add($pay_log_data);
    								//更新排课记录
    								$this->update_schedule($order_info);
    								break;
    							case '6':	//购买套餐 套餐次数
    								$course_info = $this->course_model->one(array('where'=>array('course_id'=>$order_info['course_id'])));
    									
    								//是否有套餐
    								$package_data = array(
    									'openid'		=>	$order_info['openid'],
    									'course_id'		=>	$order_info['course_id'],
    									'coach_id'		=>	$order_info['coach_id']
    								);
    								$package_info = $this->package_model->one(array('where'=>$package_data));//已有套餐信息
    								$surplus = $course_info['package_num']	- $order_info['num']; //支付后剩余次数
    								if(empty($package_info)){
    									//无套餐新增套餐
    									$package_data['package_num'] = $course_info['package_num'];
    									$package_data['userd_num'] = $order_info['num'];
    									$package_id = $this->package_model->add($package_data);
    									$balance_num = $surplus; //剩余次数
    								}else{
    									//有套餐，增加次数
    									$package_id = $package_info['package_id'];
    									$this->db->where($package_data)->set('userd_num',"userd_num + {$order_info['num']}")->set('package_num',"package_num + {$course_info['package_num']}",false)->update('w_package');
    									$balance_num = $package_info['package_num'] - $package_info['userd_num'] + $surplus; //剩余次数
    								}
    								//套餐充值记录
    								$package_log = array(
    									'openid'	=>	$res['openid'],
    									'package_id'=>	$package_id,	
    									'order_id'	=>	$order_info['order_id'],
    									'pay_type'	=>	'2',	
    									'gain'		=>	$course_info['package_num'],
    									'expence'	=>	$order_info['num'],
    									'balance_num'	=>	$balance_num
    								);
    								$this->package_log_model->add($package_log);
    								if(empty($user_info['bind_coach_id'])){	//绑定私教
    									$this->user_model->update(array('bind_coach_id'=>$order_info['coach_id']), array('openid'=>$order_info['openid']));
    								}
    								//更新排课记录
    								$this->update_schedule($order_info);
    								break;
    							case '7'://充值
    								$this->db->where('openid',$res['openid'])->set('is_open','1')->set('balance',"balance+$pay",false)->update('w_user');
    								//充值记录
    								$pay_log_data = array(
    										'user_id'	=>	$user_info['user_id'],
    										'openid'	=>	$res['openid'],
    										'pay_type'	=>	'2',
    										'gain'		=> 	$pay,
    										'balance'	=> 	$user_info['balance']+$pay,
    										'order_id'	=>	$order_info['order_id']
    								);
    								$this->paylog_model->add($pay_log_data);
    								break;
    							case '8': //购买私教套餐
    								$course_info = $this->course_model->one(array('where'=>array('course_id'=>$order_info['course_id'])));
    									
    								//是否有套餐
    								$package_data = array(
    										'openid'		=>	$order_info['openid'],
    										'course_id'		=>	$order_info['course_id'],
    										'coach_id'		=>	$order_info['coach_id']
    								);
    								$package_info = $this->package_model->one(array('where'=>$package_data));//已有套餐信息
    								if(empty($package_info)){
    									//无套餐新增套餐
    									$package_data['package_num'] = $course_info['package_num'];
    									$package_id = $this->package_model->add($package_data);
    									$balance_num = $course_info['package_num'];
    								}else{
    									//有套餐，增加次数
    									$package_id = $package_info['package_id'];
    									$this->db->where($package_data)->set('package_num',"package_num + {$course_info['package_num']}",false)->update('w_package');
    									$balance_num = $package_info['package_num'] - $package_info['userd_num'] + $course_info['package_num']; //剩余次数
    								}
    								//套餐充值记录
    								$package_log = array(
    										'openid'	=>	$res['openid'],
    										'package_id'=>	$package_id,
    										'order_id'	=>	$order_info['order_id'],
    										'pay_type'	=>	'3',
    										'gain'		=>	$course_info['package_num'],
    										'balance_num'	=>	$balance_num
    								);
    								$this->package_log_model->add($package_log);
    								break;
    						}
    						//执行事务
    						$this->db->trans_complete ();
    						//返回微信
    						echo $this->wxpay->array2xml(array('return_code'=>'SUCCESS'));
    					}
    				}
    			}
    		}
    	}
    }
    
   	//更新排课记录
    private function update_schedule($order_info){
    	$where_arr = array(
    			'course_id'	=>	$order_info['course_id'],
    			'coach_id'	=>	$order_info['coach_id'],
    			'date'		=>	$order_info['date'],
    			'time'		=>	$order_info['time']
    	);
    	$table = 'schedule_'.date('Y',strtotime($order_info['date']));
    	
    	$this->db->where($where_arr)->set('is_order','1')->set('order_num',"order_num+{$order_info['num']}",FALSE)->update($table);
    }
 	//退款
}

/* End of file Notice.php */
/* Location: ./application/controllers/Notice.php */