<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// only string allowed
$config = array();
$config['main'] = array(
	"basepath" => "/var/www/html/background/log/",// with trailing slash
	'state_file' => 'state.log',
);
$config['sync_customer'] = array(
	"log_start_time" => "thread_start_time.log",
	"log_message" => "threads/",
	"max_alive_time" => 3600,
	"max_data_produced" => 30000,
	"max_data_each_batch" => 100,
	"sleep_wait_time" => 10,
	"max_wait_count" => 3
);
