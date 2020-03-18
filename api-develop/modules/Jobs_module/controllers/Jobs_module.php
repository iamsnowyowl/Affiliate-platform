<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_module extends MX_Controller {

    protected $error;
    protected $error_code;
    protected $definition;
    protected $rules;
    protected $configuration;
    protected $my_parameter;
    protected $node;

    public function __construct()
    {

        parent::__construct();
        $this->load->database();

        $definition_name = 'definition_'.strtolower(get_class($this));
        $rules_name = 'form_validation_'.strtolower(get_class($this));
        $config_name = 'configuration_'.strtolower(get_class($this));

        $this->config->load($definition_name, TRUE, TRUE);
        $this->config->load($rules_name, TRUE, TRUE);
        $this->config->load($config_name, TRUE, TRUE);

        $this->definition = $this->config->item($definition_name);
        $this->rules = $this->config->item($rules_name);
        $this->configuration = $this->config->item($config_name);

        $this->node = strtolower(get_class($this));
    }

    public function check($parameter = array(), $check, $default = "default", $optional = "optional")
    {
        $graph = $this->get_graph_result($parameter, $default, $optional);
        $this->load->model("Jobs_model");

        $job = $this->Jobs_model->check($check, $graph);

        if (!isset($job))
        {
            modules::run("Error_module/set_error", "Job not found on database");
            modules::run("Error_module/set_error_code", 404);
            return FALSE;
        }
        return $job;
    }

    public function create_jobs($parameter = array(), $created_by = 0, $auto_commit = TRUE)
    {
        $this->my_parameter = $parameter;

        if (!$this->configuration["pk_use_ai"]){
            $this->my_parameter["jobs_id"] = guidv4(random_bytes(16));
        }

        if ($this->validate_input("create_jobs") === FALSE) return FALSE;

        if ($this->configuration["check_unique"])
        {
            $check = modules::run("Jobs_module/check", NULL, $this->my_parameter['jobs_code']);
            if (!empty($check->jobs_id)){
                modules::run("Error_module/set_error", "Jobs already exist");
                modules::run("Error_module/set_error_code", 409);
                return FALSE;
            }
        }
        $this->load->model("Jobs_model");

        $this->my_parameter['created_by'] = intval($created_by);

        $job_id = $this->Jobs_model->create_job($this->my_parameter, $auto_commit);

        $job_id = (!$this->configuration["pk_use_ai"] && !empty($job_id)) ? $this->my_parameter["jobs_id"] : $job_id;

        return $job_id;
    }


    public function get_job_by_id($parameter = array(), $job_id, $default = "default", $optional = "optional")
    {
        $graph = $this->get_graph_result($parameter, $default, $optional);
        $this->load->model("Jobs_model");

        $job = $this->Jobs_model->get_job_by_id($job_id, $graph);

        if (!isset($job))
        {
            modules::run("Error_module/set_error", "Job not found on database");
            modules::run("Error_module/set_error_code", 404);
            return FALSE;
        }
        return $job;
    }

    public function get_job_list($parameter = array(), $default = "default", $optional = "optional")
    {
        $graph = $this->get_graph_result($parameter, $default, $optional);
        $this->load->model("Jobs_model");

        $job = $this->Jobs_model->get_job_list($graph);
		$job_count = $this->get_job_count($parameter);
		$graph_pagination = $this->get_graph_pagination($job_count->count);
        
        $this->load->helper('url');
        $query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
        $data = array(
            'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $job_count->count,
			'data' => $job,
			'pagination' => $graph_pagination
        );
        return $data;
    }
    
public function get_job_count($parameter = array(), $default = "default", $optional = "optional")
{
    $graph = $this->get_graph_result($parameter, $default, $optional);
    $this->load->model("Jobs_model");

    $job_count = $this->Jobs_model->get_job_count($graph);

    return $job_count;
}

protected function get_graph_pagination($count)
{
    $this->load->library("graph");
    //  check whether graph validation error or not
    $this->graph->initialize_pagination($this->node, $count);

    return
    $this->graph->get_compile_result_pagination($this->node);
}

protected function get_graph_result($parameter = array(), $default = "default", $optional = "optional")
	{
		$default = $this->definition[$default];
		$optional = $this->definition[$optional];

		$this->load->library("graph");
		// check whether graph validation error or not
		if (!$this->graph->initialize($parameter, $default, $optional, $this->node))
		{
			response($this->graph->get_error_code(), array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $this->graph->get_error_code(),
						"message" => $this->graph->get_error(),
						"errors" => array(
							"domain" => "GRAPH_VALIDATION",
							"reason" => "GraphError"
						),
					)
				)
			);   
		}

		return $this->graph->get_compile_result($this->node);
    }
    
    protected function validate_input($group, $extra_rules = NULL)
    {
        $this->load->library('form_validation');

        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->my_parameter, TRUE);
        $this->form_validation->set_rules($this->rules[$group]);
        if (!empty($extra_rules))$this->form_validation->set_rules($extra_rules);

        if ($this->form_validation->run(NULL, $this->my_parameter) == FALSE)
        {
            modules::run("Error_module/set_error", "error validation on input data");
            modules::run("Error_module/set_error_code", 400);
            $extra = (!is_array($this->form_validation->error_array())) ? array('invalid_field' => $this->form_validation->error_array()) : $this->form_validation->error_array();

            modules::run("Error_module/set_error_extra", $extra);
            return FALSE;
        }
        return TRUE;
    }
}