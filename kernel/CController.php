<?php
/*
 * v0.2
 * 添加redirect功能
 * 添加pageTitle属性
 */
class CController{
	public $layout="layouts/main";
	public $pageTitle="";
	public function render($view,$var=null){
		if(!file_exists(JF::app()->ViewDir."/".$this->layout.".php")){
			throw new Exception("Can't find layout!<br />");
			return false;
		}
		$output=$this->renderFile($view,$var,true);
		$content=array('content'=>$output);
		$this->renderFile($this->layout,$content);
	}

	public function renderFile($view,$var=null,$return=false){
		if(!file_exists(JF::app()->ViewDir."/".$view.".php")){
			throw new Exception("Can't find view!<br />");
			return false;
		}
		if(is_array($var)){
			extract($var,EXTR_PREFIX_SAME,'data');
		}
		if($return){
			ob_start();
			ob_implicit_flush(0);
			require(JF::app()->ViewDir."/".$view.".php");
			return ob_get_clean();
		}else{
			require(JF::app()->ViewDir."/".$view.".php");
		}
	}

	public function redirect($url,$statusCode=302){
		JF::app()->redirect($url,$statusCode);
	}

	public function accessRules(){
		return array();
	}

}
?>
