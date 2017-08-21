<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Error extends My_Controller {
	public function __construct()
	{
		parent::__construct ();
	
	}	
	public function index()
	{
		$this->template->display('error/index.html');		
	}
}
