<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_tuk.tuk_id' => 'tuk_id',
		'v_tuk.tuk_name' => 'tuk_name',
		'v_tuk.address' => 'address',
		'v_tuk.contact' => 'contact',
		'v_tuk.description' => 'description',
		'v_tuk.longitude' => 'longitude',
		'v_tuk.latitude' => 'latitude'
	),
	"optional" => array(
		'v_tuk.created_by' => 'created_by',
		'v_tuk.modified_by' => 'modified_by',
		'v_tuk.created_date' => 'created_date',
		'v_tuk.modified_date' => 'modified_date'
	)
);