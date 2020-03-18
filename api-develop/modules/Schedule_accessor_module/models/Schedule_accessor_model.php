<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_accessor_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_schedule_accessor_by_date($date, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_schedule_accessor')
		    	->where(array('date' => $date))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_schedule_accessor_by_date: '.$date."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_schedule_accessor_by_id($schedule_accessor_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_schedule_accessor')
		    	->where(array('schedule_accessor_id' => $schedule_accessor_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_schedule_accessor_by_id: '.$schedule_accessor_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			if (isset($row->CalendarDay))
			{
				$row->CalendarDay = date("Y-m-d", strtotime($row[$key]->CalendarDay));
			}
		}
		
		return $row;
  	}

  	function get_schedule_accessor_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_schedule_accessor');

		    // datetime filter
		    if (!empty($graph->range_time))
		    {
		    	foreach ($graph->range_time as $field => $value) {
		    		$this->db->group_start();
    				$this->db->where($field." BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
		    	}
		    }

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
		    log_message("error", 'Error get_schedule_accessor_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		if (!empty($row))
		{
			foreach ($row as $key => $value) 
			{
				// since there is fix value and not null on this variable. then i choose isset as test case
				if (isset($row[$key]->CalendarDay)) $row[$key]->CalendarDay = date("Y-m-d", strtotime($row[$key]->CalendarDay));
			}
		}

		return $row;
  	}

  	function get_schedule_accessor_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_schedule_accessor');

		    // datetime filter
		    if (!empty($graph->range_time))
		    {
		    	foreach ($graph->range_time as $field => $value) {
		    		$this->db->group_start();
    				$this->db->where($field." BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
		    	}
		    }
		    
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
		    log_message("error", 'Error get_schedule_accessor_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_schedule_accessor($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$query = $this->db->set($params)->get_compiled_insert('tbl_schedule_accessor');
			// since we are delete before inserting then we need to set date to our default date. milenium year
			$query .= " ON DUPLICATE KEY UPDATE modified_date = NOW(), deleted_at = '". $this->config->item('deleted_at')."'";

			$schedule_accessor_id = $this->db->query($query);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_schedule_accessor: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$schedule_accessor_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $schedule_accessor_id;
  	}

  	function update_schedule_accessor_by_id($params, $schedule_accessor_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			$schedule_accessor_id = intval($schedule_accessor_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("schedule_accessor_id", $schedule_accessor_id);
			$affeted_rows = $this->db->update('tbl_schedule_accessor');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_schedule_accessor_by_id: '.$schedule_accessor_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_schedule_accessor_by_id($schedule_accessors, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($schedule_accessors)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_schedule_accessor', $schedule_accessors, 'schedule_accessor_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_schedule_accessor_by_id: '.json_encode($schedule_accessors)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($schedule_accessors))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}

	function delete_schedule_accessor_by_accessor_id($accessor_ids, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($accessor_ids)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_schedule_accessor', $accessor_ids, 'accessor_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_schedule_accessor_by_accessor_id: '.json_encode($accessor_ids)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}
}