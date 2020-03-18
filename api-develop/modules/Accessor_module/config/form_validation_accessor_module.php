<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_accessor" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|is_natural|required'
		),
		array(
			'field' => 'registration_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'npwp',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'integrity_pact',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'certificate',
			'rules' => 'trim'
		)
	),
	"update_accessor" => array(
		array(
			'field' => 'npwp',
			'rules' => 'trim'
		),
		array(
			'field' => 'registration_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'integrity_pact',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'certificate',
			'rules' => 'trim'
		)
	)
);