<?php  if (! defined('_ROOT_')) exit('No direct script access allowed');

/**
 * Config Manager Class
 *
 * Loads the application configuration settings
 *
 * @package		SwiftMVC
 * @author		Syed Ghulam Akbar
 * @copyright	Copyright (c) 2012
 * @link		http://www.syedgakbar.com
 * @since		Version 1.0
 * @filesource
 */
		
class ConfigManager
{
	public function ConfigManager()
	{
		require (_ROOT_. '/config.php');
		
		// Load all the configuration option as the properties of this class
		foreach ($config as $var => $value)
		{
			$this->$var =& $config[$var];
		}
	}
}

?>