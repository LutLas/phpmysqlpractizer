<?php
namespace Jokessite;
use Generic\DatabaseTable;
use Generic\Website;
use Generic\Authentication;
use Jokessite\Controllers\Joke;
use Jokessite\Controllers\Author;
use Jokessite\Controllers\Login;
//use Jokessite\Entity\Author as AuthorEntity;
//use Jokessite\Controllers\Error;
class JokeWebsite implements Website {
    private ?DatabaseTable $jokesTable;
    private ?DatabaseTable $authorsTable;
    private Authentication $authentication;

    public function __construct() {
        //include __DIR__ . '/../includes/DatabaseConnection.php';
        $pdo = null;
        try {
            //code...
            $pdo = new \PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Unexpected Error Code:".$e->getCode());
        }
      
        $this->jokesTable = new DatabaseTable($pdo, 'joke', 'id', '\Jokessite\Entity\Joke', [&$this->authorsTable]);
        $this->authorsTable = new DatabaseTable($pdo, 'author', 'id', '\Jokessite\Entity\Author', [&$this->jokesTable]);
        $this->authentication = new Authentication($this->authorsTable, 'email', 'password');
    }

    public function getLayoutVariables(): array {
        return [
            'loggedIn' => $this->authentication->isLoggedIn()
        ];
    }

    public function checkLogin(string $uri): ?string {
        $restrictedPages = ['joke/edit', 'joke/delete'];

        if (in_array($uri, $restrictedPages) && !$this->authentication->isLoggedIn()) {
            header('location: /login/login');
            exit();
        }

        return $uri;
    }

    public function getDefaultRoute(): string {
        return 'joke/home';
    }

    public function getController(string $controllerName): ?object {

        if ($controllerName === 'joke') {
            $controller = new Joke($this->jokesTable, $this->authorsTable, $this->authentication);
        }
        else if ($controllerName === 'author') {
            $controller = new Author($this->authorsTable);
        }
        else if($controllerName === 'login') {
            $controller = new Login($this->authentication);
        } 
        else {
            $controller = null;
        }

        return $controller;
    }

}