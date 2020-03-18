<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_submodule" => array(
		array(
			'field' => 'submodule_name',
			'rules' => 'trim|required'
		)
	),
	"update_submodule" => array(
		array(
			'field' => 'submodule_name',
			'rules' => 'trim'
		)
	)
);