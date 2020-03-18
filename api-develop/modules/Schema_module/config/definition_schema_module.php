<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_schema.schema_id' => 'schema_id',
		'v_schema.skkni' => 'skkni',
		'v_schema.skkni_year' => 'skkni_year',
		'v_schema.total_uk' => 'total_uk',
		'v_schema.schema_name' => 'schema_name'
	),
	"optional" => array(
		'v_schema.created_by' => 'created_by',
		'v_schema.modified_by' => 'modified_by',
		'v_schema.created_date' => 'created_date',
		'v_schema.modified_date' => 'modified_date'
	)
);