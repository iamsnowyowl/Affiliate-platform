<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// only string allowed
$config = array();
// $config['base_path'] = rtrim(FCPATH, "/"); // no trailing slash
$base_path = getenv("FILE_PATH");

/** required field
* base_path
* image_library
* path_destination
* ext
* thumb_path_destination
* width
* height
*
*/

$config['default']['base_path'] = $base_path; // no trailing slash
$config['default']['image_library'] = 'gd2';
$config['default']['path_destination'] = "/content"; // always beginning with slash and no trailing slash
$config['default']['unique_path'] = ""; // always beginning with slash and no trailing slash
$config['default']['filename'] = ""; // always beginning with slash and no trailing slash
$config['default']['ext'] = "jpg"; 
$config['default']['thumb_path_destination'] = "/thumb/content"; // always beginning with slash and no trailing slash
$config['default']['thumb_unique_path'] = ""; // always beginning with slash and no trailing slash
$config['default']['thumb_filename'] = ""; // always beginning with slash and no trailing slash
$config['default']['thumb_ext'] = "jpg"; 
$config['default']['width'] = "width";
$config['default']['height'] = "height";
$config['default']['thumb'] = "thumb";
$config['default']['create_thumb'] = TRUE;
$config['default']['maintain_ratio'] = TRUE;
$config['default']['default_width'] = 96;
$config['default']['default_height'] = 96;

$config['user']['base_path'] = getenv("FILE_PATH"); // no trailing slash
$config['user']['image_library'] = 'gd2';
$config['user']['path_destination'] = ""; // always beginning with slash and no trailing slash
$config['user']['unique_path'] = "/users/profile/origin"; // always beginning with slash and no trailing slash
$config['user']['filename'] = ""; // always beginning with slash and no trailing slash
$config['user']['ext'] = "jpg"; 
$config['user']['thumb_path_destination'] = "/thumb"; // always beginning with slash and no trailing slash
$config['user']['thumb_unique_path'] = "/users/profile"; // always beginning with slash and no trailing slash
$config['user']['thumb_filename'] = ""; // always beginning with slash and no trailing slash
$config['user']['thumb_ext'] = "jpg"; 
$config['user']['width'] = "width";
$config['user']['height'] = "height";
$config['user']['thumb'] = "thumb";
$config['user']['create_thumb'] = TRUE;
$config['user']['maintain_ratio'] = TRUE;
$config['user']['default_width'] = 96;
$config['user']['default_height'] = 96;

$config['certificate']['base_path'] = getenv("FILE_PATH");; // no trailing slash
$config['certificate']['image_library'] = 'gd2';
$config['certificate']['path_destination'] = ""; // always beginning with slash and no trailing slash
$config['certificate']['unique_path'] = "/certificates/origin"; // always beginning with slash and no trailing slash
$config['certificate']['filename'] = ""; // always beginning with slash and no trailing slash
$config['certificate']['ext'] = "jpg"; 
$config['certificate']['thumb_path_destination'] = "/thumb"; // always beginning with slash and no trailing slash
$config['certificate']['thumb_unique_path'] = "/certificates"; // always beginning with slash and no trailing slash
$config['certificate']['thumb_filename'] = ""; // always beginning with slash and no trailing slash
$config['certificate']['thumb_ext'] = "jpg"; 
$config['certificate']['width'] = "width";
$config['certificate']['height'] = "height";
$config['certificate']['thumb'] = "thumb";
$config['certificate']['create_thumb'] = TRUE;
$config['certificate']['maintain_ratio'] = TRUE;
$config['certificate']['default_width'] = 96;
$config['certificate']['default_height'] = 96;
