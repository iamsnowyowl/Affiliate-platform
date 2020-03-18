<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function require_permission($permission_code)
{
	$require_permission = array();

	if (is_array($permission_code))
	{
		foreach ($permission_code as $key => $value) 
		{
			$require_permission[] = $value;
		}
	}
	else
	{
		$require_permission[] = $permission_code;
	}
	
	$missing = array_diff($require_permission, get_instance()->userdata['permission']);

	$granted = (boolean) (empty($missing)) ? TRUE : FALSE;

	if (!$granted)
	{
		get_instance()->load->helper('http');
		response(401, "missing permission : '".implode("', '", $missing))."'";
	}
}
