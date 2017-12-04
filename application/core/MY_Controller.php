<?php
if (!defined('BASEPATH'))exit ('No direct script access allowed');

/**
 * Class MY_Controller
 * CI_Controller
 */
class MY_Controller extends CI_Controller {

	var $isIframe = false;

	function __construct() {
		parent::__construct();
		// 验证iframe调用
		$this->load->model('common/MY_model');
	}
}