<?php
require __DIR__.'/../vendor/autoload.php';

$app=new JF\Kernel\App(realpath(dirname(__FILE__)."/../"));

$app->bind('route',new JF\Kernel\Route);

$app->run();

echo "<br/>".memory_usage()."<br/>";

?>
