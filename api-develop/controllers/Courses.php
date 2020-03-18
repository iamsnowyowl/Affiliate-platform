<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends MX_Controller {
	
	protected $my_parameter;
	protected $node;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
		$this->node = strtolower(get_class($this));
	}

	public function get_own_course_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->course_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_course_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->course_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_course_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "COURSE_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->course_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_course_list() 
	{
		modules::run("Permission_module/require_permission", "COURSE_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->course_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function course_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$courses = modules::run("Course_module/get_course_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($courses === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "COURSE",
								"reason" => "CourseNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $courses;
		}
		else
		{
			$courses = array();
			$count = NULL;

			$data = modules::run("Course_module/get_course_list", $this->my_parameter);
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function get_own_course_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->course_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_course_count() 
	{
		modules::run("Permission_module/require_permission", "COURSE_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->course_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function course_count()
	{
		$count = modules::run("Course_module/get_course_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create course
	public function create_course_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_course();
	}

	public function create_course_session()
	{
		modules::run("Permission_module/require_permission", "COURSE_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_course($created_by);
	}

	protected function create_course($created_by = 0)
	{
		$row_id = modules::run("Course_module/create_course", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "COURSE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array($this->node => array("row_id" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_own_course_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_course($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "COURSE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_course_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "COURSE_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_course($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "COURSE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_course($row_id, $modified_by)
	{
		return modules::run("Course_module/update_course_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_course_by_id()
	{
		modules::run("Permission_module/require_permission", "COURSE_DELETE");

		$affected_row = $this->delete_course();

		if ($affected_row != count($courses))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "COURSE",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_course_by_id()
	{
		modules::run("Permission_module/require_permission", "COURSE_DELETE");

		$affected_rows = $this->delete_course();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_course()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$courses = array_map("trim", $segs);

		$affected_rows = modules::run("Course_module/delete_course_by_id", $courses);

		if ($affected_rows != count($courses))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "COURSE",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_rows)
						),
					)
				)
			);
		}

		return $affected_rows;
	}
}


