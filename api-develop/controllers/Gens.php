<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gens extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_gen_detail($gen_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "GEN_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->gen_detail($gen_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_gen_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "GEN_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->gen_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function gen_detail($gen_id)
	{
		$gens = modules::run("Gen_module/get_gen_by_id", $this->my_parameter, $gen_id);

		$this->load->helper("url");

		if ($gens === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GEN",
							"reason" => "GenNotFound"
						),
					)
				)
			);
		}

		return array("data" => $gens);
	}

	protected function gen_list()
	{
		return modules::run("Gen_module/get_gen_list", $this->my_parameter);
	}

	public function get_gen_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "GEN_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->gen_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function gen_count()
	{
		$count = modules::run("Gen_module/get_gen_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create gen
	public function create_gen_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_gen();
	}

	public function create_gen_session()
	{
		if (!modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "GEN_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_gen($created_by);
	}

	protected function create_gen($created_by = 0)
	{
		$gen_id = modules::run("Gen_module/create_gen", $this->my_parameter, $created_by);
			
		if ($gen_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GEN",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("gen_id" => $gen_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_gen_by_id($gen_id)
	{
		$gen = modules::run("Gen_module/get_gen_by_id", array(), $gen_id);
		
		if (!(modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "GEN_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_gen($gen_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GEN",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_gen($gen, $modified_by)
	{
		return modules::run("Gen_module/update_gen_by_id", $gen, $this->my_parameter, $modified_by);
	}

	public function delete_soft_gen_by_id($gen_id)
	{
		$gen = modules::run("Gen_module/get_gen_by_id", array(), $gen_id);

		if (!(modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "GEN_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_gen($gen_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GEN",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_gen($gen_id, $modified_by = 0)
	{
		return modules::run("Gen_module/delete_soft_gen_by_id", $gen_id, $modified_by);
	}

	public function delete_hard_gen_by_id($gen_id, $confirmation)
	{
		$gen = modules::run("Gen_module/get_gen_by_id", array(), $gen_id);

		if (!(modules::run("Permission_module/require_permission", "GEN_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "GEN_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_gen($gen_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GEN",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_gen($gen_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Gen_module/delete_hard_gen_by_id", $gen_id, $confirmation, $modified_by);
	}
}


