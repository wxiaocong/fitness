<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Exercise extends CI_Controller {
	
	private $appId = '';
	private $appSecret = '';
	
	public function __construct() {
		parent::__construct ();
		
		$this->load->model ( 'user_model' );
		$this->load->model ( 'exercise_model' );
		
		$this->appId = $this->config->item ( 'appId' );
		$this->appSecret = $this->config->item ( 'appSecret' );
		
		$this->load->library ( 'jssdk', array (
			'appId' => $this->appId,
			'appSecret' => $this->appSecret 
		) );
		
	}
	public function index() {
		//refresh_token 过期,重新获取,获取openid,静默
		$redirect_url = urlencode(base_url().'exercise/oauth2');
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.
		'&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_base#wechat_redirect';
		redirect($url);
	}
	
	//微信返回回调页面
	public function oauth2(){
		//微信返回数据
		$code = $this->input->get('code');
		
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$code&grant_type=authorization_code";
		
		$sec = $this->jssdk->httpGet($url);
		$data = json_decode($sec,true);
		//保存返回数据
		$this->load->model ( 'return_model' );
		$saveData = json_encode($this->input->get()).PHP_EOL.$sec;
		$this->return_model->add(array('msg'=>$saveData));
		
		if( ! empty($data['openid']) ){
			redirect(base_url().'exercise/get_user_info/'.$data['openid']);
		}
	}
	
	
	public function get_user_info($open_id){
		//获取token
		$access_token = $this->jssdk->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$open_id.'&lang=zh_CN';
		$sec = $this->jssdk->httpGet($url);
		//保存返回数据
// 		$this->load->model ( 'return_model' );
// 		$this->return_model->add(array('msg'=>json_encode($sec)));
		
		$data = json_decode($sec,true);
		
		if( ! empty($data['openid'])){
			//查询用户是否存在
			$info = $this->user_model->one(array('where'=>array('openid'=>$data['openid'])));
			
			$db_data = array(
				'nickname'	=>	isset($data['nickname']) ? $data['nickname'] : '',
				'headimgurl'=>	isset($data['headimgurl']) ? $data['headimgurl'] : '',
				'sex'		=>	isset($data['sex']) ? $data['sex'] : '',
				'city'		=>	isset($data['city']) ? $data['city'] : '',
				'country'	=>	isset($data['country']) ? $data['country'] : '',
				'province'	=>	isset($data['province']) ? $data['province'] : '',
				'subscribe_time'=>isset($data['subscribe_time']) ? $data['subscribe_time'] : '',
				'remark'	=>	isset($data['remark']) ? $data['remark'] : ''
			);
			//初次登录，新增用户
			if(empty($info)){
				$db_data['openid'] = $data['openid'];
				$uid = $this->user_model->add($db_data);
				//生成会员卡号  年月日+6位不重复数字
				$no = date('Ymd').sprintf("%06d",substr($uid,-6));
				$this->user_model->update(array('no'=>$no),array('user_id'=>$uid));
			}		
			
			set_cookie('openid',$data['openid'],3600);			
			
			$this->template->display ( 'exercise/index.html', $data );
		}else{
			show_error('未获取到用户信息,请重试.');
		}
	}
	
	//注册
	public function reg($type){
		if( ! in_array($type, range(1, 5))){
			show_error('未知套餐');
		}
		$openid = get_cookie('openid');
		$data = $this->user_model->one(array('where'=>array('openid'=>$openid)));
		$data['type'] = $type;
		if(!empty($data)){
			$this->template->display ( 'exercise/reg.html', $data );
		}else{
			redirect(base_url().'exercise');
		}
	}
	
	//提交
	public function save(){
		$realname = $this->input->post('realname');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$type = $this->input->post('type');
		
		if( ! in_array($type, range(1, 5))){
			echo json_encode(array('status'=>'0','msg'=>'未知套餐'));
			exit;
		}
		if(strlen($realname) < 2){
			echo json_encode(array('status'=>'0','msg'=>'姓名错误'));
			exit;
		}
		if( ! preg_match("/^1[34578]\d{9}$/", $phone)){
			echo json_encode(array('status'=>'0','msg'=>'手机号格式错误'));
			exit;
		}
		
		$openid = get_cookie('openid');
		$data = $this->user_model->one(array('where'=>array('openid'=>$openid)));
		$update = array();
		if($data['realname'] == ''){
			$update['realname'] = $realname;
		}
		if($data['phone'] == ''){
			$update['phone'] = $phone;
		}
		if(!empty($update)){
			//更新用户数据
			$this->user_model->update($update,array('openid'=>$openid));
		}
		//更新exercise
		$exe_data = array(
			'realname'=> $realname,
			'phone'	=>	$phone,
			'email'	=>	$email,		
			'no'	=>	$data['no'],
			'packageId'	=>	$type
		);
		$exe_data = array_filter($exe_data);
		$this->exercise_model->add($exe_data);
// 		$sql = "UPDATE w_exercise SET realname = '{$realname}',phone = '{$phone}',email = '{$email}' ORDER BY eid DESC limit 1";
// 		$this->db->query($sql);
		echo json_encode(array('status'=>'1'));
	}
	
	public function pay(){
		$data = array();
		$this->template->display ( 'exercise/pay.html', $data );
	}
	
	//开发票
	public function invoice(){
		$openid = get_cookie('openid');
		$invoice = $this->input->post('invoice');
		$this->exercise_model->update(array('invoice'=>$invoice),array('openid'=>$openid));
	}
}
