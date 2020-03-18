<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_article.article_id' => 'article_id',
		'v_article.media' => 'media',
		'v_article.author' => 'author',
		'v_article.title' => 'title',
		'v_article.tags' => 'tags',
		'v_article.creator' => 'creator',
		'v_article.categories' => 'categories',
		'v_article.content' => 'content'
	),
	"optional" => array(
		'v_article.created_by' => 'created_by',
		'v_article.modified_by' => 'modified_by',
		'v_article.created_date' => 'created_date',
		'v_article.modified_date' => 'modified_date'
	)
);