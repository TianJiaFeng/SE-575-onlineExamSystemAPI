<?php
if (!defined('BASEPATH'))exit ('No direct script access allowed');

/**
 * Class Grade
 */
class Grade extends MY_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('grade/Grade_model');
		$this->load->model('user/User_model');
	}

	function page() {

		$page = $this->input->post('page');
        $limit = $this->input->post('limit');

        $page = $page?intval($page):1;
        $limit = $limit?intval($limit):20;

        $result = $this->Grade_model->pageCount($count,$page,$limit);
        $totalPage = ceil($count/$limit);
        foreach ($result as $r){
        	$studentID = $r['studentID'];
        	$student = $this->User_model->getBystudentID($studentID);
        	if($r['grade'] < $r['total']){
        		$percentage = (100*($r['grade']/$r['total']))%100;
        	} else {
        		$percentage = 100;
        	}
        	
			$data[] = array(
				'id' => $r['id'],
				'studentID' => $r['studentID'],
				'name' => $student['name'],
				'grade' => $r['grade'],
				'total' => $r['total'],
				'percentage' => $percentage,
			);
		}
        if(is_array($data)){
            $response = array(
                'code' => '0',
                'msg' => 'Success',
                'totalPage' => $totalPage,
                'data' => $data,
            );
        }
        else {
            $response = array(
                'code' => '1',
                'msg' => 'Failed',
                'totalPage' => '1',
                'data' => 'error',
            );
        }
        $this->output->myOutput($response);
	}

	function addGrade() {

		$studentID = $this->input->post('studentID');
		$grade = $this->input->post('grade');
		$total = $this->input->post('total');
		if (!$studentID || !$total) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$search = $this->Grade_model->getBystudentID($studentID);
		if($search){
			$response = array(
				'code' => '1',
				'msg' => 'repeated student',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->Grade_model->addGrade($studentID,$grade,$total);
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

	function getBystudentID() {

		$studentID = $this->input->post('studentID');
		if (!$studentID) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}
		$result = $this->Grade_model->getBystudentID($studentID);
		if(!$result){
			$response = array(
				'code' => '1',
				'msg' => 'cannot find result',
				'data' => array(),
			);
		} else {
			$student = $this->User_model->getBystudentID($studentID);
			if($result['grade'] < $result['total']){
        		$percentage = (100*($result['grade']/$result['total']))%100;
        	} else {
        		$percentage = 100;
        	}
			$data = array(
				'id' => $result['id'],
				'name' => $student['name'],
				'grade' => $result['grade'],
				'total' => $result['total'],
				'percentage' => $percentage,
			);
			$response = array(
				'code' => '0',
				'msg' => 'success',
				'data' => $data,
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
		$result = $this->Grade_model->del($id);
		$response = array(
			'code' => '0',
			'msg' => 'success',
			'data' => $result,
		);
		$this->output->myOutput($response);
	}
}