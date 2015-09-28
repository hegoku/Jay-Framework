<?php
/*
v0.2
加入特殊字符转意
attributes赋值
删除功能
将findByPk改为protected->public
v0.3
判断attributes
v0.4
添加model()
v0.5
添加update()
v0.6
insert()判断字段值为空时,写入null
v.07
取消魔术引号
 */
	class CModel{
		protected $table;
		protected $pk;
		public $cols=array();
		public $_isNewRecord=true;
		function __construct($row=null){
			JF::app()->db->getFields($this->table,$this->cols,$this->pk);
			if($row==null){
				foreach($this->cols as $k=>$col){
					$this->cols[$k]=null;
				}
			}else{
				$this->cols=$row;
			}
		}

		function __set($property,$value){
			if($property=='attributes' && is_array($value)){
				$keys=array_keys($this->cols);
				foreach($keys as $v){
					$v=str_replace('"',"",$v);
					if(isset($value[$v])){
						$this->cols[$v]=$value[$v];
					}else{
						//$this->cols[$v]=null;
					}
				}
			}else{
				$this->cols[$property]=$value;
			}
		}

		function __get($property){
			return $this->cols[$property];
		}

		public function findByPk($pk){
			$result=JF::app()->db->query("SELECT * FROM ".$this->table." WHERE ".$this->pk."='$pk'");
			$row=JF::app()->db->getRow($result);
			if($row==null){
				return null;
			}else{
				$model=$this->model();
				$model->attributes=$row;
				$model->_isNewRecord=false;
				return $model;
			}
		}

		public function find($sql){
			//if($sql!=""){$sql=" WHERE ".$sql;}
			$result=JF::app()->db->query("SELECT * FROM ".$this->table." ".$sql);
			$row=JF::app()->db->getRow($result);
			if($row==null){
				return null;
			}else{
				$model=$this->model();
				$model->attributes=$row;
				return $model;
			}
		}

		public function findAll($sql="",$parms=null){
			//if($sql!=""){$sql=" WHERE ".$sql;}
			if($sql==""){
				$sqlTxt ='SELECT * FROM ' . $this->table;
			}else{
				$where = $this->parseWhere($sql,$parms);
				$sqlTxt ='SELECT * FROM ' . $this->table.$where;
			}
			$result=JF::app()->db->query($sqlTxt);
			$rows=Array();
			while($row=JF::app()->db->getRow($result)){
				$model=$this->model();
				$model->attributes=$row;
				$model->_isNewRecord=false;
				array_push($rows,$model);
			}
			return $rows;
		}

		public function save(){
			if($this->_isNewRecord){
				return $this->insert();
			}else{
				return $this->update();
			}
		}

		public function insert(){
			$p="";
			foreach($this->cols as $row){
				if($row==null){
					$row='null';
				}else{
					$row="'".$row."'";
				}
				if($p==""){
					$p=$row;
				}else{
					$p.=",".$row;
				}
			}
			$sql="INSERT INTO ".$this->table." VALUES($p)";
			JF::app()->db->query($sql);
			return JF::app()->db->last_id();
		}

		public function deleteByPk($pk){
			$sql="DELETE FROM ".$this->table." where ".$this->pk."='$pk'";
			//echo $sql;
			JF::app()->db->query($sql);
		}

		public function update($sql){
			$p="";
			foreach($this->cols as $k=>$row){
				if($this->pk==$k)continue;
				if($p==""){
					$p="$k='".$row."'";
				}else{
					$p.=",$k='".$row."'";
				}
			}
			JF::app()->db->query("UPDATE ".$this->table." SET ".$p." WHERE ".$sql);
		}

		public static function model($className=__CLASS__){
			return new $className();
		}

		protected function parseWhere($where,$para=array()){
			if($para){
				foreach($para as $key=>$value){
					if(is_array($value)){
						foreach($value as $k=>$v){
							$value[$k] = addslashes($v);
						}
						$para[$key] = "'".implode("','",$value)."'";
					}
					else{
						$para[$key] = '"'.addslashes($value).'"';
					}
				}
				$where = str_replace(array_keys($para),array_values($para),$where);
			}
			$where = " WHERE ".$where;
			return $where;
		}
	}
?>
