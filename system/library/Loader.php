<?php  if (! defined('_ROOT_')) exit('No direct script access allowed');

/**
 * Loader Class
 *
 * Loads the Views, Model and Contoller clases.
 *
 * @package		SwiftMVC
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2014
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */
		
class Loader
{
	private $Base = '';
	
	public function Loader()
	{
	}
	
	/**
	 * SetBase
	 *
	 * Set base class of current context, so that any model or controller loaded are available to base page 
	 */
	public function set_base($base)
	{
		$this->Base = $base;
	}
	
	/**
	 * LoadModel
	 *
	 * Load the given Model class
	 */
	public function model($className, $path='/mvc/model/')
	{
		// Remove any nested folder name from the class name
		if (strrpos($className, '/') > 0)
		{
			$path = $path . substr($className, 0, strrpos($className, '/') + 1);
			$className = substr($className, strrpos($className, '/')+1);
		}

		// Store a reference to the base class, as this seem to change as soon as the new class is loaded
		$baseClass = $this->Base;		
		$baseClass->$className =& load_class($className, true, $path);
		$this->Base = $baseClass;
	}
	
	/**
	 * LoadHelper
	 *
	 * Load the given Library class
	 */
	public function library($className, $path='/system/core/library/')
	{
		$baseClass = $this->Base;	
		$baseClass->$className =& load_class($className, true, $path);
		$this->Base = $baseClass;
	}
	
	/**
	 * LoadLibrary
	 *
	 * Load the given Helper class
	 */
	public function helper($className, $path='/system/core/library/')
	{
		$baseClass = $this->Base;	
		$this->Base->$className =& load_class($className, true, $path);
		$this->Base = $baseClass;
	}
	
	/**
	 * Load View
	 *
	 * Renders the given view in current oupput buffer stream
	 *
	 * 1. The name of the "view" file to be included.
	 * 2. An associative array of data to be extracted for use in the view.
	 *
	 */
	public function view($view, $vars = array(), $return = FALSE)
	{
		// Check the page defined variables from controller
		if (is_array($this->Base->data))
			$vars = array_merge($this->Base->data, $vars);
			
		// Register the passed variables for accessing in the page
		if (is_array($vars))
			extract($vars);
			
		// Buffer the output
		ob_start();
		
		// Now render the actual view file
		$view_file_path = _ROOT_.'/mvc/view/'.$view.'.php';
		include($view_file_path);
		
		// Return the file data if requested
		if ($return === TRUE)
		{		
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}
		
		ob_end_flush();
	}
}

?>