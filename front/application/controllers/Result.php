<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Result extends My_Controller {
	private $msg = array('参数错误','操作失败');
	public function __construct()
	{
		parent::__construct ();
	
	}	
	public function index($status = 0, $mtype = 0)
	{
		$data['status'] = $status;
		$data['msg'] = $this->msg[$mtype];
		$data['headerCss'] = array('slide.css','weui.css');
		$this->template->display('result/index.html',$data);		
	}
}
