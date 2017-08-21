<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends My_Model {

    public $model = 'file';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    public function save($data,$file_id = '')
    {
        if ($file_id) {
            $this->update($data, array('file_id' => $file_id));
        }else{
            $this->add($data);
        }
    }
}

/* End of file File_model.php */
/* Location: ./application/models/File_model.php */