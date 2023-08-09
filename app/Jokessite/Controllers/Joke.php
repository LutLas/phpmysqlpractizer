<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
use Generic\Authentication;
//use Jokessite\Entity\Joke as JokeEntity;
use Jokessite\Entity\Author as AuthorEntity;
class Joke {
    public function __construct(private DatabaseTable $jokesTable, private DatabaseTable $authorsTable, private DatabaseTable $categoriesTable, private Authentication $authentication) {

    }

    public function list(mixed $categoryId = null, int $page = 0) {
        $offset = ($page-1)*10;

        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->findAllGeneric('jokedate DESC', 10, $offset);

        $totalJokes = $this->jokesTable->totalGeneric();


        foreach ($categories as $categoryEntity) {
            if (!empty($categoryId) && $categoryEntity->id == $categoryId) {
                $category = $this->categoriesTable->findGeneric('id', $categoryId)[0];
                $jokes = $category->getJokes(10,$offset);
                $totalJokes = $category->getNumJokes();
            }
        }

       /* if (is_numeric($categoryId)) {
            $category = $this->categoriesTable->findGeneric('id', $categoryId)[0] ?? null;
            $jokes = $category->getJokes(10, $offset);
            $totalJokes = $category->getNumJokes();
          }
          else {
            $jokes = $this->jokesTable->findAllGeneric('jokedate DESC', 10, $offset);
            $totalJokes = $this->jokesTable->totalGeneric();
          } */  
        

        $user = $this->authentication->getUser();

        $link = '<a class="navmaster2" href="/joke/edit">Add Joke</a>';

        $alertText = $link.' '.$totalJokes. ' jokes have been submitted to the Internet Joke Database.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Joke List',
                'heading' => 'List of Jokes',
                'alertText' => $alertText,
                'alertStyle' => 'noticep',
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'user' => $user, //previously $user->id ?? null,
                    'categories' => $categories,
                    'currentPage' => $page,
                    'categoryId' => $categoryId
                ]
            ];
    }

    public function home() {

        $title = 'Internet Joke Database';
           
        $heading = 'The Ultimate Joke Database';

        return ['template' => 'home.html.php', 'title' => $title, 'heading' => $heading];
    }

    public function deleteSubmit() {

        $author = $this->authentication->getUser();
      
        $joke = $this->jokesTable->findGeneric('id', $_POST['jokeid'])[0];
      
        if ($joke->authorid != $author->id && !$author->hasPermission(AuthorEntity::DELETE_JOKE)) {
          return;
        }

        $this->jokesTable->deleteGeneric('id', $_POST['jokeid']);

        header('location: /joke/list');
    }

    public function editSubmit() {
            // Get the currently logged in user as the $author to associate the joke with
            $author = $this->authentication->getUser();

            if (is_null($author)) {
                # code...
                header('location: /login/login'); 
            }

            if (!empty($id)) {
              $joke = $this->jokesTable->findGeneric('id', $id)[0];
        
              if ($joke->authorId != $author->id) {
               return;
              }
            }
        
            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();

            // Save the joke using the new addJoke method
            $jokeEntity = $author->addJoke($joke);

            $jokeEntity->clearCategories();

            if (isset($_POST['category'])) {
                # code...
                foreach ($_POST['category'] as $categoryId) {
                    $jokeEntity->addCategory($categoryId);
                }
            }
    
            header('location: /joke/list');  
    }

    function edit($id = null) {
        $author = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->findAllGeneric();
        
        $joke = null;
        $heading = 'Joke Modification Page';

        
            foreach ($jokes as $jokeEntity) {
                # code...
                if (!empty($id) && $jokeEntity->id == $id){
                    $joke = $this->jokesTable->findGeneric('id', $id)[0];
                     
                    $heading = 'Editing Joke No:'.$id;
                }
            }
        
            return ['template' => 'editjoke.html.php', 
                    'title' => 'Edit Joke',
                    'heading' => $heading,
                    'variables' => [
                        'joke' => $joke ?? null,
                        'user' => $author ?? null,
                        'categories' => $categories
                    ]
                ];
    }
}