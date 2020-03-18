<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accessor_competence_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_accessor_competence_by_user_id_and_cfc($competence_field_code, $user_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_accessor_competence')
		    	->where(array('competence_field_code' => $competence_field_code))
		    	->where(array('user_id' => $user_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_accessor_competence_by_user_id_and_cfc: '.$competence_field_code."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			// since there is fix value and not null on this variable. then i choose isset as test case
			if (isset($row->verification_flag)) $row->verification_flag = intval($row->verification_flag);
			if (isset($row->expired_flag)) $row->expired_flag = intval($row->expired_flag);
		}
		
		return $row;
  	}

  	function get_accessor_competence_by_id($accessor_competence_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_accessor_competence')
		    	->where(array('accessor_competence_id' => $accessor_competence_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_accessor_competence_by_id: '.$accessor_competence_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			// since there is fix value and not null on this variable. then i choose isset as test case
			if (isset($row->verification_flag)) $row->verification_flag = intval($row->verification_flag);
			if (isset($row->expired_flag)) $row->expired_flag = intval($row->expired_flag);
		}
		
		return $row;
  	}

  	function get_accessor_competence_by_code($accessor_competence_code, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_accessor_competence')
		    	->where(array('accessor_competence_id' => $accessor_competence_code))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_accessor_competence_by_code: '.$accessor_competence_code."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			// since there is fix value and not null on this variable. then i choose isset as test case
			if (isset($row->verification_flag)) $row->verification_flag = intval($row->verification_flag);
			if (isset($row->expired_flag)) $row->expired_flag = intval($row->expired_flag);
		}
		
		return $row;
  	}

  	function get_accessor_competence_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_accessor_competence');

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
		    log_message("error", 'Error get_accessor_competence_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();
		
		if (!empty($row))
		{
			foreach ($row as $key => $value) 
			{
				// since there is fix value and not null on this variable. then i choose isset as test case
				if (isset($row[$key]->verification_flag)) $row[$key]->verification_flag = intval($row[$key]->verification_flag);
				if (isset($row[$key]->expired_flag)) $row[$key]->expired_flag = intval($row[$key]->expired_flag);
			}
		}

		return $row;
  	}

  	function get_accessor_competence_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_accessor_competence');

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
		    log_message("error", 'Error get_accessor_competence_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_accessor_competence($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$accessor_competence_id = $this->db->insert('tbl_accessor_competence', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_accessor_competence: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$accessor_competence_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $accessor_competence_id;
  	}

  	function update_accessor_competence_by_id($params, $accessor_competence_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			$accessor_competence_id = intval($accessor_competence_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("accessor_competence_id", $accessor_competence_id);
			$affeted_rows = $this->db->update('tbl_accessor_competence');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_accessor_competence_by_id: '.$accessor_competence_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_accessor_competence_by_code($accessor_competences, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($accessor_competences)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_accessor_competence', $accessor_competences, 'accessor_competence_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_accessor_competence_by_code: '.json_encode($accessor_competences)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($accessor_competences))
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