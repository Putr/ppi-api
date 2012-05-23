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
 * @version 1.0
 * @package PPI-API
 * 
 * @todo Needs loging, better error message, testing and optimisation
 */

$q = $_GET['q'];
$versions = array("v1");

if (preg_match_all("/\/(v[0-9]+)\/([a-zA-Z0-9]+)\/([a-zA-Z]+)/", $q, $matches) !== false) {
	if (!in_array($matches[1][0], $versions)) {
		echo "Error: Wrong version";
		die();
	}
	$action = $matches[2][0];
	$v = $matches[1][0];

	if(empty($matches[3][0])) {
		$type = "json";
	} else {
		$type = $matches[3][0];
	}
	try {
		require("class/generator.".$v.".class.php");
		$generator = new Generator($type);
	} catch (Exception $e) {
		echo "Error";
	}
	
	if (method_exists($generator, $action."Action")) {
		$generator->{$action."Action"}();
	} else {
		echo "Error: Action does not exsist";
	}
	
} 