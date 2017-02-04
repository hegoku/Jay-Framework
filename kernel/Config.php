<?php
namespace JF\Kernel;

class Config{
    protected static $basePath;

    public static function setBasePath($path){
        self::$basePath=$path;
    }

    public static function load($filename){
        return require self::$basePath."/config/".$filename.".php";
    }
}
?>
