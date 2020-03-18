<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('http', 'permission', 'common'));
        $this->load->database();
        $this->output->enable_profiler(FALSE);
    }

	public function common_gender_list()
    {
        $data = modules::run("Common_module/get_common_gender_list");

        response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
    }
}


