<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_article" => array(
		array(
			'field' => 'article_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'media',
			'rules' => 'trim'
		),
		array(
			'field' => 'author',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'title',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'tags',
			'rules' => 'trim'
		),
		array(
			'field' => 'content',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'creator',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'categories',
			'rules' => 'trim|required'
		)
	),
	"update_article" => array(
		array(
			'field' => 'author',
			'rules' => 'trim'
		),
		array(
			'field' => 'title',
			'rules' => 'trim'
		),
		array(
			'field' => 'tags',
			'rules' => 'trim'
		),
		array(
			'field' => 'content',
			'rules' => 'trim'
		),
		array(
			'field' => 'creator',
			'rules' => 'trim'
		),
		array(
			'field' => 'categories',
			'rules' => 'trim'
		),
		array(
			'field' => 'media',
			'rules' => 'trim'
		)
	)
);