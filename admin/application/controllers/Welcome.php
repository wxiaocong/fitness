<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends My_Controller {

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->template->display('welcome.html');
    }

    public function loginOut()
    {
       $this->session->sess_destroy();
       redirect(base_url());
    }

}