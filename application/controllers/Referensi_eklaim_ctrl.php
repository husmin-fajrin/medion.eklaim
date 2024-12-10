<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_eklaim_ctrl extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function pencarian()
	{
		$param = json_encode(
			array(
				"metadata" => array(
					"method" => $this->input->post('method')
				),
				"data" => array(
					"keyword" => $this->input->post('keyword')
				)
			)
		);

		$this->load->helper('bridging');
		$result = bridging_eklaim($param);
		
		if ($result['metadata']['code'] == '200') {
			$return['items'] = $result['response'];
			$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'];
			$return['success'] = true;
		} else {
			$return['message'] = $result['metadata']['message'] . '<br>Code: ' . $result['metadata']['code'] . '<br>Error No: ' . $result['metadata']['error_no'];
			$return['success'] = false;
		}
		echo json_encode($return);
	}
}