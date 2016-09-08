<?php
/**
 * Friday
 * Bootstrap file
 *
 * Initialise whatever we need to get
 * Friday up and running
 */

// Friday defines
defined('FRIDAY_APP_BASEDIR') or define('FRIDAY_APP_BASEDIR', __DIR__);
defined('FRIDAY_APP_VENDORDIR') or define('FRIDAY_APP_VENDORDIR', FRIDAY_APP_BASEDIR . '/vendor');
defined('FRIDAY_APP_CONFIGDIR') or define('FRIDAY_APP_CONFIGDIR', FRIDAY_APP_BASEDIR . '/config');

// start session if we don't have one
if (!session_id()) {
    session_start();
}

// require composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// hand back over to parent script