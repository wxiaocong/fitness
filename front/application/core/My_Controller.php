<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class My_Controller extends CI_Controller
{
	public $openid = '1';
	public $latitude = '';
	public $longitude = '';
	
	public function __construct()
	{
		parent::__construct ();
		date_default_timezone_set ( 'PRC' );
		$uri_string = $this->uri->uri_string ();

		$this->load->library ( 'jssdk', array (
			'appId' => $this->config->item ( 'appId' ),
			'appSecret' => $this->config->item ( 'appSecret' )
		) );
		
// 		$this->openid = get_cookie('openid');
// 		if(empty($this->openid)){
// 			set_cookie('uri_string',$uri_string,3600);
// 			redirect(base_url().'load');
// 		}
		
// 		$this->latitude = get_cookie('latitude');
// 		$this->longitude = get_cookie('longitude');
// 		if( ! $this->latitude || ! $this->longitude){
// 			redirect(base_url().'load');
// 		}

// 		$this->template->assign ( 'signPackage',  $this->jssdk->GetSignPackage() );
		$this->template->assign ( 'openid', $this->openid );
		$this->template->assign ( 'uri_string', $uri_string );
		$this->template->assign ( 'ci', $this );

	}
	
	/**
	 * 跳转方法
	 *
	 * @param string $uri
	 *        	提示后，需要跳转到的页面
	 * @param string $tips
	 *        	跳转信息
	 * @param string $status
	 *        	success 成功跳转 fail 失败跳转
	 */
	function redirect_page($uri = '', $tips = '提示', $status = 'success')
	{
		if (! preg_match ( '#^https?://#i', $uri ))
		{
			$uri = site_url ( $uri );
		}
		
		$data = array ();
		$tips = $tips == '' ? '提示' : $tips;
		$data ['status'] = $status;
		$data ['tips'] = $tips;
		$data ['uri'] = $uri;
		$this->template->display ( 'info_page/info.html', $data );
		exit ();
	}

}

/* End of file Milk_Controler.php */
/* Location: ./application/controllers/Milk_Controler.php */