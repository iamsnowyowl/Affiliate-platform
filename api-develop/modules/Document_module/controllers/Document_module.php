<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $my_parameter;
	protected $node;


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$definition_name = 'definition_'.strtolower(get_class($this));
		$rules_name = 'form_validation_'.strtolower(get_class($this));
		$this->config->load($definition_name, TRUE, TRUE);
		$this->config->load($rules_name, TRUE, TRUE);
		$this->definition = $this->config->item($definition_name);
		$this->rules = $this->config->item($rules_name);
		$this->node = strtolower(get_class($this));
	}

	public function view_integrity_pact()
	{
		setlocale(LC_TIME, 'id_ID.UTF8', 'Indonesian_indonesia', 'Indonesian');
		$data["date"] = strftime("%d %B %Y", time());
		return $this->load->view("integrity_pact/form", $data, TRUE);
	}

	public function view_assessment_assignment($data)
	{
		$data = (array) $data;

		$data["title"] = "Surat Tugas";
		$data["number"] = "1";
		$data["full_name"] = ucfirst($data["first_name"])." ".ucfirst($data["last_name"]);
		$data["letter_number"] = "001";
		$data["experts"] = "Staff";
		$data["applicant_count"] = 15;
		$data["address"] = "Bekasi barat no 92. Gedung Berdikari lantai 3. masuk lewat tangga kiri";
		$data["contact_fax"] = "021871212";

		setlocale(LC_TIME, 'id_ID.UTF8', 'Indonesian_indonesia', 'Indonesian');
		$data["date"] = strftime("%d %B %Y", time());
		return $this->load->view("assessment/assignment_form", $data, TRUE);
	}

	public function get_integrity_pact_by_user_id($user_id)
	{
		$userdata = modules::run("Accessor_module/get_accessor_by_id", NULL, $user_id, "default_integrity_pact", "optional_integrity_pact");

		if (empty($userdata)) return NULL;

		if (!empty($userdata->integrity_pact)) return $userdata->integrity_pact;

		setlocale(LC_TIME, 'id_ID.UTF8', 'Indonesian_indonesia', 'Indonesian');
		$data["date"] = strftime("%d %B %Y", time());
		$data["full_name"] = ucwords($userdata->first_name." ".$userdata->last_name);
		$data["place_date_of_birth"] = ucfirst($userdata->place_of_birth).", ".strftime("%d %B %Y", strtotime($userdata->date_of_birth));
		$data["address"] = $userdata->address;
		$data["signature"] = (!empty($userdata->signature) && !empty($userdata->integrity_pact)) ? base64_encode($userdata->signature) : "";
		return $this->load->view("integrity_pact/form", $data, TRUE);
	}

	public function sign_own_integrity_pact_signature($user_id, $latitude, $longitude)
	{
		$userdata = modules::run("Accessor_module/get_accessor_by_id", NULL, $user_id, "default_integrity_pact", "optional_integrity_pact");

		if (empty($userdata)) {
			modules::run("Error_module/set_error", "User are not accessor");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (!empty($userdata->integrity_pact)) 
		{
			modules::run("Error_module/set_error", "Document already sign. you can only sign once");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		setlocale(LC_TIME, 'id_ID.UTF8', 'Indonesian_indonesia', 'Indonesian');
		$data["date"] = strftime("%d %B %Y", time());
		$data["full_name"] = ucwords($userdata->first_name." ".$userdata->last_name);
		$data["place_date_of_birth"] = ucfirst($userdata->place_of_birth).", ".strftime("%d %B %Y", strtotime($userdata->date_of_birth));
		$data["address"] = $userdata->address;
		$data["signature"] = (!empty($userdata->signature)) ? base64_encode($userdata->signature) : "";
		$data["background1"] = site_url("/files/content/documents/pakta_integritas/background1.jpg");
		$data["logo"] = site_url("/files/content/documents/pakta_integritas/lsp-logo.png");
		$data["city_name"] = $this->get_city_name_by_latlng($latitude, $longitude);
		return modules::run("Accessor_module/update_accessor_by_id", $user_id, array("integrity_pact" => $this->load->view("integrity_pact/form", $data, TRUE)));
	}

	public function generate_to_pdf($user_id, $latitude, $longitude)
	{
		$userdata = modules::run("Accessor_module/get_accessor_by_id", NULL, $user_id, "default_integrity_pact", "optional_integrity_pact");

		if (empty($userdata)) {
			modules::run("Error_module/set_error", "User are not accessor");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (!empty($userdata->integrity_pact)) 
		{
			modules::run("Error_module/set_error", "Document already sign. you can only sign once");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		setlocale(LC_TIME, 'id_ID.UTF8', 'Indonesian_indonesia', 'Indonesian');
		$data["date"] = strftime("%d %B %Y", time());
		$data["full_name"] = ucwords($userdata->first_name." ".$userdata->last_name);
		$data["place_date_of_birth"] = ucfirst($userdata->place_of_birth).", ".strftime("%d %B %Y", strtotime($userdata->date_of_birth));
		$data["address"] = $userdata->address;
		$data["signature"] = (!empty($userdata->signature)) ? base64_encode($userdata->signature) : "";
		$data["background1"] = "data:image/jpg;base64,".base64_encode(file_get_contents(site_url("/files/content/documents/pakta_integritas/background1.jpg")));
		$data["logo"] = "data:image/png;base64,".base64_encode(file_get_contents(site_url("/files/content/documents/pakta_integritas/lsp-logo.png")));
		$data["city_name"] = $this->get_city_name_by_latlng($latitude, $longitude);

		$documents = $this->load->view("integrity_pact/form", $data, TRUE);

		$tfile_name = "/tmp/pakta_integritas_".generate_random_base62_string();

		file_put_contents("$tfile_name.html", $documents);

		$result = shell_exec("cat $tfile_name.html | wkhtmltopdf --disable-smart-shrinking - $tfile_name.pdf 2>&1");

		if (strpos($result, 'Done') === false || !file_exists("$tfile_name.pdf")) {
			modules::run("Error_module/set_error", "Document Failed to sign. generate pdf error");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		$document = file_get_contents("$tfile_name.pdf");

		unlink("$tfile_name.pdf");
		unlink("$tfile_name.html");

		return $document;
	}

	public function get_city_name_by_latlng($latitude, $longitude)
	{
		// $city = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=true&key=AIzaSyBW3qW63i17M4DJAGGrCAX6ApoKp-wi-5g");

		// $city = json_decode($city);
		// $city = (!empty($city->results[6])) ? $city->results[6] : "Not found";
		$city_name = "Jakarta";
		$city = "Jakarta";


		// foreach ($city->address_components as $key => $value) {
		// 	if (!empty($city->address_components[$key]->types)){
		// 		for ($i=0; $i < count($city->address_components[$key]->types); $i++) { 
		// 			if ($city->address_components[$key]->types[$i] == "administrative_area_level_1") $city_name = $city->address_components[$key]->short_name;				
		// 		}
		// 	}
		// }

		return $city_name;
	}
}