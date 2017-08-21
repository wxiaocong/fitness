<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends My_Model {

    public $model = 'menu';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }
    
    /**
     * 获取所有菜单
     */
    public function get_all_menu($where= '')
    {
    	$sql = "SELECT menu_id,menu_name,parent_id,model,ctrl,act,data,icon,disabled FROM w_menu WHERE $where ORDER BY parent_id, list_order IS NULL, list_order";
    	return $this->get_all($sql);
    }
    
    public function status()
    {
    	
    }
    
}

/* End of file menu_model.php */
/* Location: ./application/models/menu_model.php */