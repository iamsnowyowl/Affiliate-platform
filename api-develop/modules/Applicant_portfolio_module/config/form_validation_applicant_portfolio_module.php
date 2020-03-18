<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_applicant_portfolio" => array(
		array(
			'field' => 'master_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'applicant_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'is_multiple',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_value',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_description',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'document_state',
			'rules' => 'trim'
		)
	),
	"create_custom_applicant_portfolio" => array(
		array(
			'field' => 'master_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'applicant_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'is_multiple',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_type',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_value',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_description',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'document_state',
			'rules' => 'trim'
		)
	),
	"update_applicant_portfolio" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'assessment_applicant_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'applicant_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'type',
			'rules' => 'trim'
		),
		array(
			'field' => 'is_multiple',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_value',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_description',
			'rules' => 'trim'
		),
		array(
			'field' => 'document_state',
			'rules' => 'trim'
		)
	)
);