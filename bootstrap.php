<?php
/**
 * Friday
 * Bootstrap file
 *
 * Initialise whatever we need to get
 * Friday up and running
 */

// Friday defines
defined('FRIDAY_BASE_DIR') or define('FRIDAY_BASE_DIR', __DIR__);
defined('FRIDAY_VENDOR_DIR') or define('FRIDAY_VENDOR_DIR', FRIDAY_BASE_DIR . '/vendor');
defined('FRIDAY_CONFIG_DIR') or define('FRIDAY_CONFIG_DIR', FRIDAY_BASE_DIR . '/config');
defined('FRIDAY_APPLICATION_CONFIG') or define('FRIDAY_APPLICATION_CONFIG', FRIDAY_CONFIG_DIR . '/application-config.json');
defined('FRIDAY_VIEW_DIR') or define('FRIDAY_VIEW_DIR', FRIDAY_BASE_DIR . '/views');

// start session if we don't have one
if ( ! session_id()) {
    session_start();
}

// require composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');
