<?php
if (!defined('BASEPATH'))exit ('No direct script access allowed');

/**
 * Class User
 */
class User extends MY_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user/User_model');
		$this->load->model('grade/Grade_model');
	}

	function verify() {

		$studentID = $this->input->post('studentID');
		if (!$studentID) {
			$response = array(
				'code' => '1',
				'msg' => 'Please input a studentID',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}

		$search = $this->Grade_model->getBystudentID($studentID);
		if($search){
			$response = array(
				'code' => '1',
				'msg' => 'Aleady finished this exam',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}

		$result = $this->User_model->getBystudentID($studentID);
		if($result){
			$response = array(
				'code' => '0',
				'msg' => 'success',
				'data' => array(),
			);
		} else {
			$response = array(
				'code' => '1',
				'msg' => 'Invaild studentID',
				'data' => array(),
			);
		}
		
		$this->output->myOutput($response);

	}

	function getStudent() {

		$studentID = $this->input->post('studentID');
		if (!$studentID) {
			$response = array(
				'code' => '1',
				'msg' => 'need a studentID',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->User_model->getBystudentID($studentID);
		if($result){
			$response = array(
				'code' => '0',
				'msg' => 'success',
				'data' => $result,
			);
		} else {
			$response = array(
				'code' => '1',
				'msg' => 'no result',
				'data' => array(),
			);
		}
		
		$this->output->myOutput($response);
	}

	function addStudent() {

		$studentID = $this->input->post('studentID');
		$name = $this->input->post('name');
		if (!$studentID || !$name) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$search = $this->User_model->getBystudentID($studentID);
		if($search){
			$response = array(
				'code' => '1',
				'msg' => 'repeated user',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->User_model->addStudent($studentID,$name);
		if($result){
			$response = array(
				'code' => '0',
				'msg' => 'success',
				'data' => $result,
			);
		} else {
			$response = array(
				'code' => '1',
				'msg' => 'fail',
				'data' => array(),
			);
		}
		
		$this->output->myOutput($response);
	}

	function deleteStudent() {

		$id = $this->input->post('id');
		if (!$id) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->User_model->del($id);
		$response = array(
			'code' => '0',
			'msg' => 'success',
			'data' => $result,
		);
		$this->output->myOutput($response);
	}
}