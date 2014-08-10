<?php namespace core;

class Router{

	public function __construct(){
		echo "Hi!";
	}

	public static function send(){
		echo "< br/> something was sernd!";

		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];  

        echo "URI:".$uri."</br></br></br>";
        echo "method:".$method; 
	}

	public static function __callstatic($method, $params) 
    {
      	echo "Im in";
        $uri = dirname($_SERVER['PHP_SELF']).'/'.$params[0];
        $callback = $params[1];
        echo "uri:".$uri."</br>";
        echo "method:".$method."</br>"; 
	    // array_push(self::$routes, $uri);
        // array_push(self::$methods, strtoupper($method));
        // array_push(self::$callbacks, $callback);
    }
}

