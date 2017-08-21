<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Return_model extends My_Model {

    public $model = '';

    public function __construct()
    {
        parent::__construct();

        $this->model = 'return_'.date('Y');
        $this->create_return_table();
        $this->setModel($this->model);
    }  
    
    
    private function create_return_table(){
    	$table = 'w_'.$this->model;
    	$table_exists = $this->db->query("show tables like '$table' ");
    	 
    	if( count($table_exists->result_array()) <= 0 ){
    
    		$sql = "CREATE TABLE `$table` (
				  `return_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `msg` text,
				  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`return_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信返回参数表'";
    		$this->db->query($sql);
    	}
    }
}

/* End of file Return_model.php */
/* Location: ./application/models/Return_model.php */