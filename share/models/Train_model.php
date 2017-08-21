<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Train_model extends My_Model {

    public $model = 'train';

    public function __construct()
    {
        parent::__construct();

        $this->setModel($this->model);
    }  
    
    public function save($data, $train_id = '')
    {
    	if ($train_id) {
    		$this->update($data, array('train_id' => $train_id));
    	}else{
    		$this->add($data);
    	}
    }
    
	// 获取训练表详情
	public function get_train_detail($order_id) {
		if ($order_id) {
			$sql = "SELECT
			  b.coach_name,c.nickname,d.*
			FROM w_order a
				LEFT JOIN w_coach b ON a.coach_id = b.coach_id
			  	LEFT JOIN w_user c ON c.openid = a.openid
			  	LEFT JOIN w_train d ON a.order_id = d.order_id
    		WHERE a.order_id = {$order_id}";
			$result = $this->get_one ( $sql );
			
			$data['max_warn'] = $data['max_stre'] = $data['max_card'] = $data['max_cool'] = 1;
			foreach ($result as $key=>$val){
				if ($key == 'goals'){
					$data['result'][$key] = $val;
				}else{
					if(!empty($val)){
						$data['result'][$key] = json_decode($val);
						if(strpos($key,'warn_') !== FALSE){
							$data['max_warn'] = max($data['max_warn'], count(json_decode($val)));
						}elseif (strpos($key,'stre_') !== FALSE){
							$data['max_stre'] = max($data['max_stre'], count(json_decode($val)));
						}elseif (strpos($key,'card_') !== FALSE){
							$data['max_card'] = max($data['max_card'], count(json_decode($val)));
						}elseif (strpos($key,'cool_') !== FALSE){
							$data['max_cool'] = max($data['max_cool'], count(json_decode($val)));
						}
					}
				}
			}
			return $data;
		}
		return NULL;
	}
}

/* End of file Train_model.php */
/* Location: ./application/models/Train_model.php */