<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon_model extends My_Model {

    public $model = 'coupon';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
    
    public function save($data,$coupon_id = '')
    {
    	if ($coupon_id) {
    		$this->update($data, array('coupon_id' => $coupon_id));
    	}else{
    		$this->add($data);
    	}
    }
}

/* End of file Coupon_model.php */
/* Location: ./application/models/Coupon_model.php */