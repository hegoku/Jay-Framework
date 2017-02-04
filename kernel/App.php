<?php
namespace JF\Kernel;

use JF\Kernel\Routing\Route;

class App extends Container{
	protected $route;
	protected $basePath;
	protected $container;

	public function __construct($basePath){
		$this->basePath=$basePath;
		Config::setBasePath($basePath);
	}

	public function run(){
		$this->route=new Route();
		$controller=$this->make($this->route->controller);
		echo $this->callMethod($controller,$this->route->action,$this->route->parameters);
	}

}
?>
