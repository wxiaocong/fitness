<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paylog_model extends My_Model {

    public $model = '';

    public function __construct()
    {
        parent::__construct();

        $this->model = 'paylog_'.date('Y');
        $this->create_paylog_table();
        $this->setModel($this->model);
    }
    
    public function set_table($year){
    	$this->model = 'paylog_'.$year;
    	$this->create_paylog_table();
    }
    
    private function create_paylog_table(){
    	$table = 'w_'.$this->model;
    	$table_exists = $this->db->query("show tables like '$table' ");
    	 
    	if( count($table_exists->result_array()) <= 0 ){
    
    		$sql = "CREATE TABLE `$table` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
			  `openid` varchar(50) NOT NULL DEFAULT '0',
			  `dateline` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `pay_type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1后台充值,2用户充值,3预约付款,4退款',
			  `gain` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '收入',
			  `expense` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '支出',
			  `balance` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
			  `remark` text COMMENT '备注',
			  `opera_role` int(11) NOT NULL DEFAULT '0' COMMENT '操作人角色',
			  `opera_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
			  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金交易记录'";
    
    		$this->db->query($sql);
    	}
    }
}

/* End of file Paylog_model.php */
/* Location: ./application/models/Paylog_model.php */