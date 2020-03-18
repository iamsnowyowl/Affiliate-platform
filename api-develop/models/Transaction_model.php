<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model 
{
	function trans_start()
	{
		$this->db->trans_start();
	}

	function trans_rollback()
	{
		$this->db->trans_rollback();
	}

	function trans_complete()
	{
		$this->db->trans_complete();
	}
}
