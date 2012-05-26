<?php
namespace inc;

/**
 * Router class
 * 
 * @author Rok Andrée <rok@andree.si>
 * @copyright CC-BY-NC
 * @license http://creativecommons.org/licenses/by-nc/2.0/
 * 
 * @package PPI-API
 * @subpackage inc
 * 
 * @todo Needs loging, better error message, testing and optimisation
 */
class Router {
	
	protected $q;
	
	protected $version;
	
	protected $type;
	
	protected $action;
	
	/**
	 * Constructor
	 * 
	 * @global array $conf
	 * 
	 * @param array $q | Request query
	 */
	function __construct($q) {
		global $conf;
		$this->conf = $conf;
		
		$this->q = $this->parseQuery($q);
	}
	
	/**
	 * Executes the router by calling the action requested.
	 */
	public function execute() {

		if (empty($this->type)) {
			$type = "json";
		} else {
			$type = $this->type;
		}
		
		$class = "\\" . $this->version . "\Generator";
		$generator = new $class($type);

		if (method_exists($generator, $this->action . "Action")) {
			$generator->setCalledAction($this->action);
			$generator->{$this->action . "Action"}();
		} else {
			throw new \Exception("Action does not exsist.");
		}
	}
	
	/**
	 * Validates and parses the query
	 * 
	 * @param type $q | Query string
	 */
	protected function parseQuery($q) {
		if (preg_match_all("/\/(v[0-9]+)\/([a-zA-Z0-9]+)\/([a-zA-Z]+)/", $q, $matches) !== false) {
			$this->q       = $matches;
			$this->version = $this->q[1][0];
			$this->action  = $this->q[2][0];
			$this->type    = $this->q[3][0];
			
			if (!empty($this->version) && !in_array($this->version, $this->conf["versions"])) {
				throw new \Exception("Not a valid version.");
			}
		} else {
			throw new \Exception("Not a valid query.");
		}	
	}

}

