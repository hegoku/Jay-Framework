<?php
namespace JF\Kernel;

use Exception;

class Config{
    protected static $basePath;
    protected static $config=[];

    public static function setBasePath($path){
        self::$basePath=$path;
    }

    public static function read($filename){
        $path=self::$basePath."/config/".$filename.".php";
        if(!file_exists($path)){
            throw new Exception("Config file 'config/".$filename.".php' does not exist.");
        }
        return require $path;
    }

    public static function load($filename){
        $array=self::read($filename);
        self::loopArray($filename,$array);
    }

    protected static function loopArray($key,$array){
        foreach($array as $k=>$v){
            if(is_array($v)){
                self::loopArray($key.".".$k,$v);
            }else{
                self::$config[$key.".".$k]=$v;
            }
        }
    }

    public static function get($key){
        if(isset(self::$config[$key])){
            return self::$config[$key];
        }else{
            return null;
        }
    }
}
?>
