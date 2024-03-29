<?php
namespace Generic;
class EntryPoint {
    public function __construct(private Website $website) {
    }

    private function loadTemplate($templateFileName, $variables = []) {
        extract($variables);
    
        ob_start();
        include  __DIR__ . '/../public/templates/' . $templateFileName;
    
        return ob_get_clean();
    }

    private function checkUri($uri) {
        if ($uri != strtolower($uri)) {
            http_response_code(301);
            header('location: ' . strtolower($uri));
        }
    }

    public function run(string $uri, string $method){
        try {
            $this->checkUri($uri);
          
            if ($uri == '') {
                $uri = $this->website->getDefaultRoute();
            }
          
            $route = explode('/', $uri);
          
            $controllerName = array_shift($route);
            $action = array_shift($route);

            $this->website->checkLogin($controllerName . '/' . $action);

            if ($method === 'POST') {
                $action .= 'Submit';
            }

            $controller = $this->website->getController($controllerName);

            if (is_callable([$controller, $action])) {
                $page = $controller->$action(...$route);
           
                $title = $page['title'];
           
                $heading = $page['heading'] ?? "";

                $alertText = $page['alertText'] ?? "";

                $alertStyle = $page['alertStyle'] ?? "hidden";

                $errors = $page['errors'] ?? [];
           
               $variables = $page['variables'] ?? [];
               $output = $this->loadTemplate($page['template'], $variables);
           }
           else {
               http_response_code(404);
               $title = 'Not found';
           
               $heading = 'Missing Page';

               $alertText = '404';

               $alertStyle = "noticef";

               $errors = [];

               $output = 'Sorry, the page you are looking for could not be found.';
           }
          
        } catch (\PDOException $e) {
              $title = 'An error has occurred';
           
              $heading = ''. $e->getCode();

              $alertText = '500';

              $alertStyle = "noticef";

              $errors = ['Database Error'];
          
              $output = 'Database error: ' . $e->getMessage() . ' in ' .
            $e->getFile() . ':' . $e->getLine();
        }
          
        $layoutVariables = $this->website->getLayoutVariables();
        $layoutVariables['title'] = $title;
        $layoutVariables['output'] = $output;
        $layoutVariables['errors'] = $errors;
        $layoutVariables['heading'] = $heading;
        $layoutVariables['alertText'] = $alertText;
        $layoutVariables['alertStyle'] = $alertStyle;

        echo $this->loadTemplate('layout.html.php', $layoutVariables);
    }
}