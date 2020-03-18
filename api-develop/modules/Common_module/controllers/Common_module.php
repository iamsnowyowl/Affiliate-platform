<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_module extends MX_Controller {
	protected $error;
	protected $error_code;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_common_gender_list()
	{
		$this->load->model("Common_model");

		$common = $this->Common_model->get_common_gender_list();

		$this->load->helper('url');
		$data = array(
			'data' => $common
		);
		return $data;
	}

	public function tbs_merge($parameter, $template, $output, $action_show = "STORE") 
	{
		$ext = pathinfo($template, PATHINFO_EXTENSION);

		$allowed_ext = ["docx","xlsx"];
		
		if (!in_array($ext, $allowed_ext)){
			modules::run("Error_module/set_error", "extension not allowed. only allowed:".implode(",", $allowed_ext));
			return FALSE;
		}

		$this->load->library("Tinybutstrong/Tbswrapper");
		$this->tbswrapper->tbsLoadTemplate($template);

		if (!empty($parameter["GLOBALS"]) && is_array($parameter["GLOBALS"])) {
			$this->tbswrapper->tbsSetGlobal($parameter["GLOBALS"]);
		}
		
		if (!empty($parameter["merge_block"]) && is_array($parameter["merge_block"])) {
			for ($i=0; $i < count($parameter["merge_block"]); $i++) { 
				$this->tbswrapper->tbsMergeBlock($parameter["merge_block"][$i]["name"], $parameter["merge_block"][$i]["data"]);
			}
		}

		if ($action_show == "STORE") {
			if (!file_exists(dirname($output))) {
				mkdir(dirname($output), 0755, TRUE);
			}
			$this->tbswrapper->tbsShow(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
			if (file_exists($output)) return TRUE;
		}
		else $this->tbswrapper->tbsShow(OPENTBS_DOWNLOAD, $output);

		return TRUE;
	}

}