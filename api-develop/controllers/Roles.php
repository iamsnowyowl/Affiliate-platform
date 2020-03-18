<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('http', 'permission', 'common'));
        $this->load->database();
        $this->output->enable_profiler(FALSE);
    }

	public function role_list($role_code = NULL)
    {
        $role = array();
        $count = NULL;

        if (!empty($role_code))
        {
            $role = modules::run("Role_module/get_role_by_code", $role_code);
            if ($role === FALSE)
            {
                $code = modules::run("Error_module/get_error_code");
                response($code, array(
                        "responseStatus" => "ERROR",
                        "error" => array(
                            "code" => $code,
                            "message" => modules::run("Error_module/get_error"),
                            "errors" => array(
                                "domain" => "ROLE",
                                "reason" => "RoleNotFound"
                            ),
                        )
                    )
                );
            }

            $data['data'] = $role;
        }
        else
        {
            $data = array(
                'count' => modules::run("Role_module/get_role_count")->count,
                'data' => modules::run("Role_module/get_role_list")
            );
        }

        response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
    }

    public function role_count()
    {
        $count = modules::run("Role_module/get_role_count", $this->input->get(NULL, TRUE));
        response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
    }
}


