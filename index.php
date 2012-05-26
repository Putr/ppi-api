<?php
/**
 * Router for PPI Api
 * 
 * Takes in the q paramater (should be rewriten with server)
 * and calls the correct version and action.
 * 
 * @author Rok Andrée <rok@andree.si>
 * @copyright CC-BY-NC
 * 
 * @package PPI-API
 * 
 */
require("config.php");

//
// Autoloader
//
spl_autoload_register(function ($className) {
	$filename = "class/" . str_replace('\\', '/', $className) . ".php";
	if (file_exists($filename)) {
		include($filename);
		if (class_exists($className)) {
			return TRUE;
		}
	}
	return FALSE;
});

//
// ROUTER
//
$q = $_GET['q'];

try {
	$router = new \inc\Router($q);
	$router->execute();
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}



