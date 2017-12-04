<?php

class MY_Output extends CI_Output {

	public function __construct() {
		parent::__construct();
	}

	public function setAccessHeader() {
		$this->set_header('Access-Control-Allow-Origin:*');
		$this->set_header('Access-Control-Allow-Methods:POST');
		$this->set_header('Access-Control-Allow-Headers:x-requested-with,content-type');
		return $this;
	}

	public function myOutput($response) {
		$str = json_encode($response);

		$this->setAccessHeader()
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($str);
	}

}