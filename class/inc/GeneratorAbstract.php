<?php
namespace inc;

/**
 * Abstract class for Generator classes
 * 
 * @author Rok Andrée <rok@andree.si>
 * @copyright CC-BY-NC
 * @license http://creativecommons.org/licenses/by-nc/2.0/
 * 
 * @package PPI-API
 * @subpackage inc
 * 
 */
abstract class GeneratorAbstract {
	
	/**
	 * Type of return data (xml || json)
	 * 
	 * @var string
	 */
	protected $type;
	
	/**
	 * What action has been called
	 * 
	 * @var string
	 */
	protected $action;
	
	/**
	 * Version information
	 * @var string
	 */
	protected $version;
	
	/**
	 * Constructor
	 * 
	 * @global array $conf | Global configuration 
	 */
	function __construct($type) {
		global $conf;
		$this->conf = $conf;
		$type = strtolower($type);
		
		if($type == "xml" || $type == "json") {
			$this->type = $type;
		} else {
			throw new \Exception("Not a valid type.");
		}
		
	}
	
	public function setCalledAction($action) {
		$this->action = $action;
	}
	/**
	 * Parses the data for output
	 * 
	 * @param array $data
	 */
	protected function output(array $data) {
		switch ($this->type) {
			case "json":
			case "JSON":
				header("Content-type: application/json");
				$output = json_encode($data);
				if ($output === false) {
					echo "Error in data retrival";
					die();
				}
				
				break;
				
			case "xml":
			case "XML":
				header('Content-type: text/xml');
				$output = $this->encodeToXML($data);
				
				break;
		}
		$this->writeToFile($output);
		
		echo $output;
	}
	
	/**
	 * Writes the output to a cache file
	 * 
	 * File outputed should be accessed with nginx.
	 * 
	 * @param string $data   | String to be written
	 * 
	 * @return boolean 
	 */
	protected function writeToFile($data) {
		$filename = "cache/v".$this->version."_".$this->action.".".$this->type;
		
		$h = fopen($filename, "w");
		
		if ($h !== false) {
			if (fwrite($h, $data) !== false) {
				return true;
			} else {
				throw new \Exception("Could not write data to cache file.");
			}
		} else {
			throw new \Exception("Could not open file to write cache file.");
		}
		
		return false;
	}
	/**
	 * Basic encoder to XML
	 * 
	 * @param array $data
	 * 
	 * @return string 
	 */
	protected function encodeToXML(array $data) {
		$output = "<xml>";
		foreach ($data as $k => $v) {
			$output .= "<result id='{$k}'>";
			foreach ($v as $i => $j)
				$output .= "<" . $i . ">" . $j . "</" . $i . ">";
			$output .= "</result>";
		}
		$output .= "</xml>";
		return $output;
	}

}

