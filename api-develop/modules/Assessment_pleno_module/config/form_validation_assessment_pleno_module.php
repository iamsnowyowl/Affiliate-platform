<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_pleno" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_pleno_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'pleno_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'position',
			'rules' => 'trim|required|in_list[ketua,anggota]'
		)
	),
	"update_assessment_pleno" => array(
		array(
			'field' => 'pleno_id',
			'rules' => 'trim'
		)
	)
);