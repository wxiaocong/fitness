<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store_model extends My_Model {

    public $model = 'store';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }


    public function save($data,$store_id = '')
    {
        if ($store_id) {
            $this->update($data, array('store_id' => $store_id));
        }else{
            $this->add($data);
        }
    }
    
    public function get_store_list($store_id = 0)
    {
    	$data = array();
    	$sql = "select store_id,name from w_store where disabled = '0'";
    	$store_id && $sql .= " and store_id = {$store_id}";
    	
    	$res = $this->get_all($sql);
    	foreach ($res as $val){
    		$data[$val['store_id']] = $val['name'];
    	}
    	return $data;
    }
    
    //查询地区是否存在分店
    public function search_area_store_cnt($area_id = 0){
    	if($area_id){
    		$sql = "SELECT COUNT(1) AS cnt  FROM w_store WHERE (city={$area_id}  OR area={$area_id})";
    		$res = $this->get_one($sql);
    		if($res['cnt'] > 0){
    			return false;
    		}
    	}
    	return true;
    }
    
}

/* End of file store_model.php */
/* Location: ./application/models/store_model.php */