<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// only string allowed
$config = array();
$config['api_key'] = getenv("FCM_API_KEY");
