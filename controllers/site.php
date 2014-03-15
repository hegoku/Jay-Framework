<?php
class site extends Controller{

	public function index(){
		$this->pageTitle="Home";
		$this->render('index');
	}
	
}
?>
