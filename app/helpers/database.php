<?php namespace helpers;
use \PDO;
class Database extends PDO{
	

	public function __construct(){

		try{
					
			parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASS);	

		}catch(PDOException $e){

			// Pass to Logger	
			echo 'Something went wrong connectiog to the Database';
					
		}
		
	}
}


