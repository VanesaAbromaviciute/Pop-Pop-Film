<?php

class Router {

    private $routes = [];

    function setRoutes(Array $routes) {
        $this->routes = $routes;
    }

    function getFilename(string $url) {
        global $urlPrefix;
        error_log("Buscando ruta para url $url");
        foreach($this->routes as $route => $file) {
            if(strpos($url , $urlPrefix . "/" . $route) !== false){
                error_log("Ruta encontrada: $file");
                return $file;
            }
        }
        error_log("No se ha encontrado la ruta");

    }
}
