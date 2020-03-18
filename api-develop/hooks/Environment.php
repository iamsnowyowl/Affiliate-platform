<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Environment {


	public function load()
	{
        $dotenv = Dotenv\Dotenv::create(APPPATH);
        $dotenv->load();
	}
}