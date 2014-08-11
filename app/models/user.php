<?php namespace models;
class User extends \core\model {
	
	function __construct(){
		parent::__construct();
		echo "This is acually called from the model!<br/>";
	}

	public function getAllUsers(){
		return "Here are all my users!";
	}
	
}