<?php
/**
 * Generator class
 * 
 * @author Rok Andrée <rok@andree.si>
 * @copyright CC-BY-NC
 * @license http://creativecommons.org/licenses/by-nc/2.0/
 * 
 * @package PPI-API
 * @subpackage v1
 * 
 * @version 1.0
 */
class Generator {
	
	/**
	 * URL for static content
	 * 
	 * @var string
	 */
	public $url = "http://api.piratskastranka.net/";
	
	/**
	 * Type of return data (xml || json)
	 * 
	 * @var string
	 */
	public $type;
	
	/**
	 * Constructor
	 * 
	 * @param string $type 
	 */
	public function __construct($type) {
		$this->type = $type;
	}

	/**
	 * ACTION: Returns basic data about all pirate parties
	 */
	public function getPiratePartiesAction() {
		$csvData = array();

		$row = true;
		if (($handle = fopen("data/pp-data.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if ($row !== true) {
					$csvData[$data[0]] = array(
						"title" => $data[1],
						"link" => $data[8],
						"ppi-member" => false
					);

					if ($data[9] == "yes") {
						$csvData[$data[0]]["ppi-member"] = true;
					}
				}
				$row = false;
			}
			fclose($handle);
		}
		
		require("data/links.php"); // $PP
		require("data/titles.php"); // $en
		
		foreach ($csvData as $code => $data) {
			if (!empty($en[$code])) {
				$csvData[$code]["title"] = $en[$code];
			}
			if(!empty($PP[$code])) {
				$csvData[$code]["link"] = $PP[$code];
			}
			
			// Add country image
			$flag = "flags/".strtolower($code).".gif";
			if(file_exists($flag)) {
				$csvData[$code]["flags"] = $this->url . $flag;
			}
			// Add logo
			$logo = "logo/".$code.".png";
			if(file_exists($logo)) {
				$csvData[$code]["logo"] = $this->url . $logo;
			}
			
			// add banner
			$banner = "banner/".$code.".gif";
			if(file_exists($banner)) {
				$csvData[$code]["banner"] = $this->url . $banner;
			}
		}
		$this->output($csvData);
	}
	
	/**
	 * Parses the data for output
	 * 
	 * @param array $data
	 * 
	 * @todo Should be moved to abstract class
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
		echo $output;
	}
	
	/**
	 * Basic encoder to XML
	 * 
	 * @param array $data
	 * 
	 * @return string 
	 */
	private function encodeToXML(array $data) {
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

