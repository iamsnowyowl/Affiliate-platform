<?php 
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applicant_portfolio_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function check($check, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_applicant_portfolio');
		    $this->db->where(array('applicant_portfolio_name' => $check));

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

  	function get_ungroup_applicant_portfolio_by_id($applicant_portfolio_id)
  	{
	    try
	    {
		    $this->db->select("*")->from('tbl_applicant_portfolio');
		    $this->db->where(array('applicant_portfolio_id' => $applicant_portfolio_id));
		    $query = $this->db->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_applicant_portfolio_by_id: '.$applicant_portfolio_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_applicant_portfolio_by_id($applicant_portfolio_id, $graph)
  	{
	    try
	    {
		    $this->db->select($graph->select)->from('v_applicant_portfolio');
		    $this->db->where(array('applicant_portfolio_id' => $applicant_portfolio_id));

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
		    log_message("error", 'Error get_applicant_portfolio_by_id: '.$applicant_portfolio_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_applicant_portfolio_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_applicant_portfolio');

			// datetime filter
			// debug($graph);

		    // filter
			if (!empty($graph->filter))
			{
				foreach ($graph->filter as $field => $each) {
					if ($field == "v_applicant_portfolio.apl_document_state"){
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.apl_document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else if ($field == "v_applicant_portfolio.acs_document_state")
					{
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.acs_document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else if ($field == "v_applicant_portfolio.document_state")
					{
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else
					{
						$this->db->group_start();
						foreach ($each as $key => $value) {
							$this->db->or_where($field, $value);
						}
						$this->db->group_end();
					}
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
		    log_message("error", 'Error get_applicant_portfolio_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$rows = $query->result();

		foreach ($rows as $key => $row) 
		{
			$row->applicant_portfolio_id = explode(",", $row->applicant_portfolio_id);
			$row->form_value = explode(",", $row->form_value);
			$row->filename = explode(",", $row->filename);
			$row->mime_type = explode(",", $row->mime_type);
			$row->ext = explode(",", $row->ext);

			$rows[$key]->applicant_portfolio = array();
			foreach ($row->applicant_portfolio_id as $key2 => $value) 
			{
				$rows[$key]->applicant_portfolio[$key2] = array(
					"applicant_portfolio_id" => (!empty($row->applicant_portfolio_id[$key2])) ? $row->applicant_portfolio_id[$key2] : "", 
					"form_value" => (!empty($row->form_value[$key2])) ? $row->form_value[$key2] : "", 
					"filename" => (!empty($row->filename[$key2])) ? $row->filename[$key2] : "", 
					"mime_type" => (!empty($row->mime_type[$key2])) ? $row->mime_type[$key2] : "", 
					"ext" => (!empty($row->ext[$key2])) ? $row->ext[$key2] : ""
				);
			}

			unset($row->applicant_portfolio_id);
			unset($row->form_value);
			unset($row->filename);
			unset($row->mime_type);
			unset($row->ext);
		}

		return $rows;
  	}

  	function get_applicant_portfolio_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_applicant_portfolio');

		    // filter
		    if (!empty($graph->filter))
		    {
		    	foreach ($graph->filter as $field => $each) {
					if ($field == "v_applicant_portfolio.apl_document_state"){
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.apl_document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else if ($field == "v_applicant_portfolio.acs_document_state")
					{
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.acs_document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else if ($field == "v_applicant_portfolio.document_state")
					{
						$this->db->group_start();
						for ($i=0; $i < count($each); $i++) { 
							$this->db->or_like("v_applicant_portfolio.document_state", $each[$i], 'both'); // pro index
						}
						$this->db->group_end();
					}
					else
					{
						$this->db->group_start();
						foreach ($each as $key => $value) {
							$this->db->or_where($field, $value);
						}
						$this->db->group_end();
					}
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
		    log_message("error", 'Error get_applicant_portfolio_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_applicant_portfolio($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$applicant_portfolio_id = $this->db->insert('tbl_applicant_portfolio', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_applicant_portfolio: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$applicant_portfolio_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $applicant_portfolio_id;
  	}

  	function update_applicant_portfolio_by_id($params, $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("assessment_id", $assessment_id);
			$this->db->where("assessment_applicant_id", $assessment_applicant_id);
			$this->db->where("applicant_portfolio_id", $applicant_portfolio_id);

			$affeted_rows = $this->db->update('tbl_applicant_portfolio');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_applicant_portfolio_by_id: '.$applicant_portfolio_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function update_applicant_portfolio($params, $condition, $auto_commit = TRUE)
	{
		$affeted_rows = 0;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);

			if (!empty($condition) && is_array($condition)) {
				$this->db->where($condition);
			}
			$affeted_rows = $this->db->update('tbl_applicant_portfolio');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_applicant_portfolio: ' . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}

	function delete_hard_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		if (empty($assessment_id) || empty($assessment_applicant_id) || empty($applicant_portfolio_id)) return TRUE;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			
			$tables = array('tbl_applicant_portfolio');

			$this->db->where(array('assessment_id' => $assessment_id, 'assessment_applicant_id' => $assessment_applicant_id, 'applicant_portfolio_id' => $applicant_portfolio_id));
			
			$affeted_rows = $this->db->delete($tables);
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_hard_applicant_portfolio_by_id: '.json_encode($applicant_portfolio_id)."<||>" . $e->getMessage());
  			$affeted_rows = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($auto_commit) $this->db->trans_complete();

		return $affeted_rows;
	}
}