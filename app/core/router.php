<?php namespace core;

/**
 * Router class
 * @author David Carr - dave@daveismyname.com - http://www.daveismyname.com
 * @version 2.1
 * @date June 27, 2014
 */
class Router{

    /**
     * [$halts description]
     * @var boolean
     */
    public static $halts = true;

    /**
     * [$halts description]
     * @var boolean
     */
    public static $routes = array();

    /**
     * [$halts description]
     * @var boolean
     */
    public static $methods = array();

    /**
     * [$halts description]
     * @var boolean
     */
    public static $callbacks = array();

    /**
     * [$halts description]
     * @var boolean
     */
    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

    /**
     * is triggered when invoking inaccessible methods in a static context.
     * used to register routes that will be handled later by dispatch
     * @param  [type] $method [the name of the method being called]
     * @param  [type] $params [array containing the parameters passed to the $method]
     * @return [type]         [description]
     */
	public static function __callstatic($method, $params) 
    {
        // Get the uri
        $uri = dirname($_SERVER['PHP_SELF']).'/'.$params[0];
        
        // Get the callback
        $callback = $params[1];

        // Add uri to the $routes array
        array_push(self::$routes, $uri);

        // Add method to $methods array
        array_push(self::$methods, strtoupper($method));

        // Add callback to $callbacks array
        array_push(self::$callbacks, $callback);
    }

    /**
     * Defines callback if route is not found
     * @param  [type] $callback [callback]
     */
    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    public static function haltOnMatch($flag = true)
    {
        self::$halts = $flag;
    }

    public static function dispatch()
    {   
        // Parses url and returns an array
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);     

        // Get the method called
        $method = $_SERVER['REQUEST_METHOD'];  

        // Get all the keys from the $patterns array
        $searches = array_keys(static::$patterns);
        

        // Get all the values from the $patters array
        $replaces = array_values(static::$patterns);

        // replace '//' on routes with '/'
        self::$routes = str_replace('//','/',self::$routes);  
        
        // Set found route flag to false
        $found_route = false;

        // Check if our current uri is within $routes
        if (in_array($uri, self::$routes)) {


            // Get the position of the route
            $route_pos = array_keys(self::$routes, $uri);       

            // foreach route 
            foreach ($route_pos as $route) {    

                // Check if route's method matches or method == 'ANY'
                if ( self::$methods[$route] == $method || self::$methods[$route] == 'ANY' ) {


                    $found_route = true;

                    //if route is not an object 
                    if(!is_object(self::$callbacks[$route])){
                        
                        // Explode and get the parts from the action ex. from: '\controllers\blog@post'
                        $parts = explode('@',self::$callbacks[$route]);
                        
                        /**
                        * $parts[0] -> is the file that we need to include
                        * $parts[1] -> is method from the controller that we want to file
                        */
                        $file = strtolower('app/controllers/'.$parts[0].'.php'); 


                        //try to load and instantiate model     
                        if(file_exists($file)){
                            require $file;
                        }

                        //grab all parts based on a / separator 
                        $parts = explode('/',self::$callbacks[$route]);
                               
                        //collect the last index of the array
                        $last = end($parts);
                        
                        //grab the controller name and method call
                        $segments = explode('@',$last);                         
                               
                        //instanitate controller
                        $controller = new $segments[0]();

                        //call method
                        $controller->$segments[1](); 

                        if (self::$halts) return;
                        
                    } else { 

                        new \core\config();

                        //call closure
                        call_user_func(self::$callbacks[$route]);

                        if (self::$halts) return;
                    }
                }
            }
        } else {

            // if we have regex
            $pos = 0;
            
            // foreach route
            foreach (self::$routes as $route) {

                // replace '// with '/''
                $route = str_replace('//','/',$route);
                

                // if $route has the ':'
                if (strpos($route, ':') !== false) {
                    // replace with the original regex! :)
                    $route = str_replace($searches, $replaces, $route);
                }

                // If matches is provided, then it is filled with the results of search. 
                // $matches[0] will contain the text that matched the full pattern, 
                // $matches[1] will have the text that matched the first captured parenthesized subpattern, and so on.
                if (preg_match('#^' . $route . '$#', $uri, $matched)) {


                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
                        $found_route = true; 

                        array_shift($matched); //remove $matched[0] as [1] is the first parameter.

                        // If is not an Object, we call the constoller
                        if(!is_object(self::$callbacks[$pos])){

                            $parts = explode('@',self::$callbacks[$pos]);
                            $file = strtolower('app/controllers/'.$parts[0].'.php'); 
                           
                            //try to load and instantiate model     
                            if(file_exists($file)){
                                require $file;
                            }

                            //grab all parts based on a / separator 
                            $parts = explode('/',self::$callbacks[$pos]); 

                            //collect the last index of the array
                            $last = end($parts);

                            //grab the controller name and method call
                            $segments = explode('@',$last); 

                            //instanitate controller
                            $controller = new $segments[0]();

                            $params = count($matched);

                            //call method and pass any extra parameters to the method
                            switch ($params) {
                                case '0':
                                    $controller->$segments[1]();
                                    break;
                                case '1':
                                    $controller->$segments[1]($matched[0]);
                                    break;
                                case '2':
                                    $controller->$segments[1]($matched[0],$matched[1]);
                                    break;
                                case '3':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2]);
                                    break;
                                case '4':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3]);
                                    break;
                                case '5':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4]);
                                    break;
                                case '6':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4],$matched[5]);
                                    break;
                                case '7':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4],$matched[5],$matched[6]);
                                    break;
                                case '8':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4],$matched[5],$matched[6],$matched[7]);
                                    break;
                                case '9':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4],$matched[5],$matched[6],$matched[7],$matched[8]);
                                    break;
                                case '10':
                                    $controller->$segments[1]($matched[0],$matched[1],$matched[2],$matched[3],$matched[4],$matched[5],$matched[6],$matched[7],$matched[8],$matched[9]);
                                    break;
                            }

                            if (self::$halts) return;
                            
                        } else {

                            // Else call the callback function
                            new \core\config();                            
                            call_user_func_array(self::$callbacks[$pos], $matched);                       
                            if (self::$halts) return;
                        }
                        
                    }
                }
            $pos++;
            }
        }
 

        /*=======================================
        =            Route not found            =
        =======================================*/       
        
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '404';
                };
            } 

            $parts = explode('@',self::$error_callback);
            $file = strtolower('app/controllers/'.$parts[0].'.php'); 
            
            //try to load and instantiate model     
            if(file_exists($file)){
                require $file;
            }

            if(!is_object(self::$error_callback)){

                //grab all parts based on a / separator 
                $parts = explode('/',self::$error_callback); 

                //collect the last index of the array
                $last = end($parts);

                //grab the controller name and method call
                $segments = explode('@',$last);

                //instanitate controller
                $controller = new $segments[0]('No routes found.');

                //call method
                $controller->$segments[1]();

                if (self::$halts) return;

            } else {

               new \Core\config();
               
               call_user_func(self::$error_callback); 

               if (self::$halts) return;
            }
            
        }

        /*-----  End of Route not found  ------*/
    }

    protected function registerController(){

    }
}

