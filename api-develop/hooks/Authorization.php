<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization {

    protected $userdata;
    protected $CI;
    protected $config;
    protected $upn;
    protected $mac_address;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->mac_address = NULL;
        $this->CI->load->helper('http_helper');
        $this->CI->userdata = NULL;
    }

    public function on_check_authorization()
    {
        $params = $this->CI->request_info;
        // load configuration file
        if (!$this->load_config('user_authentication')) {
            $code = 500;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "user_authentication configuration not found",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "UserAuthenticationConfigurationNotFound"
                        ),
                    ),
                )
            );
        }

        // is segments exist?
        if (empty($this->CI->uri->segments))
        {
            $code = 404;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "Page not found",
                        "error" => array(
                            "domain" => "ROUTE",
                            "reason" => "PagesNotFound"
                        ),
                    )
                )
            );
        }

        if (is_cli())
        {
            return;
        }

        if (!empty($params['api_key']))
        {
            return;
        }

        if (empty($params['Authorization']))
        {
            if ($this->is_whitelist_exist(array_values($this->CI->uri->segments), $params['method'])) { // check whitelist
                return;
            }
        }

        $this->authenticate($params);
    }

    protected function is_whitelist_exist($segments, $method)
    {
        $is_whitelist_exist = FALSE;

        foreach ($this->get_config("whitelist") as $key => $value) 
        {
            // speed up. is method exist? no? throw away 
            if (!in_array($method, $value)) continue;

            $keys = explode("/", trim($key, "/"));

            $recursive = FALSE;

            for ($i=0; $i < count($segments); $i++) 
            { 
                if (empty($keys[$i])) // lenght not same. skip
                {
                    $is_whitelist_exist = FALSE;
                    break;
                }

                if ($keys[$i] == "(.+)" && $is_whitelist_exist)
                {
                    $recursive = TRUE;
                    break;
                }

                if ($keys[$i] == "(:any)")
                {
                    $is_whitelist_exist = TRUE;
                    continue;
                }

                if ($keys[$i] == "(:num)")
                {
                    if (is_numeric($segments[$i]))
                    {
                        $is_whitelist_exist = TRUE;
                        continue;
                    }
                    else
                    {
                        $is_whitelist_exist = FALSE;
                        break;
                    }
                }


                if ($keys[$i] == $segments[$i])
                {
                    $is_whitelist_exist = TRUE;
                }
                else
                {
                    $is_whitelist_exist = FALSE;
                    break;
                }
            }
            if ($is_whitelist_exist) 
            {
                if ($recursive)
                {
                    break;
                }
                else if (count($segments) != count($keys))
                {
                    $is_whitelist_exist = FALSE;
                    continue;
                }
                
                break;
            }
        }

        return $is_whitelist_exist;
    }

    protected function load_config($config)
    {
        return $this->CI->config->load($config, TRUE);
    }

    protected function get_config($key = NULL)
    {
        $config = $this->CI->config->item('user_authentication');
        return (empty($key)) ? $config : (empty($config[$key])) ? [] : $config[$key];
    }

    protected function authenticate($params)
    {
        $config_header = $this->get_config("header");

        if (empty($params[$config_header['authorization']]) OR empty($params[$config_header['date']]))
        {   
            $code = 401;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "User Unauthorized",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "UserUnauthorized",
                            "extra" => $_SERVER
                        ),
                    )
                )
            );
        }

        $auth = explode(":", $params[$config_header['authorization']]);
        if (count($auth) !== 2)
        {
            $code = 400;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "Invalid Authorization parameter",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "InvalidAuthorizationParameter"
                        ),
                    )
                )
            );
        }

        $key = trim($auth[0]);
        $digest = trim($auth[1]);

        $keys = explode(" ", $key);

        if (count($keys) !== 2)
        {
            $code = 400;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "Invalid Authorization parameter",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "InvalidAuthorizationParameter"
                        ),
                    ),
                )
            );
        }

        $prefix_upn = trim($keys[0]);
        $email = trim($keys[1]);

        if (!empty($prefix_upn) && $prefix_upn != $config_header['prefix_upn'])
        {
            $code = 400;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "Invalid Authorization parameter",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "InvalidAuthorizationParameter"
                        ),
                    ),
                )
            );
        }

        // load common helper
        $this->CI->load->helper('email');

        // load common, email helper
        $this->CI->load->helper('common');
        
        $this->CI->upn = $email;

        // session_id(create_finger_print($email));
        if (empty($_COOKIE)) session_id(create_finger_print($email));
        // load library session

        $this->CI->load->library('session');
        // parse user data from session

        if ($this->CI->session->userdata('logged_in') !== TRUE)
        {
            $code = 401;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "User Unauthorized",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "UserUnauthorized",
                            "extra" => (!empty($this->CI->session->userdata())) ? $this->CI->session->userdata() : array()
                        ),
                    ),
                )
            );
        }

        $user_data = $this->CI->session->userdata();

        $segment = array_values($this->CI->uri->segments);
        $is_logout = (is_array($segment) && count($segment) == 2
        && $segment[0] == "users" && $segment[1] == "logout") ? TRUE : FALSE;
        
        if (!empty($user_data["expired_date"]) && $user_data["expired_date"] != "2000-01-01 00:00:00" && $is_logout === FALSE) {
            $expired_time = strtotime($user_data["expired_date"]);
            $current_time = time();

            if ($current_time > $expired_time) {
				$code = 419;
				$message = "Your account already expired. please contact Administrator.";
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => $message,
							"errors" => array(
								"domain" => "AUTHENTICATION",
								"reason" => "ExpireaEntity",
							),
						)
						), $message
				);
			}
		}

        // session_write_close(); this must be put after controller rules

        // if ($this->CI->session->userdata('logged_in') !== TRUE)
        // {
        //     $code = 401;
        //     response($code, array(
        //             "responseStatus" => "ERROR",
        //             "error" => array(
        //                 "code" => $code,
        //                 "message" => "User Unauthorized",
        //                 "error" => array(
        //                     "domain" => "AUTHENTICATION",
        //                     "reason" => "UserUnauthorized"
        //                 ),
        //             ),
        //         )
        //     );
        // }


        $config_secret_key = $this->get_config("secret_key");

        $data = $params['method'].'+'.$params['path'].'+'.$params[$config_header['date']];
        $hash = base64_encode(hash_hmac('SHA512', $data, $user_data[$config_secret_key['key']], FALSE));

        if($hash == $digest)
        {
            $this->CI->userdata = $user_data; 
            if (!empty($user_data[$config_secret_key['key']]))
            {
                $this->CI->mac_address = md5($user_data[$config_secret_key['key']]);
                unset($this->CI->userdata[$config_secret_key['key']]);
            }
        }
        else
        {
            $code = 401;
            response($code, array(
                    "responseStatus" => "ERROR",
                    "error" => array(
                        "code" => $code,
                        "message" => "Forbidden",
                        "error" => array(
                            "domain" => "AUTHENTICATION",
                            "reason" => "UserUnauthorized"
                        ),
                    ),
                )
            );
        }
    }
}