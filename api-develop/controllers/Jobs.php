<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends MX_Controller{

    protected $my_parameter;

public function __construct(){
    parent::__construct();
    $this->load->helper(array('http', 'permission', 'common'));
    $this->load->database();
    $this->output->enable_profiler(FALSE);
}

public function get_job_list()
{

    $this->my_parameter = $this->parameter;

    $data = $this->job_list();
    response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
}

public function get_job_detail($job_id)
{
    $this->my_parameter = $this->parameter;

    $data = $this->job_detail($job_id);
    response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
}

protected function job_list()
{
    return modules::run("Jobs_module/get_job_list", $this->my_parameter);
}

protected function job_detail($job_id)
{
    $jobs = modules::run("Jobs_module/get_job_by_id", $this->my_parameter, $tuk_id);

    $this->load->helper("url");

    if($jobs === FALSE)
    {
        $code = modules::run("Error_module/get_error_code");
        response($code, array(
            "responseStatus" => "ERROR",
            "error" => array(
                "code" => $code,
                "message" => modules::run("Error_module/get_error"),
                "errors" => array(
                    "domain" => "JOB",
                    "reason" => "JobNotFound"
                ),
            )
            )
            );
    }
    return array("data" => $jobs);
}

public function create_jobs_public()
{
    $this->my_parameter = $this->parameter;

    $this->create_jobs();
}

public function create_jobs_session()
{
    if (!modules::run("Permission_module/require_permission", "JOBS_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "JOBS_CREATE");

    $this->my_parameter = $this->parameter;
    $created_by = $this->userdata['user_id'];

    $this->create_jobs($created_by);
}

protected function create_jobs($created_by = 0)
{
    $job_id = modules::run("Jobs_module/create_jobs", $this->my_parameter, $created_by);

    if ($job_id === FALSE)
    {
        $code = modules::run("Error_module/get_error_code");
        response($code, array(
            "responseStatus" => "ERROR",
            "error" => array(
                "code" => $code,
                "message" => modules::run("Error_module/get_error"),
                "errors" => array(
                    "domain" => "JOBS",
                    "reason" => "UpdateErrorException", "extra" => modules::run("Error_module/get_error_extra")
                ),
            )
            )
        );
    }

    $data = array("data" => array("job_id" => $job_id));

    response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
}
}