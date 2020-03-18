<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_gen.gen_id' => 'gen_id',
		'v_gen.gen_name' => 'gen_name'
	),
	"optional" => array(
		'v_gen.created_by' => 'created_by',
		'v_gen.modified_by' => 'modified_by',
		'v_gen.created_date' => 'created_date',
		'v_gen.modified_date' => 'modified_date'
	)
);