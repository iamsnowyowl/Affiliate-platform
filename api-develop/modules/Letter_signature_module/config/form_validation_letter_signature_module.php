<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_letter_signature" => array(
		array(
			'field' => 'media',
			'rules' => 'trim'
		),
		array(
			'field' => 'mime_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'letter_signature_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'letter_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'letter_signature_name',
			'rules' => 'trim'
		)
	),
	"update_letter_signature" => array(
		array(
			'field' => 'media',
			'rules' => 'trim'
		),
		array(
			'field' => 'mime_type',
			'rules' => 'trim'
		),
		array(
			'field' => 'letter_signature_name',
			'rules' => 'trim'
		)
	)
);