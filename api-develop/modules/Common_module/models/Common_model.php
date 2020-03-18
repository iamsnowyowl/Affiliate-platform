<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

  	function get_common_gender_list()
  	{
	    try
	    {
		    // select
		    $this->db->select("gender_code, gender_name")
		    	->from('tbl_gender');
		    // datetime filter
		    $query = $this->db->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_common_gender_list' . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}
}