<?php namespace models;
class Welcome extends \core\model {
	
	function __construct(){
		parent::__construct();
		echo "This is acually called from the model!<br/>";
	}

	public function myWelcomeFunction(){
		return "Hi from my welcome Function";
	}
	
}