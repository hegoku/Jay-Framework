<?php
namespace JF;

class App{
	protected $bindings=[];
	protected $route;

	public function __construct(){
		$this->route=new Route;
	}

	public function singleton($abstract,$extract){
		$this->bindings[$abstract]=new $extract;
	}

	public function run(){
		$this->route->run();
	}
}
?>
