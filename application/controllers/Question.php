<?php
if (!defined('BASEPATH'))exit ('No direct script access allowed');

/**
 * Class Question
 */
class Question extends MY_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('question/Question_model');
		$this->load->model('question/QuestionItem_model');
	}

	function page() {

		$page = $this->input->post('page');
        $limit = $this->input->post('limit');

        $page = $page?intval($page):1;
        $limit = $limit?intval($limit):20;

        $compute = $this->Question_model->searchAll();
        $sum = 0;
        foreach ($compute as $temp) {
        	$sum = $sum + $temp['score'];
        }

        $result = $this->Question_model->pageCount($count,$page,$limit);
        $totalPage = ceil($count/$limit);
        foreach ($result as $r){
			$data[] = array(
				'id' => $r['id'],
				'content' => $r['content'],
				'score' => $r['score'],
			);
		}
        if(is_array($data)){
            $response = array(
                'code' => '0',
                'msg' => 'Success',
                'sum' => $sum,
                'totalPage' => $totalPage,
                'data' => $data,
            );
        }
        else {
            $response = array(
                'code' => '1',
                'msg' => 'Failed',
                'sum' => '0',
                'totalPage' => '1',
                'data' => 'error',
            );
        }
        $this->output->myOutput($response);
	}

	function getAll() {
		

        $compute = $this->Question_model->searchAll();
        $sum = 0;
        foreach ($compute as $temp) {
        	$sum = $sum + $temp['score'];
        	$item = array();
        	$questionItem = $this->QuestionItem_model->getByquestionID($temp['id']);
        	$right = 0;
        	foreach ($questionItem as $key => $r){
        		if($r['correct'] == 1){
        			$right = $key+1;
        		}
				$item[] = array(
					'id' => $r['id'],
					'content' => $r['content'],
				);
			}
        	$data[] = array(
				'id' => $temp['id'],
				'content' => $temp['content'],
				'score' => $temp['score'],
				'correctAnswer' => $right,
				'questionItem' => $item,
				'check' => '',
			);
        }
        if(is_array($data)){
            $response = array(
                'code' => '0',
                'msg' => 'Success',
                'sum' => $sum,
                'data' => $data,
            );
        }
        else {
            $response = array(
                'code' => '1',
                'msg' => 'Failed',
                'sum' => '0',
                'data' => 'error',
            );
        }
        $this->output->myOutput($response);
	}

	function detail() {

		$id = $this->input->post('id');
		$question = $this->Question_model->getByID($id);
		$questionItem = $this->QuestionItem_model->getByquestionID($id);
		if($questionItem){
			foreach ($questionItem as $r){
				$data[] = array(
					'id' => $r['id'],
					'content' => $r['content'],
					'correct' => $r['correct'],
				);
			}
			$response = array(
	            'code' => '0',
	            'msg' => 'success',
	            'question' => $question['content'],
	            'score' => $question['score'],
	            'questionItem' => $data,
	        );
		} else {
			$response = array(
	            'code' => '0',
	            'msg' => 'success-empty content',
	            'question' => $question['content'],
	            'questionItem' => array(),
	        );
		}
		$this->output->myOutput($response);
	}

	//add or edit
	function saveQuestion() {

		$id = $this->input->post('id');
		$questionContent = $this->input->post('questionContent');
		$questionScore = $this->input->post('questionScore');
		$questionItem = $this->input->post('questionItem');
		if (!$questionContent || !$questionItem) {
			$response = array(
				'code' => '1',
				'msg' => 'insufficient info',
				'data' => array(),
			);
			$this->output->myOutput($response);
			return;
		}

		if(!$id){
			//add
			$questionID = $this->Question_model->addQuestion($questionContent,$questionScore);
			foreach ($questionItem as $r) {
				$result = $this->QuestionItem_model->addQuestionItem($questionID,$r['content'],$r['correct']);
			}

		} else {
			//edit
			$question = $this->Question_model->updateQuestion($id,$questionContent,$questionScore);
			foreach ($questionItem as $r) {
				$result = $this->QuestionItem_model->updateByQuestionItemID($r['id'],$r['content'],$r['correct']);
			}
		}
		
		$response = array(
			'code' => '0',
			'msg' => 'success',
			'data' => array(),
		);
		$this->output->myOutput($response);
	}

	function deleteQuestion() {

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
		$this->Question_model->del($id);
		$this->QuestionItem_model->delByquestionID($id);

		$response = array(
			'code' => '0',
			'msg' => 'success',
			'data' => array(),
		);
		$this->output->myOutput($response);
	}
}