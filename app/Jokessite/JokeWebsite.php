<?php
namespace Jokessite;
use Generic\DatabaseTable;
use Generic\Website;
use Generic\Authentication;
use Jokessite\Controllers\Joke;
use Jokessite\Controllers\Author;
use Jokessite\Controllers\Login;
use Jokessite\Controllers\Category;
use Jokessite\Entity\Author as AuthorEntity;
//use Jokessite\Controllers\Error;
class JokeWebsite implements Website {
    private ?DatabaseTable $jokesTable;
    private ?DatabaseTable $authorsTable;
    private ?DatabaseTable $categoriesTable;
    private ?DatabaseTable $jokeCategoriesTable;
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
      
        $this->jokesTable = new DatabaseTable($pdo, 'joke', 'id', '\Jokessite\Entity\Joke', [&$this->authorsTable, &$this->jokeCategoriesTable]);
        $this->authorsTable = new DatabaseTable($pdo, 'author', 'id', '\Jokessite\Entity\Author', [&$this->jokesTable]);
        $this->categoriesTable = new DatabaseTable($pdo, 'category', 'id', '\Jokessite\Entity\Category', [&$this->jokesTable, &$this->jokeCategoriesTable]);
        $this->jokeCategoriesTable = new DatabaseTable($pdo, 'jokecategory', 'categoryid');

        $this->authentication = new Authentication($this->authorsTable, 'email', 'password');
    }

    public function getLayoutVariables(): array {
        return [
            'loggedIn' => $this->authentication->isLoggedIn()
        ];
    }

    public function checkLogin(string $uri): ?string {
        $restrictedPages = [
            'category/list' => AuthorEntity::LIST_CATEGORIES,
            'category/delete' => AuthorEntity::DELETE_CATEGORY,
            'category/edit' => AuthorEntity::EDIT_CATEGORY,
            'author/permissions' => AuthorEntity::EDIT_USER_ACCESS,
            'author/list' => AuthorEntity::EDIT_USER_ACCESS
           ];

        if (isset($restrictedPages[$uri])) {
            if (
                !$this->authentication->isLoggedIn() || 
                !$this->authentication->getUser()->hasPermission($restrictedPages[$uri])
            ) {
                header('location: /login/login');
                exit();
            }
        }

        return $uri;
    }



    public function getDefaultRoute(): string {
        return 'joke/home';
    }

    public function getController(string $controllerName): ?object {
        
        $controllers = [
            'joke' => new Joke($this->jokesTable, $this->authorsTable, $this->categoriesTable, $this->authentication),
            'author' => new Author($this->authorsTable),
            'login' => new Login($this->authentication),
            'category' => new Category($this->categoriesTable)
        ];

        return $controllers[$controllerName] ?? null;
    }

}