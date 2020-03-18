<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication_module extends MX_Controller {
	protected $user_data;

	public function __construct()
	{
		parent::__construct();
	}

	public function check($email, $password, $definition = "default_with_password")
	{
		$this->load->helper("email");

		if (valid_email($email))
		{
			$user_data = modules::run("User_module/get_user_by_email", array(), $email, $definition); // override definition to default_with_password
		}
		else{
			$user_data = modules::run("User_module/get_user_by_username", array(), $email, $definition); // override definition to default_with_password
			
			if ($user_data !== FALSE && $email != $user_data->username)
			{
				modules::run("Error_module/set_error", "Couldn't find your account. Try again.");
				modules::run("Error_module/set_error_code", 422);
				modules::run("Error_module/set_error_extra", array("email" => "usernotfound"));
				return FALSE;
			}
		}

		if ($user_data === FALSE)
		{
			modules::run("Error_module/set_error", "Couldn't find your account. Try again.");
			modules::run("Error_module/set_error_code", 422);
			modules::run("Error_module/set_error_extra", array("email" => "usernotfound"));
			return FALSE;
		}

		$this->set_user_data($user_data);

		// user exist. now verify password
		$valid_password = $this->verify_password($password);
		if ($valid_password === FALSE)
		{
			modules::run("Error_module/set_error", "Wrong password. Try again.");
			modules::run("Error_module/set_error_code", 422);
			modules::run("Error_module/set_error_extra", array("password" => "invalidauthentication"));
			return FALSE;
		}
		// unset password
		unset($this->user_data->password);
		return $this->get_user_data();
	}

	public function is_user_login()
	{
		$this->load->helper('common');
        if (get_user_data() === FALSE)
        {
            response(401, array(
                    "responseStatus" => "ERROR",
                    "code" => 401,
                    "error" => array(
                        "message" => "user not authorized",
                        "errors" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "user not authorized"
                        ),
                    )
                )
            );
        }
	}

	public function get_user_data()
	{
		return $this->user_data;
	}

	protected function verify_password($password)
	{
		return password_verify($password, $this->user_data->password);
	}

	protected function set_user_data($user_data)
	{
		$this->user_data = $user_data;
	}
}