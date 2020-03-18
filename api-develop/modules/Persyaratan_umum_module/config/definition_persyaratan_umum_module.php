<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_persyaratan_umum.row_id' => 'row_id',
		'tbl_persyaratan_umum.persyaratan_umum_id' => 'persyaratan_umum_id',
		'tbl_persyaratan_umum.master_portfolio_id' => 'master_portfolio_id',
		'tbl_persyaratan_umum.applicant_id' => 'applicant_id',
		'tbl_persyaratan_umum.form_value' => 'form_value',
		'tbl_persyaratan_umum.filename' => 'filename',
		'tbl_persyaratan_umum.mime_type' => 'mime_type',
		'tbl_persyaratan_umum.ext' => 'ext'
	),
	"optional" => array(
		'tbl_persyaratan_umum.created_by' => 'created_by',
		'tbl_persyaratan_umum.modified_by' => 'modified_by',
		'tbl_persyaratan_umum.created_date' => 'created_date',
		'tbl_persyaratan_umum.modified_date' => 'modified_date'
	)
);