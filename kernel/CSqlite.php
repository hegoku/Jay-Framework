<?php
class Sqlite{
	var $con;
	
	function __construct($tb){
			$this->con = new PDO("sqlite:".$tb, "", "");
	}
	
	public function query($sql){
			return $this->con->query($sql);
	}
	
	public function getRow($result){
			return $result->fetch();
	}

	public function getFields($table,&$cols,&$pk){
		$sql=$this->con->query("SELECT sql FROM sqlite_master WHERE tbl_name='".$table."'");
		foreach($sql as $row){
			$fields = $row["sql"];
		}
		$cut=strtok($fields,"(");
		while($fieldnames[]=strtok(",")) {};
		array_pop($fieldnames);
		foreach($fieldnames as $no => $field){
			if (strpos($field, "PRIMARY KEY")){
				strtok($field,"(");
				//$pk=strtok(")");
				//unset($fieldnames[$no]);
				$pk=strtok($field, " ");
				preg_match('/(?<=\[)([^\]]*?)(?=\])/',$pk,$matches);
				$pk=$matches[0];
			}
			preg_match('/(?<=\[)([^\]]*?)(?=\])/',strtok($field, " "),$matches);
			$fieldnames[$no]=$matches[0];
		}
		$cols=array_flip($fieldnames);
	}
}
?>
