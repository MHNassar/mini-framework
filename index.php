<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * ========================================================
 * Front Controller
 * =======================================================
 * That handles all requests for a App
 * ========================================================
 */

/**
 *  classes Auto loader
 */


require __DIR__ . "/vendor/autoload.php";

include "config.php";
include "route.php";



