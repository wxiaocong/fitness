<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * 系统写日志库
 *
 */
class CI_Sys_log {
	private $_config;
	private $_ci;
	private $_enabled = TRUE;
	private $index = 0;
	private $main_table = "";
	private $slave_table = "";
	// 主表信息
	private $main_msg_sql = '';
	// 从表信息
	private $slave_msg_sql = " ";

	private $uid = '';
	private $username = '';

	public function __construct() {
		$this->_ci = &get_instance();
		$this->_config = &get_config();
		$this->create_log_table();
		$this->slave_msg_sql = " insert into `" . $this->slave_table . "`(`op_id`,`step`,`op_log_id`,`op_datetime`,`op_msg`,`op_sql`,`other_msg`) values  ";
 	}

	private function create_log_table()
	{
		$this_year = date('Y');
		$main_table = "op_log_$this_year";
		$slave_table = "op_log_detail_$this_year";

		$this->main_table = $main_table;
		$this->slave_table = $slave_table;
		$res_main_table_exists = $this->_ci->db->query("show tables like '$main_table' ");
 		$res_slave_table_exists = $this->_ci->db->query("show tables like '$slave_table' ");

 		$main_table_sql = "CREATE TABLE `$main_table` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `title` varchar(250) COLLATE utf8_bin DEFAULT NULL COMMENT '大标题',
				 `op_username` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT '操作人用户名',
				 `op_id` int(11) DEFAULT NULL COMMENT '操作人id',
				 `op_role` int(11) DEFAULT NULL COMMENT '操作人角色',
				 `type` enum('front','admin','back','admin_login') COLLATE utf8_bin DEFAULT NULL COMMENT 'front前台，admin后台，back定时脚本，admin_login后台用户登录',
				 `dateline` timestamp NULL DEFAULT NULL COMMENT '当前时间',
				 `op_ip` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'ip地址',
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='操作日志'";
		$slave_table_sql = "CREATE TABLE `$slave_table` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `step` int(11) DEFAULT NULL COMMENT '第几步骤',
				 `op_log_id` int(11) DEFAULT NULL COMMENT '主表id',
				 `op_datetime` timestamp NULL DEFAULT NULL COMMENT '操作时间',
				 `op_msg` text COLLATE utf8_bin COMMENT '操作信息',
				 `op_sql` text COLLATE utf8_bin COMMENT '操作sql',
				 `other_msg` text COLLATE utf8_bin COMMENT '其他信息',
				 `op_id` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT '操作人id',
				 PRIMARY KEY (`id`),
				 KEY `op_log_id_idx` (`op_log_id`) USING BTREE
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin";

		if( count($res_main_table_exists->result_array()) <= 0 )
		{
			$this->_ci->db->query($main_table_sql);
		}
		if( count($res_slave_table_exists->result_array()) <= 0 )
		{
			$this->_ci->db->query($slave_table_sql);
		}		 

		
	}

	/*
		 * 日志准备
		 * $title 标题
		 * $type 类型【front前台，admin后台，back定时脚本，interface接口，front_login前台用户登录，admin_login后台用户登录】
	*/
	public function prepare_log($title = NULL, $type = NULL) {
		$this->uid = isset($this->_ci->_user['uid']) ? $this->_ci->_user['uid'] : '0';
		$this->role_id = isset($this->_ci->_user['role_id']) ? $this->_ci->_user['role_id'] : '0';
		$this->username = isset($this->_ci->_user['username']) ? $this->_ci->_user['username'] : 'none';
		// 操作用户的ip，在命令行的时候不需要获取IP[在命令行的时候，$_SERVER没有IP，会报错提示]
		// 只有命令行才能执行
		if (!$this->_ci->input->is_cli_request()) {
			$op_ip = getIP();
		} else {
			$op_ip = "cli_request_commond";
		}

		(!isset($type) || empty($type)) && $type = WEBSITE;

		$this->main_msg_sql = " insert into `" . $this->main_table . "`(`title`,`op_username`,`op_id`,`op_role`,`type`,`dateline`,`op_ip`) " .
		" VALUES('$title','" . $this->username . "','" . $this->uid . "','" . $this->role_id . "','" . $type . "','" . date('Y-m-d H:i:s') . "','" . $op_ip . "')";
		$this->index = 0;
		$this->slave_msg_sql = " insert into `".$this->slave_table."`(`op_id`,`step`,`op_log_id`,`op_datetime`,`op_msg`,`op_sql`,`other_msg`) values ";
	}

	/**
	 * 加入日志信息
	 */
	public function add_log_msg($msg = NULL, $uid = NULL, $last_sql = NULL, $ext_msg = '无') {

		if ($last_sql !== FALSE) {
			$last_sql = !empty($last_sql) ? $last_sql : $this->_ci->db->last_query();
		}

		$this->index += 1;
		$step = $this->index; // 第几步骤
		$op_datetime = date('Y-m-d H:i:s'); // 操作时间
		$op_msg = $msg . '，操作成功的条数：' . $this->_ci->db->affected_rows() . '条'; // 操作信息
		$op_sql = $last_sql; // 操作sql
		$other_msg = $ext_msg; // 其他信息

//		$this->slave_msg_sql .= "('$step',op_log_id_placeholder,'" . date('Y-m-d H:i:s') . "','$op_msg','$op_sql','$other_msg'),";

		$this->slave_msg_sql .= "(\"$uid\",\"$step\",op_log_id_placeholder,\"" . date('Y-m-d H:i:s') . "\",\"$op_msg\",\"$op_sql\",\"$other_msg\"),";
	}
	/*
		 * 写日志
	*/
	public function write_log() {
		if($this->main_msg_sql)
			$this->_ci->db->query($this->main_msg_sql);
		if ($this->_ci->db->affected_rows() > 0) {
			$insert_id = $this->_ci->db->insert_id();
			$this->slave_msg_sql = str_replace('op_log_id_placeholder', $insert_id, trim($this->slave_msg_sql, ','));
			$this->_ci->db->query($this->slave_msg_sql);
		}

	}

}
