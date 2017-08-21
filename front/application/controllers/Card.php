<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Card extends My_Controller {
	private $pay_config = array();
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'user_model' );
		$this->load->model ( 'order_model' );
		$this->load->model ( 'return_model' );
		$this->load->model ( 'file_model' );
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'course_model' );
		
		$this->pay_config = array(
				'appid'			=>	$this->config->item ( 'appId' ),
				'mch_id'		=>	$this->config->item ( 'mchid' ),
				'key'			=>	$this->config->item ( 'key' ),
				'sslcertPath'	=>	$this->config->item ( 'sslcertPath' ),
				'sslkeyPath'	=>	$this->config->item ( 'sslkeyPath' )
		);
	}
	
	public function index(){
		$data = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
		
		$res = $this->file_model->one (array('where'=>array('openid'=>$this->openid)));
		$data['has_file'] = empty($res) ? 0 : 1;
		
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/detail.html',$data);		
	}
	
	//套餐信息
	public function package(){
		$this->load->model ( 'package_model' );
		$data['list'] = $this->package_model->get_user_package($this->openid);
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/package.html',$data);
	}
	
	//套餐消费记录
	public function package_info($package_id){
		$this->load->model ( 'package_log_model' );
		$data['list'] = $this->package_log_model->get_package_record($this->openid,$package_id);
		$data['pay_type_cn'] = array(
			'1'	=>	'预约消费',
			'2'	=>	'套餐支付',
			'3'	=>	'充值套餐',
			'4'	=>	'取消订单'		
		);
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/package_info.html',$data);
	}
	
	//会员信息
	public function user_info(){
		$data = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/user_info.html',$data);
	}
	
	//资金明细
	public function funds(){
		$this->load->model ( 'paylog_model' );
// 		$data['list'] = $this->paylog_model->one(array('where'=>array('openid'=>$this->openid),'order_by'=>array('id'=>'DESC')),1);
		
		
// 		$data = $this->paylog_model->get(array('openid'=>$this->openid),pageSize,0,null,null,array('id'=>'DESC'));
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/fund.html',$data);
	}
	
	public function get_funds_data(){
		$page = intval($this->input->post('page'));
		$date = $this->input->post('date');
		$date = $date ? $date : 'Y-m';
		
		if( ! is_date($date.'-01')){
			return;
		}
		$page < 1 && $page = 1;
		$offset = pageSize * ($page - 1);
		
		$this->load->model ( 'paylog_model' );
// 		$data = $this->paylog_model->get(array('openid'=>$this->openid),pageSize,$offset,null,null,array('id'=>'DESC'));
		$table = 'w_paylog_'.substr($date, 0, 4);
		$sql_arr ['data_sql'] = "SELECT * FROM $table WHERE openid = '{$this->openid}' AND LEFT(dateline,7)='{$date}' ORDER BY id DESC limit $offset," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM $table WHERE openid = '{$this->openid}' AND LEFT(dateline,7)='{$date}'";
		$data = $this->paylog_model->get_page_list_by_sql($sql_arr);
		$data['pay_type_cn'] = array(
				'1'	=>	'后台充值',
				'2'	=>	'会员充值',
				'3'	=>	'预约付款',
				'4'	=>	'退款'
		);
		if($data['num'] > 0)
			$this->template->display('card/fund_data.html',$data);
	}
	
	//会员档案
	public function record(){
		$result = $this->file_model->one (array('where'=>array('openid'=>$this->openid)));
		if(!empty($result)){
			$tar = array();
			$len = 0;
			foreach ($result as $k=>$v){
				if(strpos($k,'test_') !== FALSE){
					//显示6条数据，第二条为目标
					$tar = json_decode($v);
					$len = count($tar);
					if(count($tar) <= 6){
						$data['result'][$k] = $tar;
					}else{
						$data['result'][$k][0] = $tar[$len-5];
						$data['result'][$k][1] = $tar[1];
						for ($i=4; $i>0; $i--){
							$data['result'][$k][] = $tar[$len-$i];
						}
					}
				}else{
					$data ['result'][$k] = $v;
				}
			}
		}else{
			show_error('暂无档案');
		} 
		$data ['user_info'] = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
		
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/record.html',$data);
	}
	
	
	//开通会员
	public function open_member(){
		$data = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
		if($data['bind_coach_id']){
			$data['coach_name'] = $this->coach_model->get_coach_by_id($data['bind_coach_id']);
			//教练私教课程
			$data['course_list'] = $this->course_model->get_course_by_coach($data['bind_coach_id'],'2');
		}
		$data['headerCss'] = array('slide.css');
		$this->template->display('card/open.html',$data);
	}
	
	//预支付
	public function pre_pay(){
		$pay_money = intval($this->input->post('money')*100); 
		$order_sn = createOrderSn();
		$data = array(
			'device_info'=>	'WEB',	
			'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],	
			'body'		=>	'热炼健身-会员卡充值'.$order_sn,	
			'trade_type'=>	'JSAPI',
			'notify_url'=>	base_url().'notice',
			'out_trade_no'=>$order_sn,
			'total_fee'	=> 	$pay_money,
			'openid'	=> 	$this->openid
		);
		$this->load->library('wxpay',$this->pay_config);
		
		$res = $this->wxpay->unifiedOrder($data);
		if( ! empty($res) && $res['return_code'] == 'SUCCESS'){
			//写入订单
			$order_data = array(
				'order_sn'	=>	$order_sn,
				'prepay_id'	=>	isset($res['prepay_id'])?$res['prepay_id']:'',
				'openid'	=>	$this->openid,
				'total'		=>	$pay_money/100,
				'payment'	=>	$pay_money/100,
				'pay_type'	=>	'7',	
				'status'	=>	'0'				
			);
			$this->order_model->add($order_data);
			//返回数据保存
			$this->return_model->add(array('msg'=>json_encode($res)));
		}
		echo json_encode($res);
	}
	
	//支付套餐
	public function pay_package(){
		$course_id = intval($this->input->post('course_id'));
		$course_info = $this->course_model->one(array('where'=>array('course_id'=>$course_id,'course_type'=>'2')));
		$user_info = $this->user_model->one(array('where'=>array('openid'=>$this->openid)));
		if (empty($course_info)){
			echo json_encode(array('status'=>'0','msg'=>'课程不存在'));
			exit;
		}
		
		$order_sn = createOrderSn();
		$data = array(
				'device_info'=>	'WEB',
				'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
				'body'		=>	'热炼健身-充值套餐'.$order_sn,
				'trade_type'=>	'JSAPI',
				'notify_url'=>	base_url().'notice',
				'out_trade_no'=>$order_sn,
				'total_fee'	=> 	$course_info['package_price']*100,
				'openid'	=> 	$this->openid
		);
		$this->load->library('wxpay',$this->pay_config);
		
		$res = $this->wxpay->unifiedOrder($data);
		if( ! empty($res) && $res['return_code'] == 'SUCCESS'){
			//写入订单
			$order_data = array(
					'order_sn'	=>	$order_sn,
					'prepay_id'	=>	isset($res['prepay_id'])?$res['prepay_id']:'',
					'openid'	=>	$this->openid,
					'store_id'	=>	$course_info['store_id'],
					'course_id'	=>	$course_id,
					'coach_id'	=>	$user_info['bind_coach_id'],
					'total'		=>	$course_info['package_price'],
					'payment'	=>	$course_info['package_price'],
					'pay_type'	=>	'8',
					'status'	=>	'0'
			);
			$this->order_model->add($order_data);
			//返回数据保存
			$this->return_model->add(array('msg'=>json_encode($res)));
		}
		echo json_encode($res);
	}
	
	//获取签名
	public function get_sign(){
		$timeStamp = $this->input->post('timeStamp');
		$nonceStr = $this->input->post('nonceStr');
		$prepay_id = $this->input->post('prepay_id');
		
		$data = array(
			'appId'	=>	$this->config->item ( 'appId' ),
			'timeStamp'	=>	$timeStamp,
			'nonceStr'	=>	$nonceStr,
			'package'	=>	"prepay_id=".$prepay_id,
			'signType'	=>	'MD5'						
		);
		
		$this->load->library('wxpay',$this->pay_config);
		echo $this->wxpay->sign($data);
	}
}
