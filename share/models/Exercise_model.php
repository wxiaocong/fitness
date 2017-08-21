<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exercise_model extends My_Model {

    public $model = 'exercise';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
}

/* End of file Exercise_model.php */
/* Location: ./application/models/Exercise_model.php */