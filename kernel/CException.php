<?php
class CException extends Exception{
	public $status;
	function __construct($message, $status=400,$code = 0){
		$this->status=$status;
		parent::__construct($message, $code);
	}	
}
function MyException_fun($exception){
	header("Content-Type:text/html;charset=utf-8",true,$exception->status);
	echo "Error:".$exception->status."<br/>";
	echo $exception->getMessage();
}
set_exception_handler('MyException_fun');
?>
