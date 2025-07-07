<?php
header("Access-Control-Allow-Methods: GET, POST, GET");
header("Access-Control-Allow-Origin: *");

//Routing starts here (Mapping between the request and the controller & method names)
//It's an key-value array where the value is an key-value array
//----------------------------------------------------------
$apis = [
    '/login'         => ['controller' => 'UserController', 'method' => 'login'],
    '/signup'         => ['controller' => 'UserController', 'method' => 'signup'],
    '/getMovies'         => ['controller' => 'MovieController', 'method' => 'getMovies'],
    '/getMovie'         => ['controller' => 'MovieController', 'method' => 'getMovie'],
];
