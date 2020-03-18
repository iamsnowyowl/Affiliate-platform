<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_document" => array(
		array(
			'field' => 'document_name',
			'rules' => 'trim|required'
		)
	),
	"update_document" => array(
		array(
			'field' => 'document_name',
			'rules' => 'trim'
		)
	)
);