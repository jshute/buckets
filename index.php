<?php

/*******************************************************************************
 * SQ APP
 * 
 * Entry point of website PHP
 ******************************************************************************/

error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('display_errors', true);
ini_set('error_log', 'error_log');

require_once '../sq/sq.php';

sq::load('/config');
sq::init();

?>