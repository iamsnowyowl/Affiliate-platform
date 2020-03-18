<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		// 'v_application_setting.row_id' => 'row_id',
		'v_application_setting.application_id' => 'application_id',
		'v_application_setting.application_setting_id' => 'application_setting_id',
		'v_application_setting.application_master_setting_id' => 'application_master_setting_id',
		'v_application_setting.application_master_setting_name' => 'application_master_setting_name',
		'v_application_setting.application_master_setting_type' => 'application_master_setting_type',
		'v_application_setting.application_id' => 'application_id',
		'v_application_setting.application_name' => 'application_name',
		'v_application_setting.setting_value' => 'setting_value',
		'v_application_setting.reference_id' => 'reference_id'
	),
	"optional" => array(
		'v_application_setting.created_by' => 'created_by',
		'v_application_setting.modified_by' => 'modified_by',
		'v_application_setting.created_date' => 'created_date',
		'v_application_setting.modified_date' => 'modified_date'
	)
);