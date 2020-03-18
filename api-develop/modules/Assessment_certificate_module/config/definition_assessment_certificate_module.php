<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_certificate.row_id' => 'row_id',
		'v_assessment_certificate.assessment_id' => 'assessment_id',
		'v_assessment_certificate.assessment_certificate_id' => 'assessment_certificate_id',
		'v_assessment_certificate.assessment_applicant_id' => 'assessment_applicant_id',
		'v_assessment_certificate.sub_schema_number' => 'sub_schema_number',
		'v_assessment_certificate.is_print' => 'is_print',
		'v_assessment_certificate.assessment_certificate_name' => 'assessment_certificate_name'
	),
	"optional" => array(
		'v_assessment_certificate.created_by' => 'created_by',
		'v_assessment_certificate.modified_by' => 'modified_by',
		'v_assessment_certificate.created_date' => 'created_date',
		'v_assessment_certificate.modified_date' => 'modified_date'
	)
);