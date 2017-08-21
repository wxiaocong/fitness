<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Account {
	protected $ci;
	private $skey = 'fjeixcmgjel&%$f8';
	public function __construct() {
		$this->ci = & get_instance ();
	}
	public function getKey($pwd) {
		return md5 ( md5 ( $pwd ) . $this->skey );
	}
	public function loginKey($username) {
		return md5 ( md5 ( $username ) . $this->skey );
	}
	public function checkLogin() {
		$username = $this->ci->session->userdata ( 'username' );
		$uid = $this->ci->session->userdata ( 'uid' );
		$role_id = $this->ci->session->userdata ( 'role_id' );
		$login_dateline = $this->ci->session->userdata ( 'login_dateline' );
		$pic_persion = $this->ci->session->userdata ( 'pic_persion' );
		$store_id = $this->ci->session->userdata ( 'store_id' );
		
		if (empty ( $uid )) {
			redirect ( base_url () );
		}
		
		return array (
			'uid' => $uid,
			'username' => $username,
			'role_id' => $role_id,
			'login_dateline' => $login_dateline,
			'pic_persion'	=>	$pic_persion,
			'store_id'		=>	$store_id
		);
	}
}

/* End of file Account.php */
/* Location: ./application/libraries/Account.php */
