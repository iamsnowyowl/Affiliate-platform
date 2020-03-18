<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_lsp.lsp_code' => 'lsp_code',
		'v_lsp.lsp_name' => 'lsp_name',
		'v_lsp.leader_name' => 'leader_name',
		'v_lsp.vice_name' => 'vice_name',
		'v_lsp.address' => 'address',
		'v_lsp.longitude' => 'longitude',
		'v_lsp.latitude' => 'latitude'
	),
	"optional" => array(
		'v_lsp.created_by' => 'created_by',
		'v_lsp.modified_by' => 'modified_by',
		'v_lsp.created_date' => 'created_date',
		'v_lsp.modified_date' => 'modified_date'
	)
);