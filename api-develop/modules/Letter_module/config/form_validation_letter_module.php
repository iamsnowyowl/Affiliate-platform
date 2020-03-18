<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"update_letter" => array(
		array(
			'field' => 'file',
			'rules' => 'required|trim'
		),
		array(
			'field' => 'mime_type',
			'rules' => 'required|trim'
		),
		array(
			'field' => 'filename',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'letter_lable',
			'rules' => 'trim'
		)
	),
	"update_deleted_by_id" => array(
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