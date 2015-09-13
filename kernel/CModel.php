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
						$this->cols[$v]=addslashes($value[$v]);
					}else{
						//$this->cols[$v]=null;
					}
				}
			}else{
				$this->cols[$property]=$value;
			}
		}

		function __get($property){
			return stripslashes($this->cols[$property]);
		}

		public function findByPk($pk){
			$result=JF::app()->db->query("SELECT * FROM ".$this->table." WHERE ".$this->pk."='$pk'");
			$row=JF::app()->db->getRow($result);
			if($row==null){
				return null;
			}else{
				$model=$this->model();
				$model->attributes=$row;
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

		public function findAll($sql=""){
			//if($sql!=""){$sql=" WHERE ".$sql;}
			$result=JF::app()->db->query("SELECT * FROM ".$this->table." ".$sql);
			$rows=Array();
			while($row=JF::app()->db->getRow($result)){
				$model=$this->model();
				$model->attributes=$row;
				array_push($rows,$model);
			}
			return $rows;
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
	}
?>
