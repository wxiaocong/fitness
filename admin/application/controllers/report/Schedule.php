<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends My_Controller {
	//课程分段时间
	public $ltime = array(
			'1'	=>	'8:00',
			'2'	=>	'9:00',
			'3'	=>	'10:00',
			'4'	=>	'11:00',
			'5'	=>	'12:00',
			'6'	=>	'13:00',
			'7'	=>	'14:00',
			'8'	=>	'15:00',
			'9'	=>	'16:00',
			'10'=>	'17:00',
			'11'=>	'18:00',
			'12'=>	'19:00',
			'13'=>	'20:00',
			'14'=>	'21:00',
			'15'=>	'22:00',
			'16'=>	'23:00'
	);
    public function __construct()
    {
        parent::__construct();
        $this->load->model('store_model');
        $this->load->model('order_model');
    }

    public function index($page = 1)
    {
    	
    	$data['store_list'] = $this->store_model->get_store_list($this->_user['store_id']);
    	$data['footerJs'] = array('DatePicker/WdatePicker.js','report_schedule.js');
        $this->template->display('report/schedule/list.html', $data);
    }

    //获取一周订单
    public function get_order_schedule()
    {
    	$store_id = intval($this->input->get('store_id'));
    	$start_date = $this->input->get('start_date');
    	if ($store_id){
	    	$in_date = array();
	    	//默认当天开始
	    	if( empty($start_date) || ! is_date($start_date)){
	    		$in_date = array(
	    			date('Y-m-d'),
	    			date("Y-m-d",strtotime("+1 day")),
	    			date("Y-m-d",strtotime("+2 day")),
	    			date("Y-m-d",strtotime("+3 day")),
	    			date("Y-m-d",strtotime("+4 day")),
	    			date("Y-m-d",strtotime("+5 day")),
	    			date("Y-m-d",strtotime("+6 day"))
	    		);
	    	}else{
	    		$in_date = array(
	    			$start_date,
	    			date("Y-m-d",strtotime("+1 day",strtotime($start_date))),
	    			date("Y-m-d",strtotime("+2 day",strtotime($start_date))),
	    			date("Y-m-d",strtotime("+3 day",strtotime($start_date))),
	    			date("Y-m-d",strtotime("+4 day",strtotime($start_date))),
	    			date("Y-m-d",strtotime("+5 day",strtotime($start_date))),
	    			date("Y-m-d",strtotime("+6 day",strtotime($start_date)))
	    		);
	    	}
	    	$sql = "SELECT a.date,a.time,a.num,b.course_name,c.coach_name,d.realname FROM w_order a 
				LEFT JOIN w_course b ON a.course_id = b.course_id
				LEFT JOIN w_coach c ON a.coach_id = c.coach_id
				LEFT JOIN w_user d ON a.openid = d.openid
				WHERE a.store_id = $store_id AND a.date IN ('".implode("','",$in_date)."') AND a.status IN('1','3')";
	    	$res = $this->order_model->get_all($sql);
	    	
	    	//构造空数组
	    	global $ltime;
	    	$ltime = $this->ltime;
	    	$data = array_map(function(){
	    		global $ltime;
	    		return array_combine($ltime, array_pad(array(),count($ltime),''));
	    	},array_combine($in_date, array_pad(array(),count($in_date),'')));
	    	unset($ltime);
	    	//补订单
	    	if( ! empty($res)){
	    		foreach ($res as $val){
	    			if(empty($data[$val['date']][$val['time']])){
	    				$data[$val['date']][$val['time']] = $val['course_name'].'('.$val['coach_name'].')'.'<br>'.$val['realname'].'['.$val['num'].'/3]';
	    			}else{
	    				$data[$val['date']][$val['time']] .= '<br>'.$val['course_name'].'('.$val['coach_name'].')'.'<br>'.$val['realname'].'['.$val['num'].'/3]';
	    			}
	    		}
	    	}
	    	echo json_encode($data);
    	}
    }
}