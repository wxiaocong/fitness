<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pwd extends My_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }

    public function index()
    {
        $id = $this->_user['uid'];
        $data['detail'] = $this->admin_model->one(array('where' => array('admin_id' => $id)));
        $this->template->display('pwd.html', $data);
    }

    public function change()
    {
    	$this->load->library('Account');
        $data = $this->input->post();

        $id = $this->_user['uid'];
		$oldPwd = $this->account->getKey($data['old_pwd']);
		
		$res = $this->admin_model->one(array('where' => array('admin_id' => $id)));
		if(empty($res) || $oldPwd != $res['passwd']){
			show_error("原密码错误!");
		}
		
        $newPwd = $this->account->getKey($data['pwd']);
        $insertArr = array(
            'passwd' => $newPwd
        );

        $this->admin_model->update($insertArr, array('admin_id' => $id));

        redirect(base_url().'welcome/loginOut');
    }
}