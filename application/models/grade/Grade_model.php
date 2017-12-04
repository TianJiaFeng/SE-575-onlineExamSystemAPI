<?php

/**
 * Class Grade_model
 */
class Grade_model extends MY_model {

	var $table = 'grade';

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
	 * @param $id
	 * @return array
	 */
	function getByID($id) {
		$where = array( 'id'=>$id );
		$arr = $this->_where($where)->_limit(1)->_select();
		if ($arr) {
			return $arr[0];
		} else {
			return array();
		}
	}

	/**
	 * @param int $grade
	 * @param int $studentID
	 * @return int
	 */
	function addGrade($studentID="", $grade="", $total="") {
		$insert = array(
			'studentID' => $studentID,
			'grade' => $grade,
			'total' => $total,
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
	 * @param $grade
	 * @param $name
	 */
	function updateGrade($id,$grade,$total){
		$where = array('id'=>$id);
		$update = array(
			'grade' => $grade,
			'total' => $total,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}
}