<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
use Generic\Authentication;
//use Jokessite\Entity\Author;
class Joke {
    public function __construct(private DatabaseTable $jokesTable, private DatabaseTable $authorsTable, private Authentication $authentication) {

    }

    public function list() {
        $jokes = $this->jokesTable->findAllGeneric();

        $title = 'Joke List';

        $totalJokes = $this->jokesTable->totalGeneric();

        $user = $this->authentication->getUser();
           
        $heading = 'List of Jokes';

        $alertText = $totalJokes. ' jokes have been submitted to the Internet Joke Database.';

        $alertStyle = 'noticep';

        return ['template' => 'jokes.html.php', 
                'title' => $title,
                'heading' => $heading,
                'alertText' => $alertText,
                'alertStyle' => $alertStyle,
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'userId' => $user->id ?? null
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
            $author->addJoke($joke);
    
            header('location: /joke/list');  
    }

    function edit($id = null) {
            if (isset($id)) {
                $joke = $this->jokesTable->findGeneric('id', $id)[0] ?? null;
            }

            $author = $this->authentication->getUser();
    
            $title = 'Edit joke';
           
            $heading = 'Joke Modification Page';

            return ['template' => 'editjoke.html.php', 
                    'title' => $title,
                    'heading' => $heading,
                    'variables' => [
                        'joke' => $joke ?? null,
                        'userId' => $author->id ?? null
                    ]
                ];
    }
}