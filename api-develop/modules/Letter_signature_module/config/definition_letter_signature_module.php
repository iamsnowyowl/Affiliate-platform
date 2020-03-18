<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_letter_signature.letter_signature_id' => 'letter_signature_id',
		'v_letter_signature.letter_id' => 'letter_id',
		'v_letter_signature.letter_signature_name' => 'letter_signature_name',
		'v_letter_signature.mime_type' => 'mime_type',
		'v_letter_signature.media' => 'media'
	),
	"optional" => array(
		'v_letter_signature.created_by' => 'created_by',
		'v_letter_signature.modified_by' => 'modified_by',
		'v_letter_signature.created_date' => 'created_date',
		'v_letter_signature.modified_date' => 'modified_date'
	)
);