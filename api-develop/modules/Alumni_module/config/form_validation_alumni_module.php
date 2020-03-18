<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_alumni" => array(
		array(
			'field' => 'alumni_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'competence',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'certificate_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'register_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'location_assessment',
			'rules' => 'trim'
		),
		array(
			'field' => 'assessment_date',
			'rules' => 'trim'
		),
		array(
			'field' => 'tuk',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_date_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'email',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'company_address',
			'rules' => 'trim'
		),
		array(
			'field' => 'description',
			'rules' => 'trim'
		),
		array(
			'field' => 'blanko_number',
			'rules' => 'trim'
		)
	),
	"update_alumni" => array(
		array(
			'field' => 'alumni_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'competence',
			'rules' => 'trim'
		),
		array(
			'field' => 'certificate_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'register_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'location_assessment',
			'rules' => 'trim'
		),
		array(
			'field' => 'assessment_date',
			'rules' => 'trim'
		),
		array(
			'field' => 'tuk',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_date_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'email',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'company_address',
			'rules' => 'trim'
		),
		array(
			'field' => 'description',
			'rules' => 'trim'
		),
		array(
			'field' => 'blanko_number',
			'rules' => 'trim'
		)
	)
);