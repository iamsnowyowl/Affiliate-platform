<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// only string allowed
$config = array();
// $config['base_path'] = rtrim(FCPATH, "/"); // no trailing slash
$base_path = getenv("FILE_PATH");
$config['base_path'] = rtrim($base_path, "/"); // no trailing slash
$config['image_library'] = 'gd2';
$config['default_img_user'] = "/users/profile/default/photo.jpg";
$config['upload_destination'] = "/users/profile/origin";
$config['default_img'] = "/content/default/default_image.jpg";
$config['thumb_destination_path_profile'] = "/users/profile/thumb";
$config['thumb_destination_path'] = "/content/thumb";
$config['width'] = "width";
$config['height'] = "height";
$config['thumb'] = "thumb";
$config['create_thumb'] = TRUE;
$config['maintain_ratio'] = TRUE;
$config['default_width'] = 96;
$config['default_height'] = 96;

$config['certificate']['base_path'] = getenv("FILE_PATH");
$config['certificate']['image_library'] = 'gd2';
$config['certificate']['default_img_user'] = "/users/profile/default/photo.jpg";
$config['certificate']['upload_destination'] = "/certificate/origin";
$config['certificate']['default_img'] = "/content/default/default_image.jpg";
$config['certificate']['thumb_destination_path_certificate'] = "/certificate/thumb";
$config['certificate']['thumb_destination_path'] = "/content/thumb";
$config['certificate']['width'] = "width";
$config['certificate']['height'] = "height";
$config['certificate']['thumb'] = "thumb";
$config['certificate']['create_thumb'] = TRUE;
$config['certificate']['maintain_ratio'] = TRUE;
$config['certificate']['default_width'] = 96;
$config['certificate']['default_height'] = 96;
