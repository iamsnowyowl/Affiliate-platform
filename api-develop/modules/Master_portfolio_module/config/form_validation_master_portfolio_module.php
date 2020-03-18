<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_master_portfolio" => array(
		array(
			'field' => 'master_portfolio_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'is_multiple',
			'rules' => 'trim'
		),
		array(
			'field' => 'type',
			'rules' => 'trim|required|in_list[UMUM,DASAR]'
		),
		array(
			'field' => 'form_type',
			'rules' => 'trim|required|in_list[file_online,file,checkbox,text]'
		),
		array(
			'field' => 'form_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'form_description',
			'rules' => 'trim'
		),
		array(
			'field' => 'document_state',
			'rules' => 'trim'
		),
		array(
			'field' => 'apl_document_state',
			'rules' => 'trim'
		),
		array(
			'field' => 'acs_document_state',
			'rules' => 'trim'
		)
	),
	"update_master_portfolio" => array(
		array(
			'field' => 'master_portfolio_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'is_multiple',
			'rules' => 'trim'
		),
		array(
			'field' => 'type',
			'rules' => 'trim|in_list[UMUM,DASAR]'
		),
		array(
			'field' => 'form_type',
			'rules' => 'trim|in_list[file,checkbox,text]'
		),
		array(
			'field' => 'form_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'form_description',
			'rules' => 'trim'
		),
		array(
			'field' => 'document_state',
			'rules' => 'trim'
		),
		array(
			'field' => 'apl_document_state',
			'rules' => 'trim'
		),
		array(
			'field' => 'acs_document_state',
			'rules' => 'trim'
		)
	)
);