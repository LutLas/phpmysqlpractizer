<?php
namespace Jokessite;
use Generic\DatabaseTable;
use Jokessite\Controllers\Joke;
use Jokessite\Controllers\Author;
class JokeWebsite {
    public function getDefaultRoute() {
        return 'joke/home';
    }

    public function getController(string $controllerName) {
        include __DIR__ . '/../includes/DatabaseConnection.php';
      
        $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
        $authorsTable = new DatabaseTable($pdo, 'author', 'id');

        if ($controllerName === 'joke') {
            $controller = new Joke($jokesTable, $authorsTable);
        }
        else if ($controllerName === 'author') {
            $controller = new Author($authorsTable);
        }

        return $controller;
    }

}