<?php

/**
 * Class User_model
 */
class User_model extends MY_model {

	var $table = 'user';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param $studentID
	 * @return array
	 */
	function getBystudentID($studentID) {
		$where = array( 'studentID'=>$studentID );
		$arr = $this->_where($where)->_limit(1)->_select();
		if ($arr) {
			return $arr[0];
		} else {
			return array();
		}
	}

	/**
	 * @param string $studentID
	 * @param string $name
	 * @return int
	 */
	function addStudent($studentID="", $name="") {
		$insert = array(
			'studentID' => $studentID,
			'name' => $name,
			'created' => date('Y-m-d H:i:s'),
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_insert($insert);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	function del($id) {
		$where  = array( 'id'=>$id );
		return $this->_delete($where);
	}


	/**
	 * @param $id
	 * @param $studentID
	 * @param $name
	 */
	function update($id,$studentID,$name){
		$where = array('id'=>$id);
		$update = array(
			'studentID' => $studentID,
			'name' => $name,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}
	/**
	 * @param $name
	 * @return $this
	 */
	function  searchLikeName($name){
		$like = array();
		if ($name !== null) {
			$like['name'] = $name;
		}
		return $this->_like($like)->_select();
	}

	/**
	 * @param $name
	 * @return $this
	 */
	function  likeNameCount($name){
		$like = array();
		if ($name !== null) {
			$like['name'] = $name;
		}
		return $this->_like($like)->_count();
	}


	/**
	 * @return array
	 */
	function searchAll() {
		return $this->_select();
	}
}