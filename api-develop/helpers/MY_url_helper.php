<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function concat_url($arg1, $arg2)
{
	$arg1 = rtrim($arg1,'/');
	$arg2 = ltrim($arg2,'/');
	return $arg1.'/'.$arg2;
}