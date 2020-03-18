<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessor_competences extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_accessor_competence_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->accessor_competence_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_accessor_competence_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->accessor_competence_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_competence_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->accessor_competence_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_competence_list() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->accessor_competence_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_competence_list_by_user_id($user_id) 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_LIST");
		$this->my_parameter = $this->parameter;
		$this->my_parameter['user_id'] = $user_id;

		$data = $this->accessor_competence_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_competence_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$accessor_competences = modules::run("Accessor_competence_module/get_accessor_competence_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($accessor_competences === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ACCESSOR_COMPETENCE",
								"reason" => "Accessor_competenceNotFound"
							),
						)
					)
				);
			}
			$data['data'] = $accessor_competences;
		}
		else
		{
			$data = modules::run("Accessor_competence_module/get_accessor_competence_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_accessor_competence_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->accessor_competence_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_competence_count() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->accessor_competence_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_competence_count()
	{
		$count = modules::run("Accessor_competence_module/get_accessor_competence_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create accessor_competence
	public function create_accessor_competence_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_accessor_competence();
	}

	public function create_accessor_competence_session()
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_CREATE");
		
		$this->my_parameter = $this->parameter;

		// must have role
		if ($this->userdata['role_code'] == "ACS") $this->my_parameter['user_id'] = $this->userdata['user_id'];

		$created_by = $this->userdata['user_id'];

		$this->create_accessor_competence($created_by);
	}

	protected function create_accessor_competence($created_by = 0)
	{
		if (!empty($this->parameter['image_b64']))
		{
			$config_file = "image_b64";
			$this->config->load($config_file, TRUE, TRUE);
			$config = $this->config->item($config_file);

			$unique = (!empty($this->my_parameter['sub_schema_number'])) ? $this->my_parameter['sub_schema_number'] : "";
			$unique .= (!empty($created_by)) ? $created_by : "";

			$config['certificate']['filename'] = md5($unique);
			$config['certificate']['thumb_filename'] = md5($unique);
			// debug($config);
			$img = $this->parameter['image_b64'];
			$this->load->library('image_lib');
			if (!$this->image_lib->store_image_from_base64($img, $config['certificate']))
			{
				$code = 400;
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => "Image parsing failure",
							"errors" => array(
								"domain" => "USER",
								"reason" => "ImageError"
							),
						)
					)
				);
			}

			$this->my_parameter['certificate_file'] = implode(
				array(
					$config['certificate']['path_destination'],
					$config['certificate']['unique_path'],
					"/",
					$config['certificate']['filename'],
					".",
					$config['certificate']['ext']
				)
			);

			if (!empty($this->my_parameter['image_b64'])) unset($this->my_parameter['image_b64']);
		}

		if (!empty($this->my_parameter["expired_date"]) && empty($this->my_parameter["verification_date"])) {
			$this->my_parameter["verification_date"] = date("Y-m-d H:i:s");
		}

		$row_id = modules::run("Accessor_competence_module/create_accessor_competence", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("accessor_competence_id" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_own_accessor_competence_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_accessor_competence($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_accessor_competence_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_accessor_competence($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_accessor_competence($row_id, $modified_by)
	{
		if (!empty($this->my_parameter["expired_date"]) && empty($this->my_parameter["verification_date"])) {
			$this->my_parameter["verification_date"] = date("Y-m-d H:i:s");
		}

		return modules::run("Accessor_competence_module/update_accessor_competence_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_accessor_competence_by_id()
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_DELETE");

		$affected_row = $this->delete_accessor_competence();

		if ($affected_row != count($accessor_competences))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_accessor_competence_by_id()
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_COMPETENCE_DELETE");

		$affected_rows = $this->delete_accessor_competence();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_accessor_competence()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$accessor_competences = array_map("trim", $segs);

		$affected_rows = modules::run("Accessor_competence_module/delete_accessor_competence_by_code", $accessor_competences);

		if ($affected_rows != count($accessor_competences))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_rows)
						),
					)
				)
			);
		}

		return $affected_rows;
	}

	public function accessor_competence_picture($accessor_competence_id)
	{
		$accessor_competence_id = intval($accessor_competence_id);
		
		$accessor_competence = modules::run("Accessor_competence_module/get_accessor_competence_by_id", array(), $accessor_competence_id);
		if ($accessor_competence === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR_COMPETENCE",
							"reason" => "AccessorCompetenceNotFound"
						),
					)
				)
			);
		}

		$accessor_competence_id = md5($accessor_competence_id);

		$this->load->helper('url');
		$config_file = 'image';
		$this->config->load($config_file, TRUE);

		$config_image = $this->config->item('certificate', $config_file);

		$thumb_path = $config_image['base_path'].rtrim($this->config->item('thumb_destination_path_certificate', $config_file), "/")."/";
		$image_src = $config_image['base_path'].$accessor_competence->certificate_file;

		if (!file_exists($image_src))
		{
			$image_src = $config_image['base_path'].$this->config->item('default_img_user', $config_file);
		}

		$config['image_library'] = $this->config->item('image_library', $config_file);
		$config['source_image'] = $image_src;
		$config['maintain_ratio'] = $this->config->item('maintain_ratio', $config_file);

		if (!$this->input->get($this->config->item('thumb', $config_file), TRUE))
		{
			if ($this->input->get($this->config->item('width', $config_file), TRUE) && $this->input->get($this->config->item('height', $config_file), TRUE))
			{
				$config['width'] = $this->input->get($this->config->item('width', $config_file), TRUE);
				$config['height'] = $this->input->get($this->config->item('height', $config_file), TRUE);
				$config['maintain_ratio'] = FALSE;
				$config['dynamic_output'] = TRUE;
			}
		}
		else
		{
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $this->config->item('default_width', $config_file);
			$config['height'] = $this->config->item('default_height', $config_file);
			$expl = explode(".", $image_src);
			$config['new_image'] = $thumb_path.$accessor_competence_id.".".end($expl);
			$image_src = $config['new_image'];
		}

		$this->load->library('image_lib');
		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->resize())
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => $this->image_lib->display_errors(),
						"errors" => array(
							"domain" => "USER",
							"reason" => "ImageError"
						),
					)
				)
			);
		}
		$this->output // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
		->set_output(file_get_contents($image_src))->_display();
		return;
	}
}


