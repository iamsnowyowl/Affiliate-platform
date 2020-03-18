<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_kuk_section_detail.row_id' => 'row_id',
		'tbl_kuk_section_detail.number' => 'number',
		'tbl_kuk_section_detail.kuk_section_id' => 'kuk_section_id',
		'tbl_kuk_section_detail.kuk_section_detail_id' => 'kuk_section_detail_id',
		'tbl_kuk_section_detail.question' => 'question',
		'tbl_kuk_section_detail.answer' => 'answer'
	),
	"optional" => array(
		'tbl_kuk_section_detail.created_by' => 'created_by',
		'tbl_kuk_section_detail.modified_by' => 'modified_by',
		'tbl_kuk_section_detail.created_date' => 'created_date',
		'tbl_kuk_section_detail.modified_date' => 'modified_date'
	)
);