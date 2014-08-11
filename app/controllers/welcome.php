<?php namespace controllers;
use core\view as View;
use core\controller as Controller;

/*
 * Welcome controller
 *
 * @author David Carr - dave@daveismyname.com - http://www.daveismyname.com
 * @version 2.1
 * @date June 27, 2014
 */
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
	
		echo $this->welcome->myWelcomeFunction();
		echo $this->user->getAllUsers();
		$data['title'] = 'js13';
		View::render('welcome/welcome',$data);
		
	}

	
}