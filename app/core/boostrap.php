<?php namespace core;

/**
 * Boostrap Class
 * Provides helpfull initialization functions
 */

class Boostrap{
	/**
	 * Initialising framework
	 */
	public  function init(){

		// registering routes
		require 'app/routes.php';		

		Router::dispatch();
	}
}
?>