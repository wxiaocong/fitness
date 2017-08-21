<?php
/**
 * 会员档案
 */
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class File extends My_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'file_model' );
		$this->load->model ( 'user_model' );
	}
	
	public function detail($openid)
	{
		$data = array ('max_item'=>6);
		
		$whereArr = array ('openid' => $openid);
		$result = $this->file_model->one ( array (
				'where' => $whereArr 
		) );
		if(!empty($result)){
			foreach ($result as $k=>$v){
				if(strpos($k,'test_') !== FALSE){
					$data ['result'][$k] = json_decode($v);
					$data['max_item'] = max($data['max_item'],count(json_decode($v)));
				}else{
					$data ['result'][$k] = $v;
				}
			}
		}
		$data ['user_info'] = $this->user_model->one(array('where'=>array('openid'=>$openid)));
		
		$this->template->display ( 'user/file/detail.html', $data );
	}
	
	public function save($openid, $file_id = '')
	{
		$data = $this->input->post ();
	
		$insert_data = array('openid'=>$openid);
		foreach ($data as $key=>$val){
			if(strpos($key,'test_') !== FALSE){
				$insert_data[$key] = json_encode($val);
			}else{
				$insert_data[$key] = $val;
			}
		}
			
		$this->file_model->save ( $insert_data, $file_id);
	
		redirect ( base_url () . 'user/file/detail/'.$openid );
	}
}