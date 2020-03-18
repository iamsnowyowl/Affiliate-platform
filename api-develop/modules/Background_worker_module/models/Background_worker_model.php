<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Background_worker_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_background_worker_by_name($background_worker_name, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_background_worker')
		    	->where(array('background_worker_name' => $background_worker_name))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_background_worker_by_name: '.$background_worker_name."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_background_worker_by_id($background_worker_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_background_worker')
		    	->where(array('background_worker_id' => $background_worker_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_background_worker_by_id: '.$background_worker_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_background_worker_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_background_worker');

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
		    log_message("error", 'Error get_background_worker_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_background_worker_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_background_worker');

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
		    log_message("error", 'Error get_background_worker_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_background_worker($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$background_worker_id = $this->db->insert('tbl_background_worker', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_background_worker: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$background_worker_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $background_worker_id;
  	}

  	function update_background_worker_by_id($params, $background_worker_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			$background_worker_id = intval($background_worker_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("background_worker_id", $background_worker_id);
			$affeted_rows = $this->db->update('tbl_background_worker');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_background_worker_by_id: '.$background_worker_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_background_worker_by_id($background_workers, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($background_workers)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_background_worker', $background_workers, 'background_worker_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_background_worker_by_id: '.json_encode($background_workers)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($background_workers))
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