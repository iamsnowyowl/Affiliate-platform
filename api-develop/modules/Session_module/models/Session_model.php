<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication_model extends CI_Model 
{
	function get_auth_user_by_email($email, $select = "*")
  	{
	    try
	    {
		    $query = $this->db->get_where('tbl_user', array('email' => $email), 1, 0);
	    }
	    catch (PDOException $e) {
	    	throw new PDOException($e->getMessage(), 1);
		    log_message("error", 'Error get_auth_user_by_email: '.$email."<||>" . $e->getMessage());
		}

		return $query->row();
  	}
}