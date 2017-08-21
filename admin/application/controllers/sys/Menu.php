<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends My_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_model');
    }

    public function index($page = 1)
    {
    	$page < 1 && $page = 1;
    	$page = pageSize * ($page - 1);
    	
    	$data ['list'] = $this->menu_model->get(NULL,pageSize,$page,NULL,NULL,array('parent_id'=>'ASC','menu_id'=>'ASC'));
    	// 分页
    	$config ['base_url'] = site_url ( 'sys/menu/index' );
    	$config ['total_rows'] = $data ['list'] ['totalNum'];
    	$this->pagination->initialize ( $config );
    	$data ['pages'] = $this->pagination->create_links ();
    	
        $this->template->display('sys/menu/list.html', $data);
    }
    
    public function add($id = '')
    {
    	$data = array();
    	$id && $data['detail'] = $this->menu_model->one(array('where'=>array('menu_id'=>$id)));
    	$this->template->display('sys/menu/addMenu.html', $data);
    }
    
    public function save($id = '')
    {
    	$data = $this->input->post();
    	if ($id) {
    			$this->menu_model->update($data,array('menu_id' => $id));
    	}else{
    			$this->menu_model->add($data);
    	}
    	//更新缓存
    	$menu = $this->menu_model->get_all_menu("disabled = 'false'");
    	$this->cache->file->save('menu', $menu, file_cache_time);
    	
    	redirect(base_url().'sys/menu/index');
    }
    
    public function get_all_menu()
    {
    	//菜单 优先缓存
    	$menu = $this->cache->file->get('menu');
    	if( empty($menu) )
    	{
    		$menu = $this->menu_model->get_all_menu("disabled = 'false'");
    		$this->cache->file->save('menu', $menu, file_cache_time);
    	}
    	return $menu;
    }    
    
    public function status($id, $status)
    {
    	if($id && isset($status))
    	{
    		$status = $status == 'false' ? 'true' : 'false';
    		$this->menu_model->update(array('disabled'=>$status) ,array('menu_id'=>(int)$id));
    		redirect(base_url().'sys/menu/index');
    	}
    	else
    	{
    		show_error('参数错误');
    	}    	
    }

    public function del($id)
    {
    	$id = intval($id);
    	if($id)
    	{
	    	$this->menu_model->del(array('menu_id'=>$id));
	    	redirect(base_url().'sys/menu/index');
    	}
    	else
    	{
    		show_error('参数错误');  
    		die ();
    	}
    }

}