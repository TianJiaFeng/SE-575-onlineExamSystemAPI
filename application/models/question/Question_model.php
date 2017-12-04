<?php

/**
 * Class Question_model
 */
class Question_model extends MY_model {

	var $table = 'question';

	public function __construct() {
		parent::__construct();
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
	 * @param string $content
	 * @param int $score
	 * @return int
	 */
	function addQuestion($content="",$score="") {
		$insert = array(
			'content' => $content,
			'score' => $score,
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
	 * @param string $content
	 * @param int $score
	 */
	function updateQuestion($id,$content,$score=""){
		$where = array('id'=>$id);
		$update = array(
			'content' => $content,
			'score' => $score,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}

	/**
	 * @return array
	 */
	function searchAll() {
		return $this->_select();
	}
}