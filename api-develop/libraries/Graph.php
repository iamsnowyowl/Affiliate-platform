<?php
/**
 * Graph
 *
 * @package	Graph
 * @author	ari@djemana.com, Marianus Djemana.com
 * @copyright	Copyright (c) 2016, Djemana Development (http://djemana.com)
 * @link	http://djemana.com
 * @since	Version 1.0.0
 * @filesource
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Graph {

	protected $CI;
	protected $graph = array();
	protected $count = 0;
	protected $pagination = array();

	private $config = array();
	private $all_field_alias;
	private $node;
	
	public $select = array();
	public $search;
	public $range_time = array();
	public $filter = array();
	public $filter_in = array();
	public $filter_not_in = array();
	public $group = array();
	public $sort = array();
	public $limit = 10;
	public $offset = 0;
	public $published_time;
	public $created_time;
	public $modified_time;
	public $error;
	public $error_code;
	public $input = array();
	public $flag;
	public $forbidden = array();

	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('http');
		$this->config = $config;
	}

	public function initialize($input, $default, $optional, $node, $tes = NULL)
	{
		$this->node = $node;
		$this->forbidden[$node]["filter"] = array();
		if (is_array($input) && empty($input["datatable"])) $input = array_filter($input, "strlen");
		$this->_set_input($input);
		$status = FALSE;

		$this->flag = $tes;

		try 
		{
			if (!is_array($default) OR !is_array($optional))
			{
				$message = "You need to specify default and optional params as array. node name:".$this->node;
				$this->set_error($message);
				$this->set_error_code(500);
				throw new Exception($message, 1);
			}
			// get all field alias
			$this->all_field_alias[$this->node] = $this->_get_key_name(array_merge($default, $optional), TRUE);

			$this->compile_datatable($default, $optional); // must be in high priority
			$this->compile_select($default, $optional);
			$this->compile_range_time($default, $optional);
			$this->compile_filter($default, $optional);
			$this->compile_filter_in($default, $optional);
			$this->compile_filter_not_in($default, $optional);
			$this->compile_sort($default, $optional);
			$this->compile_group($default, $optional);
			$this->compile_search($default, $optional);
			$this->compile_offset();
			$this->compile_limit();

			$this->published_time = $this->compile_time($this->config['published_time'], $this->config['separator_published_time']);
			$this->created_time = $this->compile_time($this->config['created_time'], $this->config['separator_created_time']);
			$this->modified_time = $this->compile_time($this->config['modified_time'], $this->config['separator_modified_time']);
			$status = TRUE;
		} 
		catch (Exception $e) {
		}

		return $status;
	}

	public function initialize_pagination($node, $count)
	{
		$CI =& get_instance();
		$CI->load->library('pagination');

		$CI->load->helper('url');
		$config['base_url'] = current_url();
		$config['total_rows'] = $count;
		$config['per_page'] = (!empty($this->compile_limit[$node])) ? $this->compile_limit[$node] : $this->limit;
		$config['num_links'] = $this->config['max_link_page'];
		$config['query_string_segment'] = $this->config['offset'];
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['next_link'] = "next";
		$config['prev_link'] = "prev";
		$config['first_link'] = "first";
		$config['last_link'] = "last";

		$CI->pagination->initialize($config);

		$CI->pagination->create_links();
		return $this->pagination[$node] = $CI->pagination->get_list_url();
	}

	public function get_compile_result($node, $key = NULL)
	{
		$def_key = array(
			"select",
			"search",
			"range_time",
			"filter",
			"filter_in",
			"filter_not_in",
			"group",
			"sort",
			"limit",
			"offset",
			"published_time",
			"created_time",
			"modified_time"
		);


		if (isset($key))
		{
			$lkey = array_map("trim", explode(",", $key));
			$fkey = array_diff($lkey, $def_key);
			if (!empty($fkey))
			{
				$message = "invalid graph key. on compile result graph: ".implode("`,`", $fkey);
				$this->set_error($message);
				$this->set_error_code(500);
				throw new Exception($message, 1);
			}
			$def_key = $lkey;
		}

		$result = new stdClass();
		foreach ($def_key as $key => $value) {
			$compile = "compile_".$value;
			$result->$value = (!empty($this->$compile[$node])) ? $this->$compile[$node] : $this->$value;
		}

		// add additional config
		$result->config = $this->config;
		return $result;
	}

	public function get_compile_result_pagination($node)
	{
		return (object) $this->pagination[$node];
	}

	protected function compile_datatable($default, $optional)
	{
		$params = $this->_get_input();

		if (empty($params['datatable']) || empty($params['columndef']))
		{
			return;
		}

		$this->CI->load->library('SSP');
		$columns = array();
		$columndef_expl = explode(",", $params['columndef']);
		
		for ($i=0; $i < count($columndef_expl); $i++) 
		{ 
			if ($columndef_expl[$i] == "created_date" || $columndef_expl[$i] == "modified_date")
			{
				$each_column = array('db' => $columndef_expl[$i], 'dt' => $columndef_expl[$i], 'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				});
			}
			else
			{
				$each_column = array('db' => $columndef_expl[$i], 'dt' => $columndef_expl[$i]);
			}
			array_push($columns, $each_column);
		}

		$binding = array();
		$limit = $this->CI->ssp->limit($params, $columns);
		$order = $this->CI->ssp->order($params, $columns);
		$filter = $this->CI->ssp->filter($params, $columns, $binding);
		
		// reconfigure offset and limit
		$expl_offset_limit = explode(",", $limit);
		
		if (count($expl_offset_limit) == 2) {
			$params[$this->config['offset']] = $expl_offset_limit[0]; 
			$params[$this->config['limit']] = $expl_offset_limit[1];
		}

		// reconfigure order
		$params[$this->config['sort']] = $order;

		// reconfigure filter

		// clean unwanted key datatable
		$params = $this->_clean_unwanted_key_datatable($params);

		$params = array_merge($params, $filter);
		// debug($params);
		// for test
		// unset($params['search']);
		// reasign params 
		$this->_set_input($params);
	}

	protected function compile_select($default, $optional)
	{
		$fields = $this->_get_array_from_string($this->_get_input($this->config['select']), $this->config['separator_select']);

		if (empty($fields))
		{
			$default = $this->_get_key_name($default); 
			$optional = $this->_get_key_name($optional);
			$this->compile_select[$this->node] = $this->_get_key_name(array_merge($default, $optional));
			return; 
		}

		$this->compile_select[$this->node] = $this->_get_key_name($default);
		
		$error = array();
		foreach ($fields as $key => $value)
		{
			if (!in_array($value, $optional) && !in_array($value, $default))
			{
				$error[] = $value;
				continue;
			}

			if (!in_array($value, $this->compile_select[$this->node]))
			{
				foreach ($optional as $key_opt => $value_opt) {
					if ($value_opt == $value) $this->compile_select[$this->node][] = (!is_int($key_opt)) ? $key_opt : $value_opt;
				}
			}
		}

		if (!empty($error))
		{
			$message = "invalid ".$this->config['select']." properties `".implode("`,`", $error)."` on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		return $this->compile_select[$this->node];
	}

	protected function compile_range_time($default, $optional)
	{
		$params = $this->_get_input();
		if (empty($params)) return;

		$filter = array_diff_key($params, array_flip($this->config));
		$this->_validate_filter($filter);

		$new_range_time = array();
		// look to alias name
		$all_field = array_merge($default, $optional);

		foreach ($filter as $alias => $value) 
		{
			if (!((substr($value, 0,1) == $this->config['range_time_wrapper_start']) && (substr($value, -1) == $this->config['range_time_wrapper_end']))) continue;
			
			$value = substr($value, 1,-1);

			// altering alias
			if (!empty($this->input[$this->node][$alias])) {
				$this->input[$this->node][$alias] = $value;
				$this->forbidden[$this->node]["filter"][] = $alias;
			}

			$value = $this->compile_time($alias, $this->config['separator_range_time']);

			$key = (false !== $key = array_search($alias, $all_field)) ? (!is_int($key)) ? $key : $alias : $alias;
			$new_range_time[$key] = $value;
		}

		return $this->compile_range_time[$this->node] = $new_range_time;
	}

	protected function compile_filter($default, $optional)
	{
		$params = $this->_get_input();
		if (empty($params)) return;

		$filter = array_diff_key($params, array_flip($this->config));
		$this->_validate_filter($filter);
		
		$new_filter = array();
		// look to alias name
		$all_field = array_merge($default, $optional);
		
		foreach ($filter as $alias => $value) 
		{
			if (substr($alias, 0,1) == $this->config['filter_not_identifier']) continue;

			if (in_array($alias, $this->forbidden[$this->node]["filter"])) continue;

			$value = $this->_get_array_from_string($value, $this->config['separator_filter']);
			if (count($value) > $this->config['min_filter_in_to_transform']) continue; 

			$key = (false !== $key = array_search($alias, $all_field)) ? (!is_int($key)) ? $key : $alias : $alias;
			$new_filter[$key] = $value;
		}

		return $this->compile_filter[$this->node] = $new_filter;

	}

	protected function compile_filter_in($default, $optional)
	{
		$params = $this->_get_input();
		if (empty($params)) return;

		$filter = array_diff_key($params, array_flip($this->config));
		$this->_validate_filter($filter);

		$new_filter_in = array();

		// look to alias name
		$all_field = array_merge($default, $optional);
		foreach ($filter as $alias => $value) 
		{
			if (substr($alias, 0,1) == $this->config['filter_not_identifier']) continue;

			if (in_array($alias, $this->forbidden[$this->node]["filter"])) continue;

			$value = $this->_get_array_from_string($value, $this->config['separator_filter']);
			if (!(count($value) > $this->config['min_filter_in_to_transform'])) continue;

			$key = (false !== $key = array_search($alias, $all_field)) ? (!is_int($key)) ? $key : $alias : $alias;
			$new_filter_in[$key] = $value;
		}

		return $this->compile_filter_in[$this->node] = $new_filter_in;
	}

	protected function compile_filter_not_in($default, $optional)
	{
		$params = $this->_get_input();
		if (empty($params)) return;

		$filter = array_diff_key($params, array_flip($this->config));
		$this->_validate_filter($filter);

		$new_filter_not_in = array();
		// look to alias name
		$all_field = array_merge($default, $optional);

		foreach ($filter as $alias => $value) 
		{
			if (substr($alias, 0,1) != $this->config['filter_not_identifier']) continue;

			if (in_array($alias, $this->forbidden[$this->node]["filter"])) continue;

			$value = $this->_get_array_from_string($value, $this->config['separator_filter']);
			$alias = substr($alias, 1);

			$key = (false !== $key = array_search($alias, $all_field)) ? (!is_int($key)) ? $key : $alias : $alias;
			$new_filter_not_in[$key] = $value;
		}

		return $this->compile_filter_not_in[$this->node] = $new_filter_not_in;
	}



	protected function compile_sort($default, $optional)
	{
		$sort = $this->_get_array_from_string($this->_get_input($this->config['sort']), $this->config['separator_sort']);

		$prep_sort = array();

		foreach ($sort as $key => $value) 
		{
			if (substr($value, 0,1) == "-") 
			{
				$direction = "DESC";
				$value = substr($value, 1);
			}
			else
			{
				$direction = "ASC";
			}

			$prep_sort[$value] = $direction;
		}

		$diff_forbidden = array_diff(array_keys($prep_sort), $this->all_field_alias[$this->node]);

		if (!empty($diff_forbidden))
		{
			$message = "invalid sort key `".implode("`,`", $diff_forbidden)."` on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		$new_sort = array();
		// look to alias name
		$all_field = array_merge($default, $optional);

		foreach ($prep_sort as $alias => $value) 
		{
			$key = (false !== $key = array_search($alias, $all_field)) ? (!is_int($key)) ? $key : $alias : $alias;
			$new_sort[$key] = $value;
		}

		return $this->compile_sort[$this->node] = $new_sort;
	}

	protected function compile_group($default, $optional)
	{
		$group = $this->_get_array_from_string($this->_get_input($this->config['group']), $this->config['separator_group']);

		$diff_forbidden = array_diff($group, $this->all_field_alias[$this->node]);

		if (!empty($diff_forbidden))
		{
			$message = "invalid group key `".implode("`,`", $diff_forbidden)."` on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		$new_group = array();
		// look to alias name
		$all_field = array_merge($default, $optional);

		foreach ($group as $alias => $value) 
		{
			$key = (false !== $key = array_search($value, $all_field)) ? (!is_int($key)) ? $key : $value : $value;
			$new_group[] = $key;
		}

		return $this->compile_group[$this->node] = $new_group;
	}

	protected function compile_offset()
	{
		$offset = $this->_get_input($this->config['offset']);

		$offset = (!is_null($offset)) ? intval($offset) : $this->config['default_offset']; 

		if ($offset < 0)
		{
			$message = "invalid offset position. cursor can't lower than (0) on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		return $this->compile_offset[$this->node] = $offset;
	}

	protected function compile_limit()
	{
		$limit = $this->_get_input($this->config['limit']);

		$limit = (!is_null($limit)) ? intval($limit) : $this->config['default_limit']; 

		if ($limit < 0)
		{
			$message = "invalid limit position. cursor can't lower than (0) on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		// check is max allowed packet not reached?
		if ($limit > $this->config['max_allowed_packet'])
		{
			$message = "limit has reached maximal packet. max_allowed_packet : ".$this->config['max_allowed_packet']." on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}

		return $this->compile_limit[$this->node] = $limit;
	}

	protected function compile_time($key_config, $separator_config)
	{
		$prep_time = $this->_get_input($key_config);

		if (is_null($prep_time))
		{
			return;
		}

		$prep_time = $this->_get_array_from_string($prep_time, $separator_config);

		$this->CI->load->helper('date');
		$format = '%Y-%m-%d %H:%i:%s';
		$time = time();
		
		$created_time = array();

		switch (count($prep_time)) {
			case (!is_numeric($prep_time[0])) :
				$message = $key_config." only allowed for 1 or 2 separate by (".$separator_config.") value unix_timestamp(numeric). example: 1234, 4566 on node ".$this->node;
				$this->set_error($message);
				$this->set_error_code(400);
				throw new Exception($message, 1);
				break;
			case 1:
				$created_time = array(mdate($format, intval($prep_time[0])), mdate($format, now()));
				break;
			case (!is_numeric($prep_time[1])) :
				$message = $key_config." only allowed for 1 or 2 separate by (".$separator_config.") value unix_timestamp(numeric). example: 1234, 4566 on node ".$this->node;
				$this->set_error($message);
				$this->set_error_code(400);
				throw new Exception($message, 1);
				break;
			case 2:
				$created_time = array(mdate($format, $prep_time[0]), mdate($format, $prep_time[1]));
				break;
			
			default:
				$message = $key_config." only allowed for 1 or 2 separate by (".$separator_config.") value unix_timestamp(numeric). example: 1234, 4566 on node ".$this->node;
				$this->set_error($message);
				$this->set_error_code(400);
				throw new Exception($message, 1);
				break;
		}

		return $created_time;
	}

	protected function compile_search($default, $optional)
	{
		$search = $this->_get_array_from_string($this->_get_input($this->config['search']), $this->config['separator_search']);
		
		if (empty($search)) return;

		$new_search = array();
		// look to alias name
		$all_field = array_merge($default, $optional);
		// all_time_type not included
		$all_field = array_diff($all_field, array_map("trim", explode(",", $this->config['forbidden_key_search'])));
		$all_field = array_filter($all_field, function($val, $key){return !strpos($key, "date");}, ARRAY_FILTER_USE_BOTH);

		foreach ($search as $val) 
		{
			$t_search = array();
			foreach ($all_field as $key => $value) {
				$key = (!is_int($key)) ? $key : $value;
				if (in_array($key, $this->forbidden[$this->node]["filter"])) continue;
				$t_search[$key] = $val;
			}
			$new_search[] = $t_search;
		}

		return $this->compile_search[$this->node] = $new_search;
	}

	public function get_error()
	{
		return $this->error[$this->node];
	}

	public function set_error($error)
	{
		$this->error[$this->node] = $error;
	}

	public function get_error_code()
	{
		return $this->error_code[$this->node];
	}

	public function set_error_code($error_code)
	{
		$this->error_code[$this->node] = $error_code;
	}

	protected function _clean_unwanted_key_datatable($params)
	{
		$datatable_key = array(
			'datatable',
			'draw',
			'columns',
			'order',
			'start',
			'length',
			'search',
			'columndef',
			'_'
		);

		$filter = array_diff_key($params, array_flip($this->config));
		$tobeclean = array_diff_key($filter, array_flip($this->all_field_alias[$this->node]));
		$otherfromdatatable = array_diff_key($tobeclean, array_flip($datatable_key));

		foreach ($otherfromdatatable as $key => $value) {
			unset($tobeclean[$key]);
		}
		// $result = array_intersect($params, $datatable_key);
		foreach ($tobeclean as $key => $value) {
			unset($params[$key]);
		}

		return $params;
	}

	protected function _get_key_name($default, $alias = FALSE)
	{
		if (!is_array($default)) return array();
		
		$new_array = array();
		foreach ($default as $key => $value) 
		{
			$new_array[] = (is_int($key)) ? $value : ($alias) ? $value : $key;
		}
		return $new_array;
	}

	protected function _get_array_from_string($str, $separator = ",")
	{ 
		// make sure trim each value
		if (!empty($str) && is_array($str)) return $str;
		
		$trim = function($value) {
		    return (is_numeric($value)) ? $value : trim($value);
		};

		return (isset($str)) ? array_map($trim, explode($separator, $str)) : array(); 
	}

	protected function _validate_filter($filter)
	{
		$diff_forbidden = array();
		
		foreach ($filter as $key => $value) 
		{
			// check is has filter not in. if not in both then forbidden
			if (!(in_array($key, $this->all_field_alias[$this->node]) 
				|| in_array(substr($key, 1), $this->all_field_alias[$this->node])))	$diff_forbidden[$key] = $key;
		}
		// $diff_forbidden = array_diff_key($filter, array_flip($this->all_field_alias[$this->node]));

		if (!empty($diff_forbidden))
		{
			$message = "invalid filter key `".implode("`,`", array_keys($diff_forbidden))."` on node ".$this->node;
			$this->set_error($message);
			$this->set_error_code(400);
			throw new Exception($message, 1);
		}
	}

	protected function _get_input($key = NULL)
	{
		return (!empty($key)) ? (empty($this->input[$this->node][$key])) ? NULL : $this->input[$this->node][$key] : $this->input[$this->node];
	}

	protected function _set_input($input) {
		$this->input[$this->node] = $input;
	}
}