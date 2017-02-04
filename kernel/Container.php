<?php
namespace JF\Kernel;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Exception;

class Container{

    /**
     * Bindings
     * @var array
     */
    protected $bindings=[];

    /**
     * Bind
     * @param  string $key
     * @param  string|object|Closure $concrete
     * @return void
     */
    public function bind($key,$concrete){
        if(is_string($concrete)){ // If $concrete is string
            $concrete=ltrim($concrete, '\\');
            $this->bindings[$key]=function($container,$parameters=[]) use($concrete){
                return $container->make($concrete,$parameters);
            };
        }else{ // When $concrete is object or Closure
            $this->bindings[$key]=$concrete;
        }
    }

    /**
     * make
     * @param  string $abstract Can be a key or full class name
     * @param  array $parameters
     * @return object
     */
    public function make($abstract,$parameters=[]){
        // If the key has been bound
        if(isset($this->bindings[$abstract])){
            $concrete=$this->bindings[$abstract];
            if($concrete instanceof \Closure){
                return $concrete($this,$parameters);
            }else{
                return $concrete;
            }
        }

        // If the key has not been bound, it may be a class name, so we should build it.
        $reflector=new ReflectionClass($abstract);

		if (! $reflector->isInstantiable()) {
            throw new Exception("Target [$concrete] is not instantiable.");
        }

		$constructor = $reflector->getConstructor();

		if(is_null($constructor)){
			return new $abstract;
		}

		$dependencies=$constructor->getParameters();
		$instances = $this->getDependencies($dependencies,$parameters);

		return $reflector->newInstanceArgs($instances);
    }

    protected function getDependencies(array $parameters, array $primitives = []){
		$dependencies = [];
		foreach($parameters as $parameter){
			$dep=$parameter->getClass();

			if (array_key_exists($parameter->name, $primitives)) {
                $dependencies[] = $primitives[$parameter->name];
            }else if(is_null($dep)){ //如果类名为null,说明是php自带类型
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
			return $this->make($parameter->getClass()->name);
		}catch(Exception $e){
			if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $e;
		}
	}

    public function callMethod($class,$method,$parameters){
        $method=new ReflectionMethod($class,$method);
		$dependencies=$method->getParameters();
		$instances = $this->getDependencies($dependencies,$parameters);
		return call_user_func_array([$class,$method->name],$instances);
	}
}
?>
