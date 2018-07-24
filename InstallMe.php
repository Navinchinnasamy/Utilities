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
body {
	background-color: floralwhite;
}
.success {
	color: green;
}
.error {
	color: red;
}
.extn {
	color: blue;
}
#next {
	padding: 7px;
    border-radius: 5px;
    border: 1px solid #24a530;
    background-color: #008000bf;
    color: white;
    font-weight: bold;
    margin-right: 20px;
	margin-bottom: 10px;
	margin-left: 84%;
}
h3 {
	background-color: blueviolet;
	color: azure;
	border-radius: 5px 5px 0px 0px;
	text-align: center;
	margin: auto;
	padding: 5px;
}
.results {
	width: 50%;
    margin: auto;
    border-radius: 10px;
    background-color: white;
    border: 1px solid blueviolet;
}
.reqirements {
	margin-left: 20px;
}
#requirement-table {
	width: 100%;
    padding: 5px;
    margin-bottom: 10px;
}
.textbox {
	padding: 5px;
	border: 1px solid #a9a9a9;
	border-radius: 5px;
}
label {
	font-weight: bold;
	padding-right: 10px;
}
.form-elements {
	margin-top: 10px;
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
	
$i = new InstallMe();
$fail_count = 0;

echo "<div class='results'>";
echo "<h3>Software Requirements</h3>";
echo "<div class='reqirements'>";
echo "<table id='requirement-table'><thead><tr><th>Requirement</th><th>Status</th></tr></thead><tbody>";
if($i->getPhpVersion() >= $requirements['php_version']){
	echo "<tr><td>PHP version <b class='extn'>{$requirements['php_version']}</b> is required.</td><td> <b class='success'>Installed ".$i->getPhpVersion()."</b> </td></tr>";
} else {
	$fail_count++;
	echo "<tr><td>PHP version <b class='extn'>{$requirements['php_version']}</b> is required.</td><td><b class='error'>Installed  ".$i->getPhpVersion()."</b> </td></tr>";
}

foreach($requirements['extensions'] as $ext){
	if($i->checkExtensionEnabled($ext)){
		echo "<tr><td>PHP extension <b class='extn'>".$ext."</b> should be installed & enabled</td><td><b class='success'>Installed & Enabled</b></td>";
	} else {
		$fail_count++;
		echo "<tr><td>PHP extension <b class='extn'>".$ext."</b> should be installed & enabled</td><td><b class='error'>Not Installed or Enabled</b></td>";
	}
}
echo "</tbody></table>";

echo "</div>";
if($fail_count == 0){
	echo "<button type='button' id='next'>Next Step</button>";
}
echo "</div>";
?>

<div id="database-credentials" style="display: none;">
	<form id="dbcreds" method="post">
		<div class="form-elements">
			<label for="db_user">Database Username</label>
			<input id="db_user" name="db[user]" type="text" class="textbox" placeholder="Database Username.." />
		</div>
		<div class="form-elements">
			<label for="db_pass">Database Password</label>
			<input id="db_pass" name="db[pass]" type="text" class="textbox" placeholder="Database Password.." />
		</div>
		<div class="form-elements">
			<label for="db_name">Database Password</label>
			<input id="db_name" name="db[name]" type="text" class="textbox" placeholder="Database Name.." />
		</div>
	</form>
</div>

<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
	$("body").on("click", "#next", function(){
		$(".reqirements").html($("#database-credentials").html());
		$("h3").html("Database Credentials");
		$("#next").attr('id', 'checkdb').text('Check Database');
	});
	
	$("body").on("click", "#checkdb", function(){
		
	});
</script>
