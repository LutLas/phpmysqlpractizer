<?php
class JokeController {
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

        return ['template' => 'jokes.html.php', 
                'title' => $title,
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes
                ]
            ];
    }

    public function home() {

        $title = 'Internet Joke Database';

        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function delete() {
        $this->jokesTable->deleteGeneric('id', $_POST['jokeid']);

        header('location: /joke/list');
    }

    public function edit($id = null) {
        if (isset($_POST['joke'])) {
            $joke = $_POST['joke'];
            $joke['jokedate'] = new DateTime();
            $joke['authorId'] = 1;
    
            $this->jokesTable->saveGeneric($joke);
    
            header('location: /joke/list');  
        } else {
            if (isset($id)) {
                $joke = $this->jokesTable->findGeneric('id', $id)[0] ?? null;
            }
            else {
              $joke = null;
            }
    
            $title = 'Edit joke';

            return ['template' => 'editjoke.html.php', 
                    'title' => $title,
                    'variables' => [
                        'joke' => $joke
                    ]
                ];
        }
    }
}