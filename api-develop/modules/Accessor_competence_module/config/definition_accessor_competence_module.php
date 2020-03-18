<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_accessor_competence.accessor_competence_id' => 'accessor_competence_id',
		'v_accessor_competence.user_id' => 'user_id',
		'v_accessor_competence.first_name' => 'first_name',
		'v_accessor_competence.last_name' => 'last_name',
		'v_accessor_competence.sub_schema_number' => 'sub_schema_number',
		'v_accessor_competence.sub_schema_name' => 'sub_schema_name',
		'v_accessor_competence.certificate_file' => 'certificate_file',
		'v_accessor_competence.verification_date' => 'verification_date',
		'v_accessor_competence.verification_flag' => 'verification_flag',
		'v_accessor_competence.expired_date' => 'expired_date',
		'v_accessor_competence.expired_flag' => 'expired_flag'
	),
	"optional" => array(
		'v_accessor_competence.created_by' => 'created_by',
		'v_accessor_competence.modified_by' => 'modified_by',
		'v_accessor_competence.created_date' => 'created_date',
		'v_accessor_competence.modified_date' => 'modified_date'
	)
);