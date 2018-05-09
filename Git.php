<?php
class Git {
	protected $os;
	public $git_cmds = array();
	
	function __construct(){
		$this->git_cmds = array(
			"status" => "git status",
			"remote" => "git remote -v",
			"branch" => "git branch",
			"diff" => "git diff --name-only",
			"log" => "git log"
		);
		if(stripos(php_uname(), "windows") != -1){
			$this->os = "windows";
		} else {
			$this->os = "linux";
		}
	}
	
	function runCmds($cmd){
		return htmlentities(trim(shell_exec($cmd)));
	}
}

?>

<?php
if(isset($_POST['action'])){
	$g = new Git;
	$path = preg_replace('~\{2,}~', '\\\\', $_POST['projpath']);
	$_POST['projpath'] = ucfirst($path);
	$cmd = "cd ".$_POST['projpath']." && ".$g->git_cmds[$_POST['action']];
	$output = $g->runCmds($cmd);
}
?>

<html>
	<head>
		<title>Git</title>
		<style>
			button {
				padding: 5px;
				border: 1px solid;
				border-radius: 5px;
			}
			input {
				padding: 5px;
				border: 1px solid;
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
		<form method="POST">
			<fieldset>
				<legend>Project</legend>
				<label>Project Path: </label>
				<input type="text" name="projpath" id="projpath" value="<?php echo isset($_POST['projpath']) ? $_POST['projpath'] : ''; ?>" placeholder="Project directory.." />
			</fieldset>
			<fieldset>
				<legend>Actions</legend>
				<button type="submit" name="action" value="status">Status</button>
				<button type="submit" name="action" value="remote">Remote</button>
				<button type="submit" name="action" value="branch">Branch</button>
				<button type="submit" name="action" value="log">Log</button>
				<button type="submit" name="action" value="diff">Difference</button>
			</fieldset>
		</form>
		<fieldset>
			<legend>Output</legend>
			<div id="output">
				<?php echo isset($output) ? "<pre>".$output."</pre>" : "Sorry! Nothing to display..!!"; ?>
			</output>
		</fieldset>
	</body>
</html>