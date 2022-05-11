<?php

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));
require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
 */

define('DB_HOST', "localhost");
define('DB_USER', "adminpanel");
define('DB_PASSWORD', "Manal@lina.13");
define('DB_NAME', "corephpadmin");

/**
 * Get instance of DB object
 */
function getDbInstance() {
	$mysqli = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
return new MysqliDb ($mysqli);
	//return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

function GetRandomPwd(){
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
	$password = substr( str_shuffle( $chars ), 0, 8 );
	// Encrypt password
	//$password = password_hash($password, PASSWORD_ARGON2I);
	return $password;
}