<?php
namespace JF\Kernel;

class App extends Container{
	protected $route;
	protected $basePath;
	protected $container;

	public function __construct($basePath){
		$this->basePath=$basePath;
		$this->loadEnvFile();
		Config::setBasePath($basePath);
		Config::load('app');
	}

	public function run(){
		$this->route=$this->make('route');
		$controller=$this->make($this->route->controller);
		echo $this->callMethod($controller,$this->route->action,$this->route->parameters);
	}

	public function loadEnvFile()
    {
        $env_file = parse_ini_file($this->basePath."/.env");
        foreach ($env_file as $k=>$v) {
            putenv($k.'='.$v);
        }
    }

}
?>
