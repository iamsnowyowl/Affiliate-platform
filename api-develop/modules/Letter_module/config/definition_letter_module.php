<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_letter.letter_id' => 'letter_id',
		'tbl_letter.letter_name' => 'letter_name',
		'tbl_letter.letter_lable' => 'letter_lable',
		'tbl_letter.available_variable' => 'available_variable',
		'tbl_letter.mime_type' => 'mime_type',
		'tbl_letter.filename' => 'filename'
	),
	"optional" => array(
		'tbl_letter.created_by' => 'created_by',
		'tbl_letter.modified_by' => 'modified_by',
		'tbl_letter.created_date' => 'created_date',
		'tbl_letter.modified_date' => 'modified_date'
	),
	"default_download" => array(
		'tbl_letter.letter_id' => 'letter_id',
		'tbl_letter.letter_name' => 'letter_name',
		'tbl_letter.letter_lable' => 'letter_lable',
		'tbl_letter.available_variable' => 'available_variable',
		'tbl_letter.mime_type' => 'mime_type',
		'tbl_letter.filename' => 'filename',
		'tbl_letter.file' => 'file'
	),
	"optional_download" => array(
		'tbl_letter.created_by' => 'created_by',
		'tbl_letter.modified_by' => 'modified_by',
		'tbl_letter.created_date' => 'created_date',
		'tbl_letter.modified_date' => 'modified_date'
	),
	"default_deleted_list" => array(
		'v_letter_deleted.row_id' => 'row_id',
        'v_letter_deleted.letter_id' => 'letter_id',
        'v_letter_deleted.letter_name' => 'letter_name',
        'v_letter_deleted.letter_lable' => 'letter_lable',
        'v_letter_deleted.filename' => 'filename',
        'v_letter_deleted.file' => 'file',
        'v_letter_deleted.mime_type' => 'mime_type'
	),
	"optional_deleted_list" => array(
		'v_letter_deleted.created_by' => 'created_by',
        'v_letter_deleted.modified_by' => 'modified_by',
        'v_letter_deleted.deleted_at' => 'deleted_at',
        'v_letter_deleted.created_date' => 'created_date',
        'v_letter_deleted.modified_date' => 'modified_date'
	)
);