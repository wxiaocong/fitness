<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Login extends CI_Controller {
	private $code_sess = "code_admin_login";
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'code_model' );
		$this->load->model ( 'admin_role_priv_model' );
	}
	
	// 登陆
	public function index() {
		$data ['error'] = $this->input->get ( 'error' );
		$this->template->display ( 'login.html', $data );
	}
	
	// 检测登陆过程
	public function toLogin() {
		$this->load->library ( 'Account' );
		
		$username = trim ( $this->input->post ( 'username' ) );
		$password = trim ( $this->input->post ( 'pwd' ) );
		$code = trim ( $this->input->post ( 'code' ) );
		
		if ($username && $password && $code) {
			$this->sys_log->prepare_log ( "后台登录，IP：" . getIP (), 'admin_login' );
			// 验证码判断
			if ($this->session->userdata ( $this->code_sess ) != $code) {
				$this->sys_log->add_log_msg ( '使用用户名：' . $username . '用户登录后台系统失败，原因：验证码错误' );
				$this->sys_log->write_log ();
				echo json_encode ( array (
						'status' => '0',
						'msg' => '验证码错误' 
				) );
				exit ();
			}
			
			$this->load->model ( 'admin_model' );
			$user = $this->admin_model->get_md5_user ( $username );
			
			if (! empty ( $user )) {
				$checkPwd = get_check_pwd ( $password );
				
				if ($checkPwd === $user ['passwd']) {
					$this->session->set_userdata ( 'uid', $user ['admin_id'] );
					$this->session->set_userdata ( 'username', $user ['uname'] );
					
					$user ['pic_persion'] = empty ( $user ['pic_persion'] ) ? base_url () . 'static/img/avatar1.jpg' : $user ['pic_persion'];
					$this->session->set_userdata ( 'pic_persion', $user ['pic_persion'] );
					
					$auth = $this->admin_role_priv_model->get_role_auth ( $user ['role_id'] );
					
					$this->session->set_userdata ( 'login_dateline', date ( 'H:i' ) );
					$this->session->set_userdata ( 'role_id', $user ['role_id'] );
					$this->session->set_userdata ( 'store_id', $user ['store_id'] );
					$this->session->set_userdata ( 'auth', $auth );
					
					$this->sys_log->add_log_msg ( '使用用户名：' . $username . '用户登录后台系统登录成功' );
					$this->sys_log->write_log ();
					echo json_encode ( array (
							'status' => '1',
							'msg' => '登录成功' 
					) );
				} else {
					echo json_encode ( array (
							'status' => '0',
							'msg' => '密码错误' 
					) );
				}
			} else {
				echo json_encode ( array (
						'status' => '0',
						'msg' => '用户不存在' 
				) );
			}
		} else {
			echo json_encode ( array (
					'status' => '0',
					'msg' => '缺少参数' 
			) );
		}
	}
	
	/**
	 * 验证码
	 */
	public function refresh_code() {
		$this->code_model->refresh_code ( $this->code_sess );
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
