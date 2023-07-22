<?php
namespace Jokessite;
use Generic\DatabaseTable;
use Generic\Website;
use Jokessite\Controllers\Joke;
use Jokessite\Controllers\Author;
class JokeWebsite implements Website {
    public function getDefaultRoute(): string {
        return 'joke/home';
    }

    public function getController(string $controllerName): object {
        //include __DIR__ . '/../includes/DatabaseConnection.php';
        $pdo = new \PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      
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