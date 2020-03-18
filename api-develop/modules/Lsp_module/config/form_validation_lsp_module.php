<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_lsp" => array(
		array(
			'field' => 'lsp_code',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'lsp_name',
			'rules' => 'trim required'
		),
		array(
			'field' => 'leader_name',
			'rules' => 'trim required'
		),
		array(
			'field' => 'vice_name',
			'rules' => 'trim required'
		),
		array(
			'field' => 'address',
			'rules' => 'trim required'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim required'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim required'
		)
	),
	"update_lsp" => array(
		array(
			'field' => 'lsp_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'lsp_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'leader_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'vice_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim'
		)
	)
);