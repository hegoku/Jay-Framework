<?php
namespace JF;

class Route{
    public $baseUrl="";

    public function run(){
        $http_method=$_SERVER['REQUEST_METHOD']?strtolower($_SERVER['REQUEST_METHOD']):'get';
        $path=str_replace("?".$_SERVER['QUERY_STRING'],"",$_SERVER['REQUEST_URI']);
        echo $http_method.$path;
    }
}

?>
