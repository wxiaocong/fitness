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
		
		$this->db->update ( $table, $data_arr, $where_arr );
		$this->dbLog ();
	}
	
	/**
	 * 批量修改表
	 */
	public function update_batch($data_arr, $filed,$table_pass=NULL)
	{
		$table_pass ? $table = $table_pass : $table = $this->_modelTable;
		$this->db->update_batch ( $table, $data_arr, $filed );
		// $this->dbLog();
	}
	
	/**
	 * 删除表数据
	 */
	public function del($where_arr)
	{
		$this->db->delete ( $this->_modelTable, $where_arr );
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
		
		if (! empty ( $arr ['where'] ))
		{
			$this->db->where ( $arr ['where'] );
		}
		
		if (! empty ( $arr ['like'] ))
		{
			$this->db->like ( $arr ['like'] );
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
	public function get($where_arr = null, $limit = 0, $offset = 0, $like_arr = null, $in_arr = null, $order_arr = null)
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
			foreach ( $in_arr as $key => $value )
			{
				$this->db->where_in ( $key, $value );
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
	
	/**
	 * 获取所有
	 *
	 * @param string $sql        	
	 * @return multitype:
	 */
	public function get_all($sql = NULL)
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
	
	public function get_page_list_by_sql($sql_arr = array('data_sql'=>'','count_sql'=>''))
	{
		if(isset($sql_arr) && !empty($sql_arr))
		{
			$return_arr = $this->get_all($sql_arr['data_sql']);
			$res_count = $this->get_one($sql_arr['count_sql']);
			$totalNum = isset($res_count['cnt']) ? $res_count['cnt'] : 0;
			return array (
					'num' => count ( $return_arr ),
					'data' => $return_arr,
					'totalNum' => $totalNum
			);
		}
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

}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */