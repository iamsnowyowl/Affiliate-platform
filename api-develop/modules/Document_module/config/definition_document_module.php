<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_document.document_id' => 'document_id',
		'v_document.document_name' => 'document_name'
	),
	"optional" => array(
		'v_document.created_by' => 'created_by',
		'v_document.modified_by' => 'modified_by',
		'v_document.created_date' => 'created_date',
		'v_document.modified_date' => 'modified_date'
	)
);