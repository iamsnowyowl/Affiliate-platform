<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

## base configutation
$config["base"] = array();
$config["base"]["path"] = getenv("BACKGROUND_WORKER_LOG");

## reset password
$config["reset_password"] = array();
$config["reset_password"]["log_path"] = $config["base"]["path"].'/reset_password';
$config["reset_password"]["log_file"] = '/log_'.date("j.n.Y").'.log';
$config["reset_password"]["url"] = getenv("BASE_URL").'/public/setup/password';
$config["reset_password"]["expired_range"] = '+1 week';
$config["reset_password"]["token_length"] = 45;
$config['reset_password']['email']['max_retry_email'] = 2;
$config['reset_password']['email']['protocol'] = getenv("EMAIL_SMTP");
$config['reset_password']['email']['smtp_host'] = getenv("EMAIL_SMTP_HOST");
$config['reset_password']['email']['smtp_user'] = getenv("EMAIL_SMTP_USER");
$config['reset_password']['email']['smtp_pass'] = getenv("EMAIL_SMTP_PASSWORD");
$config['reset_password']['email']['smtp_port'] = 465;
$config['reset_password']['email']['mailtype'] = 'html';
$config['reset_password']['email']['charset'] = 'iso-8859-1';

## reset password
$config["sync_tas"] = array();
$config['sync_tas']['key'] = '46xYSrCEfwPeQfW1KCT7';

$config["fcm_broadcast"] = array();
$config['fcm_broadcast']['limit'] = 1000;

$config['archive'] = array();
$config['archive']['limit'] = 2;
$config['archive']['base_file'] = getenv("FILE_PROTECTED_PATH");
$config['archive']['root_dir_name'] = "archive";
$config['archive']['column_separator'] = "|";
