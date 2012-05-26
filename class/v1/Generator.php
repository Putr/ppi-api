<?php
namespace v1;

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
class Generator extends \inc\GeneratorAbstract {
	
	/**
	 * Version information
	 * 
	 * @var string
	 */
	protected $version = 1;
	
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
				$csvData[$code]["flags"] = $this->conf["staticUrl"] . $flag;
			}
			// Add logo
			$logo = "logo/".$code.".png";
			if(file_exists($logo)) {
				$csvData[$code]["logo"] = $this->conf["staticUrl"] . $logo;
			}
			
			// add banner
			$banner = "banner/".$code.".gif";
			if(file_exists($banner)) {
				$csvData[$code]["banner"] = $this->conf["staticUrl"] . $banner;
			}
		}
		$this->output($csvData);
	}

}

