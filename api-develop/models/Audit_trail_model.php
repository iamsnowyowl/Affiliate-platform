<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_trail_model extends CI_Model 
{
	function add($params, $autoCommit = TRUE)
	{
		$id = 0;
		$dbh = $this->db->conn_id;

		$attr = array
		(
			'user_id',
			'path',
			'method',
			'data',
			'user_agent',
			'ip_address',
			'usage_memory',
			'usage_time_ms',
			'execution_start_time_ms',
			'execution_end_time_ms',
			'response_code'
		);

		$field = implode(",", $attr);
		// since modified_date use sql syntax. we will not binding any value to modified_date
		$placeholder = ':'.implode(", :", $attr);

		try 
		{
			
			if ($autoCommit) $dbh->beginTransaction();

			$stmt = $dbh->prepare('
									INSERT 
										INTO 
										tbl_audit_trail 
												(
													'.$field.'
												)
										VALUES (
													'.$placeholder.'
												)
			');
			foreach ($attr as $key => &$value) 
			{
				if (empty($params[$value]))
				{
					$params[$value] = NULL;
				}
				
				if (is_int($params[$value]))
				{
					$stmt->bindParam(":".$value, $params[$value], PDO::PARAM_INT); 
				}
				else
				{
					$stmt->bindParam(":".$value, $params[$value], PDO::PARAM_STR); 
				}
			}

			$stmt->execute();
			$id = $dbh->lastInsertId();

			// second insert to tblUserCompanyRoleAssign
			if ($autoCommit) $dbh->commit();
		}
		catch (PDOException $e) {
			if ($autoCommit) $dbh->rollback();
		    log_message("error", 'failed add Audit Trail: ' . $e->getMessage());
		}
		return $id;
	}
}
