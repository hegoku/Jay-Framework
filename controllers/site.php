<?php
class site extends CController{

	public function index(){
		$this->pageTitle="Home";
		$this->render('index');
	}

}
?>
