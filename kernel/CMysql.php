<?php
class MySql{
	var $host="";
	var $user="";
	var $pwd="";
	var $db="";
	var $con;
	
	function __construct($host,$user,$pwd,$db,$charset){
		$this->host=$host;
		$this->user=$user;
		$this->pwd=$pwd;
		$this->db=$db;
		
		$this->con = mysql_connect($this->host,$this->user,$this->pwd);
		if (!$this->con){
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($this->db, $this->con);
		mysql_query("SET NAMES '".$charset."'");
		echo  mysql_error();
	}
	
	public function query($sql){
		return mysql_query($sql,$this->con);
	}

	public function getFields($table,&$cols,&$pk){
		$result=$this->query("DESCRIBE ".$table);
		while($row=mysql_fetch_array($result)){
			if($row['Key']=='PRI'){
				$pk=$row['Field'];
			}
			array_push($cols,$row['Field']);
		}
		$cols=array_flip($cols);
	}
	
	public function getRow($result){
		return mysql_fetch_array($result);
	}

	public function last_id(){
		return mysql_insert_id();
	}
}
?>
