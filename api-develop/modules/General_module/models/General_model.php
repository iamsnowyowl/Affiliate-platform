<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model 
{
	public function __construct()
	{
		if (ENVIRONMENT == "development") $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_user_by_email($email, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'left')
		    	->join('tbl_gender', 'tbl_gender.gender_code = v_user.gender_code', 'left')
		    	->where(array('email' => $email))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_by_email: '.$email."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_user_by_id($user_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'left')
		    	->join('tbl_gender', 'tbl_gender.gender_code = v_user.gender_code', 'left')
		    	->where(array('user_id' => $user_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_by_id: '.$user_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();
		
		return $row;
  	}

  	function get_user_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'left')
		    	->join('tbl_gender', 'tbl_gender.gender_code = v_user.gender_code', 'left');

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
		    log_message("error", 'Error get_user_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
  	}

  	function get_user_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'left')
		    	->join('tbl_gender', 'tbl_gender.gender_code = v_user.gender_code', 'left');

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
		    log_message("error", 'Error get_user_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

  	function create_user($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$user_id = $this->db->insert('tbl_user', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_user: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$user_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $user_id;
  	}

  	function update_user_by_id($params, $user_id, $auto_commit = TRUE)
	{
		$affeted_row = 0;

		try 
		{
			$user_id = intval($user_id);
			
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("user_id", $user_id);
			$affeted_row = $this->db->update('tbl_user');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_user_by_id: '.$user_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_row;
	}

	function delete_user_by_id($users, $auto_commit = TRUE)
	{
		$affeted_row = 0;
		if (empty($users)) return TRUE;
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$affeted_row = $this->db->update_batch('tbl_user', $users, 'user_id');
		}
		catch (PDOException $e) {
			log_message("error", 'Error delete_user_by_id: '.json_encode($users)."<||>" . $e->getMessage());
  			$affeted_row = FALSE;
			if ($auto_commit) $this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}

		if ($affeted_row != count($users))
		{
			if ($auto_commit) $this->db->trans_rollback();
		}
		else
		{
			if ($auto_commit) $this->db->trans_complete();
		}

		return $affeted_row;
	}

  	function update_last_login($user_id, $auto_commit = TRUE)
  	{
  		$status = TRUE;
  		
  		try {
  			$this->db->set('last_login', 'NOW()', FALSE);
			$this->db->where('user_id', intval($user_id));
			$this->db->update('tbl_user'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
  		} catch (PDOException $e) {
		    log_message("error", 'Error update_last_login: '.$user_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
  		}
  		return $status;
  	}
}