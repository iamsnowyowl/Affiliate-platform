<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_background_worker" => array(
		array(
			'field' => 'background_worker_name',
			'rules' => 'trim|required'
		)
	),
	"update_background_worker" => array(
		array(
			'field' => 'background_worker_name',
			'rules' => 'trim'
		)
	)
);