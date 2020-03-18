<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_accessor_competence" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'certificate_file',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'verification_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim'
		)
	),
	"update_accessor_competence" => array(
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'certificate_file',
			'rules' => 'trim'
		),
		array(
			'field' => 'verification_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim'
		)

	)
);