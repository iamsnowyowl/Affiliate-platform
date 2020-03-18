<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config["pk_use_ai"] = TRUE;
$config["soft_delete"] = TRUE;
$config["check_unique"] = TRUE;
$config["hard_delete_word"] = "HAPUS";

// media configuration
$config["enable_media"] = FALSE;
$config["media_column_name"] = "media";
/* 
*	available option is [FILE, BINARY]. 
*	default is FILE if option is not one of [FILE,BINARY]. 
* 	keep in mind: Use BINARY when your priority is confidentiality. 
*/
$config["media_store_operation"] = "FILE"; 