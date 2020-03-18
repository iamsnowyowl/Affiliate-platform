<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	protected $_strict;

    public function __construct($rules = array())
    {
        parent::__construct($rules);
    }

    function get_error()
    {
    	return "error";
    }

    /**
	 * By default, form validation uses the $_POST array to validate
	 *
	 * If an array is set through this method, then this array will
	 * be used instead of the $_POST array
	 *
	 * Note that if you are validating multiple arrays, then the
	 * reset_validation() function should be called after validating
	 * each array due to the limitations of CI's singleton
	 *
	 * @param	array	$data
	 * @return	CI_Form_validation
	 */
	public function set_data($data = array(), $strict = FALSE)
	{
		$this->_strict = $strict;
		if (!is_array($data)) return;

		if ( ! empty($data))
		{
			$this->validation_data = $data;
		}

		return $this;
	}

	protected function _validate_invalid_key()
	{
		if (empty($this->validation_data))
		{

			foreach (array_keys($this->_field_data) as $key => $value) 
			{
				$this->validation_data[$value] = NULL;
			}
		}
        $diff = array_diff(array_keys($this->validation_data), array_keys($this->_field_data));

        if (!empty($diff))
        {
	        $this->_error_array = "Bad parameter. parameter not allowed : ".implode(", ", $diff);
        }
	}

	/**
	 * Run the Validator
	 *
	 * This function does all the work.
	 *
	 * @param	string	$config
	 * @param	array	$data
	 * @return	bool
	 */
	public function run($config = NULL, &$data = NULL)
	{
		$validation_array = empty($this->validation_data)
			? $_POST
			: $this->validation_data;

		// Does the _field_data array containing the validation rules exist?
		// If not, we look to see if they were assigned via a config file
		if (count($this->_field_data) === 0)
		{
			// No validation rules?  We're done...
			if (empty($this->_config_rules))
			{
				return FALSE;
			}

			if (empty($config))
			{
				// Is there a validation rule for the particular URI being accessed?
				$config = trim($this->CI->uri->ruri_string(), '/');
				isset($this->_config_rules[$config]) OR $config = $this->CI->router->class.'/'.$this->CI->router->method;
			}

			$this->set_rules(isset($this->_config_rules[$config]) ? $this->_config_rules[$config] : $this->_config_rules);

			// Were we able to set the rules correctly?
			if (count($this->_field_data) === 0)
			{
				log_message('debug', 'Unable to find validation rules');
				return FALSE;
			}
		}

		if ($this->_strict===TRUE) $this->_validate_invalid_key();

		if (!empty($this->_error_array)) return FALSE;

		// Load the language file containing error messages
		$this->CI->lang->load('form_validation');

		// Cycle through the rules for each field and match the corresponding $validation_data item
		foreach ($this->_field_data as $field => &$row)
		{
			// Fetch the data from the validation_data array item and cache it in the _field_data array.
			// Depending on whether the field name is an array or a string will determine where we get it from.
			if ($row['is_array'] === TRUE)
			{
				$this->_field_data[$field]['postdata'] = $this->_reduce_array($validation_array, $row['keys']);
			}
			elseif (isset($validation_array[$field]))
			{
				$this->_field_data[$field]['postdata'] = $validation_array[$field];
			}
		}

		// Execute validation rules
		// Note: A second foreach (for now) is required in order to avoid false-positives
		//	 for rules like 'matches', which correlate to other validation fields.
		foreach ($this->_field_data as $field => &$row)
		{
			// Don't try to validate if we have no rules set
			if (empty($row['rules']))
			{
				continue;
			}

			$this->_execute($row, $row['rules'], $row['postdata']);
		}

		if ( ! empty($this->_error_array))
		{
			return FALSE;
		}


		// Fill $data if requested, otherwise modify $_POST, as long as
		// set_data() wasn't used (yea, I know it sounds confusing)
		if (func_num_args() >= 2)
		{
			$data = empty($this->validation_data) ? $_POST : $this->validation_data;
			$this->_reset_data_array($data);
			return TRUE;
		}

		empty($this->validation_data) && $this->_reset_data_array($_POST);
		return TRUE;
	}

	/**
	 * Re-populate the _POST array with our finalized and processed data
	 *
	 * @return	void
	 */
	protected function _reset_data_array(&$data)
	{
		foreach ($this->_field_data as $field => $row)
		{
			if ($row['postdata'] !== NULL)
			{
				if ($row['is_array'] === FALSE)
				{
					isset($data[$field]) && $data[$field] = $row['postdata'];
				}
				else
				{
					$data_ref =& $data;

					// before we assign values, make a reference to the right POST key
					if (count($row['keys']) === 1)
					{
						$data_ref =& $data[current($row['keys'])];
					}
					else
					{
						foreach ($row['keys'] as $val)
						{
							$data_ref =& $data_ref[$val];
						}
					}

					$data_ref = $row['postdata'];
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Email
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_email($str)
	{
		return valid_email($str);
	}


	public function valid_emails($str)
	{
		if (strpos($str, ',') === FALSE)
		{
			return valid_email(trim($str));
		}

		foreach (explode(',', $str) as $email)
		{
			if (trim($email) !== '' && valid_email(trim($email)) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function valid_date($str, $format = 'Y-m-d\TH:i:sP')
	{
		$d = DateTime::createFromFormat($format, $str);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $str;
	}
}