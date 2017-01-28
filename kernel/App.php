<?php
namespace JF\Kernel;

use ReflectionClass;
use JF\Kernel\Routing\Route;

class App{
	protected $bindings=[];
	protected $route;
	protected $basePath;

	public function __construct($basePath){
		$this->basePath=$basePath;
	}

	public function singleton($abstract,$extract){
		$this->bindings[$abstract]=new $extract;
	}

	public function run(){
		$this->route=new Route($this->basePath."/app/routes.php");
		$controller=$this->make($this->route->controller);
		echo call_user_func_array([$controller,$this->route->action],$this->route->parameters);
	}

	public function make($className){
		return $this->build($className);
	}

	public function build($className){
		$reflector=new ReflectionClass($className);

		if (! $reflector->isInstantiable()) {
            throw new Exception("Target [$concrete] is not instantiable.");
        }

		$constructor = $reflector->getConstructor();

		if(is_null($constructor)){
			return new $className;
		}

		$params=$constructor->getParameters();

	}
}
?>
