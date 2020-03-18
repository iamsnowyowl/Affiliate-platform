<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_master_portfolio.master_portfolio_id' => 'master_portfolio_id',
		'v_master_portfolio.sub_schema_number' => 'sub_schema_number',
		'v_master_portfolio.sub_schema_name' => 'sub_schema_name',
		'v_master_portfolio.is_multiple' => 'is_mutiple',
		'v_master_portfolio.type' => 'type',
		'v_master_portfolio.form_type' => 'form_type',
		'v_master_portfolio.form_name' => 'form_name',
		'v_master_portfolio.apl_document_state' => 'apl_document_state',
		'v_master_portfolio.acs_document_state' => 'acs_document_state',
		'v_master_portfolio.document_state' => 'document_state',
		'v_master_portfolio.form_description' => 'form_description'
	),
	"optional" => array(
		'v_master_portfolio.created_by' => 'created_by',
		'v_master_portfolio.modified_by' => 'modified_by',
		'v_master_portfolio.created_date' => 'created_date',
		'v_master_portfolio.modified_date' => 'modified_date'
	)
);