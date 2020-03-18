<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function store_file_from_base64($base64, $location, $replace = FALSE){
	// decode image from base64
	$expl = explode(",", $base64);

	$file = base64_decode(array_pop($expl));

	if ($file === FALSE) {
		return FALSE;
	}

	if (!file_exists(dirname($location))) {
		mkdir(dirname($location), 0755, TRUE);
	}
	
	$n = 0;
	$max_inc = 20;
	while (file_exists($location) && !$replace) {
		if ($max_inc < $n) break;
		$location = str_replace('.', "$n.", $location);
		$n++;
	}

	if ($max_inc < $n) return FALSE;
	file_put_contents($location, $file);

	if (file_exists($location)) return TRUE;
	else return FALSE;
}