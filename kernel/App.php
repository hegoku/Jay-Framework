<?php
namespace JF\Kernel;

use ReflectionClass;
use ReflectionParameter;
use Exception;
use JF\Kernel\Routing\Route;

class App{
	protected $bindings=[];
	protected $route;
	protected $basePath;

	public function __construct($basePath){
		$this->basePath=$basePath;
	}

	public function run(){
		$this->route=new Route($this->basePath."/app/routes.php");
		$controller=$this->make($this->route->controller);
		echo call_user_func_array([$controller,$this->route->action],$this->route->parameters);
	}

	public function make($className, array $parameters = []){
		return $this->build($className,$parameters);
	}

	public function build($className, array $parameters = []){
		$reflector=new ReflectionClass($className);

		if (! $reflector->isInstantiable()) {
            throw new Exception("Target [$concrete] is not instantiable.");
        }

		$constructor = $reflector->getConstructor();

		if(is_null($constructor)){
			return new $className;
		}

		$dependencies=$constructor->getParameters();
		$instances = $this->getDependencies($dependencies,$parameters);

		return $reflector->newInstanceArgs($instances);
	}

	protected function callMethod($class,$method,$parameters){
		$dependencies=$method->getParameters();
		$instances = $this->getDependencies($dependencies,$parameters);
		return call_user_func_array([$class,$method],$instances);
	}

	protected function getDependencies(array $parameters, array $primitives = []){
		$dependencies = [];
		foreach($parameters as $parameter){
			$dep=$parameter->getClass();
			//如果类名为null,说明是php自带类型
			if (array_key_exists($parameter->name, $primitives)) {
                $dependencies[] = $primitives[$parameter->name];
            }else if(is_null($dep)){
				$dependencies[]=$this->resolveNonClass($parameter);
			}else{
				$dependencies[]=$this->resolveClass($parameter);
			}
		}
		return $dependencies;
	}

	protected function resolveNonClass(ReflectionParameter $parameter){
		if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

		throw new Exception("Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}");
	}

	protected function resolveClass(ReflectionParameter $parameter){
		try{
			$this->make($parameter->getClass()->name);
		}catch(Exception $e){
			if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $e;
		}
	}
}
?>
