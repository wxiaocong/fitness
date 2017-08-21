<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class My_Model extends CI_Model
{
	
	private $_modelTable;
	
	protected $_fields = array (); // 全表字段
	
	public function __construct()
	{
		parent::__construct ();
		$this->get_sys_param ();
	}
	
	public function setModel($model)
	{
		$this->_modelTable = $model;
		
		$this->_fields = $this->db->list_fields ( $model );
	}
	
	public function get_sys_param()
	{
		$sql = "select `s_key`,`s_val` from `w_setting`";
		$res = $this->get_all ( $sql );
		foreach ( $res as $key => $value ){
			$s_key = $value ['s_key'];
			$this->$s_key = $value ['s_val'];
		}
	}
	public function dbLog()
	{
	
	}
	public function get_rand_num($len = 4)
	{
		$nums = '0123456789';
		$shuffled = str_shuffle ( $nums );
		return substr ( $shuffled, 0, $len );
	
	}
	
	/**
	 * 添加表数据
	 * 
	 * @param $data_arr 数据数组        	
	 */
	public function add($data_arr,$table_pass=NULL)
	{
		
		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->insert ( $table, $data_arr );
		$id = $this->db->insert_id ();
		$this->dbLog ();
		return $id;
	}
	
	/**
	 * 添加表数据
	 * 
	 * @param $data_arr 数据数组        	
	 */
	public function add_batch($data_arr,$table_pass=NULL)
	{
		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->insert_batch ( $table, $data_arr );
		$this->dbLog ();
	}
	
	/**
	 * 修改表
	 */
	public function update($data_arr, $where_arr,$table_pass=NULL)
	{

		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->where($where_arr);
		$this->db->update ( $table, $data_arr );
		
		$this->dbLog ();
		return $this->db->affected_rows();
	}
	
	/**
	 * 批量修改表
	 */
	public function update_batch($data_arr, $where_val,$table_pass=NULL)
	{
		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->update_batch ( $table, $data_arr, $where_val );
		// $this->dbLog();
	}
	
	/**
	 * 删除表数据
	 */
	public function del($where_arr,$table_pass=NULL)
	{
		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->delete ( $table, $where_arr );
		$this->dbLog ();
	}
	
	/**
	 * 获取一条记录
	 * 
	 * @param array $arr
	 *        	包含where,like,join的key
	 * @return array
	 */
	public function one($arr = array(), $type = 0)
	{
		$return_arr = array ();
		
		if(!empty($arr['where']))
		{
			$this->db->where ( $arr ['where'] );
		}

		if(!empty($arr['order_by']))
		{
			foreach($arr['order_by'] as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}
		
		$this->db->from ( $this->_modelTable );
		
		$query = $this->db->get ();
		
		if (! empty ( $query ))
		{
			$return_arr = $type ? $query->result_array () : $query->row_array ();
		}
		
		return $return_arr;
	}
	
	public function get_in($key, $valueArr)
	{
		if (empty ( $valueArr ))
		{
			return array ();
		}
		$this->db->from ( $this->_modelTable );
		
		$this->db->where_in ( $key, $valueArr );
		
		$query = $this->db->get ();
		
		$return_arr = array ();
		
		if (! empty ( $query ))
		{
			$return_arr = $query->result_array ();
		}
		
		return $return_arr;
	}
	
	/**
	 * 获取数据
	 * 
	 * @param array $where_arr
	 *        	key字段，value搜索值
	 * @param array $join_arr
	 *        	$join_arr[0]连接表、$join_arr[1]条件、$join_arr[2]left、$join_arr[3]select字段
	 * @param integer $limit        	
	 * @param integer $offset        	
	 * @param array $like_arr
	 *        	key字段，value搜索值
	 * @param array $join_arr        	
	 * @param array $order_arr
	 *        	key字段,value desc asc
	 * @param string $field
	 *        	全字段搜索，使用like or拼接
	 * @return array
	 */
	public function get($where_arr = null, $limit = 0, $offset = 0, $like_arr = null, $join_arr = null, $order_arr = null, $field_str = '', $in_arr = array())
	{
		$this->db->start_cache ();
		
		$return_arr = array ();
		
		$this->db->from ( $this->_modelTable );
		
		if (! empty ( $where_arr ))
		{
			$this->db->where ( $where_arr );
		}
		
		if (! empty ( $like_arr ))
		{
			foreach ( $like_arr as $key => $value )
			{
				$this->db->like ( $key, $value );
			}
		} 
		
		if (! empty ( $in_arr ))
		{
			foreach ($in_arr as $k=>$v)
			{
				$this->db->where_in($k,$v);
			}
		}
		
		if (! empty ( $field_str ))
		{
			$tmp = '';
			foreach ( $field_str as $key => $value )
			{
				#$this->db->or_like ( $key, $value );
				$tmp .= " or $key like '%$value%' ";
				
			}

			if($tmp) {
				$tmp = '('.trim($tmp,' or') . ')';
				$this->db->where($tmp);
			}
		}
		/*if (! empty ( $field_str ))
		{
			foreach ( $this->_fields as $key => $value )
			{
				if (preg_match_all ( '/date/', $value ))
				{
					continue;
				}
				$this->db->or_like ( $value, $field_str );
			}
		}*/
		
		if (! empty ( $join_arr ))
		{
			if (isset ( $join_arr [2] ))
			{
				$this->db->join ( $join_arr [0], $join_arr [1], $join_arr [2] );
			} else
			{
				$this->db->join ( $join_arr [0], $join_arr [1] );
			}
			
			if (isset ( $join_arr [3] ))
			{
				$this->db->select ( $this->_modelTable . '.*,' . $join_arr [3] );
			}
		}
		
		if (! empty ( $order_arr ))
		{
			foreach ( $order_arr as $key => $value )
			{
				$this->db->order_by ( $key, $value );
			}
		}
		
		$this->db->stop_cache ();
		
		$totalNum = $this->db->count_all_results ();
		
		if (! empty ( $limit ))
		{
			$this->db->limit ( $limit, $offset );
		}
		$query = $this->db->get (); 
		$this->db->flush_cache ();
		if (! empty ( $query ))
		{
			$return_arr = $query->result_array ();
		}
		
		return array (
				'num' => count ( $return_arr ),
				'data' => $return_arr,
				'totalNum' => $totalNum 
		);
	}
	
	public function get_page_list_by_sql($sql_arr = array('data_sql'=>'','count_sql'=>''))
	{
		if(isset($sql_arr) && !empty($sql_arr))
		{
			$return_arr = $this->get_all($sql_arr['data_sql']);
			$res_count = $this->get_one($sql_arr['count_sql']);
			#var_dump($sql_arr);die;
			$totalNum = isset($res_count['cnt']) ? $res_count['cnt'] : 0;
			return array (
					'num' => count ( $return_arr ),
					'data' => $return_arr,
					'totalNum' => $totalNum
			);
		}
	}	
	
	/**
	 * 获取所有
	 *
	 * @param string $sql        	
	 * @return multitype:
	 */
	public  function get_all($sql = NULL)
	{
		$return_arr = array ();
		if ($sql)
		{
			$query = $this->db->query ( $sql );
			$return_arr = $query->result_array ();
		}
		return $return_arr;
	}
	
	/**
	 * 获取一条数据
	 *
	 * @param string $sql        	
	 */
	public function get_one($sql = NULL)
	{
		$return_arr = array ();
		
		if ($sql)
		{
			$query = $this->db->query ( $sql );
			$return_arr = $query->row_array ();
		}
		return $return_arr;
	}
	
	/**
	 * 获取配送方式
	 *
	 * @param string $delivery_status_index        	
	 */
	public function get_delivery_methods($delivery_methods_id = NULL)
	{
		$payment_methods_id = intval ( $delivery_methods_id );
		// 支付方式
		static $delivery_methods = array (
				'1' => '平台送货',
				'2' => '自提货',
				'3' => '快递发货' 
		);
		if ($delivery_methods_id)
		{
			if (array_key_exists ( $delivery_methods_id, $delivery_methods ))
			{
				return $delivery_methods [$delivery_methods_id];
			}
		}
		return '未知配送方式';
	}
	
	/**
	 * 获取支付方式
	 *
	 * @param string $delivery_status_index        	
	 */
	public function get_payment_methods($payment_methods_id = NULL)
	{
		$payment_methods_id = intval ( $payment_methods_id );
		// 支付方式
		static $payment_methods = array (
				'1' => '在线支付',
				'2' => '余额支付',
				'3' => '线下转帐'  ,
				'4' => '账期' 
		);
		if ($payment_methods_id)
		{
			if (array_key_exists ( $payment_methods_id, $payment_methods ))
			{
				return $payment_methods [$payment_methods_id];
			}
		}
		return '未知支付方式';
	}
	
	/**
	 * 获取订单状态中文
	 *
	 * @param string $delivery_status_index        	
	 */
	public function get_order_status($order_status_index = NULL)
	{
		$order_status_index = trim ( $order_status_index );
		// 订单状态
		static $order_status_arr = array (
				'unaudit' => '待审核',
				'audit' => '审核通过',
				'cancel' => '取消' ,
				'back' => 'back',
				'sys_cancel' => '系统取消' 
		);
		if ($order_status_index)
		{
			if (array_key_exists ( $order_status_index, $order_status_arr ))
			{
				return $order_status_arr [$order_status_index];
			}
		}
		return '未知状态';
	}
	/**
	 * 获取发货状态中文
	 *
	 * @param string $delivery_status_index        	
	 */
	public function get_delivery_status($delivery_status_index = NULL)
	{
		$delivery_status_index = intval ( $delivery_status_index );
		// 发货状态
		static $delivery_status_arr = array (
				'1' => '待发货',
				'2' => '已发货',
				'3' => '部分发货' 
		);
		if ($delivery_status_index)
		{
			if (array_key_exists ( $delivery_status_index, $delivery_status_arr ))
			{
				return $delivery_status_arr [$delivery_status_index];
			}
		}
		return '未知发货状态';
	}
	
	/**
	 * 获取付款状态中文
	 *
	 * @param string $delivery_status_index        	
	 */
	public function get_payment_status($payment_status_index = NULL)
	{
		$payment_status_index = intval ( $payment_status_index );
		// 付款状态
		static $payment_status_arr = array (
				'1' => '未付款',
				'2' => '部分付款',
				'3' => '已付款' 
		);
		
		if ($payment_status_index)
		{
			if (array_key_exists ( $payment_status_index, $payment_status_arr ))
			{
				return $payment_status_arr [$payment_status_index];
			}
		}
		return '未知付款状态';
	}

		/**
	 * 获取当前用户的id
	 */
	public function get_current_user_uid()
	{
		static $uid = '';
		if (! $uid)
		{
			if ($this->session->userdata ( 'uid' ))
			{
				$uid = $this->session->userdata ( 'uid' );
			}
		}
		return $uid;
	}
	/**
	 * 获取当前用户的名称
	 */
	public function get_current_user_name()
	{
		static $uname = '';
		if (! $uname)
		{
			if ($this->session->userdata ( 'username' ))
			{
				$uname = $this->session->userdata ( 'username' );
			}
		}
		return $uname;
	}
	
	//获取表字段值
	public function get_field_value($field,$name_arr,$limit=1,$model='')
	{
		$limit && $this->db->limit($limit);
		$model = $model ? $model : $this->_modelTable;
		$res = $this->db->select($field)->where($name_arr)->get($model);
		if($limit)
			return isset($res->row()->$field) ? $res->row()->$field : '';
		else
		{
			$result = $res->result_array();
			foreach ($result as $v)
			{
				$return[] = $v[$field];
			}
			return $return;
		}		
	}
}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */