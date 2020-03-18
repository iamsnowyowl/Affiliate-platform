<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_portfolio_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($check, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_master_portfolio');
		    $this->db->where(array('master_portfolio_name' => $check));

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

  	function get_master_portfolio_by_id($master_portfolio_id, $graph)
  	{
	    try
	    {
			$this->db->reset_query();
		    $this->db->select($graph->select)->from('v_master_portfolio');
		    $this->db->where(array('master_portfolio_id' => $master_portfolio_id));

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

		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_master_portfolio_by_id: '.$master_portfolio_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if ($row)
		{
			if (!empty($row->document_state)){
				$row->document_state = json_decode($row->document_state);
			}
			else $row->document_state = [];
			if (!empty($row->apl_document_state)){
				$row->apl_document_state = json_decode($row->apl_document_state);
			}
			else $row->apl_document_state = [];
			if (!empty($row->acs_document_state)){
				$row->acs_document_state = json_decode($row->acs_document_state);
			}
			else $row->acs_document_state = [];
		}
		
		return $row;
  	}

  	function get_master_portfolio_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_master_portfolio');

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
		    log_message("error", 'Error get_master_portfolio_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		for ($i=0; $i < count($row); $i++) { 
			if (!empty($row[$i]->document_state)){
				$row[$i]->document_state = json_decode($row[$i]->document_state);
			}
			else $row[$i]->document_state = [];

			if (!empty($row[$i]->apl_document_state)){
				$row[$i]->apl_document_state = json_decode($row[$i]->apl_document_state);
			}
			else $row[$i]->apl_document_state = [];

			if (!empty($row[$i]->acs_document_state)){
				$row[$i]->acs_document_state = json_decode($row[$i]->acs_document_state);
			}
			else $row[$i]->acs_document_state = [];
		}

		return $row;
  	}

  	function get_master_portfolio_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_master_portfolio');

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
		    log_message("error", 'Error get_master_portfolio_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_master_portfolio($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$master_portfolio_id = $this->db->insert('tbl_master_portfolio', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_master_portfolio: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$master_portfolio_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $master_portfolio_id;
  	}

  	function update_master_portfolio_by_id($params, $master_portfolio_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("master_portfolio_id", $master_portfolio_id);
			$affeted_rows = $this->db->update('tbl_master_portfolio');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_master_portfolio_by_id: '.$master_portfolio_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_soft_master_portfolio_by_id($master_portfolios, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		if (empty($master_portfolios)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_rows = $this->db->update_batch('tbl_master_portfolio', $master_portfolios, 'master_portfolio_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_soft_master_portfolio_by_id: '.json_encode($master_portfolios)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_rows != count($master_portfolios))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_rows;
	}

	function delete_hard_master_portfolio_by_id($master_portfolio_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		if (empty($master_portfolio_id)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			
			$tables = array('tbl_master_portfolio');

			$this->db->where(array('master_portfolio_id' => $master_portfolio_id));
			
			$affeted_rows = $this->db->delete($tables);
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_hard_master_portfolio_by_id: '.json_encode($master_portfolio_id)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}
}