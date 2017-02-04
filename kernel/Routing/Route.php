<?php
namespace JF\Kernel\Routing;

use Exception;
use JF\Kernel\Config;

class Route{
    protected static $classNameSpance="App\\Controllers\\";

    /**
     * Route map,which load from route file
     * @var array
     */
    protected $routers=[
        'GET'=>[],
        'POST'=>[],
        'PUT'=>[],
        'DELETE'=>[]
    ];

    /**
     * Current request uri
     * @var string
     */
    public $request_uri;

    /**
     * Matched uri
     * @var string
     */
    public $uri;

    /**
     * HTTP method
     * @var string
     */
    public $method;

    /**
     * Controller Class Name
     * @var string
     */
    public $controller;

    /**
     * Action Name
     * @var string
     */
    public $action;

    /**
     * Route parameters
     * @var array
     */
    public $parameters;

    public function __construct(){
        $this->loadConfig();

        $this->method=$_SERVER['REQUEST_METHOD']?strtoupper($_SERVER['REQUEST_METHOD']):'GET';

        $this->setRequestUri();
        $this->setControllerAndAction();
        $this->setParameters();
    }

    /**
     * Load route file
     * @param string $path
     */
    protected function loadConfig(){
        $func=Config::load("routes");
        $func($this);
    }

    protected function setRequestUri(){
        $uri=$_SERVER['REQUEST_URI'];
        if(isset($_SERVER['QUERY_STRING'])){
            $uri=str_replace("?".$_SERVER['QUERY_STRING'],"",$_SERVER['REQUEST_URI']);
        }

        $this->request_uri=rawurldecode($uri);
    }

    protected function setControllerAndAction(){
        if(!isset($this->routers[$this->method])){
            throw new Exception("HTTP Method not found.");
        }

        foreach($this->routers[$this->method] as $uri=>$action){
            $pregUri=preg_quote($uri,"/");
            $pattern=preg_replace('/\\\{(\w+)\\\}/','(\w+)',$pregUri); // \/index\/\{fd\} replace \{fd\} to (w+)
            $res=preg_match_all('/'.$pattern.'$/',$this->request_uri);
            if($res>0){
                $this->uri=$uri;
                list($this->controller,$this->action)=explode("@",$action);
                $this->controller=static::$classNameSpance.$this->controller;
                return;
            }
        }
        throw new Exception("Route not found.");
    }

    /**
     * Register a new GET route
     * @param  string $uri
     * @param  \Closure|string $action
     * @return void
     */
    public function get($uri,$action){
        $this->addRouter('GET',$uri,$action);
    }

    /**
     * Register a new POST route
     * @param  string $uri
     * @param  \Closure|string $action
     * @return void
     */
    public function post($uri,$action){
        $this->addRouter('POST',$uri,$action);
    }

    protected function addRouter($method,$uri,$action){
        $this->routers[$method][$uri]=$action;
    }

    protected function setParameters(){
        preg_match_all('/\{(\w+)\}/',$this->uri,$matches);
        $parametersKey= isset($matches[1]) ? $matches[1] : [];

        $pregUri=preg_quote($this->uri,"/");
        $pattern=preg_replace('/\\\{(\w+)\\\}/','(\w+)',$pregUri); // \/index\/\{fd\} replace \{fd\} to (w+)
        preg_match_all('/'.$pattern.'$/',$this->request_uri,$matches);
        array_shift($matches);
        $parametersValue=[];
        foreach($matches as $value){
            array_push($parametersValue,$value[0]);
        }

        $this->parameters=array_combine($parametersKey,$parametersValue);
    }
}

?>
