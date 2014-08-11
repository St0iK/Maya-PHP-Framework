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
		require_once 'vendor/php-activerecord/php-activerecord/ActiveRecord.php';
		
		\ActiveRecord\Config::initialize(function($cfg)
		{
			
		  $cfg->set_model_directory('app/models');
		  $cfg->set_connections(array('development' =>
		    'mysql://root:root@localhost/test'));
		});

		Router::dispatch();
	}
}
?>