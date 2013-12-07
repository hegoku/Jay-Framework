<?php
/*
 * v0.2
 * 增加redirect()
 * 增加app()
 * 修改框架名为Jay Framework (JF)
 */
class JF{
	/*
	 *public static $db;
	 *public static $ModelDir;
	 *public static $ControllerDir;
	 *public static $ViewDir;
	 *public static $user;
	 *public static $dir;
	 */

	public static $_app;

	public function configure($config){
		if(is_array($config)){
			foreach($config as $key=>$value)
				$this->$key=$value;
		}
	}

	public static function init($config){
		self::$_app=new CApp($config);
	}

	public static function app(){
		return self::$_app;
	}
}

class CApp{
	public $config;
	function __construct($config){
		if(is_array($config)){
			$this->config=array();
			foreach($config as $k=>$v){
				$this->config+=array($k=>$v);
			}
		}
	}

	function __get($property){
		return $this->config[$property];
	}

	public function redirect($url,$statusCode=302){
		header('Location: '.$url, true, $statusCode);
		exit(0);
	}
}
?>
