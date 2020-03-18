<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function create_finger_print($upn)
{
	$CI = get_instance();

	$config_header = $CI->config->item('user_authentication');
	$prefix = $config_header['header']['prefix_upn'];
	$salt_path = $config_header['header']['salt_path'];

	$uniqid = array(
		$salt_path,
		$prefix,
    	$upn,
    	$CI->input->ip_address(),
    	$CI->input->user_agent()
	);

	return md5(implode(",", $uniqid));
}

function guidv4($data)
{
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function make_unique_id()
{
	$unique_id = '';
	$CI = get_instance();

	do
	{
		$unique_id .= mt_rand();
	}
	while (strlen($unique_id) < 32);

	$unique_id .= $CI->input->ip_address();

	// Turn it into a hash and return
	return md5(uniqid($unique_id, TRUE));
}

function get_user_data()
{
	$CI = get_instance();

	if ($CI->userdata['logged_in'] != 1)
	{
		$CI->load->config('user_authentication');
        $index_secret_key = $CI->config->item('user_authentication')['secret_key']['key'];
		return FALSE;	
	}
	unset($CI->userdata['__ci_last_regenerate']);
	$userdata = $CI->userdata;

	return $userdata;
}

function debug($data, $continue = FALSE)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	if ($continue === FALSE) die();
}

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function generate_random_base62_string($length = 32)
{
	$result = '';
	$remaining_length = $length;
	$CI = get_instance();
	do
	{
		$binary_length = (int)($remaining_length * 3 / 4 + 1);
		$binary_string = $CI->security->get_random_bytes($binary_length);
		$base64_string = base64_encode($binary_string);

		// Remove invalid characters
		$base62_string = str_replace(array('+', '/', '='), '', $base64_string);
		$result .= $base62_string;
		
		// If too many characters have been removed, we repeat the procedure
		$remaining_length = $length - strlen($result);
	}
	while ($remaining_length > 0);
	return substr($result, 0, $length);
}

function simple_curl($method = "POST", $url, $params = array()){
	$ch = curl_init();
	$agent = "lsp-api-agent";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	
	if (strtoupper(trim($method)) == "POST") curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Disabling SSL Certificate support temporarly
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	return curl_exec ($ch);
}

function bulan($n) {
	$bulan = array (1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);

	return (!empty($bulan[$n])) ? $bulan[$n] : "";
}

function hari($N) {
	$hari = array (1 =>   'Senin',
		'Selasa',
		'Rabu',
		'Kamis',
		'Jumat',
		'Sabtu',
		'Minggu'
	);

	return (!empty($hari[$N])) ? $hari[$N] : "";
}