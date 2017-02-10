<?php
use JF\Kernel\Debug\HtmlDumper;


if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd(){
        array_map(function ($x) {
            (new HtmlDumper)->var_dump($x);
        }, func_get_args());
    }
}

if (! function_exists('memory_usage')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function memory_usage(){
        $size=memory_get_usage(true);
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
}
?>
