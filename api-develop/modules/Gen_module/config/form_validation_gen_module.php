<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_gen" => array(
		array(
			'field' => 'gen_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'gen_name',
			'rules' => 'trim|required'
		)
	),
	"update_gen" => array(
		array(
			'field' => 'gen_name',
			'rules' => 'trim'
		)
	)
);