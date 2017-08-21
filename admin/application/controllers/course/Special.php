<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Course extends My_Controller {
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
		$where = "a.course_type = '1'";
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
		$config ['base_url'] = site_url ( 'course/course/index' );
		$config ['total_rows'] = $data ['list'] ['totalNum'];
		$this->pagination->initialize ( $config );
		$data ['pages'] = $this->pagination->create_links ();
		
		//分店教练
		$data['coach'] = $this->coach_model->get_coach_by_store($this->_user['store_id']);

		$data['footerJs'] = array('course.js');
		$this->template->display ( 'course/course/list.html', $data );
	}
	
	//获取课程教练
	public function get_course_coach(){
		$course_id = intval($this->input->post('course_id'));
		if($course_id){
			$data['coach'] = $this->course_model->get_course_coach($course_id);
			$this->template->display ( 'course/course/coach.html', $data );
		}
	}
	
	//获取已分配教练
	public function get_choose_coach(){
		$course_id = intval($this->input->post('course_id'));
		if($course_id){
			$res = $this->coach_relate_course_model->one(array('where'=>array('course_id'=>$course_id)),1);
			if( ! empty($res) )
				echo json_encode(array_column($res,'coach_id'));
		}
	}
	
	//保存课程分配教练
	public function save_assign(){
		$course_id = intval($this->input->post('course_id'));
		$coach_id = $this->input->post('coach_id');
		
		//非超管，验证课程是否该分店的
		if($this->_user['role_id'] != 1){
			$res = $this->course_model->get_store_by_course($course_id);
			if( empty($res) || $res['store_id'] != $this->_user['store_id']){
				show_error('权限不足');
			}
		}
		//已分配教练
		$res = $this->coach_relate_course_model->one(array('where'=>array('course_id'=>$course_id)),1);
		
		$old_coach = empty($res) ? NULL : array_column($res,'coach_id'); //已选
		$new_coach = empty($coach_id) ? NULL : explode(',',$coach_id); //新选
		//变更
		$result = array_diff_assoc2_deep($new_coach, $old_coach);
		if( ! empty($result['add']) ){	//新增
			foreach ($result['add'] as $value){
				$insertData[] = array('coach_id'=>$value,'course_id'=>$course_id);
			}
			$this->db->insert_batch('w_coach_relate_course', $insertData);
		}
		if(!empty($result['del']))	//去除
		{
			foreach ($result['del'] as $val){
				$this->db->delete('w_coach_relate_course', array('coach_id'=>$val,'course_id'=>$course_id));
			}
		}
	}
	
	public function detail($course_id = '') {
		$data = array ();
		if ($course_id) {
			
			$sql = "SELECT a.*,GROUP_CONCAT(b.tag_name) as tag_name FROM w_course a LEFT JOIN w_tag b ON FIND_IN_SET(b.tag_id,a.tag_id)
					WHERE a.course_id = {$course_id}";
			$store_id = $this->_user ['store_id'];
			if ($store_id) {
				$sql .= " and b.store_id = {$store_id}";
			}
			$data ['result'] = $this->course_model->get_one ( $sql );
		}
		$where_arr = array('disabled' => '0' );
		if($this->_user ['store_id']){
			$where_arr['store_id'] = $this->_user ['store_id'];
		}
		$data ['tag'] = $this->tag_model->one ( array ('where' => $where_arr), 1 );
		//分店
		if($this->_user['role_id'] == 1){
			$data['store_list'] = $this->store_model->get_store_list();
		}
		
		$data['footerJs'] = array(
				'../assets/ueditor/ueditor.config.js',
				'../assets/ueditor/ueditor.all.js',
				'coach.js'
		);
		$this->template->display ( 'course/course/detail.html', $data );
	}
	
	public function save($course_id = '') {
		if( empty($_FILES['video']) ){
			show_error('视频文件过大,请处理后上传.');
		}
		if( empty($_FILES['pic']) ){
			show_error('图片文件过大,请处理后上传.');
		}
		//视频
		if( $_FILES['video']['error'] == 0){
			$filename = $_FILES ['video'] ['name'];
			$MAXIMUM_FILESIZE = 2 * 1024 * 1024; // 详情限制200KB;
		
			if ($_FILES ['video'] ['size'] > $MAXIMUM_FILESIZE) {
				show_error('视频文件过大，请处理后重新上传.');
			}
			if( ! in_array(strrchr($filename, '.'), array('.mp4','.ogg'))){
				show_error("视频格式错误");
			}
			$file_name = uniqid ().strrchr($_FILES['video']['name'], '.');
			$md_dir = './upload/course/'.$file_name;
			$isMoved = @move_uploaded_file ( $_FILES ['video'] ['tmp_name'], $md_dir);
			$isMoved &&	$data['video'] = base_url().'upload/course/'.$file_name;
		}
		//图片
		if( $_FILES['pic']['error'] == 0){
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
		//其它
		$data ['course_name'] = trim ( $this->input->post ( 'course_name' ) );
		$data ['store_id'] = $this->_user['role_id'] == 1 ? intval($this->input->post ( 'store_id' )) : $this->_user['store_id'];
		
		if($data ['store_id'] < 1){
			show_error("课程必须选择分店.");
		}
		$tag = $this->input->post ( 'tag' );
		$data ['tag_id'] = empty($tag) ? 0 : implode(',',$tag);
		
		$data ['price'] = intval ( $this->input->post ( 'price' ) * 100)/100;
		$data ['num'] = intval ( $this->input->post ( 'num' ) );
		$data['introduce'] =  $this->input->post ( 'introduce' );
		$data['notice'] =  $this->input->post ( 'notice' );
		
		$this->course_model->save ( $data, $course_id );
		redirect ( base_url () . 'course/course' );
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