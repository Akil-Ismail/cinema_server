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
    '/deleteMovie'         => ['controller' => 'MovieController', 'method' => 'deleteMovie'],
    '/addMovie'         => ['controller' => 'MovieController', 'method' => 'addMovie'],
    '/updateMovie'         => ['controller' => 'MovieController', 'method' => 'updateMovie'],
    '/getShowtimes'         => ['controller' => 'ShowtimeController', 'method' => 'getShowtimes'],
    '/addShowtime'         => ['controller' => 'ShowtimeController', 'method' => 'addShowtime'],
    '/deleteShowtime'         => ['controller' => 'ShowtimeController', 'method' => 'deleteShowtime'],
    '/getTheater'         => ['controller' => 'TheatersController', 'method' => 'getTheater'],
    '/getTheaters'         => ['controller' => 'TheaterController', 'method' => 'getTheaters'],
    '/getTickets'         => ['controller' => 'TicketsController', 'method' => 'getTickets'],
    '/addTickets'         => ['controller' => 'TicketsController', 'method' => 'addTickets'],
    '/getPricing'         => ['controller' => 'PricingsController', 'method' => 'getPricing'],
    '/getPricings'         => ['controller' => 'PricingsController', 'method' => 'getPricings'],
    '/setPricings'         => ['controller' => 'PricingsController', 'method' => 'setPricings'],
];
