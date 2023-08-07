<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
use Generic\Authentication;
//use Jokessite\Entity\Joke as JokeEntity;
//use Jokessite\Entity\Author as AuthorEntity;
class Joke {
    public function __construct(private DatabaseTable $jokesTable, private DatabaseTable $authorsTable, private DatabaseTable $categoriesTable, private Authentication $authentication) {

    }

    public function list($categoryId = null) {
        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->findAllGeneric();

        foreach ($categories as $categoryEntity) {
            if (!empty($categoryId) && $categoryEntity->id == $categoryId) {
                $category = $this->categoriesTable->findGeneric('id', $categoryId)[0];

                $jokes = $category->getJokes();
            }
        }

        $totalJokes = $this->jokesTable->totalGeneric();

        $user = $this->authentication->getUser();

        $alertText = $totalJokes. ' jokes have been submitted to the Internet Joke Database.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Joke List',
                'heading' => 'List of Jokes',
                'alertText' => $alertText,
                'alertStyle' => 'noticep',
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'userId' => $user->id ?? null,
                    'categories' => $categories
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
      
        if ($joke->authorid != $author->id) {
          return;
        }

        $this->jokesTable->deleteGeneric('id', $_POST['jokeid']);

        header('location: /joke/list');
    }

    public function editSubmit() {
            // Get the currently logged in user as the $author to associate the joke with
            $author = $this->authentication->getUser();

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

            foreach ($_POST['category'] as $categoryId) {
              $jokeEntity->addCategory($categoryId);
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
                        'userId' => $author->id ?? null,
                        'categories' => $categories
                    ]
                ];
    }
}