<?php
namespace JF;

class Config{
    protected $config=[];

    public static function load($config){
        $this->config=$config;
    }

    public static function get($key){
        return $this->config[$key];
    }
}
?>
