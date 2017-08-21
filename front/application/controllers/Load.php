<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Load extends CI_Controller {
	
	private $appId = '';
	private $appSecret = '';
	
	public function __construct() {
		parent::__construct ();
		
		$this->load->model ( 'user_model' );
		
		$this->appId = $this->config->item ( 'appId' );
		$this->appSecret = $this->config->item ( 'appSecret' );
		
		$this->load->library ( 'jssdk', array (
			'appId' => $this->appId,
			'appSecret' => $this->appSecret 
		) );
		
	}
	public function index($referer_url = '') {
			//refresh_token 过期,重新获取,获取openid,静默
			$redirect_url = urlencode(base_url().'load/oauth2');
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
			redirect(base_url().'load/get_user_info/'.$data['openid']);
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
			}else{
				//信息变动更新
				if($info['nickname'] != $data['nickname'] || $info['headimgurl'] != $data['headimgurl'] ){
					$this->user_model->update($db_data,array('openid'=>$data['openid']));
				}
			}
			set_cookie('openid',$data['openid'],3600);			
			
			$data ['referer_url'] = base_url().get_cookie('uri_string');
			
			//JS-SDK
			$data['signPackage'] = $this->jssdk->GetSignPackage();
			$this->template->display ( 'load/load.html', $data );
		}else{
			show_error('未获取到用户信息,请重试.');
		}
	}
	
	
	//cookie保存地理位置
	public function location(){
		$latitude = $this->input->post('latitude');
		$longitude = $this->input->post('longitude');
		
		set_cookie('latitude',$latitude,7200);//纬度
		set_cookie('longitude',$longitude,7200);//经度
		
		echo 'success';
	}
}
