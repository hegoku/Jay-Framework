<?php
require __DIR__.'/vendor/autoload.php';

$app=new JF\App;

$app->singleton('config',JF\Config::class);

$app->run();

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

echo convert(memory_get_usage(true))."<br/>";

?>
