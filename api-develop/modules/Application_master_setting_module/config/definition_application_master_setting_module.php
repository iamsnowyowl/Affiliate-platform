<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_application_master_setting.row_id' => 'row_id',
		'tbl_application_master_setting.application_id' => 'application_id',
		'tbl_application_master_setting.application_master_setting_id' => 'application_master_setting_id',
		'tbl_application_master_setting.application_master_setting_name' => 'application_master_setting_name',
		'tbl_application_master_setting.application_master_setting_type' => 'application_master_setting_type'
	),
	"optional" => array(
		'tbl_application_master_setting.created_by' => 'created_by',
		'tbl_application_master_setting.modified_by' => 'modified_by',
		'tbl_application_master_setting.created_date' => 'created_date',
		'tbl_application_master_setting.modified_date' => 'modified_date'
	)
);