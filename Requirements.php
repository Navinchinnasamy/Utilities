<?php
class Requiremets 
{
	public $requirements = array();
	private $ini_path;
	
	public function __construct(){
		$this->requirements['php_version'] = function_exists('phpversion') ? phpversion() : FALSE;
		
		$mysqlAvailable = defined('PDO::MYSQL_ATTR_LOCAL_INFILE');
		$this->requirements['mysql'] = $mysqlAvailable;
		
		$jsonAvailable = function_exists('json_encode');
		$this->requirements['json'] = $mysqlAvailable;
		
		$memoryLimit = ((int) ini_get('memory_limit') >= 32 ? false : true);
		$this->requirements['memory_limit'] = $memoryLimit;
		
		$curlSupport = function_exists('curl_init');
		$this->requirements['curl'] = $curlSupport;
		
		if($this->requirements['php_version']){
			$this->ini_path = php_ini_loaded_file();
			$this->readIniFile();
		}
	}
	
	private function readIniFile(){
		$this->requirements['php']['config']=  parse_ini_file($this->ini_path));
	}
	
}

$r = new Requiremets();
echo "<pre>";
print_r($r->requirements);
exit
?>