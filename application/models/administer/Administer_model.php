<?php

/**
 * Class Administer_model
 */
class Administer_model extends MY_model {

	var $table = 'administer';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param $accountID
	 * @return array
	 */
	function getByaccountID($accountID) {
		$where = array( 'accountID'=>$accountID );
		$arr = $this->_where($where)->_limit(1)->_select();
		if ($arr) {
			return $arr[0];
		} else {
			return array();
		}
	}

	/**
	 * @param int $accountID
	 * @param string $password
	 * @return int
	 */
	function addAccount($accountID="", $password="") {
		$insert = array(
			'accountID' => $accountID,
			'password' => $password,
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
	function update($id,$password){
		$where = array('id'=>$id);
		$update = array(
			'password' => $password,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}
	
	/**
	 * @param $name
	 * @param $name
	 * @return $this
	 */
	function verify($accountID="", $password=""){
		$where = array( 
			'accountID'=>$accountID,
			'password'=>$password, 
		);
		$arr = $this->_where($where)->_limit(1)->_select();
		if ($arr) {
			return $arr[0];
		} else {
			return array();
		}
	}
}