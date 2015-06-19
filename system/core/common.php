<?php if ( ! defined('_ROOT_')) exit('No direct script access allowed');

/**
 * Common Function
 *
 * This class contains the misc. utility functions
 *
 * @package		WebStatistics
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2012
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
* Class registry
*
* This function acts as a singleton.  If the requested class does not
* exist it is instantiated and set to a static variable.  If it has
* previously been instantiated the variable is returned.
*
* @access	public
* @param	string	the class name being requested
* @param	bool	optional flag that lets classes get loaded but not instantiated
* @return	object
*/
function &load_class($class, $instantiate = TRUE, $path="/system/library/")
{
	static $objects = array();
	static $object_paths = array();
	
	// Does the class exist?  If so, we're done...
	if (isset($objects[$class]) && isset($object_paths[$class]) && $object_paths[$class] == $path)
	{
		return $objects[$class];
	}

	// Load the class from library file
	if (file_exists(_ROOT_.$path.$class.'.php'))
	{
		include_once(_ROOT_.$path.$class.'.php');
	}
	
	// Store this class full path
	$object_paths[$class] = $path;

	if ($instantiate == FALSE)
	{
		$objects[$class] = TRUE;
		return $objects[$class];
	}
	
	$name = $class;
	$objects[$class] =& instantiate_class(new $name());
	
	return $objects[$class];
}

/**
* Error Handler
*
* This function lets us invoke the exception class and
* display errors using the standard error template located
* in application/errors/errors.php
* This function will send the error page directly to the
* browser and exit.
*
* @access	public
* @return	void
*/
function show_error($message, $status_code = 500, $header='')
{
	// Set defaults values
	if ($header=='')
		switch ($status_code)
		{
			case 500:
				$header = "Internal Server Error";
				break;
			case 404:
				$header = "Not Found";
				break;
			default:
				$header = "Unknown Error";
				break;
		}
		
	// Now set the page header
	header('HTTP/1.0 ' . $status_code  . ' ' . $header );
	
	// Now render the actual error page
	if (file_exists(_ROOT_.'/app/error/error-'.$status_code.'.php'))
		include(_ROOT_.'/app/error/error-'.$status_code.'.php');
	else if (file_exists(_ROOT_.'/app/error/error.php'))
		include(_ROOT_.'/app/error/error.php');
	else
		echo $message;
		
	exit;
}

/**
 * Instantiate Class
 *
 * Returns a new class object by reference, used by load_class() and the DB class.
 * Required to retain PHP 4 compatibility and also not make PHP 5.3 cry.
 *
 * Use: $obj =& instantiate_class(new Foo());
 * 
 * @access	public
 * @param	object
 * @return	object
 */
function &instantiate_class(&$class_object)
{
	return $class_object;
}

/**
 * Post Variable
 *
 * Returns the given post variable if set. 
 *
 * Use: $obj = _post("varname");
 * 
 * @access	public
 * @param	string		Name of the post parameter
 * @param	string		(Optional) Default Value
 * @return	object
 */
function _post($param, $defvalue = '')
{
	if(!isset($_POST[$param])) 	{
		return $defvalue;
	}
	else {
		return $_POST[$param];
	}
}

/**
 * Get Variable
 *
 * Returns the given get variable if set. 
 *
 * Use: $obj = _post("varname");
 * 
 * @access	public
 * @param	string		Name of the get parameter
 * @param	string		(Optional) Default Value
 * @return	object
 */
function _get($param,$defvalue = '')
{
	if(!isset($_GET[$param])) {
		return $defvalue;
	}
	else {
		return $_GET[$param];
	}
} 

?>