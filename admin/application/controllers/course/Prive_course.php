<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Prive_course extends My_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'course_model' );
		$this->load->model ( 'tag_model' );
		$this->load->model ( 'store_model' );
		$this->load->model ( 'coach_model' );
		$this->load->model ( 'coach_relate_course_model' );
	}
	public function index($page = 1) {
		$page < 1 && $page = 1;
		$page = pageSize * ($page - 1);
		
		// 显示通用及分店自定义课程
		$where = "a.course_type = '2'";
		if ($this->_user ['store_id']) {
			$where .= " AND a.store_id = {$this->_user['store_id']}";
		}
		
		$sql_arr ['data_sql'] = "SELECT a.*,GROUP_CONCAT(DISTINCT b.tag_name) AS tag_name,GROUP_CONCAT(DISTINCT d.coach_name) AS coach_name FROM w_course a
			LEFT JOIN w_tag b ON FIND_IN_SET(b.tag_id,a.tag_id)
			LEFT JOIN w_coach_relate_course c ON a.course_id = c.course_id
			LEFT JOIN w_coach d ON c.coach_id = d.coach_id
			WHERE $where
			GROUP BY a.course_id
			limit $page," . pageSize;
		$sql_arr ['count_sql'] = "SELECT count(1) as cnt FROM w_course a
			WHERE $where";
		
		$data ['list'] = $this->course_model->get_page_list_by_sql ( $sql_arr );
		// 分页
		$config ['base_url'] = site_url ( 'course/prive_course/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		//分店教练
		$data['coach'] = $this->coach_model->get_coach_by_store($this->_user['store_id']);

		$data['footerJs'] = array('course.js');
		$this->template->display ( 'course/prive_course/list.html', $data );
	}
	
	public function detail($course_id = '') {
		$data = array ();
		if ($course_id) {
			
			$sql = "SELECT * FROM w_course WHERE course_id = {$course_id} AND course_type = '2'";
			$store_id = $this->_user ['store_id'];
			if ($store_id) {
				$sql .= " and store_id = {$store_id}";
			}
			$data ['result'] = $this->course_model->get_one ( $sql );
		}
		//分店
		if($this->_user['role_id'] == 1){
			$data['store_list'] = $this->store_model->get_store_list();
		}
		//标签
		$where_arr = array('disabled' => '0' );
		if($this->_user ['store_id']){
			$where_arr['store_id'] = $this->_user ['store_id'];
		}
		$data ['tag'] = $this->tag_model->one ( array ('where' => $where_arr), 1 );
		$data['footerJs'] = array(
				'../assets/ueditor/ueditor.config.js',
				'../assets/ueditor/ueditor.all.js',
				'coach.js'
		);
		$this->template->display ( 'course/prive_course/detail.html', $data );
	}
	
	public function save($course_id = '') {
		$data ['course_name'] = trim ( $this->input->post ( 'course_name' ) );
		$data ['store_id'] = $this->_user['role_id'] == 1 ? intval($this->input->post ( 'store_id' )) : $this->_user['store_id'];
		if($data ['store_id'] < 1){
			show_error("课程必须选择分店.");
		}
		
		$tag = $this->input->post ( 'tag' );
		$data ['tag_id'] = empty($tag) ? 0 : implode(',',$tag);
		
		$data['course_type'] = '2'; //私教
		$data['summary'] =  $this->input->post ( 'summary' );
		$data ['price'] = intval ( $this->input->post ( 'price' ) );
		$data ['package_price'] = intval ( $this->input->post ( 'package_price' ) );
		$data ['package_num'] = intval ( $this->input->post ( 'package_num' ) );
		$data ['num'] = intval ( $this->input->post ( 'num' ) );
		$data['introduce'] =  $this->input->post ( 'introduce' );
		$data['notice'] =  $this->input->post ( 'notice' );
		
		if($_FILES['pic']['error'] == 0){
			$filename = $_FILES ['pic'] ['name'];
			$MAXIMUM_FILESIZE = 0.5 * 1024 * 1024; // 详情限制200KB;
		
			if ($_FILES ['pic'] ['size'] > $MAXIMUM_FILESIZE) {
				show_error('详情图图片过大，请处理后重新上传.');
			}
			if( ! in_array(exif_imagetype($_FILES['pic']['tmp_name']),array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_GIF))){
				show_error("图片文件格式错误");
			}
			$file_name = uniqid ().'.'.substr(strrchr($_FILES['pic']['name'], '.'), 1);
			$md_dir = './upload/course/'.$file_name;
			$isMoved = @move_uploaded_file ( $_FILES ['pic'] ['tmp_name'], $md_dir);
			$isMoved &&	$data['pic'] = base_url().'upload/course/'.$file_name;
		}
		
		$this->course_model->save ( $data, $course_id );
		redirect ( base_url () . 'course/prive_course' );
	}
	
	public function status($id, $status)
	{
		if($id && isset($status))
		{
			$status = $status == '0' ? '1' : '0';
			$this->course_model->update(array('disabled'=>$status) ,array('course_id'=>$id));
			redirect(base_url().'course/course');
		}
		else
		{
			show_error('参数错误');
		}
	}
	
	//暂不允许删除
	public function del($course_id) {
		exit();
		$course_id = intval($course_id);
		$course_id && $this->course_model->del(array('course_id'=>$course_id));
		redirect ( base_url () . 'course/course' );
	}
}