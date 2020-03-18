<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_module extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function generate($email)
    {
        $this->load->helper('common');

        $is_cookie = !empty($this->input->get_request_header("X-Session-Type", TRUE));

        if ($is_cookie) {
            $_COOKIE[$this->config->item("sess_cookie_name")] = create_finger_print($email);
        }
        else session_id(create_finger_print($email));

        $this->load->library('session');

        if (!$is_cookie && session_id() != create_finger_print($email)) session_id(create_finger_print($email));

        $this->load->config('user_authentication');
        
        $index_secret_key = $this->config->item('user_authentication')['secret_key']['key'];

        return ($this->session->userdata('logged_in') !== TRUE) ? generate_random_base62_string(128) : $this->session->userdata($index_secret_key);
    }
}