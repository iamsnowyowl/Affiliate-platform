<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_audit_trail.audit_trail_id' => 'audit_trail_id',
		'v_audit_trail.audit_trail_name' => 'audit_trail_name'
	),
	"optional" => array(
		'v_audit_trail.created_by' => 'created_by',
		'v_audit_trail.modified_by' => 'modified_by',
		'v_audit_trail.created_date' => 'created_date',
		'v_audit_trail.modified_date' => 'modified_date'
	)
);