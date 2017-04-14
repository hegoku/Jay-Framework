<?php
return [
    ['method'=>'get', 'uri'=>'/', 'action'=>'SiteController@index'],
    ['method'=>'get', 'uri'=>'/index/{fd}', 'action'=>'SiteController@index'],
    ['method'=>'get', 'uri'=>'/index/{foo}/a/{fd}', 'action'=>'SiteController@index'],
]
?>
