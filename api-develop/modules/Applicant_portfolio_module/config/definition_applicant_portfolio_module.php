<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
        'v_applicant_portfolio.master_portfolio_id' => 'master_portfolio_id',
    	'v_applicant_portfolio.applicant_portfolio_id' => 'applicant_portfolio_id',
        'v_applicant_portfolio.assessment_id' => 'assessment_id',
        'v_applicant_portfolio.assessment_applicant_id' => 'assessment_applicant_id',
        'v_applicant_portfolio.applicant_id' => 'applicant_id',
        'v_applicant_portfolio.sub_schema_number' => 'sub_schema_number',
        'v_applicant_portfolio.type' => 'type',
        'v_applicant_portfolio.is_multiple' => 'is_multiple',
        'v_applicant_portfolio.form_type' => 'form_type',
        'v_applicant_portfolio.form_name' => 'form_name',
        'v_applicant_portfolio.form_value' => 'form_value',
        'v_applicant_portfolio.form_description' => 'form_description',
        'v_applicant_portfolio.acs_document_state' => 'acs_document_state',
        'v_applicant_portfolio.apl_document_state' => 'apl_document_state',
        'v_applicant_portfolio.document_state' => 'document_state',
        'v_applicant_portfolio.mime_type' => 'mime_type',
        'v_applicant_portfolio.filename' => 'filename',
        'v_applicant_portfolio.ext' => 'ext'
	),
	"optional" => array(
		'v_applicant_portfolio.created_by' => 'created_by',
		'v_applicant_portfolio.modified_by' => 'modified_by',
		'v_applicant_portfolio.created_date' => 'created_date',
		'v_applicant_portfolio.modified_date' => 'modified_date'
	)
);