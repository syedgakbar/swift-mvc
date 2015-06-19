<?php
/**
 * SwiftMVC
 *
 * An open source MVC framework for the PHP 5.0 and later
 * @filesource
 */

// ------------------------------------------------------------------------

$root_folder = realpath(dirname(__FILE__));
$root_folder = str_replace("\\", "/", $root_folder);

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| _SELF_		- The name of THIS file (typically "index.php")
| _ROOT_	- The full server path to the "system" folder
|
*/
define('_SELF_', pathinfo(__FILE__, PATHINFO_BASENAME));
define('_ROOT_', $root_folder);

/*
|---------------------------------------------------------------
| LOAD THE Maing Handler/Controll
|---------------------------------------------------------------
|
*/
require_once _ROOT_.'/system/core/main.php';

?>