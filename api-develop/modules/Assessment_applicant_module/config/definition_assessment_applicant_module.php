<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
		
$config = array(
    "default" => array(
		'v_assessment_applicant.assessment_id' => 'assessment_id',
		'v_assessment_applicant.assessment_applicant_id' => 'assessment_applicant_id',
		'v_assessment_applicant.applicant_id' => 'applicant_id',
		'v_assessment_applicant.assessor_id' => 'assessor_id',
		'v_assessment_applicant.registration_number' => 'registration_number',
		'v_assessment_applicant.tuk_id' => 'tuk_id',
		'v_assessment_applicant.tuk_name' => 'tuk_name',
		'v_assessment_applicant.nik' => 'nik',
		'v_assessment_applicant.full_name' => 'full_name',
		'v_assessment_applicant.first_name' => 'first_name',
		'v_assessment_applicant.last_name' => 'last_name',
		'v_assessment_applicant.contact' => 'contact',
		'v_assessment_applicant.institution' => 'institution',
		'v_assessment_applicant.email' => 'email',
		'v_assessment_applicant.gender_code' => 'gender_code',
		'v_assessment_applicant.address' => 'address',
		'v_assessment_applicant.date_of_birth' => 'date_of_birth',
		'v_assessment_applicant.jobs_code' => 'jobs_code',
		'v_assessment_applicant.jobs_name' => 'jobs_name',
		'v_assessment_applicant.pendidikan_terakhir' => 'pendidikan_terakhir',
		'v_assessment_applicant.place_of_birth' => 'place_of_birth',
		'v_assessment_applicant.schema_label' => 'schema_label',
		'v_assessment_applicant.sub_schema_number' => 'sub_schema_number',
		'v_assessment_applicant.schema_label' => 'schema_label',
		'v_assessment_applicant.status_recomendation' => 'status_recomendation',
		'v_assessment_applicant.status_graduation' => 'status_graduation',
		'v_assessment_applicant.test_method' => 'test_method',
		'v_assessment_applicant.notes' => 'notes',
		'v_assessment_applicant.description_for_recomendation' => 'description_for_recomendation'
	),
	"optional" => array(
		'v_assessment_applicant.created_by' => 'created_by',
		'v_assessment_applicant.modified_by' => 'modified_by',
		'v_assessment_applicant.created_date' => 'created_date',
		'v_assessment_applicant.modified_date' => 'modified_date'
	),
	"default_assessment" => array(
		'v_assessment_by_applicant.assessment_id' => 'assessment_id',
		'v_assessment_by_applicant.applicant_id' => 'applicant_id',
		'v_assessment_by_applicant.assessor_id' => 'assessor_id',
		'v_assessment_by_applicant.tuk_id' => 'tuk_id',
		'v_assessment_by_applicant.tuk_name' => 'tuk_name',
		'v_assessment_by_applicant.title' => 'title',
		'v_assessment_by_applicant.notes' => 'notes',
		'v_assessment_by_applicant.last_activity_state' => 'last_activity_state',
		'v_assessment_by_applicant.last_activity_description' => 'last_activity_description',
		'v_assessment_by_applicant.address' => 'address',
		'v_assessment_by_applicant.longitude' => 'longitude',
		'v_assessment_by_applicant.latitude' => 'latitude',
		'v_assessment_by_applicant.start_date' => 'start_date',
		'v_assessment_by_applicant.end_date' => 'end_date',
		'v_assessment_by_applicant.pleno_date' => 'pleno_date',
		'v_assessment_by_applicant.request_date' => 'request_date',
		'v_assessment_by_applicant.sub_schema_number' => 'sub_schema_number',
		'v_assessment_by_applicant.assessor_id' => 'assessor_id',
		'v_assessment_by_applicant.admin_id' => 'admin_id',
		'v_assessment_by_applicant.pleno_id' => 'pleno_id',
		'v_assessment_by_applicant.request_letter_url' => 'request_letter_url'
	),
	"optional_assessment" => array(
		'v_assessment_by_applicant.created_by' => 'created_by',
		'v_assessment_by_applicant.modified_by' => 'modified_by',
		'v_assessment_by_applicant.created_date' => 'created_date',
		'v_assessment_by_applicant.modified_date' => 'modified_date'
	),
	"default_not_assign" => array(
		'v_user_applicant.user_id' => 'user_id',
		'v_user_applicant.first_name' => 'first_name',
		'v_user_applicant.last_name' => 'last_name'
	),
	"optional_not_assign" => array(
		'v_user_applicant.contact' => 'contact'
	)
);