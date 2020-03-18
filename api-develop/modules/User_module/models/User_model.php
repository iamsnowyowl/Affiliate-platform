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
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'both')
		    	->where(array('email' => $email))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_by_email: '.$email."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			if (isset($row->date_of_birth))
			{
				$row->date_of_birth = date("Y-m-d", strtotime($row->date_of_birth));
			}
		}
		
		return $row;
  	}

  	function get_user_by_username($username, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'both')
		    	->where(array('username' => $username))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_by_username: '.$username."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			if (isset($row->date_of_birth))
			{
				$row->date_of_birth = date("Y-m-d", strtotime($row->date_of_birth));
			}
		}
		
		return $row;
  	}

  	function get_user_by_id($user_id, $select)
  	{
	    try
	    {
		    $query = $this->db->select($select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'both')
		    	->where(array('user_id' => $user_id))
		    	->limit(1)->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_by_id: '.$user_id."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		if (!empty($row))
		{
			if (isset($row->date_of_birth))
			{
				$row->date_of_birth = date("Y-m-d", strtotime($row->date_of_birth));
			}
		}
		
		return $row;
  	}

  	function get_user_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'both');

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
		    log_message("error", 'Error get_user_list: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		if (!empty($row))
		{
			foreach ($row as $key => $value) 
			{
				// since there is fix value and not null on this variable. then i choose isset as test case
				if (isset($row[$key]->date_of_birth)) $row[$key]->date_of_birth = date("Y-m-d", strtotime($row[$key]->date_of_birth));
			}
		}

		return $row;
  	}

  	function get_user_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_user')
		    	->join('v_role', 'v_role.role_code = v_user.role_code', 'both');

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
		    log_message("error", 'Error get_user_count: '.json_encode($graph)."<||>" . $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
  	}

	function get_user_deleted_list($graph)
	{
		try
		{
			// select
			$this->db->select($graph->select)->
				from('v_user_deleted');
			
			//	datetime filter
			if (!empty($graph->range_time)) {
				foreach ($graph->range_time as $key -> $value) {
					if (empty($value[0]) && empty ($value[1])) continue;
					$this->db->group_start();
					$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					$this->db->group_end();
				}
			}
			
			//	filter
			if (!empty($graph->filter))
			{
				foreach($graph->filter as $field => $each) {
					$this->db->group_start();
					foreach ($each as $$key => $value) {
						$value = (is_numeric($value)) ? intval($value) : $value;
						$this->db->or_where($field, $value);
					}
					$this->db->group_end();
				}
			}

			//	filter in
			if (!empty($graph->filter_in))
			{
				foreach($graph->filter_in as $field => $value) {
					$this->group_start();
					$this->or_where_in($field, $value);
					$this->group_end();
				}
			}

			//	filter not in
			if (!empty($graph->filter_not_in))
			{
				foreach($graph->filter_not_in as $field => $value){
					$this->db->group_start();
					$this->db->or_where_in();
					$this->db->group_end();
				}
			}

			//	search
			if (!empty($graph->search))
			{
				foreach ($graph->search as $key => $value) {
					$this->db->group_start();
					$this->db->or_like($value, NULL, 'after');
					$this->db->group_end();
				}
			}

			//	grouping
			if (!empty($graph->group))
			{
				$this->db->group_by($graph->group);
			}

			//	sorting
			if (!empty($graph->sort)) 
			{
				foreach($graph->sort as $key => $value) {
					$this->db->order_by($key, $value);
				}
			}

			//	limit
			$this->db->limit($graph->limit, $graph->offset);
			//	execute
			$query = $this->db->get();


		}
		catch (PDOException $e) {
			log_message("error", 'Error get_user_deleted_list: '.json_encode($graph)."<||>" .$e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}

		$rows = $query->result();

		foreach ($rows as $key => $value)
		{
			if (!empty($rows[$key]->start_date)) $rows[$key]->start_date = hari(date("N", strtotime($rows[$key]->start_date))).", ".date("d", strtotime($rows[$key]->start_date))." ".bulan(date("n", strtotime($rows[$key]->start_date)))." ".date("Y", strtotime($rows[$key]->start_date));
	 	}
		return $rows;
	}

	function get_user_deleted_count($graph)
	{
		try
		{
			$this->db->select("COUNT(*) as count", FALSE)
				->from('v_user_deleted');

			//	datetime filter
			if (!empty($graph->range_time)) {
				foreach ($graph->range_time as $key => $value) {
					if (empty($value[0]) && empty($value[1])) continue;
					$this->db->group_start();
					$this->db->where(" $key BETWEEN '".$value[0]."' AND '".$value[1]."'", NULL, FALSE);
					$this->db->group_end();
				}
			}

			//	filter
			if	(!empty($graph->filter))
			{
				foreach($graph->filter as $field => $each) {
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
				foreach($graph->filter_in as $field => $value) {
					$this->db->group_start();
					$this->db->or_where_in($field, $value);
					$this->db->group_end();
				}
			}

			// filter not in
			if (!empty($graph->filter_not_in))
			{
				foreach($graph->filter_not_in as $field => $value){
					$this->db->group_start();
					$this->db->or_where_not_in($field, $value);
					$this->db->group_end();
				}
			}

			//	search
			if (!empty($graph->search))
			{
				foreach($graph->search as $key => $value){
					$this->db->group_start();
					$this->db->or_like($value, NULL, 'after');
					$this->db->group_end();
				}
			}

			//	grouping
			if (!empty($graph->group))
			{
				$this->db->group_by($graph->group);
			}

			//	sorting

			if (!empty($graph->sort))
			{
				foreach($graph->sort as $key => $value){
					$this->db->order_by($key, $value);
				}
			}

			//	execute
			$query = $this->db->get();
		}
		catch (PDOException $e) {
			log_message("error", 'Error get_user_deleted_count: '.json_encode($graph)."<||>" . $e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}
		$row = $query->row();

		return $row;
	}
  	function get_user_assign_list($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select($graph->select)
		    	->from('v_user_assign');

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

  	function get_user_assign_count($graph)
  	{
	    try
	    {
		    // select
		    $this->db->select("COUNT(*) as count", FALSE)
		    	->from('v_user_assign');

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

  	function get_user_not_assign_list()
  	{
	    try
	    {
		    // select
		    $this->db->select("*", FALSE)
		    	->from('v_user_not_assign');

		    // execute
		    $query = $this->db->get();
	    }
	    catch (PDOException $e) {
		    log_message("error", 'Error get_user_not_assign_list: '. $e->getMessage());
	    	throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

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

  	function update_login_information($user_id, $auto_commit = TRUE)
  	{
  		$status = TRUE;
  		
  		try {
  			$this->db->set('last_login', 'NOW()', FALSE);
			$this->db->where('user_id', intval($user_id));
			$this->db->update('tbl_user'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
  		} catch (PDOException $e) {
		    log_message("error", 'Error update_login_information: '.$user_id."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
  		}
  		return $status;
  	}

  	function get_list_hash_reset_password($count_retry_email = 0, $limit = 10)
	{
		try
		{
			$this->db->select("*")->from('v_reset_password');
			$this->db->where("count_retry_email <", $count_retry_email);
			$this->db->where("is_email_send", 0); // get only email with 0 flag
			$query = $this->db->limit($limit)->get();
		}
		catch (PDOException $e) {
			log_message("error", 'Error get_hash_reset_password: '.$hash."<||>" . $e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->result();

		return $row;
	}

	function get_hash_reset_password($hash)
	{
		try
		{
			$query = $this->db->select("*")
			->from('v_reset_password')
			->where(array('hash' => $hash))
			->limit(1)->get();
		}
		catch (PDOException $e) {
			log_message("error", 'Error get_hash_reset_password: '.$hash."<||>" . $e->getMessage());
			throw new PDOException($e->getMessage(), 1);
		}

		$row = $query->row();

		return $row;
	}

  	function create_hash_reset_password($params, $auto_commit = TRUE)
  	{
		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$row_id = $this->db->insert('tbl_reset_password', $params);
		}
		catch (PDOException $e) {
			log_message("error", 'Error create_hash_reset_password: '.json_encode($params)."<||>" . $e->getMessage());
  			$this->db->trans_rollback();
  			throw new PDOException($e->getMessage(), 1);
		}
		
		$row_id = $this->db->insert_id();
		if ($auto_commit) $this->db->trans_complete();
		return $row_id;
  	}

  	function update_reset_password_by_hash($params, $hash, $auto_commit = TRUE)
	{
		$affeted_row = 0;

		try 
		{
			if ($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("hash", $hash);
			$affeted_row = $this->db->update('tbl_reset_password');
			if ($auto_commit) $this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_user_by_id: '.$hash."<||>" . $e->getMessage());
  			$status = FALSE;
  			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_row;
	}

	function update_deleted_list($user_id, $params, $auto_commit = TRUE)
	{
		$affeted_rows = 0;

		try
		{
			if($auto_commit) $this->db->trans_start();
			$this->db->set($params);
			$this->db->where("user_id", $user_id);
			$affeted_rows = $this->db->update('tbl_user');
			if ($auto_commit)$this->db->trans_complete();
		}
		catch (PDOException $e) {
			log_message("error", 'Error update_deleted_list_by_id: '.$user_id."<||>" . $e->getMessage());
			$status = FALSE;
			throw new PDOException($e->getMessage(), 1);
		}

		return $affeted_rows;
	}
}