<?php
/**
 * this is extender class
 * @author	Ari Djemana
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_URI extends CI_URI {

	public function __construct(CI_Config $config)
	{
		parent::__construct($config);
	}
	
	/* since we are using versioning, there is a version path set on base url.
	* we need to parse true value from segment
	* @return useable value
	*/
	public function segmentval_array(){
		return array_values(array_intersect($this->segment_array(), $this->rsegment_array()));
	}
}
