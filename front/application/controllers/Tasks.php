<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 计划任务
 * @author cong
 *
 */
class Tasks extends CI_Controller {
	private $pay_config = array();
	
    public function __construct()
    {
        parent::__construct();
        
        date_default_timezone_set('PRC');
        
        $this->load->model ( 'order_model' );
        $this->load->model ( 'user_model' );
        $this->load->model ( 'notice_model' );
        $this->load->model ( 'return_model' );
        
        $this->load->library ( 'jssdk', array (
        	'appId' => $this->config->item ( 'appId' ),
        	'appSecret' => $this->config->item ( 'appSecret' )
        ) );
    }
    
    /**
     * 自动处理订单
     * 上课结束仍未支付订单  取消
	 *	上课结束订单		完成
     */
    public function auto_deal_order(){
    	$sql = "SELECT order_id,`status` FROM w_order 
			WHERE `status` < '2' AND UNIX_TIMESTAMP(CONCAT(`date`,' ',`time`,':00')) <= UNIX_TIMESTAMP() - 3600";
    	$res = $this->order_model->get_all($sql);
    	if( ! empty($res) ){
    		$cancel_data = $complate_data = array();
    		foreach ($res as $val){
    			if($val['status'] == '0'){ //取消订单
    				$cancel_data[] = array(
    					'order_id'	=>	$val['order_id'],
    					'status'	=>	'2'		
    				);
    			}elseif ($val['status'] == '1'){ //已完成订单
    				$complate_data[] = array(
    					'order_id'	=>	$val['order_id'],
    					'status'	=>	'3'
    				);
    			}
    		}
    		if( ! empty($cancel_data)){
    			$this->order_model->update_batch($cancel_data, 'order_id');
    		}
    		if( ! empty($complate_data)){
    			$this->order_model->update_batch($complate_data, 'order_id');
    		}
    	}
    }
    

    /**
     * 写入课程确认提醒
     */
    public function add_notice_confirm()
    {
    	$pre_notice_order = $this->order_model->one(array('where'=>array('date'=>date('Y-m-d'),'status'=>'1','is_notice != '=> '2')),1);
    	$confirm_class_template_id = $this->order_model->confirm_class_template_id; //通知模板
    	$send_notice_class_time = $this->order_model->send_notice_class_time;//提前时间
    	foreach ($pre_notice_order as $val){
    		$order_time = $val['date'].' '.$val['time'];
    		if($val['is_notice'] == '1'){ //已通知未确认
    			//检查课程是否结束
    			if(strtotime($order_time) <= strtotime(date('Y-m-d G:00',strtotime('-1 hour')))){
    				$this->notice_model->add(array('order_id'=>$val['order_id'],'template_id'=>$this->order_model->confirm_class_template_id));
    				$this->order_model->update(array('is_notice'=>'2'),array('order_id'=>$val['order_id']));
    			}
    		}else{	//未通知
    			//检查离上课时间
    			if(strtotime($order_time) <= strtotime(date('Y-m-d G:i',strtotime("+$send_notice_class_time minute"))) && strtotime($order_time) > time()){
    				$this->notice_model->add(array('order_id'=>$val['order_id'],'template_id'=>$this->order_model->confirm_class_template_id));
    				$this->order_model->update(array('is_notice'=>'1'),array('order_id'=>$val['order_id']));
    			}
    		}
    	}
    }
    
    /**
     * 发送通知
     */
    public function send_notice(){
    	$pre_notice = $this->notice_model->one(array('where'=>array('is_send'=>'0')),1);
    	foreach($pre_notice as $val){
    		if($val['order_id']){
	    		$order_info = $this->order_model->get_notice_order($val['order_id']);
	    		$order_time = $val['date'].' '.$val['time'];
	    		if(!empty($order_info)){
		    		switch ($val['template_id']){
		    			case $this->order_model->confirm_class_template_id:
		    				if(strtotime($order_time) > time()){
		    					$first = "您好,您预约的课程还有{$this->order_model->send_notice_class_time}分钟就上课啦";
		    				}else{
		    					$first = "您好,您预约的课程已结束";
		    				}
		    				$template = array(
		    					"touser"		=>	$order_info['openid'],
		    					"template_id"	=>	$this->order_model->confirm_class_template_id,
		    					"url"			=>	base_url().'tasks/confirm_class/'.$order_info['order_id'],
		    					"topcolor"		=>	"#da3720",
		    					"data"			=>	array(
		    						"first"		=>	array("value"=>$first,"color"=>"#000"),
		    						"keyword1"	=>	array("value"=>$order_info['course_name'],"color"=>"#000"),
		    						"keyword2"	=>	array("value"=>$order_info['date'].' '.$order_info['time'],"color"=>"#000"),
		    						"keyword3"	=>	array("value"=>$order_info['name'],"color"=>"#000"),
		    						"keyword4"	=>	array("value"=>$order_info['coach_name'],"color"=>"#000"),
		    						"remark"	=>	array("value"=>"\n注：点击详情，确认签到上课","color"=>"#da3720")
		    					)
		    				);
		    				$res = $this->send_template_message(json_encode($template));
		    				if($res->errcode == '0'){
		    					$this->notice_model->update(array('is_send'=>'1'),array('order_id'=>$val['order_id']));
		    				}
		    				break;
		    		}
	    		}
    		}
    	}
    }
    
    //确认上课
    public function confirm_class($order_id){
    	$order_id = intval($order_id);
    	if($order_id){
    		$firstConfirm = 0;
    		$order_info = $this->order_model->one(array('where'=>array('order_id'=>$order_id)));
    		//上课前30分钟到上完课12小时之内都可以签到
    		$send_notice_class_time = $this->order_model->send_notice_class_time;//提前时间
    		$confirm_failure_time = $this->order_model->confirm_failure_time + 1;//签到结束时间
    		if(!empty($order_info) && $order_info['is_confirm'] == '0'){
    			$order_time = $order_info['date'].' '.$order_info['time'];
    			if(strtotime($order_time) <= strtotime(date('Y-m-d H:i',strtotime("+$send_notice_class_time minute"))) && strtotime($order_time) > strtotime(date('Y-m-d H:i',strtotime("-$confirm_failure_time hour")))){
    				$this->order_model->update(array('is_confirm'=>'1','confirm_time'=>date('Y-m-d H:i:s')),array('order_id'=>$order_id));
    				$firstConfirm = 1;
    			}else{
    				$firstConfirm = 2; //超时
    			}
    		}
    		redirect(base_url().'order/detail/'.$order_id.'/'.$firstConfirm);
    		exit;
    	}
    	show_error('订单异常');
    }
    
    
    //发状模板消息
    public function send_template_message($data){
    	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->jssdk->getAccessToken();
    	$res = $this->http_request($url,$data);
//     	$this->return_model->add(array('msg'=>json_encode($res)));
    	return json_decode($res);
    }
    
    protected function http_request($url, $data = null){
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	if(!empty($data)){
    		curl_setopt($curl, CURLOPT_POST, 1);
    		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    	}
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	$output = curl_exec($curl);
    	curl_close($curl);
    	return $output;
    }
    
}

/* End of file Tasks.php */
/* Location: ./application/controllers/Tasks.php */