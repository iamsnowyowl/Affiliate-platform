<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_tuk" => array(
		array(
			'field' => 'tuk_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'api_key',
			'rules' => 'trim|required|min_length[32]'
		),
		array(
			'field' => 'tuk_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'logo',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'description',
			'rules' => 'trim'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'tuk_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'number_sk',
			'rules' => 'trim'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim'
		)
	),
	"update_tuk" => array(
		array(
			'field' => 'tuk_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'logo',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim'
		),
		array(
			'field' => 'description',
			'rules' => 'trim'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'tuk_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'number_sk',
			'rules' => 'trim'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim'
		),
		array(
			'field' => 'deleted_at',
			'rules' => 'trim'
		),
		array(
			'field' => 'modified_by',
			'rules' => 'trim'
		)
	)
);