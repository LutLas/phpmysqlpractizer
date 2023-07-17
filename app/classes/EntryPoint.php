<?php
class EntryPoint {

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

    public function run($uri){
        try {
            include __DIR__ . '/../includes/DatabaseConnection.php';
            include __DIR__ . '/../classes/DatabaseTable.php';
            include __DIR__ . '/../controllers/JokeController.php';
            include __DIR__ . '/../controllers/AuthorController.php';
          
            $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
            $authorsTable = new DatabaseTable($pdo, 'author', 'id');
          
            $this->checkUri($uri);
          
              if ($uri == '') {
                  $uri = 'joke/home';
              }
          
              $route = explode('/', $uri);
          
              $controllerName = array_shift($route);
              $action = array_shift($route);
          
            if ($controllerName === 'joke') {
                $controller = new JokeController($jokesTable, $authorsTable);
            }
            else if ($controllerName === 'author') {
                $controller = new AuthorController($authorsTable);
            }
            
            $page = $controller->$action(...$route);
          
            $title = $page['title'];
          
            $variables = $page['variables'] ?? [];
            $output = $this->loadTemplate($page['template'], $variables);
          
          } catch (PDOException $e) {
              $title = 'An error has occurred';
          
              $output = 'Database error: ' . $e->getMessage() . ' in ' .
            $e->getFile() . ':' . $e->getLine();
        }
          
        include  __DIR__ . '/../public/templates/layout.html.php';
    }
}