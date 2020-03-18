<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config["pk_use_ai"] = FALSE;
$config["soft_delete"] = TRUE;
$config["check_unique"] = FALSE;
$config["hard_delete_word"] = "HAPUS";

$config['base_path'] = getenv("FILE_PATH"); // no trailing slash
$config['image_library'] = 'gd2';
$config['path_destination'] = getenv("BASE_FILE_PATH"); // always beginning with slash and no trailing slash
$config['unique_path'] = "/assessment/origin"; // always beginning with slash and no trailing slash
$config['filename'] = ""; 
$config['thumb_path_destination'] = "/files"; // always beginning with slash and no trailing slash
$config['thumb_unique_path'] = "/assessment/thumb"; // always beginning with slash and no trailing slash
$config['thumb_filename'] = ""; 
$config['thumb_ext'] = "jpg"; 
$config['width'] = "width";
$config['height'] = "height";
$config['thumb'] = "thumb";
$config['create_thumb'] = TRUE;
$config['maintain_ratio'] = TRUE;
$config['default_width'] = 96;
$config['default_height'] = 96;

$config['apl01'] = [];
$config['apl01']['master_portfolio_id'] = '25901fa3-27e8-402c-b03a-4faf0be481cx';
$config['apl01']['is_multiple'] = 0;
$config['apl01']['type'] = 'UMUM';
$config['apl01']['document_state'] = 'ALL';
$config['apl01']['form_type'] = 'file';
$config['apl01']['form_name'] = 'apl01';
$config['apl01']['form_description'] = 'Berkas APL 01';
$config['apl01']['acs_document_state'] = 'ALL';
$config['apl01']['apl_document_state'] = 'ALL';
