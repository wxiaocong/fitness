<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_model extends My_Model {

    public $model = 'plan';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
    
    public function save($data,$plan_id = '')
    {
    	if ($plan_id) {
            $this->update($data, array('paln_id' => $plan_id));
        }else{
            $this->add($data);
        }
    }
}

/* End of file Plan_model.php */
/* Location: ./application/models/Plan_model.php */