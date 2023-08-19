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

            if (empty(trim($joke['joketitle']))){
                    
                $errors[] = "Song Title Missing";
                
            } 
            
            if(empty(trim($joke['artistname']))){
                    
                $errors[] = "Artist Name Missing";
                
            } 
            
            if (empty(trim($joke['albumname']))){
                    
                $errors[] = "Album Name Missing";
                
            } 
            
            if($dateTimePublishedStamp = !strtotime($joke['datetimepublished'])){
                $dateTimePublished = new \DateTime();
                $dateTimePublished->setTimestamp($dateTimePublishedStamp);
                
                if (!$dateTimePublished instanceof \DateTime || empty($joke['datetimepublished'])) {
                    
                    $errors[] = "Invalid Date/Time Published:";
                }
            }

            if (empty(trim($joke['joketext']))) {
                    
                $errors[] = "Song Description Missing";
                
            } 

            if (empty($errors)) {
            
                $fileUploadArray = $_FILES['joke'];
                
                //Use something similar before processing files.
                $fileUploadNames = array_filter($fileUploadArray['name']);

                // Count the number of uploaded files in array
                $fileUploadNamesCount = count($fileUploadNames);

                // File 
                $allowedImageExtensions = array("png", "jpg", "jpeg");
                $allowedAudioExtensions = array("mp3", "wav", "m4a","3gp","webm","ogg","oga","mogg");

                // Loop through every file
                for( $i=0 ; $i < $fileUploadNamesCount ; $i++ ) {

                        //The temp file path is obtained
                        $tmpFileUploadPath = $fileUploadArray['tmp_name'][$i];
                        $fileUploadedPath = $fileUploadArray['name'][$i];
                        $fileUploadedPathExtensionExtract = explode(".", $fileUploadedPath);
                        
                        $fileUploadedPathExtension = end($fileUploadedPathExtensionExtract);
                                    
                        //Setup our new file path
                        $uploadsDir = "./assets/music/uploads/";
                        $newFolderDir = $uploadsDir. $joke['artistname'] ."/". $joke['albumname'] ."/";
                        $newFolderDirAlreadyExists = true;

                        if (!file_exists($newFolderDir)){
                            $newFolderDirAlreadyExists = mkdir($newFolderDir, 0777, true);
                        }

                        $newFileUploadPath = $newFolderDir . $fileUploadedPath;

                        if(in_array($fileUploadedPathExtension, $allowedImageExtensions)){
                            $joke['albumcover'] = $newFileUploadPath;
                        }elseif(in_array($fileUploadedPathExtension, $allowedAudioExtensions)){
                            $joke['song'] = $newFileUploadPath;
                        }else{
                            $errors[] = "Music/Image File Not Supported";
                            $tmpFileUploadPath = null;
                            $i =+ $fileUploadNamesCount;
                        }
                        
                        //A file path needs to be present
                       if (!empty($tmpFileUploadPath)) {

                                if (filesize($tmpFileUploadPath) > 0 && filesize($tmpFileUploadPath) < 43000000) { 
                
                                        //File is uploaded to temp dir
                                        if($newFolderDirAlreadyExists) {
                                            //File is uploaded to temp dir
                                            if(!move_uploaded_file($tmpFileUploadPath, $newFileUploadPath)) {
                                                $errors[] = "Failed To Upload Music/Image File";
                                                $i =+ $fileUploadNamesCount;
                                            }
                                            
                                        }else{
                                            $errors[] = "Failed to create Folder for Music";
                                            $i =+ $fileUploadNamesCount;
                                        }
                                    
                                }else{
                                    $errors[] = "Invalid Music/Image File Size";
                                    $i =+ $fileUploadNamesCount;
                                }

                        }
                }
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