<?php
return array(
	'db'=>new Sqlite("xx.db"),
	//'db'=>new Mysql($host,$user,$pwd,$db,$charset),
	'ControllerDir'=>dirname(__FILE__)."/controllers",
	'ViewDir'=>dirname(__FILE__)."/views",
);
?>
