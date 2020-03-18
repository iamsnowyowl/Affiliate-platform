<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_applicant" => array(
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
			'rules' => 'trim'
		),
		array(
			'field' => 'assessor_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'tuk_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'test_method',
			'rules' => 'trim'
		),
		array(
			'field' => 'join_request_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'full_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'date_of_birth',
			'rules' => 'trim'
		)
	),
	"update_assessment_applicant" => array(
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'applicant_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'assessor_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'status_recomendation',
			'rules' => 'trim'
		),
		array(
			'field' => 'status_graduation',
			'rules' => 'trim'
		),
		array(
			'field' => 'description_for_recomendation',
			'rules' => 'trim'
		),
		array(
			'field' => 'test_method',
			'rules' => 'trim'
		),
		array(
			'field' => 'full_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'date_of_birth',
			'rules' => 'trim'
		)
	),
	"create_assessment_applicant_non_account" => array(
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
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'full_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'date_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'jobs_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'pendidikan_terakhir',
			'rules' => 'trim'
		)
	)
);