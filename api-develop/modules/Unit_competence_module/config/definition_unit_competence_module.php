<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_unit_competence.unit_competence_id' => 'unit_competence_id',
		'v_unit_competence.unit_code' => 'unit_code',
		'v_unit_competence.title' => 'title',
		'v_unit_competence.skkni' => 'skkni',
		'v_unit_competence.sub_schema_number' => 'sub_schema_number'
	),
	"optional" => array(
		'v_unit_competence.created_by' => 'created_by',
		'v_unit_competence.modified_by' => 'modified_by',
		'v_unit_competence.created_date' => 'created_date',
		'v_unit_competence.modified_date' => 'modified_date'
	)
);