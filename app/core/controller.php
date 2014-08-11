<?php namespace core;
use core\config as Config,
    core\view as View,
    core\error as Error;


/**
 * TODO:
 * 1. Validate files on parseModelsFolder
 * 2. Set ability to customize models call (ex. only. except etc)
 * 3. 
 */
class Controller{

	/**
	 * view instance
	 * @var View Object
	 */
	public $view;
	
	/**
	 * Invoke Config class
	 * Create a new View
	 * Generate member variables for models
	 */
	public function __construct(){			
		new Config();
		$this->view = new View();
		//$this->parseModelsFolder();		

	}

	/**
	 * Setting member variables for the Controller
	 * @param String $key   
	 * @param Object $value The object class that will be instantiated
	 */
    // public function __set($key, $value)
    // {	
    // 	$this->$key = $value;
    // }

	/**
	 * Error if controller is not found
	 * @param  [type] $error [description]
	 * @return [type]        [description]
	 */
	protected function _error($error) {
		require 'app/core/error.php';
		$this->_controller = new error($error);
	    	$this->_controller->index();
	    	die;
	}

	/**
	 * Checks models folder and registers member variables
	 */
	private function parseModelsFolder(){	
		// Get all files from the Models direcory & remove . & .. from the scandir	
		$scanned_directory = array_diff(scandir('app/models'), array('..', '.'));

		// Parse all files
		foreach ($scanned_directory as $value)
    	{	
    		// remove extension
    		$value = preg_replace('/\\.[^.\\s]{3,4}$/', '', $value);    		
    		$str= "\models\\".$value;    		
    		
    		// Register new Class with the same name
    		$this->$value = new $str;
    	}
		
	}


}