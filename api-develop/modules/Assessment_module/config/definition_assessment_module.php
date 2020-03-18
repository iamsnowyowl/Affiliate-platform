<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment.assessment_id' => 'assessment_id',
		'v_assessment.tuk_id' => 'tuk_id',
		'v_assessment.tuk_name' => 'tuk_name',
		'v_assessment.title' => 'title',
		'v_assessment.notes' => 'notes',
		'v_assessment.last_activity_state' => 'last_activity_state',
		'v_assessment.last_activity_description' => 'last_activity_description',
		'v_assessment.address' => 'address',
		'v_assessment.schema_text' => 'schema_text',
		'v_assessment.longitude' => 'longitude',
		'v_assessment.latitude' => 'latitude',
		'v_assessment.start_date' => 'start_date',
		'v_assessment.end_date' => 'end_date',
		'v_assessment.pleno_date' => 'pleno_date',
		'v_assessment.request_date' => 'request_date',
		'v_assessment.assessor_id' => 'assessor_id',
		'v_assessment.admin_id' => 'admin_id',
		'v_assessment.sub_schema_number' => 'sub_schema_number',
		'v_assessment.schema_label' => 'schema_label',
		'v_assessment.pleno_id' => 'pleno_id',
		'v_assessment.request_letter_url' => 'request_letter_url'
	),
	"optional" => array(
		'v_assessment.archive_flag' => 'archive_flag',
		'v_assessment.created_by' => 'created_by',
		'v_assessment.modified_by' => 'modified_by',
		'v_assessment.created_date' => 'created_date',
		'v_assessment.modified_date' => 'modified_date'
	),
	"default_non_admin" => array(
		'v_assessment_owner_full.assessment_id' => 'assessment_id',
		'v_assessment_owner_full.tuk_id' => 'tuk_id',
		'v_assessment_owner_full.tuk_name' => 'tuk_name',
		'v_assessment_owner_full.title' => 'title',
		'v_assessment_owner_full.notes' => 'notes',
		'v_assessment_owner_full.last_activity_state' => 'last_activity_state',
		'v_assessment_owner_full.last_activity_description' => 'last_activity_description',
		'v_assessment_owner_full.address' => 'address',
		'v_assessment_owner_full.request_letter_url' => 'request_letter_url',
		'v_assessment_owner_full.longitude' => 'longitude',
		'v_assessment_owner_full.latitude' => 'latitude',
		'v_assessment_owner_full.start_date' => 'start_date',
		'v_assessment_owner_full.end_date' => 'end_date',
		'v_assessment_owner_full.pleno_date' => 'pleno_date',
		'v_assessment_owner_full.request_date' => 'request_date',
		'v_assessment_owner_full.identifier' => 'identifier',
		'v_assessment_owner_full.sub_schema_number' => 'sub_schema_number',
		'v_assessment_owner_full.is_user_assessment' => 'is_user_assessment',
		'v_assessment_owner_full.is_user_pleno' => 'is_user_pleno'
	),
	"optional_non_admin" => array(
		'v_assessment_owner_full.created_by' => 'created_by',
		'v_assessment_owner_full.modified_by' => 'modified_by',
		'v_assessment_owner_full.created_date' => 'created_date',
		'v_assessment_owner_full.modified_date' => 'modified_date'
	),
	"default_system" => array(
		'v_assessment.assessment_id' => 'assessment_id',
		'v_assessment.gdrive_file_id' => 'gdrive_file_id',
		'v_assessment.gdrive_letter_id' => 'gdrive_letter_id'
	),
	"optional_system" => array(
		'v_assessment.created_by' => 'created_by',
		'v_assessment.modified_by' => 'modified_by',
		'v_assessment.created_date' => 'created_date',
		'v_assessment.modified_date' => 'modified_date'
	),
	"default_deleted_list" => array(
		'v_assessment_deleted.row_id' => 'row_id',
        'v_assessment_deleted.assessment_id' => 'assessment_id',
        'v_assessment_deleted.sub_schema_number' => 'sub_schema_number',
        'v_assessment_deleted.gdrive_file_id' => 'gdrive_file_id',
        'v_assessment_deleted.gdrive_letter_id' => 'gdrive_letter_id',
        'v_assessment_deleted.tuk_id' => 'tuk_id',
        'v_assessment_deleted.title' => 'title',
        'v_assessment_deleted.notes' => 'notes',
        'v_assessment_deleted.last_activity_state' => 'last_activity_state',
        'v_assessment_deleted.last_activity_description' => 'last_activity_description',
        'v_assessment_deleted.address' => 'address',
        'v_assessment_deleted.request_letter_url' => 'request_letter_url',
        'v_assessment_deleted.longitude' => 'longitude',
        'v_assessment_deleted.latitude' => 'latitude',
        'v_assessment_deleted.schema_label' => 'schema_label',
        'v_assessment_deleted.start_date' => 'start_date',
        'v_assessment_deleted.end_date' => 'end_date',
        'v_assessment_deleted.pleno_date' => 'pleno_date',
        'v_assessment_deleted.request_date' => 'request_date'
	),
	"optional_deleted_list" => array(
		'v_assessment_deleted.deleted_at' => 'deleted_at',
		'v_assessment_deleted.created_by' => 'created_by',
		'v_assessment_deleted.modified_by' => 'modified_by',
		'v_assessment_deleted.created_date' => 'created_date',
		'v_assessment_deleted.modified_date' => 'modified_date',
		'v_assessment_deleted.archive_flag' => 'archive_flag'
	)
);