<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_kuk_section.row_id' => 'row_id',
		'tbl_kuk_section.kuk_id' => 'kuk_id',
		'tbl_kuk_section.kuk_section_id' => 'kuk_section_id',
		'tbl_kuk_section.kuk_section_name' => 'kuk_section_name'
	),
	"optional" => array(
		'tbl_kuk_section.created_by' => 'created_by',
		'tbl_kuk_section.modified_by' => 'modified_by',
		'tbl_kuk_section.created_date' => 'created_date',
		'tbl_kuk_section.modified_date' => 'modified_date'
	)
);