<?php

/**
 * Class MY_model
 * CI_Model
 */
class MY_model extends CI_Model {

	var $myDB = null; 

	var $modelPath = null; 

	var $tablePrefix = ''; 

	var $table = ''; 

	var $primaryKey = 'id'; // primaryKey
	
	var $fkArr = array(); // fk

	var $fkLv = 0;  

	var $blkArr = array(); 

	var $blkLv = 0; 

	public function __construct() {
		parent::__construct();
	}

	function myDB() {
		if ($this->myDB == null) {
			$this->myDB = $this->db;
		}
		return $this->myDB;
	}

	function myTable() {
		return $this->tablePrefix.$this->table;
	}
	/**
	 * where (eg. array('field' =>'value',...))
	 * @param array $where
	 * @return $this
	 */
	function _where($where=array()) {
		foreach ($where as $k=>$v) {
			$this->myDB()->where($k, $v);
		}
		return $this;
	}

	/**
	 * or_where (eg. array('field' =>'value',...))
	 * @param array $or_where
	 * @return $this
	 */
	function _or_where($or_where=array()) {
		foreach ($or_where as $k=>$v) {
			$this->myDB()->or_where($k, $v);
		}
		return $this;
	}

	/**
	 * limit $offset,$limit
	 * @param int $limit
	 * @param int $offset
	 * @return $this
	 */
	function _limit($limit=1,$offset=0) {
		$this->myDB()->limit($limit,$offset);
		return $this;
	}

	/**
	 * order by (eg. array('field1'=>'asc',...))
	 * @param array $order_by
	 * @return $this
	 */
	function _order_by($order_by=array()) {
		if ($order_by) {
			foreach ($order_by as $k=>$v) {
				$this->myDB()->order_by($k, $v);
			}
		} else {
			$this->myDB()->order_by($this->primaryKey, 'desc');
		}
		return $this;
	}

	/**
	 * where in (eg. array('field1'=>array('value1','value2',...)))
	 * @param array $where_in
	 * @return $this
	 */
	function _where_in($where_in=array()) {
		if ($where_in) {
			foreach ($where_in as $k=>$v) {
				$this->myDB()->where_in($k, $v);
			}
		}
		return $this;
	}

	/**
	 * where not in (eg. array('field1'=>array('value1','value2',...)))
	 * @param array $where_not_in
	 * @return $this
	 */
	function _where_not_in($where_not_in=array()) {
		if ($where_not_in) {
			foreach ($where_not_in as $k=>$v) {
				$this->myDB()->where_not_in($k, $v);
			}
		}
		return $this;
	}

	/**
	 * like (eg.)
	 * @param array $like
	 * @return $this
	 */
	function _like($like=array()) {
		if ($like) {
			foreach ($like as $k=>$v) {
				$this->myDB()->like($k, $v);
			}
		}
		return $this;
	}

	/**
	 * or_like (eg.)
	 * @param array $or_like
	 * @return $this
	 */
	function _or_like($or_like=array()) {
		if ($or_like) {
			foreach ($or_like as $k=>$v) {
				$this->myDB()->or_like($k, $v);
			}
		}
		return $this;
	}

	/**
	 * and (...)
	 * group_start 
	 * @return $this
	 */
	function _group_start() {
		$this->myDB()->group_start();
		return $this;
	}

	/**
	 * or (...)
	 * group_start
	 * @return $this
	 */
	function _or_group_start() {
		$this->myDB()->or_group_start();
		return $this;
	}

	/**
	 * group_end 
	 * @return $this
	 */
	function _group_end() {
		$this->myDB()->group_end();
		return $this;
	}

	/**
	 *  eg. UPDATE table SET field = field+1
	 * @param string $key field
	 * @param string $val field+1
	 * @return $this
	 */
	function _set($key='',$val='') {
		if ($key && $val) {
			$this->myDB()->set($key, $val, false);
		}
		return $this;
	}

