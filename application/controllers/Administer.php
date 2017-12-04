<?php
if (!defined('BASEPATH'))exit ('No direct script access allowed');

/**
 * Class Administer
 */
class Administer extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('administer/Administer_model');
	}

	function verify() {
		

		$accountID = $this->input->post('accountID');
		$password = $this->input->post('password');
		if (!$accountID || !$password) {
			$response = array(
				'code' => '1',
				'msg' => 'Please input your accountID and password',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->Administer_model->verify($accountID,$password);
		if($result){
			$data[] = array(
				'id' => $result['id'],
				'accountID' => $result['accountID'],
			);
			$response = array(
				'code' => '0',
				'msg' => 'success',
				'data' => $data,
			);
		} else {
			$response = array(
				'code' => '1',
				'msg' => 'Invaild accountID or password',
				'data' => array(),
			);
		}
		
		$this->output->myOutput($response);
	}

	function addAccount() {

		$accountID = $this->input->post('accountID');
		$password = $this->input->post('password');
		if (!$accountID || !$password) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$search = $this->Administer_model->getByaccountID($accountID);
		if($search){
			$response = array(
				'code' => '1',
				'msg' => 'repeated user',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->Administer_model->addAccount($accountID,$password);
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
		$result = $this->Administer_model->del($id);
		$response = array(
			'code' => '0',
			'msg' => 'success',
			'data' => $result,
		);
		$this->output->myOutput($response);
	}
}