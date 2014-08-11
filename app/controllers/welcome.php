<?php namespace controllers;
use core\view as View;
use core\controller as Controller;

class Welcome extends Controller{

	/**
	 * call the parent construct
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * define page title and load template files
	 */
	public function index(){	
	
		# create Tito
		$user = \User::create(array('username' => 'Tito', 'password' => 'VA'));
		  
		# read Tito
		$user = \User::find_by_username('Tito')->to_array();
		echo "<pre>";
		print_r($user);
		echo "</pre>";
		
		# update Tito
		#$user->name = 'Tito Jr';
		#$user->save();
		 
		# delete Tito
		//$user->delete(); 
		
		$data['title'] = 'js13';
		View::render('welcome/welcome',$data);
		
	}

	
}