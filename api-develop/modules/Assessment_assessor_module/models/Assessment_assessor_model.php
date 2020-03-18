<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assessment_assessor_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($check, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_assessment_assessor');
		    $this->db->where(array('assessor_id' => $check));

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
		    log_message("error", 'Error check: '.$check."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_assessment_assessor_by_id($assessment_assessor_id, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_assessment_assessor');
		    $this->db->where(array('assessment_assessor_id' => $assessment_assessor_id));

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
		    log_message("error", 'Error get_assessment_assessor_by_id: '.$assessment_assessor_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_assessment_assessor_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_assessment_assessor');

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
		    log_message("error", 'Error get_assessment_assessor_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		return $query->result();
  	}

  	function get_assessment_assessor_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_assessment_assessor');

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
		    log_message("error", 'Error get_assessment_assessor_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		return $query->row();
  	}

  	function find_not_assign_assessor_list($graph, $start_date, $end_date)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_user_accessor');

		    // datetime filter
	    	// if (!empty($start_date) && !empty($end_date)){
	    	// 	$this->db->where_not_in('v_user_accessor.user_id', 'SELECT created_by FROM v_assessment_by_assessor where start_date <= "'.$start_date.'" and end_date >= "'.$end_date.'"', FALSE);
	    	// }

	    	// if (!empty($graph->created_time))
		    // {
	    	// 	$this->db->group_start();
	    	// 	$this->db->where(" created_date BETWEEN '".$graph->created_time[0]."' AND '".$graph->created_time[1]."'", NULL, FALSE);
	    	// 	$this->db->group_end();
		    // }

		    // if (!empty($graph->modified_time))
		    // {
	    	// 	$this->db->or_group_start();
	    	// 	$this->db->where(" modified_date BETWEEN '".$graph->modified_time[0]."' AND '".$graph->modified_time[1]."'", NULL, FALSE);
	    	// 	$this->db->group_end();
		    // }

		    // filter
		    // if (!empty($graph->filter))
		    // {
		    // 	foreach ($graph->filter as $field => $each) {
		    // 		$this->db->group_start();
		    // 		foreach ($each as $key => $value) {
			//     		$this->db->or_where($field, $value);
		    // 		}
		    // 		$this->db->group_end();
		    // 	}
		    // }

		    // // filter in
		    // if (!empty($graph->filter_in))
		    // {
		    // 	foreach ($graph->filter_in as $field => $value) {
	    	// 		$this->db->group_start();
		    // 		$this->db->or_where_in($field, $value);
		    // 		$this->db->group_end();
		    // 	}
		    // }

		    // // filter not in
		    // if (!empty($graph->filter_not_in))
		    // {
		    // 	foreach ($graph->filter_not_in as $field => $value) {
	    	// 		$this->db->group_start();
		    // 		$this->db->or_where_not_in($field, $value);
		    // 		$this->db->group_end();
		    // 	}
		    // }

		    // // search
		    if (!empty($graph->search))
		    {
		    	foreach ($graph->search as $key => $value) {
		    		$this->db->group_start();
		    		$this->db->or_like($value, NULL, 'both'); // pro index
		    		$this->db->group_end();
		    	}
		    }

		    // // grouping
		    // if (!empty($graph->group))
		    // {
		    // 	$this->db->group_by($graph->group);
		    // }

		    // // sorting
		    // if (!empty($graph->sort))
		    // {
		    // 	foreach ($graph->sort as $key => $value) {
		    // 		$this->db->order_by($key, $value);
		    // 	}
		    // }

		    // limit
		    $this->db->limit($graph->limit, $graph->offset);
		    // execute
		    $query = $this->db->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_assessment_assessor_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function find_not_assign_assessor_count($graph, $start_date, $end_date)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_user_accessor');

		    // datetime filter

	    	// if (!empty($start_date) && !empty($end_date)){
	    	// 	$this->db->where_not_in('v_user_accessor.user_id', 'SELECT created_by FROM v_assessment_by_assessor where start_date <= "'.$start_date.'" and end_date >= "'.$end_date.'"', FALSE);
	    	// }
	    	
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
		    log_message("error", 'Error get_assessment_assessor_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_assessment_assessor($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$assessment_assessor_id = $this->db->insert('tbl_assessment_assessor', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_assessment_assessor: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$assessment_assessor_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $assessment_assessor_id;
  	}

  	function update_assessment_assessor_by_id($params, $assessment_id, $assessment_assessor_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("assessment_id", $assessment_id);
			$this->db->where("assessment_assessor_id", $assessment_assessor_id);
			$affeted_rows = $this->db->update('tbl_assessment_assessor');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_assessment_assessor_by_id: '.$assessment_assessor_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_soft_assessment_assessor_by_id($assessment_assessors, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($assessment_assessors)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_assessment_assessor', $assessment_assessors, 'assessment_assessor_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_soft_assessment_assessor_by_id: '.json_encode($assessment_assessors)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($assessment_assessors))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}

	function delete_hard_assessment_assessor_by_id($assessment_id, $assessment_assessor_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		if (empty($assessment_id) || empty($assessment_assessor_id)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			
			$tables = array('tbl_assessment_assessor');

			$this->db->where(array('assessment_id' => $assessment_id, 'assessment_assessor_id' => $assessment_assessor_id));
			
			$affeted_rows = $this->db->delete($tables);
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_hard_assessment_assessor_by_id: '.json_encode($assessment_assessor_id)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}
}