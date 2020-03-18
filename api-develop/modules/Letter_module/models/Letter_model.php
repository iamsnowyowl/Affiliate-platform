<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Letter_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($check, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('tbl_letter');
		    $this->db->where(array('letter_name' => $check));

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

  	function get_letter_by_id($letter_id, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('tbl_letter');
		    $this->db->where(array('letter_id' => $letter_id));

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
		    log_message("error", 'Error get_letter_by_id: '.$letter_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_letter_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('tbl_letter');

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
		    log_message("error", 'Error get_letter_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_letter_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('tbl_letter');

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
		    log_message("error", 'Error get_letter_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}
	  function get_letter_deleted_list($graph)
	  {
		  try
		  {
			  //	select
			  $this->db->select($graph->select)->
				  from('v_letter_deleted');
  
			  //	datetime filter
			  if	(!empty($graph->range_time)) {
				  foreach ($graph->range_time as $key -> $value) {
					  if (empty($value[0]) && empty($value[1])) continue;
					  $this->db->group_start();
					  $this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					  $this->db->group_end();
				  }
			  }
  
			  //	filter
			  if (!empty($graph->filter))
			  {
				  foreach	($graph->filter as $field => $each) {
					  $this->db->group_start();
					  foreach ($each as $key => $value) {
						  $value = (is_numeric($value)) ? intval($value) : $value;
						  $this->db->or_where($field, $value);
					  }
					  $this->db->group_end();
				  }
			  }
  
			  //	filter_in
  
			  if (!empty($graph->filter_in))
			  {
				  foreach ($graph->filter_in as $field => $value) {
					  $this->db->group_start();
					  $this->db->or_where_in($field, $value);
					  $this->db->group_end();
  
				  }
			  }
  
			  //	filter_not_in
			  if(!empty($graph->filter_not_in))
			  {
				  foreach ($graph->filter_not_in as $field => $value)
				  {
					  $this->db->group_start();
					  $this->db->or_where_not_in($field, $value);
					  $this->db->group_end();
				  }
			  }
  
			  //	search
			  if(!empty($graph->search))
			  {
				  foreach ($graph->search as $key => $value)
				  {
					  $this->db->group_start();
					  $this->db->or_like($value, NULL, 'after');
					  $this->db->group_end();
				  }
			  }
  
			  //	group
			  if (!empty($graph->group))
			  {
				  $this->db->group_by($graph->group);
			  }
  
			  //	sort
			  if (!empty($graph->sort))
			  {
				  foreach ($graph->sort as $key => $value)
				  {
					  $this->db->order_by($key, $value);
				  }
			  }
  
			  $this->db->limit($graph->limit, $graph->offset);
  
			  $query = $this->db->get();
			  
		  }
		  catch (PDOException $e) {
			  log_message("error", 'Error get_letter_deleted_list: '.json_encode($graph)."<||>" . $e->getMessage());
			  throw new PDOException($e->getMessage(), 1);
		  }
		  
		  $rows = $query->result();

		  foreach ($rows as $key => $value)
		  {
			  if (!empty($rows[$key]->start_date)) $rows[$key]->start_date = hari(date("N", strtotime($rows[$key]->start_date))).", ".date("d", strtotime($rows[$key]->start_date))." ".bulan(date("n", strtotime($rows[$key]->start_date)))." ".date("Y", strtotime($rows[$key]->start_date));
		  }
		  return $rows;
	  }
  
	  function get_letter_deleted_count($graph)
	  {
		  try
		  {
			  $this->db->select("COUNT(*) as count", FALSE)
				  ->from('v_letter_deleted');
  
			  // datetime filter
			  if (!empty($graph->range_time))
			  {
				  foreach ($graph->range_time as $key => $value)
				  {
					  if(empty($value[0]) && empty($value[1])) continue;
					  $this->db->group_start();
					  $this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					  $this->db->group_end();
				  }
			  }
  
			  // filter
			  if (!empty($graph->filter))
			  {
				  foreach ($graph->filter as $field => $value)
				  {
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
				  foreach ($graph->filter_in as $field => $value)
				  {
					  $this->db->group_start();
					  $this->db->or_where_in($field, $value);
					  $this->db->group_end();
				  }
			  }
  
			  // filter not in
			  if (!empty($graph->filter_not_in))
			  {
				  foreach ($graph->filter_not_in as $field => $value)
				  {
					  $this->db->group_start();
					  $this->db->or_where_not_in($field, $value);
					  $this->db->group_end();
				  }
			  }
  
			  // search
			  if (!empty($graph->search))
			  {
				  foreach($graph->search as $key => $value) {
					  $this->db->group_start();
					  $this->db->or_like($value, NULL, 'after');
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
				  foreach ($graph->sort as $key => $value)
				  {
					  $this->db->order_by($key, $value);
				  }
			  }
  
			  $query = $this->db->get();
		  }
		  catch (PDOException $e) {
			  log_message("error", 'Errpr get_letter_deleted_count: '.json_encode($graph)."<||>" . $e->getMessage());
			  throw new PDOException($e->getMessage(), 1);
		  }
  
		  $row = $query->row();
  
		  return $row;
	  }
	  
  	function update_letter_by_id($params, $letter_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("letter_id", $letter_id);
			$affeted_rows = $this->db->update('tbl_letter');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_letter_by_id: '.$letter_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function update_deleted_by_id($letter_id, $params, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try {
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("letter_id", $letter_id);
			$affeted_rows = $this->db->update('tbl_letter');
			if ($auto_commit) $this->db->trans_complete();
		} catch (PDOException $e) {
			log_message("error", 'Error_update_deleted_list_by_id; '.$letter_id."<||>" . $e->getMessage());
			$status = FALSE;
			throw new PDOException($e->getMessage(), 1);
		}
		
		return $affeted_rows;
	}
}