<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_background_worker.background_worker_id' => 'background_worker_id',
		'v_background_worker.background_worker_name' => 'background_worker_name'
	),
	"optional" => array(
		'v_background_worker.created_by' => 'created_by',
		'v_background_worker.modified_by' => 'modified_by',
		'v_background_worker.created_date' => 'created_date',
		'v_background_worker.modified_date' => 'modified_date'
	)
);