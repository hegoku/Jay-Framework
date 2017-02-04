<?php
require __DIR__.'/../vendor/autoload.php';

$app=new JF\Kernel\App(realpath(dirname(__FILE__)."/../"));
$app->run();

echo "<br/>".memory_usage()."<br/>";

?>
