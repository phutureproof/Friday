<?php
// start session if we don't have one
if (!session_id()) {
    session_start();
}

// require composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');