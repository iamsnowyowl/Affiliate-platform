<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_sub_schema.schema_id' => 'schema_id',
		'v_sub_schema.sub_schema_id' => 'sub_schema_id',
		'v_sub_schema.sub_schema_number' => 'sub_schema_number',
		'v_sub_schema.sub_schema_name' => 'sub_schema_name',
		'v_sub_schema.skkni' => 'skkni',
		'v_sub_schema.skkk_year' => 'skkk_year',
		'v_sub_schema.template' => 'template'
	),
	"optional" => array(
		'v_sub_schema.created_by' => 'created_by',
		'v_sub_schema.modified_by' => 'modified_by',
		'v_sub_schema.created_date' => 'created_date',
		'v_sub_schema.modified_date' => 'modified_date'
	),
	"default_full_schema" => array(
		'v_schema_full.schema_id' => 'schema_id',
		'v_schema_full.sub_schema_id' => 'sub_schema_id',
		'v_schema_full.total_uk' => 'total_uk',
		'v_schema_full.schema_label' => 'schema_label',
		'v_schema_full.schema_name' => 'schema_name',
		'v_schema_full.sub_schema_number' => 'sub_schema_number',
		'v_schema_full.sub_schema_name' => 'sub_schema_name',
		'v_schema_full.skkni' => 'skkni',
		'v_schema_full.skkk_year' => 'skkk_year',
		'v_schema_full.template' => 'template'
	),
	"optional_full_schema" => array(
		'v_schema_full.created_by' => 'created_by',
		'v_schema_full.modified_by' => 'modified_by',
		'v_schema_full.created_date' => 'created_date',
		'v_schema_full.modified_date' => 'modified_date'
	)
);