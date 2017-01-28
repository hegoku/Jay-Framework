<?php
return function($route){
    $route->get('/index','SiteController@index');
    $route->get('/index/{fd}','SiteController@index');
    $route->get('/index/{foo}/a/{fd}','SiteController@index');
}
?>
