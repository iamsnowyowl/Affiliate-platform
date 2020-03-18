<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();
/* 
* you can set multiple method wich can passed here as array. example array("GET", "POST", "PUT")
* to allow whitelist routes. no trailing slash!
* to allow only numeric value use (:num), to allow not specific type you can use (:any)
* set header key for authorization
*/
#====================ROUTES===================================
$config['whitelist']["/users/login"] = array("POST"); 
$config['whitelist']["/users/forgot/(:any)"] = array("POST"); 
$config['whitelist']["archives/(:any)/downloads"] = array("GET"); 
$config['whitelist']["join_requests/(:any)/downloads"] = array("GET"); 
$config['whitelist']["persyaratan_umums/(:any)/apl01/(:any)"] = array("GET");
$config['whitelist']['join_requests/(:any)/apl01']= array("GET");


$config['whitelist']["/users/(:num)/picture"] = array("GET"); 
$config['whitelist']["/users/(:num)/signature"] = array("GET"); 
$config['whitelist']["/public/(.+)"] = array("GET", "POST", "PUT", "DELETE");

#===================HEADER====================================
$config['header']['api_key'] = "X-Api-Key";
$config['header']['authorization'] = "Authorization";
$config['header']['date'] = "X-Lsp-Date";
$config['header']['prefix_upn'] = "Lsp";
$config['header']['salt_path'] = md5(APPPATH); // make more unit location
$config['header']['site_url'] = "X-Site-Url";
$config['secret_key']['key'] = "secret_key";