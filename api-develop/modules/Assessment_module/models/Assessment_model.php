<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assessment_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($check, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_assessment');
		    $this->db->where(array('title' => $check));

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

  	function get_assessment_by_id($assessment_id, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_assessment');
		    $this->db->where(array('assessment_id' => $assessment_id));

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
		    			$value = (is_numeric($value)) ? intval($value) : $value;
			    		$this->db->or_where($field, $value);
		    		}
		    		$this->db->group_end();
		    	}
		    }

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_assessment_by_id: '.$assessment_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		if (!empty($row->assessment_id))
		{
			
		}

		return $row;
  	}

  	function get_assessment_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)->from('v_assessment');

		    // datetime filter
		    if (!empty($graph->range_time)) {
		    	foreach ($graph->range_time as $key => $value) {
		    		if (empty($value[0]) && empty($value[1])) continue;
		    		$this->db->group_start();
		    		$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
				}
		    }

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
		    			$value = (is_numeric($value)) ? intval($value) : $value;
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
		    log_message("error", 'Error get_assessment_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$rows = $query->result();

		foreach ($rows as $key => $value) 
		{
			if (!empty($rows[$key]->start_date)) $rows[$key]->start_date = hari(date("N", strtotime($rows[$key]->start_date))).", ".date("d", strtotime($rows[$key]->start_date))." ".bulan(date("n", strtotime($rows[$key]->start_date)))." ".date("Y", strtotime($rows[$key]->start_date));
		}

		return $rows;
  	}

  	function get_assessment_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_assessment');

	    	// datetime filter
		    if (!empty($graph->range_time)) {
		    	foreach ($graph->range_time as $key => $value) {
		    		if (empty($value[0]) && empty($value[1])) continue;
		    		$this->db->group_start();
		    		$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
		    	}
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
		    log_message("error", 'Error get_assessment_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

	function get_assessment_deleted_list($graph)
	{
		try
		{
			
			$this->db->select($graph->select)->from('v_assessment_deleted');

			if (!empty($graph->range_time)) {
				foreach ($graph->range_time as $key => $value) {
					if (empty($value[0]) && empty ($value[1])) continue;
					$this->db->group_start();
					$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					$this->db->group_end();
				}
			}

			if (!empty($graph->filter))
			{
				foreach ($graph->filter as $field => $each) {
					$this->db->group_start();
					foreach ($each as $key => $value) {
						$value = (is_numeric($value)) ? intval($value) : $value;
						$this->db->or_where($field, $value);
					}
					$this->db->group_end();
				}
			}

			if (!empty($graph->filter_in))
			{
				foreach ($graph->filter_in as $field => $value) {
					$this->db->group_start();
					$this->db->or_where_in($field, $value);
					$this->db->group_end();
				}
			}

			if (!empty($graph->filter_not_in))
			{
				foreach ($graph->filter_not_in as $field => $value)
				{
					$this->db->group_start();
					$this->db->or_where_not_in($field, $value);
					$this->db->group_end();
				}
			}

			if (!empty($graph->search))
			{
				foreach ($graph->search as $key => $value) {
					$this->db->group_start();
					$this->db->or_like($value, NULL, 'after');

					$this->db->group_end();
				}
			}

			if (!empty($graph->group))
			{
				$this->db->group_by($graph->group);
			}

			if (!empty($graph->sort))
			{
				foreach ($graph->sort as $key => $value) {
					$this->db->order_by($key, $value);
				}
			}

			$this->db->limit($graph->limit, $graph->offset);

			$query = $this->db->get();
		}
		catch (PDOException $e) {
			log_message("error", 'Error get_assessment_deleted_list: '.json_encode($graph)."<||>" . $e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}

		$rows = $query->result();

		foreach ($rows as $key => $value)
		{
			if (!empty($rows[$key]->start_date)) $rows[$key]->start_date = hari(date("N", strtotime($rows[$key]->start_date))).", ".date("d", strtotime($rows[$key]->start_date))." ".bulan(date("n", strtotime($rows[$key]->start_date)))." ".date("Y", strtotime($rows[$key]->start_date));
		}
		return $rows;

	}

	function get_assessment_deleted_count($graph)
	{
		try
		{
			$this->db->select("COUNT(*) as count", FALSE)
				->from('v_assessment_deleted');

	    	// datetime filter
			if (!empty($graph->range_time)) {
				foreach ($graph->range_time as $key => $value) {
					if (empty($value[0]) && empty($value[1])) continue;
					$this->db->group_start();
					$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					$this->db->group_end();
				}
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

			//	filter not in
			if (!empty($graph->filter_not_in))
			{
				foreach ($graph->filter_not_in as $field => $value) {
					$this->db->group_start();
					$this->db->or_where_not_in($field, $value);
					$this->db->group_end();
				}
			}

			//	search
			if	(!empty($graph->search))
			{
				foreach	($graph->search as $key => $value) {
					$this->db->group_start();
					$this->db->or_like($value, NULL, 'after');
					$this->db->group_end();
				}
			}

			//	grouping
			if	(!empty($graph->group))
			{
				$this->db->group_by($graph->group);
			}

			if (!empty($graph->sort))
			{
				foreach	($graph->sort as $key => $value) {
					$this->db->order_by($key, $value);
				}
			}

			//	 execute
			$query = $this->db->get();
		}
		catch (PDOException $e) {
			log_message("error", 'Error get_assessment_deleted_count: '.json_encode($graph)."<||>" . $e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}
		$row = $query->row();
		
		return $row;
	}
	function non_admin_get_assessment_by_id($assessment_id, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->select("identifier")->from('v_assessment_owner_full');
		    $this->db->where(array('assessment_id' => $assessment_id));

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
		    			$value = (is_numeric($value)) ? intval($value) : $value;
			    		$this->db->or_where($field, $value);
		    		}
		    		$this->db->group_end();
		    	}
		    }

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_assessment_by_id: '.$assessment_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row->assessment_id))
		{
			
		}

		return $row;
  	}

  	function non_admin_get_assessment_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)->select("identifier")
		    	->from('v_assessment_owner_full');

		    // datetime filter
		    if (!empty($graph->range_time)) {
		    	foreach ($graph->range_time as $key => $value) {
		    		if (empty($value[0]) && empty($value[1])) continue;
		    		$this->db->group_start();
		    		$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
		    	}
		    }

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
		    		$this->db->group_start();
		    		foreach ($each as $key => $value) {
		    			$value = (is_numeric($value)) ? intval($value) : $value;
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
		    log_message("error", 'Error get_assessment_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$rows = $query->result();

		foreach ($rows as $key => $value) 
		{
			
		}

		return $rows;
  	}

  	function non_admin_get_assessment_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_assessment_owner_full');

	    	// datetime filter
		    if (!empty($graph->range_time)) {
		    	foreach ($graph->range_time as $key => $value) {
		    		if (empty($value[0]) && empty($value[1])) continue;
		    		$this->db->group_start();
		    		$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
		    		$this->db->group_end();
		    	}
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
		    log_message("error", 'Error get_assessment_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_assessment($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$assessment_id = $this->db->insert('tbl_assessment', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_assessment: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$assessment_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $assessment_id;
  	}

  	function update_assessment_by_id($params, $assessment_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("assessment_id", $assessment_id);
			$affeted_rows = $this->db->update('tbl_assessment');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_assessment_by_id: '.$assessment_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function update_assessment($params, $condition, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);

			if (!empty($condition) && is_array($condition)) {
				$this->db->where($condition);
			}
			$affeted_rows = $this->db->update('tbl_assessment');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_assessment: ' . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_soft_assessment_by_id($assessments, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($assessments)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_assessment', $assessments, 'assessment_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_soft_assessment_by_id: '.json_encode($assessments)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($assessments))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}

	function delete_hard_assessment_by_id($assessment_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		if (empty($assessment_id)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			
			$tables = array('tbl_assessment');

			$this->db->where(array('assessment_id' => $assessment_id));
			
			$affeted_rows = $this->db->delete($tables);
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_hard_assessment_by_id: '.json_encode($assessment_id)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}

	function update_deleted_list($assessment_id, $params ,$auto_commit = TRUE)
	{

		$affeted_rows = 0;
		
		try
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("assessment_id", $assessment_id);
			$affeted_rows = $this->db->update('tbl_assessment');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_deleted_list_by_id: '.$assessment_id."<||>" . $e->getMessage());
			$status = FALSE;
			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;

	}
}