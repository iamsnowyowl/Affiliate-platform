<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model 
{
	function get_role_by_code($role_code = NULL, $select = "*")
  	{
	    try
	    {
		    $query = $this->db->select($select)->get_where('tbl_role', array('role_code' => $role_code));
	    }
	    catch (PDOException $e) {
	    	throw new PDOException($e->getMessage(), 1);
		    log_message("error", 'Error get_user_permission_by_id: '.$role_code."<||>" . $e->getMessage());
		}

		$row = $query->row();
		
		return $row;
  	}

	function get_role_list($select = "*")
	{
		try
	    {
		    $query = $this->db->select($select)->get_where('tbl_role');
	    }
	    catch (PDOException $e) {
	    	throw new PDOException($e->getMessage(), 1);
		    log_message("error", 'Error get_user_permission_by_id: '.$role_code."<||>" . $e->getMessage());
		}

		$result = $query->result();
		
		return $result;
	}

	function get_role_count()
	{
		try
	    {
		    $query = $this->db->select('COUNT(*) AS count', FALSE)->get_where('tbl_role');
	    }
	    catch (PDOException $e) {
	    	throw new PDOException($e->getMessage(), 1);
		    log_message("error", 'Error get_user_permission_by_id: '.$role_code."<||>" . $e->getMessage());
		}

		$result = $query->row();
		
		return $result;
	}
}
