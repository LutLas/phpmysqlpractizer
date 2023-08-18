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

        $link = '<a class="navmaster2" href="/joke/edit">Add Song</a>';

        $alertText = $link.' '.$totalJokes. ' songs have been submitted to the MasteredSite Music Database.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Music List',
                'heading' => 'List of Songs',
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

        $author = $this->authentication->getUser();

        $title = 'MasteredSite Music Database';
           
        $heading = 'The Ultimate Music Database';

        return [
            'template' => 'home.html.php', 
            'title' => $title, 'heading' => $heading,
            'variables' => [
                'user' => $author ?? null
            ]
        ];
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
            $categories = $this->categoriesTable->findAllGeneric();

            // Assume the data is valid to begin with
            $errors = [];
        
            $joke = $_POST['joke'];

            $heading = 'Song Modification Page';

            if (is_null($author)) {
                # code...
                header('location: /login/login'); 
            }

            if (!empty($joke['id'])) {
              $tempJoke = $this->jokesTable->findGeneric('id', $joke['id'])[0];
        
              if ($tempJoke->authorid != $author->id) {
                return header('location: /joke/list');  
              }
              $heading = 'Editing Song No:'.$joke['id'];
            }

            $joke['jokedate'] = new \DateTime();
            
            if($dateTimePublishedStamp = !strtotime($joke['datetimepublished'])){
                $dateTimePublished = new \DateTime();
                $dateTimePublished->setTimestamp($dateTimePublishedStamp);
                
                if (!$dateTimePublished instanceof \DateTime || empty($joke['datetimepublished'])) {
                    
                    $errors[] = "Invalid Date/Time Published:";
                }
            }

            if (empty($joke['joketext'])) {
                    
                $errors[] = "Song Description Missing:";
                
            } 

            if (empty($joke['joketitle'])) {
                    
                $errors[] = "Song Title Missing:";
                
            } 
            
            if(empty($joke['artistname'])){
                    
                $errors[] = "Artist Name Missing:";
                
            } 
            
            if (empty($joke['albumcover'])){
                    
                $errors[] = "Album Cover Missing:";
                
            } 
            
            if (empty($joke['albumname'])){
                    
                $errors[] = "Album Name Missing:";
                
            } 
            
            if (empty($joke['song'])) {
                    
                $errors[] = "File Upload Missing:";
                
            }

            if (!empty($errors)) {
                return [
                    'template' => 'editjoke.html.php', 
                    'title' => 'Edit Song',
                    'heading' => $heading,
                    'errors' => $errors,
                    'variables' => [
                        'joke' => $joke,
                        'user' => $author,
                        'categories' => $categories
                    ]
                ];
            }else{
                // Save the joke using the new addJoke method
                var_dump($joke);
                exit();
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
    }

    function edit($id = null) {
        $author = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->findAllGeneric();
        
        //$joke = null;
        $heading = 'Song Modification Page';
        
            foreach ($jokes as $jokeEntity) {
                # code...
                if (!empty($id) && $jokeEntity->id == $id){
                    $joke = $this->jokesTable->findGeneric('id', $id)[0];
                     
                    $heading = 'Editing Song No:'.$id;
                    
                }
            }
        
            return ['template' => 'editjoke.html.php', 
                    'title' => 'Edit Song',
                    'heading' => $heading,
                    'variables' => [
                        'joke' => $joke ?? null,
                        'user' => $author ?? null,
                        'categories' => $categories
                    ]
                ];
    }
}