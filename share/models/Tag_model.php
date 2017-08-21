<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends My_Model {

    public $model = 'tag';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }


    public function save($data,$tag_id = '')
    {
        if ($tag_id) {
            $this->update($data, array('tag_id' => $tag_id));
        }else{
            $this->add($data);
        }
    }
    
    public function get_tag_list($tag_id = 0)
    {
    	$course_id = intval($tag_id);
    	$data = array();
    	$sql = "select tag_id,tag_name from w_tag where disable = '0'";
    	$tag_id && $sql .= " and tag_id = {$tag_id}";
    	$res = $this->get_all($sql);
    	foreach ($res as $val){
    		$data[$val['tag_id']] = $val['tag_name'];
    	}
    	return $data;
    }
    
    public function get_tag_by_store($store_id)
    {
    	$sql = "SELECT * FROM w_tag WHERE store_id = $store_id AND disabled = '0'";
    	return $this->get_all($sql);
    }
}

/* End of file store_model.php */
/* Location: ./application/models/store_model.php */