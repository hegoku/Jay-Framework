<?php
require __DIR__.'/vendor/autoload.php';

//$app=new JF\Kernel\App(dirname(__FILE__));
$container=new JF\Kernel\Container;

$container->bind('container',$container);
$container->bind('app',new JF\Kernel\App($container,dirname(__FILE__)) );

$app=$container->make('app');
$app->run();

echo "<br/>".memory_usage()."<br/>";

?>
