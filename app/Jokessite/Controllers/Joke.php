<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
class Joke {
    public function __construct(private DatabaseTable $jokesTable, private DatabaseTable $authorsTable) {

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
                'email' => $author['email']
        ];
        }

        $title = 'Joke List';

        $totalJokes = $this->jokesTable->totalGeneric();
           
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
                    'jokes' => $jokes
                ]
            ];
    }

    public function home() {

        $title = 'Internet Joke Database';
           
        $heading = 'The Ultimate Joke Database';

        return ['template' => 'home.html.php', 'title' => $title, 'heading' => $heading];
    }

    public function deleteSubmit() {
        $this->jokesTable->deleteGeneric('id', $_POST['jokeid']);

        header('location: /joke/list');
    }

    public function editSubmit() {
            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();
            $joke['authorId'] = 1;
    
            $this->jokesTable->saveGeneric($joke);
    
            header('location: /joke/list');  
    }

    function edit($id = null) {
            if (isset($id)) {
                $joke = $this->jokesTable->findGeneric('id', $id)[0] ?? null;
            }
    
            $title = 'Edit joke';
           
            $heading = 'Joke Modification Page';

            return ['template' => 'editjoke.html.php', 
                    'title' => $title,
                    'heading' => $heading,
                    'variables' => [
                        'joke' => $joke ?? null
                    ]
                ];
    }
}