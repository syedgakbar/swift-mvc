<?php
/**
 * Controller Class
 *
 * Controller class is the base class for all the user Controller in the root MVC folder
 *
 * @package		SwiftMVC
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2014
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */

class Controller extends Base
{
	/**
	 * Constructor
	 *
	 * Calls the initialize() function
	 */
	function Controller()
	{
		// Contains the page data for rendering
		$this->data = array();
		
		parent::Base();
	}
}

?>