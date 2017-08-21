<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Activity extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'activity_model' );
	}
	
	public function index($page = 1)
	{
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$data ['list'] = $this->activity_model->get(NULL,pageSize,$page);
		// 分页
		$config ['base_url'] = site_url ( 'shop/activity/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'shop/activity/list.html', $data );
	}
	
	public function detail($activity_id = '')
	{
		$data = array ();
		$data['footerJs'] = array(
				'DatePicker/WdatePicker.js',
				'../assets/ueditor/ueditor.config.js',
				'../assets/ueditor/ueditor.all.js',
				'activity.js'
		);
		if ($activity_id)
		{
			$whereArr = array (
					'activity_id' => $activity_id 
			);
			$result = $this->activity_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		$this->template->display ( 'shop/activity/detail.html', $data );
	}
	
	public function save($activity_id = '')
	{
		$data = $this->input->post ();
		$data['create_user'] = $this->_user['uid'];
		//slide_img上传
// 		if($_FILES['slide_img']['error'] == 0){
// 			if($_FILES['slide_img']['size'] > $this->config->item ('max_img_size')){
// 				show_error("上传图片文件最大2M");
// 			}
// 			if( ! in_array(exif_imagetype($_FILES['slide_img']['tmp_name']),array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF))){
// 				show_error("图片文件格式错误");
// 			}
// 			$file_name = uniqid ().'.'.substr(strrchr($_FILES['slide_img']['name'], '.'), 1);
// 			$md_dir = './upload/activity/'.$file_name;
// 			$isMoved = @move_uploaded_file ( $_FILES ['slide_img'] ['tmp_name'], $md_dir);
// 			$isMoved &&	$data['slide_img'] = base_url().'upload/activity/'.$file_name;
// 		}
		
		$this->activity_model->save ( $data, $activity_id );
			
		redirect ( base_url () . 'shop/activity' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->activity_model->update(array('disabled'=>$status,'recommend'=>'0') ,array('activity_id'=>$id));
			redirect(base_url().'shop/activity');
		}
		else
		{
			show_error('参数错误');
		}
	}	
	
	public function del($activity_id)
	{
		$activity_id = intval($activity_id);
		$activity_id && $this->activity_model->del(array('activity_id'=>$activity_id));
		redirect ( base_url () . 'shop/activity' );
	}

}