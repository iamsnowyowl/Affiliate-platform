<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Valid Email
 *
 * @param	string
 * @return	bool
 */
function valid_email($str)
{
	return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
}


function valid_emails($str)
{
	if (strpos($str, ',') === FALSE)
	{
		return valid_email(trim($str));
	}

	foreach (explode(',', $str) as $email)
	{
		if (trim($email) !== '' && valid_email(trim($email)) === FALSE)
		{
			return FALSE;
		}
	}

	return TRUE;
}