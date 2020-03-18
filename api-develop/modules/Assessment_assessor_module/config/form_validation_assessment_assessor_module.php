<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_assessor" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_assessor_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessor_id',
			'rules' => 'trim|required'
		)
	),
	"update_assessment_assessor" => array(
		array(
			'field' => 'assessor_id',
			'rules' => 'trim'
		)
	)
);