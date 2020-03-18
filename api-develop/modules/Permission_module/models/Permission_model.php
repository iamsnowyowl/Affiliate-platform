<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_permission_by_role_code($role_code = NULL, $select = "*")
  	{
	    try
	    {
		    $query = $this->db->select($select)->get_where('tbl_permission', array('role_code' => $role_code));
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_permission_by_role_code: '.$role_code."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();
		
		return $row;
  	}

	function get_user_permission_by_id($user_id = NULL, $select = "*")
  	{
	    try
	    {
		    $query = $this->db->select($select)->get_where('tbl_permission', array('user_id' => $user_id));
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_permission_by_id: '.$user_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();
		
		return $row;
  	}
  	
  	function get_permission_by_id($permission_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('tbl_permission')
		    	->where(array('permission_id' => $permission_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_permission_by_id: '.$permission_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_permission_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('tbl_permission');

		    // datetime filter
	    	if (!empty($graph->created_time))
		    {
	    		$this->db->group_start();
	    		$this->db->where(" created_date BETWEEN '".$graph->created_time[0]."' AND '".$graph->created_time[1]."'", NULL, FALSE);
	    		$this->db->group_end();
		    }

		    if (!empty($graph->modified_time))
		    {
	    		$this->db->or_group_start();
	    		$this->db->where(" modified_date BETWEEN '".$graph->modified_time[0]."' AND '".$graph->modified_time[1]."'", NULL, FALSE);
	    		$this->db->group_end();
		    }

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
			    		$this->db->or_where($field, $value);
		    		}
		    		$this->db->group_end();
		    	}
		    }

		    // search
		    if (!empty($graph->search))
		    {
		    	foreach ($graph->search as $key => $value) {
		    		$this->db->group_start();
		    		$this->db->or_like($value, NULL, 'after'); // pro index
		    		$this->db->group_end();
		    	}
		    }

		    // grouping
		    if (!empty($graph->group))
		    {
		    	$this->db->group_by($graph->group);
		    }

		    // sorting
		    if (!empty($graph->sort))
		    {
		    	foreach ($graph->sort as $key => $value) {
		    		$this->db->order_by($key, $value);
		    	}
		    }

		    // limit
		    $this->db->limit($graph->limit, $graph->offset);
		    // execute
		    $query = $this->db->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_permission_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_permission_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('tbl_permission');

		    // datetime filter
	    	if (!empty($graph->created_time))
		    {
	    		$this->db->group_start();
	    		$this->db->where(" created_date BETWEEN '".$graph->created_time[0]."' AND '".$graph->created_time[1]."'", NULL, FALSE);
	    		$this->db->group_end();
		    }

		    if (!empty($graph->modified_time))
		    {
	    		$this->db->or_group_start();
	    		$this->db->where(" modified_date BETWEEN '".$graph->modified_time[0]."' AND '".$graph->modified_time[1]."'", NULL, FALSE);
	    		$this->db->group_end();
		    }

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
			    		$this->db->or_where($field, $value);
		    		}
		    		$this->db->group_end();
		    	}
		    }

		    // search
		    if (!empty($graph->search))
		    {
		    	foreach ($graph->search as $key => $value) {
		    		$this->db->group_start();
		    		$this->db->or_like($value, NULL, 'after'); // pro index
		    		$this->db->group_end();
		    	}
		    }

		    // grouping
		    if (!empty($graph->group))
		    {
		    	$this->db->group_by($graph->group);
		    }

		    // sorting
		    if (!empty($graph->sort))
		    {
		    	foreach ($graph->sort as $key => $value) {
		    		$this->db->order_by($key, $value);
		    	}
		    }

		    // execute
		    $query = $this->db->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_permission_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_permission($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$permission_id = $this->db->insert('tbl_permission', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_permission: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$permission_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $permission_id;
  	}

  	function update_permission_by_id($params, $permission_id, $auto_commit = TRUE)
	{
		$affeted_row = 0;

		try 
		{
			$permission_id = intval($permission_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("permission_id", $permission_id);
			$affeted_row = $this->db->update('tbl_permission');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_permission_by_id: '.$permission_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_row;
	}

	function delete_permission_by_id($permissions, $auto_commit = TRUE)
	{
		$affeted_row = 0;
		if (empty($permissions)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_row = $this->db->update_batch('tbl_permission', $permissions, 'permission_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_permission_by_id: '.json_encode($permissions)."<||>" . $e->getMessage());
  			$affeted_row = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_row != count($permissions))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_row;
	}
}