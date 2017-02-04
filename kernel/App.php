<?php
namespace JF\Kernel;

class App extends Container{
	protected $route;
	protected $basePath;
	protected $container;

	public function __construct($basePath){
		$this->basePath=$basePath;
		Config::setBasePath($basePath);
		Config::load('app');
	}

	public function run(){
		$this->route=$this->make('route');
		$controller=$this->make($this->route->controller);
		echo $this->callMethod($controller,$this->route->action,$this->route->parameters);
	}

}
?>
