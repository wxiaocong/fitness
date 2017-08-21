<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area_model extends My_Model {

    public $model = 'area';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    public function save($data,$id = '')
    {
    	$insertArr['pid'] = $data['pid'];
    	$insertArr['name'] = $data['name'];
    
    	if ($id) {
    		$this->update($insertArr, array('area_id' => $id));
    	}else{
    		$this->add($insertArr);
    	}
    }    
}

/* End of file Area_model.php */
/* Location: ./application/models/Area_model.php */