<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
use Generic\Authentication;
class Joke {
    public function __construct(private DatabaseTable $jokesTable, private DatabaseTable $authorsTable, private Authentication $authentication) {

    }

    public function list() {
        $result = $this->jokesTable->findAllGeneric();

        $jokes = [];

        foreach ($result as $joke) {
        $author = $this->authorsTable->findGeneric('id', $joke['authorid'])[0];

        $jokes[] = [
                'id' => $joke['id'],
                'joketext' => $joke['joketext'],
                'jokedate' => $joke['jokedate'],
                'name' => $author['name'],
                'email' => $author['email'],
                'authorid' => $author['id']
        ];
        }

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
                    'userId' => $user['id'] ?? null
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
      
        if ($joke['authorid'] != $author['id']) {
          return;
        }

        $this->jokesTable->deleteGeneric('id', $_POST['jokeid']);

        header('location: /joke/list');
    }

    public function editSubmit($id = null) {
            $author = $this->authentication->getUser();

            if (isset($id)) {
                $joke = $this->jokesTable->findGeneric('id', $id)[0] ?? null;
                if ($joke['authorid'] != $author['id']) {
                    return;
                }
            }
            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();
            $joke['authorid'] = $author['id'];
    
            $this->jokesTable->saveGeneric($joke);
    
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
                        'userId' => $author['id'] ?? null
                    ]
                ];
    }
}