<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Param_model extends My_Model {

    public $model = 'setting';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }

    //添加系统参数
    public function save($data,$id = '')
    {
        $insertArr['s_key'] = $data['name'];
        $insertArr['s_val'] = $data['value'];
        $insertArr['mome'] = $data['meaning'];

        if ($id) {
            $this->update($insertArr, array('id' => $id));
        }else{
            $this->add($insertArr);
        }
    }
    
    public function get_all_param($where= array())
    {
    	! empty($where) && $this->db->where($where);
    	$query = $this->db->select('s_key,s_val')->get('setting');
    	$result = $query->result_array();
    	$return = array();
    	if($result)
	    	foreach($result as $v)
	    		$return[$v['s_key']] = $v['s_val'];
    	return $return;
    }    
}

/* End of file param_model.php */
/* Location: ./application/models/param_model.php */