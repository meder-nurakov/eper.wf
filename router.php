<?php
class Router{
  private $routes;

  /*Подключить роуты*/
  public function __construct(){
    $routerPath = ROOT.'/config/routes.php';
    $this->routes = include_once($routerPath);
  }

  /*
  *   Get url
  */
  private function getURL(){
    if (!empty($_SERVER['REQUEST_URI'])){
      return trim($_SERVER['REQUEST_URI'],'/');
    }
  }
  
  public function run(){
    $url = $this->getURL();

    foreach ($this->routes as $urlPattern => $path){
      
      if (preg_match("~$urlPattern~",$url)){
        $internalRoute = preg_replace("~$urlPattern~", $path, $url);

        if ($urlPattern == '' && $urlPattern != $url) {
          $internalRoute = 'index/notF';
        }

        /*Определить контроллер, действие и параметры*/
        $segments = explode('/',$internalRoute);
        
        $controllerName = array_shift($segments).'Controller';
        $controllerName = ucfirst($controllerName);

        /*actionName*/
        $actionName = ucfirst(array_shift($segments));
        if (stristr($actionName, '?')) {
          $actionName = explode('?',$actionName);
          $actionName = array_shift($actionName);
        }

        $actionName = $actionName.'Action';
        /*actionName end*/

        $parameters = array_shift($segments);
        if (stristr($parameters, '?')) {
          $parameters = explode('?', $parameters);
          $parameters = array_shift($parameters);
        }

        /*Подключить контроллер*/
        $controllerFile = ROOT.'/controller/'.$_SESSION['user'].'/'.$controllerName.'.php';
        if (file_exists($controllerFile)){
          require_once($controllerFile);
        }

        /*Создать объект*/
        $controllerObject = new $controllerName;
        $controllerObject->$actionName($parameters);

        break;
      }
    }
  }
}