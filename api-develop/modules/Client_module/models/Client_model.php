<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_api_key_information($api_key)
  	{
	    try
	    {
		    $this->db->select("*")->from('v_tuk');
		    $this->db->where(array('api_key' => $api_key));

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error api_key: '.$api_key."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}
}