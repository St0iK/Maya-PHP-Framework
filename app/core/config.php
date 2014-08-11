<?php namespace core;


/**
 * config - System wide settings
 */

class Config {

	public function __construct(){

		//turn on output buffering
		ob_start();

		//site address
		define('DIR','http://maya.dev/');

		//database details ONLY NEEDED IF USING A DATABASE
		define('DB_TYPE','mysql');
		define('DB_HOST','localhost');
		define('DB_NAME','dbname');
		define('DB_USER','username');
		define('DB_PASS','password');
		define('PREFIX','smvc_');

		//set prefix for sessions
		define('SESSION_PREFIX','maya_');

		//optionall create a constant for the name of the site
		define('SITETITLE','V2.1');

		//turn on custom error handling
		//set_exception_handler('core\logger::exception_handler');
		//set_error_handler('core\logger::error_handler');

		//set timezone
		date_default_timezone_set('Europe/London');

		//start sessions
		// \helpers\session::init();

		//set the default template
		// \helpers\session::set('template','default');
		
	}

}