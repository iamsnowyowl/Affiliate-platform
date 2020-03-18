<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_tuk.tuk_id' => 'tuk_id',
		'v_tuk.tuk_name' => 'tuk_name',
		'v_tuk.logo' => 'logo',
		'v_tuk.address' => 'address',
		'v_tuk.contact' => 'contact',
		'v_tuk.email' => 'email',
		'v_tuk.description' => 'description',
		'v_tuk.longitude' => 'longitude',
		'v_tuk.latitude' => 'latitude',
		'v_tuk.tuk_type' => 'tuk_type',
		'v_tuk.number_sk' => 'number_sk',
		'v_tuk.expired_date' => 'expired_date',
		'v_tuk.api_key' => 'api_key'
	),
	"optional" => array(
		'v_tuk.created_by' => 'created_by',
		'v_tuk.modified_by' => 'modified_by',
		'v_tuk.created_date' => 'created_date',
		'v_tuk.modified_date' => 'modified_date'
	),
	"default_deleted_list"	=>	array(
		'v_tuk_deleted.tuk_id' => 'tuk_id',
        'v_tuk_deleted.row_id' => 'row_id',
        'v_tuk_deleted.api_key' => 'api_key',
        'v_tuk_deleted.number_sk' => 'number_sk',
        'v_tuk_deleted.tuk_type' => 'tuk_type',
        'v_tuk_deleted.tuk_name' => 'tuk_name',
        'v_tuk_deleted.logo' => 'logo',
        'v_tuk_deleted.address' => 'address',
        'v_tuk_deleted.longitude' => 'longitude',
        'v_tuk_deleted.latitude' => 'latitude',
        'v_tuk_deleted.contact' => 'contact',
        'v_tuk_deleted.email' => 'email',
        'v_tuk_deleted.description' => 'description',
        'v_tuk_deleted.created_by' => 'created_by'
	),
	"optional_deleted_list"	=>	array(
		'v_tuk_deleted.modified_by' => 'modified_by',
        'v_tuk_deleted.deleted_at' => 'deleted_at',
        'v_tuk_deleted.created_date' => 'created_date',
        'v_tuk_deleted.modified_date' => 'modified_date',
        'v_tuk_deleted.expired_date' => 'expired_date'
	)
);