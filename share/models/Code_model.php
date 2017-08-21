<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 验证码模块
 *        
 */
class Code_model extends My_Model
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->library ( 'verif_code' );
	}
	
	/**
	 * 刷新验证码
	 */
	public function refresh_code($code_name = 'code_verif')
	{
		$possible_letters = '1234567890';
		$code = '';
		$i = 0;
		$characters_on_image = 4;
		while ( $i < $characters_on_image )
		{
			$code .= substr ( $possible_letters, mt_rand ( 0, strlen ( $possible_letters ) - 1 ), 1 );
			$i ++;
		}
		$this->session->set_userdata ( "$code_name", $code ); // 将验证码保存在session中
		$this->verif_code->code ( $code_name, $code );
	}
}

/* End of file Code_model.php */
/* Location: ./application/models/Code_model.php */