<?php if ( ! defined('_ROOT_')) exit('No direct script access allowed');

/**
 * Base Class
 *
 * Base Class for most of the Library, Models and Controllers. Provide generic 
 * class information for child classes
 *
 * @package		SwiftMVC
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2014
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */

class Base
{
	/**
	 * Constructor
	 *
	 * Calls the initialize() function
	 */
	function Base()
	{
		// Define all the base libraries
		$classes = array(
							'config'	=> 'ConfigManager',
							'load'	=> 'Loader'
						);
		
		foreach ($classes as $var => $class)
		{
			$this->$var =& load_class($class);
		}
		
		$this->load->set_base($this);
		
		// Setup the existing base database manager reference
		global $DatabaseManager;
		$this->database = $DatabaseManager;
	}
}

?>