<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_letter.assessment_id' => 'assessment_id',
		'v_assessment_letter.assessment_letter_id' => 'assessment_letter_id',
		'v_assessment_letter.assessment_letter_name' => 'assessment_letter_name',
		'v_assessment_letter.reference_id' => 'reference_id',
		'v_assessment_letter.letter_type' => 'letter_type',
		'v_assessment_letter.letter_number' => 'letter_number',
		'v_assessment_letter.signature_flag' => 'signature_flag',
		'v_assessment_letter.reason_declined_signature' => 'reason_declined_signature',
		'v_assessment_letter.file_id' => 'file_id',
		'v_assessment_letter.url' => 'url'
	),
	"optional" => array(
		'v_assessment_letter.created_by' => 'created_by',
		'v_assessment_letter.modified_by' => 'modified_by',
		'v_assessment_letter.created_date' => 'created_date',
		'v_assessment_letter.modified_date' => 'modified_date'
	)
);