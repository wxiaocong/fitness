<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Refund_model extends My_Model {
	public $model = 'refund';
	public function __construct() {
		parent::__construct ();
		
		$this->setModel ( $this->model );
	}
}

/* End of file Refund_model.php */
/* Location: ./application/models/Refund_model.php */