	/**
	 * @param string $table table name
	 * @return mixed
	 */
	function _count($table='') {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return 0;
		}
		$this->myDB()->from($table);
		return $this->myDB()->count_all_results();
	}

	/**
	 * select (eg. array('field1','field2',...) or 'filed1,filed2,...')
	 * @param string $select
	 * @param string $table table name
	 * @return mixed
	 */
	function _select($select="*",$table="") {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return array();
		}

		$this->myDB()->select($select);
		$query = $this->myDB()->get($table);
		if (!$query) {
			return array();
		}
		$data = $query->result_array();
		if ($this->fkArr && $this->fkLv) {
			$data = $this->getByFK($data);
		}
		if ($this->blkArr && $this->blkLv) {
			$data = $this->getByBLK($data);
		}
		return $data;
	}

	/**
	 * select & count
	 * @param int $count
	 * @param string $select
	 * @param string $table
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	function _selectCount(&$count=0,$select="*",$table="",$limit=30,$offset=0) {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return array();
		}

		$count = $this->myDB()->count_all_results($table,false); // 不清空where条件

		$this->myDB()->limit($limit,$offset);
		$this->myDB()->select($select);
		$query = $this->myDB()->get();
		$data = $query->result_array();
		if ($this->fkArr && $this->fkLv) {
			$data = $this->getByFK($data);
		}
		if ($this->blkArr && $this->blkLv) {
			$data = $this->getByBLK($data);
		}
		return $data;
	}
#endregion

#region 通用操作
	/**
	 * @param $sql
	 * @return mixed
	 */
	function _query($sql) {
		$query = $this->myDB()->query($sql);
		$num = $this->myDB()->affected_rows();
		return $num;
	}

	/**
	 * @param $sql
	 * @return mixed
	 */
	function _getRows($sql) {
		$query = $this->myDB()->query($sql);
		return $query->result_array();
	}

	/**
	 * @param $sql
	 * @return mixed
	 */
	function _getRow($sql) {
		$data = $this->_getRows($sql);
		return $data[0];
	}

	/**
	 * @param $sql
	 * @return mixed
	 */
	function _getOne($sql) {
		$data = $this->_getRow($sql);
		return current($data);
	}

	/**
	 * @param $data array 
	 * @param string
	 * @return int
	 */
	function _insert($data, $table='') {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return 0;
		}
		$query = $this->myDB()->insert($table, $data);
		$insert_id = $this->myDB()->insert_id();
		return $insert_id;
	}

	/**
	 * @param array $where where (eg. array('field' =>'value',...))
	 * @param string $table
	 * @param int $limit
	 * @return mixed
	 */
	function _delete($where, $table='',$limit=1) {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return 0;
		}
		$this->myDB()->where($where);
		$this->myDB()->limit($limit);
		return $this->myDB()->delete($table);
	}

	/**
	 * @param array $where where (eg. array('field' =>'value',...))
	 * @param array $update update (eg. array('field' =>'value',...))
	 * @param string $table
	 * @param int $limit
	 * @return int
	 */
	function _update($where,$update,$table='',$limit=1) {
		$table = $table?$table:$this->myTable();
		if (!$table) {
			return 0;
		}
		$this->myDB()->where($where);
		$this->myDB()->limit($limit);
		$this->myDB()->update($table, $update);
		return $this->myDB()->affected_rows();
	}

#endregion

#region 

	/**
	 * @param int $page
	 * @param int $limit
	 * @param array $order_by
	 * @param string $select
	 * @param string $table
	 * @return mixed
	 */
	function page($page=1,$limit=30,$order_by=array(),$select="*",$table="") {
		$offset = ($page-1)*$limit;
		$this->_order_by($order_by);
		$this->_limit($limit,$offset);
		$data = $this->_select($select,$table);
		return $data;
	}

	/**
	 * @param string $table
	 * @return mixed
	 */
	function count($table="") {
		return $this->_count($table);
	}

	/**
	 * @param int $count
	 * @param int $page
	 * @param int $limit
	 * @param array $order_by
	 * @param string $select
	 * @param string $table
	 * @return array
	 */
	function pageCount(&$count=0,$page=1,$limit=30,$order_by=array(),$select="*",$table="") {
		$offset = ($page-1)*$limit;
		$this->_order_by($order_by);
		$data = $this->_selectCount($count,$select,$table,$limit,$offset);
		return $data;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	function getByID($id=0) {
		if (!$id) {
			return array();
		}
		$where = array( $this->primaryKey => $id );
		$arr = $this->_where($where)->_limit(1)->_select();
		if ($arr) {
			return $arr[0];
		}
		return array();
	}

	/**
	 * @param array $ids
	 * @return array
	 */
	function getByIDs($ids=array()) {
		if (!$ids) {
			return array();
		}
		$limit = count($ids);
		$where_in = array( $this->primaryKey => $ids );
		$arr = $this->_where_in($where_in)->_limit($limit)->_select();
		return $arr;
	}

}
