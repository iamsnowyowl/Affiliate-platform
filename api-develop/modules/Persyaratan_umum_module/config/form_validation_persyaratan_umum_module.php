<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_persyaratan_umum" => array(
		array(
			'field' => 'persyaratan_umum_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'master_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_value',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'mime_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'ext',
			'rules' => 'trim'
		)
	),
	"update_persyaratan_umum" => array(
		array(
			'field' => 'form_value',
			'rules' => 'trim'
		),
		array(
			'field' => 'filename',
			'rules' => 'trim'
		),
		array(
			'field' => 'mime_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'ext',
			'rules' => 'trim'
		)
	)
);