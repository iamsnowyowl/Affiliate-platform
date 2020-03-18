<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class Google_module extends MX_Controller {
	public $error;
	public $error_code;
	protected $client;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
		$this->config->load("google_config", TRUE, TRUE);
		$this->google_config = $this->config->item("google_config");

		$this->init_google_client(); 
	}

	public function get_calendar_indonesia_holiday()
	{
		$client = new Google_Client();
		$client->setApplicationName("Client_Library_Examples");
		$client->setDeveloperKey($this->google_config["api_key"]);

		$service = new Google_Service_Calendar($client);
		$optParams = array('timeMin' => (date("Y")-1).'-12-31T10:00:00-07:00');
		$events = $service->events->listEvents('id.indonesian#holiday@group.v.calendar.google.com', $optParams);
		$holiday = array();

		while(true) {
			foreach ($events->getItems() as $event) {
				$holiday[] = array(
					"summary" => $event->getSummary(),
					"start" => $event->getStart()->date,
					"end" => $event->getEnd()->date,
				);
			}
			$pageToken = $events->getNextPageToken();
			if ($pageToken) {
				$optParams = array('timeMin' => (date("Y")-1).'-12-31T10:00:00-07:00', 'pageToken' => $pageToken);
				$events = $service->events->listEvents('id.indonesian#holiday@group.v.calendar.google.com', $optParams);
			} else break;
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), array("count"=> count($holiday), "data" => $holiday)));
	}

	public function send_fcm_message($data, $to = "", $is_topic = FALSE)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		if (!empty($data["notification"])) {
			$data["notification"]["sound"] = "Enabled";
		}
		$fields = array (
	        'notification' => $data["notification"],
			"data" => $data["data"],
			"priority" => "high",
		);

		if (!empty($to) && is_array($to)) $fields["registration_ids"] = $to;
		else if (!empty($to)) {
			$fields["to"] = ($is_topic) ? "/topics/".$to : $to;
		}
		else return;

		$fields = json_encode($fields);
		$headers = array (
			'Authorization: key=' . $this->google_config["api_key"],
			'Content-Type: application/json'
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}

	public function register_fcm_topic($register_id, $topic)
	{
		$url = "https://iid.googleapis.com/iid/v1/$register_id/rel/topics/$topic";

		$headers = array (
			'Authorization: Bearer ' . $this->google_config["api_key"],
			'Content-Type: application/json'
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		// curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}

	protected function init_google_client(){
		$this->client = new Google_Client();
		$this->client->useApplicationDefaultCredentials();
		$this->client->setScopes(['https://www.googleapis.com/auth/drive']);
		$this->client->setSubject(getenv("GOOGLE_DRIVE_SUBJECT"));
	}

	public function gdrive_create_file($metadata, $fileobject) 
	{
		$service = new Google_Service_Drive($this->client);
		return $service->files->create(new Google_Service_Drive_DriveFile($metadata), $fileobject);
	}

	public function gdrive_update_file($file_id, $metadata, $fileobject) 
	{
		$service = new Google_Service_Drive($this->client);
		return $service->files->update($file_id, new Google_Service_Drive_DriveFile($metadata), $fileobject);
	}

	public function gdrive_create_folder($directory = "_Sertimedia_Others", $parents = NULL) 
	{
		$service = new Google_Service_Drive($this->client);
		$config_meta_data = array(
			'name' => $directory,
			'mimeType' => 'application/vnd.google-apps.folder',
			'fields' => 'id, webContentLink, webViewLink'
		);

		if (!empty($parents)) $config_meta_data["parents"] = array($parents);

		$fileMetadata = new Google_Service_Drive_DriveFile($config_meta_data);

		return $service->files->create($fileMetadata, array(
			'fields' => 'id'
		));
	}

	public function gdrive_permission($file_id, $permission)
	{
		$service = new Google_Service_Drive($this->client);
		$permission = new Google_Service_Drive_Permission($permission);

		return $service->permissions->create(
			$file_id, $permission, array('fields' => 'id'
		));
	}

	public function gdrive_list($file_id, $mime_type, $fields) {
		$service = new Google_Service_Drive($this->client);
		$response = $service->files->list($file_id, $mime_type, $fields);
		return $response->getBody()->getContents();
	}

	public function gdrive_get($file_id, $fields) {
		$service = new Google_Service_Drive($this->client);
		$response = $service->files->get($file_id, $fields);
		return $response->getBody()->getContents();
	}

	public function gdrive_export($file_id, $mime_type, $fields) {
		$service = new Google_Service_Drive($this->client);
		$response = $service->files->export($file_id, $mime_type, $fields);
		return $response->getBody()->getContents();
	}
}