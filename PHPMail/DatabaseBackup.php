<?php

class DatabaseBackup {
	protected $DBHost;
	protected $DBUser;
	protected $DBPassword;
	protected $DBName;
	protected $DBTables = false;
	
	private $conn;

	public function __construct($db_host, $db_user, $db_pass, $db_name, $tables = false){
		$this->DBHost = $db_host;
		$this->DBName = $db_name;
		
		$this->conn = new mysqli($db_host,$db_user,$db_pass,$db_name); 
        $this->conn->select_db($db_name);
        $this->conn->query("SET NAMES 'utf8'");
		
		$this->getTables($tables);
	}
	
	protected function getTables($tables){
		$queryTables    = $this->conn->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
		
		$this->DBTables = $target_tables;
	}
	
	public function fetchDatabaseTables(){
		foreach($this->DBTables as $table)
        {
            $result         =   $this->conn->query('SELECT * FROM '.$table);  
            $fields_count  	=   $result->field_count;  
            $res            =   $this->conn->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            
			$content	    =   $this->createSQL($table, $fields_count, $result, $TableMLine);
        }
		
		//$this->exportDatabase($this->DBName, $content);
		$this->createDatabaseExportFile($this->DBName, $content);
	}
	
	protected function createSQL($table, $fields_count, $result, $TableMLine){
		$rows_num		=   $this->conn->affected_rows; 
		$content        =   (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";
		for ($i = 0, $st_counter = 0; $i < $fields_count;   $i++, $st_counter=0) 
		{
			while($row = $result->fetch_row())  
			{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )  
				{
						$content .= "\nINSERT INTO ".$table." VALUES";
				}
				$content .= "\n(";
				for($j=0; $j<$fields_count; $j++)  
				{ 
					$row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
					if (isset($row[$j]))
					{
						$content .= '"'.$row[$j].'"' ; 
					}
					else 
					{   
						$content .= '""';
					}     
					if ($j<($fields_count-1))
					{
							$content.= ',';
					}      
				}
				$content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
				{   
					$content .= ";";
				} 
				else 
				{
					$content .= ",";
				} 
				$st_counter=$st_counter+1;
			}
		}
		$content .=   "\n\n\n";
		
		return $content;
	}
	
	protected function exportDatabase($name, $content, $backup_name = false) {
		//$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = $backup_name ? $backup_name : $name.".sql";
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; exit;
	}
	
	protected function createDatabaseExportFile($name, $content, $backup_name = false){
		$backup_name = $backup_name ? $backup_name : $name.".sql";
		$file = fopen($backup_name, "a");
		fwrite($file, $content);
		fclose($file);
	}
}

?>