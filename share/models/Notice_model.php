<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_model extends My_Model {

    public $model = 'notice';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
}

/* End of file Notice_model.php */
/* Location: ./application/models/Notice_model.php */