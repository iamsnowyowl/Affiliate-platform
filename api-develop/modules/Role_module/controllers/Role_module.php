<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_module extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_role_by_code($role_code, $select = "*")
	{
        $this->load->model('Role_model');

        // set user permission table
        $role = $this->Role_model->get_role_by_code($role_code, $select = "*");
        return (!empty($role)) ? $role : array();
	}

	public function get_role_list($select = "*")
	{
        $this->load->model('Role_model');

        // set user permission table
        $role = $this->Role_model->get_role_list($select = "*");
        return $role;
	}

	public function get_role_count($select = "*")
	{
        $this->load->model('Role_model');

        // set user permission table
        $count = $this->Role_model->get_role_count($select = "*");
        return $count;
	}
}