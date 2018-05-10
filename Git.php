<?php
class Git {
	protected $os;
	public $git_cmds = array();
	
	function __construct(){
		$this->git_cmds = array(
			"status" => "git status",
			"remote" => "git remote -v",
			"branch" => "git branch",
			"diff" 	 => "git diff",
			"log" 	 => "git log",
			"git" 	 => "git rev-parse --is-inside-work-tree",
			"switch" => "git checkout"
		);
		if(stripos(php_uname(), "windows") != -1){
			$this->os = "windows";
		} else {
			$this->os = "linux";
		}
	}
	
	function runCmds($dir, $cmd){
		if($this->os == "windows"){
			$path = preg_replace('~\{2,}~', '\\\\', $dir);
			$path = ucfirst($path);
		} else {
			$path = $dir;
		}
		
		$isGit = $this->checkGitRepo($path);
		
		if($isGit){
			$cmd = "cd {$path} && {$this->git_cmds[$cmd]}";
			return htmlentities(trim(shell_exec($cmd)));
		} else {
			return "Not a Git repository!";
		}
	}
	
	private function checkGitRepo($path){
		$cmd = "cd {$path} && {$this->git_cmds['git']}";
		$isGit = htmlentities(trim(shell_exec($cmd)));
		return ($isGit == "true");
	}
	
}

?>

<?php
if(isset($_POST['action'])){
	if(empty($_POST['projpath'])){
		$output = "Project path should not be empty!";
	} elseif($_POST['action'] == "switch" && empty($_POST['branchname'])){
		$output = "Branch name should not be empty!";
	} else {
		$g = new Git;
		$output = $g->runCmds($_POST['projpath'], $_POST['action']);
	}
}
?>

<html>
	<head>
		<title>Git</title>
		<style>
			button {
				padding: 5px;
				border: 1px solid;
				border-color: darkkhaki;
				border-radius: 5px;
			}
			input {
				margin: 5px;
				padding: 5px;
				border: 1px solid;
				border-color: darkkhaki;
				border-radius: 5px;
			}
			label {
				display: inline-block;
				width: 8%;
			}
		</style>
	</head>
	<body>
		<form method="POST">
			<fieldset>
				<legend>Project</legend>
				<div>
					<label>Project Path: </label>
					<input type="text" name="projpath" id="projpath" value="<?php echo isset($_POST['projpath']) ? $_POST['projpath'] : ''; ?>" placeholder="Project directory.." />
				</div>
			</fieldset>
			<fieldset>
				<legend>Actions</legend>
				<button type="submit" name="action" value="status">Status</button>
				<button type="submit" name="action" value="remote">Remote</button>
				<button type="submit" name="action" value="branch">Branches</button>
				<button type="submit" name="action" value="log">Log</button>
				<button type="submit" name="action" value="diff">Difference</button>
				<button type="submit" name="action" value="switch">Switch</button>
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
