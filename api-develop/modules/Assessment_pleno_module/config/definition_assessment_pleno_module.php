<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_pleno.assessment_id' => 'assessment_id',
		'v_assessment_pleno.assessment_pleno_id' => 'assessment_pleno_id',
		'v_assessment_pleno.pleno_id' => 'pleno_id',
		'v_assessment_pleno.position' => 'position',
		'v_assessment_pleno.first_name' => 'first_name',
		'v_assessment_pleno.last_name' => 'last_name',
		'v_assessment_pleno.contact' => 'contact',
		'v_assessment_pleno.signature' => 'signature'
	),
	"optional" => array(
		'v_assessment_pleno.created_by' => 'created_by',
		'v_assessment_pleno.modified_by' => 'modified_by',
		'v_assessment_pleno.created_date' => 'created_date',
		'v_assessment_pleno.modified_date' => 'modified_date'
	)
);