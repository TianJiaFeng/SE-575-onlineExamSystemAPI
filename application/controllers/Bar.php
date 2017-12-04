<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bar extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	/**
	 * @api {post} Bar/getBar
	 * @apiName getBar
	 * @apiGroup Bar
	 */
	function getBar() {
		$menu[0] = array(
			'id' => 'grade',
			'txt' => 'grade',
			'link' => 'grade.html',
		);

		$menu[1] = array(
			'id' => 'question',
			'txt' => 'question',
			'link' => 'question.html',
		);

		$response = array(
			'code' => '0',
			'msg' => '成功',
			'data' => $menu,
		);
		$this->output->myOutput($response);
	}

}