<?php
/*
 * v0.2 
 * 增加自动包含include里的类
 * v0.3
 * 增加action判断
 * v0.4
 * 添加config.php
 * 添加include_foler()
 * v0.5
 * 添加accessRules判断
 * v0.6
 * 如果controller没有重载accessRules方法,就默认所有action都能访问
 * 添加session_start()
 */
ini_set('display_errors',  '1');
include_folder('kernel');
include_folder('include');
include_folder('models');
$config=include_once("config.php");

session_start();

JF::init($config);

//route
if(isset($_GET['r'])){
	$route=explode("/",$_GET['r']);
}else{
	$route=array('0'=>'site','1'=>'index');
}
if(count($route)==2){
	$action=$route[1];
	$class=$route[0];
}else{
	$tmpr=explode("/",$_GET['r']);
	$action=$tmpr[count($tmpr)-1];
	array_pop($tmpr);
	$class=implode("/",$tmpr);
}
if(file_exists(JF::app()->ControllerDir."/".$class.".php")){
	include_once(JF::app()->ControllerDir."/".$class.".php");
	$finalclass=explode("/",$class)[count(explode("/",$class))-1];
	$tmp=new $finalclass;
	if(method_exists($tmp,"$action")){
		$access=$tmp->accessRules();
		//如果没有重载accessRules
		if($access==null){
			$tmp->$action();
			exit(0);
		}
		foreach($access as $r){
			if(in_array($action,$r['actions'])){
				eval('$logic=('.$r['expression'].');');
				if($r[0]=='allow' && $logic){
					$tmp->$action();
					exit(0);
				}elseif($r[0]=='deny' && $logic){
					throw new CException("无权限!");
					exit(0);
				}else{
					throw new CException("无权限!");
				}
			}
		}
	}else{
		throw new CException("页面不存在!",404);
	}
}else{
	throw new CException("页面不存在!",404);
}

//
function include_folder($path){
	$dir=dirname(__FILE__)."/".$path;
	if(is_dir($dir)){
		if($dh=opendir($dir)){
			while($file=readdir($dh)){
				if(preg_match('/^[^\.].*(\.php)$/',$file)){
					include_once($dir."/".$file);
				}elseif(preg_match('/^[^\.].*/',$file)){
					if(is_dir($dir."/".$file)){
						include_folder($path."/".$file);
					}
				}
			}
			closedir($dh);
		}
	}
}
?>
