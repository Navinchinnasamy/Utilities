<?php
/**
* Getting the required configurations and walk through installation instructions
*/

class InstallMe {
	private $ini_configs;
	private $php_version;
	
	public function __construct(){
		$this->getIniCinfig();
		$this->php_version = phpversion();
	}
	
	/**
	* Getting the php.ini configurations
	*/
	public function getIniCinfig(){
		$this->ini_configs = ini_get_all();
		return $this->ini_configs;
	}
	
	// Get PHP version
	public function getPhpVersion(){
		return $this->php_version;
	}
	
	public function getSpecificConfig($key){
		return $this->ini_configs[$key];
	}
	
	/**
	* Check if the extension is enabled in php.ini configuration file
	*/
	public function checkExtensionEnabled($ext) {
		return extension_loaded($ext);
	}
		
}

?>

<style>
.success {
	color: green;
}
.error {
	color: red;
}
.extn {
	color: blue;
}
</style>
<?php
$requirements = array(
		"php_version" => 5.6,
		"mysql" => "5.5.3",
		"mariadb" => 5.5,
		"postgres" => 9.3,
		
		"extensions" => array(
			"mbstring",
			"intl",
			"simplexml",
			"mysqli",
			"pgsql"
		)
	);
	
$i = new InstallMe;

if($i->getPhpVersion() >= $requirements['php_version']){
	echo "<p class='success'>PHP version <b class='extn'>".$i->getPhpVersion()."</b> installed and required <b>{$requirements['php_version']}</b></p>";
} else {
	echo "<p class='error'>PHP version <b class='extn'>".$i->getPhpVersion()."</b> installed and required <b>{$requirements['php_version']}</b></p>";
}

foreach($requirements['extensions'] as $ext){
	if($i->checkExtensionEnabled($ext)){
		echo "<p class='success'>PHP extension <b class='extn'>".$ext."</b> installed / enabled</p>";
	} else {
		echo "<p class='error'>PHP extension <b class='extn'>".$ext."</b> not installed / enabled</p>";
	}
}

?>