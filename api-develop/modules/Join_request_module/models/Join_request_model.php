<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Join_request_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($applicant_id, $sub_schema_number, $graph)
  	{
	    try
	    {
			$this->db->select($graph->select)->from('tbl_join_request')
			->join('v_schema_full_wd', 'tbl_join_request.sub_schema_number = v_schema_full_wd.sub_schema_number', 'left')
			->join('tbl_user', 'tbl_user.user_id = tbl_join_request.applicant_id', 'left');
		    $this->db->where(array('applicant_id' => $applicant_id));
		    $this->db->where(array('tbl_join_request.sub_schema_number' => $sub_schema_number));

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

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error check: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_join_request_by_id($join_request_id, $graph)
  	{
	    try
	    {
			$this->db->select($graph->select)->from('tbl_join_request')
			->join('v_schema_full_wd', 'tbl_join_request.sub_schema_number = v_schema_full_wd.sub_schema_number', 'left')
			->join('tbl_user', 'tbl_user.user_id = tbl_join_request.applicant_id', 'left');
		    $this->db->where(array('join_request_id' => $join_request_id));

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

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_join_request_by_id: '.$join_request_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_join_request_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
				->from('tbl_join_request')
				->join('v_schema_full_wd', 'tbl_join_request.sub_schema_number = v_schema_full_wd.sub_schema_number', 'left')
				->join('tbl_user', 'tbl_user.user_id = tbl_join_request.applicant_id', 'left');


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
		    log_message("error", 'Error get_join_request_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_join_request_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
				->from('tbl_join_request')
				->join('v_schema_full_wd', 'tbl_join_request.sub_schema_number = v_schema_full_wd.sub_schema_number', 'left')
				->join('tbl_user', 'tbl_user.user_id = tbl_join_request.applicant_id', 'left');

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
		    log_message("error", 'Error get_join_request_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_join_request($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$join_request_id = $this->db->insert('tbl_join_request', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_join_request: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$join_request_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $join_request_id;
  	}

  	function update_join_request_by_id($params, $join_request_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("join_request_id", $join_request_id);
			$affeted_rows = $this->db->update('tbl_join_request');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_join_request_by_id: '.$join_request_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function update_join_request($params, $condition, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);

			if (!empty($condition) && is_array($condition)) {
				$this->db->where($condition);
			}
			$affeted_rows = $this->db->update('tbl_join_request');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_join_request: ' . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_soft_join_request_by_id($join_requests, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($join_requests)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_join_request', $join_requests, 'join_request_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_soft_join_request_by_id: '.json_encode($join_requests)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($join_requests))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}

	function delete_hard_join_request_by_id($join_request_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		if (empty($join_request_id)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			
			$tables = array('tbl_join_request');

			$this->db->where(array('join_request_id' => $join_request_id));
			
			$affeted_rows = $this->db->delete($tables);
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_hard_join_request_by_id: '.json_encode($join_request_id)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}
}