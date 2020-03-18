<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_certificate" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_certificate_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_certificate_name',
			'rules' => 'trim|required'
		)
	),
	"update_assessment_certificate" => array(
		array(
			'field' => 'assessment_certificate_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'assessment_certificate_name',
			'rules' => 'trim'
		)
	)
);