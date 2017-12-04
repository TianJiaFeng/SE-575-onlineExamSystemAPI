<?php

/**
 * Class QuestionItem_model
 */
class QuestionItem_model extends MY_model {

	var $table = 'questionItem';

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
	 * @param $questionID
	 * @return array
	 */
	function getByquestionID($questionID) {
		$where = array( 'questionID'=>$questionID );
		return $this->_where($where)->_select();
	}

	/**
	 * @param int $questionID
	 * @param string $content
	 * @param bool $correct
	 * @return int
	 */
	function addQuestionItem($questionID="",$content="",$correct="") {
		$insert = array(
			'questionID' => $questionID,
			'content' => $content,
			'correct' => $correct,
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
	 * @param $questionID
	 * @return mixed
	 */
	function delByquestionID($questionID) {
		$where  = array( 'questionID'=>$questionID );
		return $this->_delete($where);
	}


	/**
	 * @param $id
	 * @param $questionID
	 * @param $content
	 * @param $correct
	 * @param $name
	 */
	function updateQuestionItem($id,$questionID="",$content="",$correct=""){
		$where = array('id'=>$id);
		$update = array(
			'questionID' => $questionID,
			'content' => $content,
			'correct' => $correct,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}

	/**
	 * @param $id
	 * @param $content
	 * @param $correct
	 * @param $name
	 */
	function updateByQuestionItemID($id="",$content="",$correct=""){
		$where = array('id'=>$id);
		$update = array(
			'content' => $content,
			'correct' => $correct,
			'updated' => date('Y-m-d H:i:s'),
		);
		return $this->_update($where, $update);
	}
}