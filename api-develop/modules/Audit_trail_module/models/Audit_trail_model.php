<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit_trail_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_audit_trail_by_name($audit_trail_name, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_audit_trail')
		    	->where(array('audit_trail_name' => $audit_trail_name))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_audit_trail_by_name: '.$audit_trail_name."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_audit_trail_by_id($audit_trail_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_audit_trail')
		    	->where(array('audit_trail_id' => $audit_trail_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_audit_trail_by_id: '.$audit_trail_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_audit_trail_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_audit_trail');

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

		    // filter in
		    if (!empty($graph->filter_in))
		    {
		    	foreach ($graph->filter_in as $field => $value) {
	    			$this->db->group_start();
		    		$this->db->or_where_in($field, $value);
		    		$this->db->group_end();
		    	}
		    }

		    // filter not in
		    if (!empty($graph->filter_not_in))
		    {
		    	foreach ($graph->filter_not_in as $field => $value) {
	    			$this->db->group_start();
		    		$this->db->or_where_not_in($field, $value);
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
		    log_message("error", 'Error get_audit_trail_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_audit_trail_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_audit_trail');

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

		    // filter in
		    if (!empty($graph->filter_in))
		    {
		    	foreach ($graph->filter_in as $field => $value) {
	    			$this->db->group_start();
		    		$this->db->or_where_in($field, $value);
		    		$this->db->group_end();
		    	}
		    }

		    // filter not in
		    if (!empty($graph->filter_not_in))
		    {
		    	foreach ($graph->filter_not_in as $field => $value) {
	    			$this->db->group_start();
		    		$this->db->or_where_not_in($field, $value);
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
		    log_message("error", 'Error get_audit_trail_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_audit_trail($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$audit_trail_id = $this->db->insert('tbl_audit_trail', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_audit_trail: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$audit_trail_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $audit_trail_id;
  	}

  	function update_audit_trail_by_id($params, $audit_trail_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			$audit_trail_id = intval($audit_trail_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("audit_trail_id", $audit_trail_id);
			$affeted_rows = $this->db->update('tbl_audit_trail');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_audit_trail_by_id: '.$audit_trail_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_audit_trail_by_id($audit_trails, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($audit_trails)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_audit_trail', $audit_trails, 'audit_trail_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_audit_trail_by_id: '.json_encode($audit_trails)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($audit_trails))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}
}