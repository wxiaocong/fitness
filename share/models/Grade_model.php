<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_model extends My_Model {

    public $model = 'grade';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    public function save($data,$grade_id = '')
    {
    	if ($grade_id) {
    		$this->update($data, array('grade_id' => $grade_id));
    	}else{
    		$this->add($data);
    	}
    }
    
    public function get_grade_list()
    {
    	$res = $this->one(array('where'=>array('disabled'=>'0')),1);
    	foreach ($res as $val){
    		$data[$val['grade_id']] = $val['grade_name'];
    	}
    	return $data;
    }
}

/* End of file Grade_model.php */
/* Location: ./application/models/Grade_model.php */