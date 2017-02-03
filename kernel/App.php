<?php
namespace JF\Kernel;

use JF\Kernel\Routing\Route;

class App{
	protected $bindings=[];
	protected $route;
	protected $basePath;
	protected $container;

	public function __construct(Container $container,$basePath){
		$this->container=$container;
		$this->basePath=$basePath;
	}

	public function run(){
		$this->route=new Route($this->basePath."/app/routes.php");
		$controller=$this->container->make($this->route->controller);
		echo call_user_func_array([$controller,$this->route->action],$this->route->parameters);
	}

	protected function callMethod($class,$method,$parameters){
		$dependencies=$method->getParameters();
		$instances = $this->getDependencies($dependencies,$parameters);
		return call_user_func_array([$class,$method],$instances);
	}

}
?>
