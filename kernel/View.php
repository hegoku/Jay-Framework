<?php
namespace JF\Kernel;

use Exception;

class View{
    public $layout="layouts/main";
    public $pageTitle="";

    public function setPageTitle($title){
        $this->pageTitle=$title;
        return $this;
    }

    public function render($view,$var=null){
		if(!file_exists(Config::get('app.view_dir')."/".$this->layout.".php")){
			throw new Exception("Can't find layout!");
			return false;
		}
		$output=$this->renderFile($view,$var,true);
		$content=array('content'=>$output);
		return $this->renderFile($this->layout,$content,true);
	}

	public function renderFile($view,$var=null,$return=false){
		if(!file_exists(Config::get('app.view_dir')."/".$view.".php")){
			throw new Exception("Can't find view!");
			return false;
		}
		if(is_array($var)){
			extract($var,EXTR_PREFIX_SAME,'data');
		}
		if($return){
			ob_start();
			ob_implicit_flush(0);
			require(Config::get('app.view_dir')."/".$view.".php");
			return ob_get_clean();
		}else{
			require(Config::get('app.view_dir')."/".$view.".php");
		}
	}
}
?>
