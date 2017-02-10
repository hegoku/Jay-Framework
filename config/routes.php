<?php
return [
    ['get','/','SiteController@index'],
    ['get','/index/{fd}','SiteController@index'],
    ['get','/index/{foo}/a/{fd}','SiteController@index'],
]
?>
