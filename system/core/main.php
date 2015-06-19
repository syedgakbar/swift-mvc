<?php  if ( ! defined('_ROOT_')) exit('No direct script access allowed');

/**
 * Swift MVC 
 * Main
 *
 * Main handler for the Web Statistics. Performs the initial loading of base
 * classes, parse the route, and load the correct controller
 *
 * @package		Swift MVC 
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2012
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require(_ROOT_.'/system/core/common.php');


/*
 * ------------------------------------------------------
 *  Instantiate the base classes
 * ------------------------------------------------------
 */

$DatabaseManager =& load_class('DatabaseManager');
$Base =& load_class('Base');
$Contoller =& load_class('Controller');
$Model =& load_class('Model');

/*
 * ------------------------------------------------------
 *  Handle the user request
 * ------------------------------------------------------
 */
 
 // get the action paramter
 $action=explode('/', _get('q'));

 if (count($action) > 0)
 {
	$controller_path=_ROOT_.'/mvc/controller';
	$counter=0;
	
	// Check for any nested folders in the controller
	for ($counter=0; $counter<count($action); $counter++)
	{
		if (!file_exists($controller_path.'/'.$action[$counter]))
			break;
		else
		{
			$controller_path = $controller_path.'/'.$action[$counter];
		}
	}
	
	// Handle the special case where both folder and controller files of name exists, and we need
	// to find the best combination (giving preference to controll file at the deepest level)
	while ($counter > 0)
	{
		// Check if the actual controller file exist at this level. If not keep moving up
		// until a controller file is found
		if ($counter < count($action) && file_exists($controller_path.'/'.$action[$counter].'.php'))
			break;
		else
		{
			$counter--;
			$controller_path = substr($controller_path, 0, strrpos($controller_path, '/'));
		}
	}
	
	// Remove the controller paths from the source
	if ($counter > 0)
		$action = array_slice($action, $counter);
	
	// Check and process for the default controller
	if ( (!file_exists($controller_path.'/'.$action[0].'.php')) && (file_exists(_ROOT_.'/mvc/controller/home.php')) )
		$action = array('home', $action[0]);
		
	$parameters = array();
	$controller=$action[0];
	
	if (!file_exists($controller_path.'/'.$controller.'.php'))
		show_error ('Page not found: ' . _get('action'), 404);
	
	require($controller_path.'/'.$controller.'.php');
	
	// Remove any dashes from the Class Name
	$className = str_replace("-","",$controller);
	
	$Controller =& load_class($className);
	
	// Get the method name to call under the controller
	if (count($action) > 1 && $action[1] != '')
	{
		$methodName=$action[1];
		
		// Now remove the first index (containing controller name) and second index (containing method name), and pass all other as the parameters
		$parameters = array_slice($action, 2);
	}
	else
		$methodName="Index";
	
	// Remove any dashes in the method name
	$methodName = str_replace("-","",$methodName);
	
	// Additional Security Check
	if (strncmp($methodName, '_', 1) == 0
		OR in_array(strtolower($methodName), array_map('strtolower', get_class_methods('Controller'))) )
		show_error ('Controller method not accessible:' . _get('action'), 500);

	// Check if this controller function is defined
	if ( ! in_array(strtolower($methodName), array_map('strtolower', get_class_methods($Controller))))
	{
		show_error("Controller Method not found: " . $methodName, 404);
	}
		
	call_user_func_array(array($Controller, $methodName), $parameters);
 }
 else
	show_error ('Controller not defined:' . _get('action'), 404);
 
/*
 * ------------------------------------------------------
 *  Disconnect any open database connections
 * ------------------------------------------------------
 */
 $DatabaseManager->disconnect()

?>