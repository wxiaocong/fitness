<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/***
 * 教练管理
 */
class Coach extends My_Controller
{
	
	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'course_model' );
		$this->load->model ( 'coach_relate_course_model' );
	}
	
	public function index($page = 1)
	{
		$data['realname'] = $realname = $this->input->post('realname');
		$data['coach_name'] = $coach_name = $this->input->post('coach_name');
		$data['store_id'] = $store_id = $this->input->post('store_id');
		
		$where = $like_where = array();
		
		$realname && $like_where['realname'] = $realname;
		$coach_name && $like_where['coach_name'] = $coach_name;
		
		$data['store_list'] = $this->store_model->get_store_list();
		
		if ($this->_user['role_id'] == 1){
			$store_id && $where['store_id'] = $store_id;
		}elseif($this->_user['store_id']){
			//分店管理员只可查询该分店下的教练
			$where['store_id'] = $this->_user['store_id'];
		}
		
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
			
		$data ['list'] = $this->coach_model->get($where,pageSize,$page,$like_where);
		// 分页
		$config ['base_url'] = site_url ( 'shop/coach/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		$this->template->display ( 'shop/coach/list.html', $data );
	}
	
	public function detail($coach_id = '')
	{
		$data = array ();
		if ($coach_id)
		{
			$whereArr = array ('coach_id' => $coach_id);
			if($this->_user['role_id'] != 1){
				$whereArr['store_id'] = $this->_user['store_id'];	
			}
			$result = $this->coach_model->one ( array (
					'where' => $whereArr 
			) );
			$data ['result'] = $result;
		}
		//分店
		$data['store'] = $this->store_model->get_store_list();
		
		$this->template->display ( 'shop/coach/detail.html', $data );
	}
	
	//保存教练
	public function save($id = '')
	{
		$data = $this->input->post ();
		
		if($_FILES['pic_persion']['error'] == 0){
			$filename = $_FILES ['pic_persion'] ['name'];
			$MAXIMUM_FILESIZE = 0.2 * 1024 * 1024; // 头像限制200KB;
			
			if ($_FILES ['pic_persion'] ['size'] > $MAXIMUM_FILESIZE) {
				show_error('头像图片过大，请处理后重新上传.');
			}
			if( ! in_array(exif_imagetype($_FILES['pic_persion']['tmp_name']),array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF))){
				show_error("图片文件格式错误");
			}
			$file_name = uniqid ().'.'.substr(strrchr($_FILES['pic_persion']['name'], '.'), 1);
			$md_dir = './upload/coach/'.$file_name;
			$isMoved = @move_uploaded_file ( $_FILES ['pic_persion'] ['tmp_name'], $md_dir);
			$isMoved &&	$data['pic_persion'] = base_url().'upload/coach/'.$file_name;
		}
		
		$this->coach_model->save ( $data, $id );

		redirect ( base_url () . 'shop/coach' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->coach_model->update(array('disabled'=>$status) ,array('coach_id'=>$id));
			redirect(base_url().'shop/coach');
		}
		else
		{
			show_error('参数错误');
		}
	}
	
	//不可删除
	public function del($coach_id)
	{
		exit();
		$coach_id = intval($coach_id);
		$coach_id && $this->coach_model->del(array('coach_id'=>$coach_id));
		redirect ( base_url () . 'shop/coach' );
	}

}