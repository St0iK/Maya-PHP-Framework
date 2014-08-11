<?php
use \core\router as Router;

Router::get('simple', function(){ 
  echo "Called from router!";
});



Router::post('simple', function(){ 
  //do something simple
  echo "Called from router -- POST!";
});

Router::get('welcome', '\controllers\welcome@index');


