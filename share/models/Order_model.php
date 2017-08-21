<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Order_model extends My_Model {
	public $model = 'order';
	public function __construct() {
		parent::__construct ();
		
        $this->setModel($this->model);
	}
	
	// 获取订单列表 缺少用户id
	public function get_order($openid, $order_id = 0, $offset = 0, $pageSize = 0) {
		$order_id = intval ( $order_id );
		$where = "WHERE a.openid = '{$openid}' AND a.store_id > 0";
		$order_id && $where .= " AND a.order_id = {$order_id}";
		$sql = "SELECT
				  a.*,
				  b.name        AS store_name,
				  b.addr,
				  b.addr_link,
				  c.coach_name,
				  c.pic_persion,
				  d.notice,
				  d.course_name,
				  d.course_type,
				  f.nickname,
				  e.content
				FROM w_order a
				  LEFT JOIN w_store b
				    ON a.store_id = b.store_id
				  LEFT JOIN w_coach c
				    ON a.coach_id = c.coach_id
				  LEFT JOIN w_course d ON d.course_id = a.course_id 
				  LEFT JOIN w_user f ON a.openid = f.openid 
				  LEFT JOIN w_plan e ON a.course_id = e.course_id 
				  AND a.coach_id = e.coach_id AND a.date = e.date AND a.time = e.time
				  $where GROUP BY a.order_id 
				ORDER BY FIELD(a.status,'1','3','4','0','2'),a.date DESC,a.time desc";
		if (! empty ( $pageSize )) {
			$sql .= " limit $offset,$pageSize";
		}
		return $order_id ? $this->get_one ( $sql ) : $this->get_all ( $sql );
	}
	
	// 后台获取订单详情
	public function get_order_detail($order_id, $offset = 0, $pageSize = 0) {
		if ($order_id) {
			$sql = "SELECT
			  a.*,
			  d.nickname,
			  b.name     AS store_name,
			  c.coach_name,
			  e.course_name
			FROM w_order a
			  LEFT JOIN w_user d
			    ON a.openid = d.openid
			  LEFT JOIN w_store b
			    ON a.store_id = b.store_id
			  LEFT JOIN w_coach c
			    ON a.coach_id = c.coach_id
			  LEFT JOIN w_course e
				ON a.course_id = e.course_id  
    		WHERE a.order_id = {$order_id}";
			if (! empty ( $pageSize )) {
				$sql .= " limit $offset,$pageSize";
			}
			return $this->get_one ( $sql );
		}
		return NULL;
	}
	
	//获取指定时间课程预约人数
	public function get_order_num_by_course_time($course_id,$date,$time){
		$sql = "SELECT SUM(num) AS num FROM w_order WHERE course_id = {$course_id} AND date = '{$date}' AND time = '{$time}' AND status = '1'";
		$res = $this->get_one($sql);
		return $res['num'];
	}
	
	//查找订单
	public function get_order_by_schedule($course_id, $coach_id, $date, $time){
		$sql = "SELECT openid FROM w_order WHERE course_id = {$course_id} AND coach_id = {$coach_id} AND date = '{$date}' AND time = '{$time}' AND status = '1'";
		$res = $this->get_one($sql);
		return empty($res) ? '' : $res['openid'];
	}
	
	//获取通知所需订单信息
	public function get_notice_order($order_id){
		$order_id = intval($order_id);
		if($order_id){
			$sql = "SELECT a.order_id,a.openid,a.date,a.time,b.course_name,c.coach_name,d.name FROM w_order a 
				LEFT JOIN w_course b ON a.course_id = b.course_id
				LEFT JOIN w_coach c ON a.coach_id = c.coach_id
				LEFT JOIN w_store d ON a.store_id = d.store_id WHERE a.order_id = $order_id";
			return $this->get_one($sql);
		}
	}
	
	//获取当月扣费取消次数
	//6.15规则变更，取消即计数
	// 原：UNIX_TIMESTAMP( CONCAT(a.date,' ',a.time) ) BETWEEN (UNIX_TIMESTAMP( b.apply_time )+$cannot_cancel_order_time*3600) AND (UNIX_TIMESTAMP( b.apply_time )+$no_reduce_fee_cancel_order_time*3600)
	//
	public function get_reduce_cancel_cnt($openid){
		$no_reduce_fee_cancel_order_time = $this->no_reduce_fee_cancel_order_time;
		$cannot_cancel_order_time = $this->cannot_cancel_order_time;
		
		$sql = "SELECT COUNT(a.order_id) AS cnt  FROM w_order a LEFT JOIN w_refund b ON a.order_sn = b.order_sn
			WHERE a.openid = '{$openid}' AND MONTH(b.apply_time) = MONTH(NOW()) AND b.status = '1' ";
		$res = $this->get_one($sql);
		return $res['cnt'];
	}
}

/* End of file Order_model.php */
/* Location: ./application/models/Order_model.php */