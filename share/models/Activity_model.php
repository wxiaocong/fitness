<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends My_Model {

    public $model = 'activity';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
    
    public function save($data,$activity_id = '')
    {
    	if ($activity_id) {
    		$res = $this->update($data, array('activity_id' => $activity_id));
    	}else{
    		$res = $this->add($data);
    	}
    	if ($res && $data['recommend'] == '1'){
    		//把之前推荐的去掉
    		$this->update(array('recommend'=>'0'), array('activity_id != ' => $activity_id));
    	}
    }
}

/* End of file activity_model.php */
/* Location: ./application/models/activity_model.php */