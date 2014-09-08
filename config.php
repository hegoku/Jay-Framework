<?php
return array(
	'basePath'=>dirname(__FILE__),
	'baseUrl'=>dirname($_SERVER['PHP_SELF'])=="/"?"":dirname($_SERVER['PHP_SELF']),
	'db'=>new Sqlite("xx.db"),
	//'db'=>new Mysql($host,$user,$pwd,$db,$charset),
	'ControllerDir'=>dirname(__FILE__)."/controllers",
	'ViewDir'=>dirname(__FILE__)."/views",
);
?>
