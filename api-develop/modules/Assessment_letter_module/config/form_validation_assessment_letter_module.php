<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_letter" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_letter_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_letter_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'reference_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'letter_type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'file_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'url',
			'rules' => 'trim'
		)
	),
	"update_assessment_letter" => array(
		array(
			'field' => 'assessment_letter_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'signature_flag',
			'rules' => 'trim'
		),
		array(
			'field' => 'letter_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'reason_declined_signature',
			'rules' => 'trim'
		)
	)
);