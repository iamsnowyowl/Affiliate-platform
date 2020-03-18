<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_audit_trail" => array(
		array(
			'field' => 'audit_trail_name',
			'rules' => 'trim|required'
		)
	),
	"update_audit_trail" => array(
		array(
			'field' => 'audit_trail_name',
			'rules' => 'trim'
		)
	)
);