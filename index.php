<?php
/**
 * Friday
 * (c)oncept by Dale Paget
 *
 * ::whats the idea::
 * Easy ajax api access
 * for database backend, supporting authentication
 *
 * ::composer::
 * I've used composer and a few libraries to save time
 * http://www.slimframework.com/ :: slim php framework
 * http://www.appelsiini.net/projects/slim-jwt-auth :: slim json web token middleware
 */

// application setup
require_once("bootstrap.php");

// use Friday
use PhutureProof\Friday;

// create new app
$friday = new Friday();

// go!
$friday->run();
