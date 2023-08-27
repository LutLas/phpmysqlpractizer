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

        $queryData = [
            'approved' => 1,
            'archived' => 0
        ];

        $totalJokes = $this->jokesTable->totalGeneric($queryData);


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

        $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';

        $msg = $totalJokes == 1 ? $totalJokes. ' song has' : $totalJokes. ' songs have';

        $jokesnav = $link.' '.$msg.' been submitted to the MasteredSite Music Database.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Music List',
                'heading' => 'List of Songs',
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'user' => $user, //previously $user->id ?? null,
                    'categories' => $categories,
                    'currentPage' => $page,
                    'categoryId' => $categoryId,
                    'jokesnav' => $jokesnav
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

    public function searchSubmit() {
        $categoryId = null; 
        $page = 0;

        $searchValue = $_POST['songquery'];

        if (trim(empty($searchValue))) {
            return header('location: /joke/list');
        }

        $searchValueArray = [
            'joketitle' => '%' . $searchValue . '%',
            'artistname' => '%' . $searchValue . '%',
            'albumname' => '%' . $searchValue . '%',
            'producername' => '%' . $searchValue . '%',
            'tracknumber' => '%' . $searchValue . '%',
            'joketext' => '%' . $searchValue . '%'
        ];

        $queryData = [
            'approved' => 1,
            'archived' => 0
        ];

        $totalJokes = $this->jokesTable->totalGeneric($queryData);

        $offset = ($page-1) * $totalJokes;

        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->searchGeneric($searchValueArray, $queryData, null, $totalJokes, $offset);
        $searchedJokesCount = count($jokes);

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

        $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';

        $msg = $totalJokes == 1 ? $totalJokes. ' song' : $totalJokes. ' songs';

        $jokesnav = $link.' '.$searchedJokesCount.' of '.$msg.' with the word "'.$searchValue.'" found.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Music List',
                'heading' => 'List of Songs',
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'user' => $user,
                    'categories' => $categories,
                    'currentPage' => $page,
                    'categoryId' => $categoryId,
                    'jokesnav' => $jokesnav,
                    'songquery' => $searchValue
                ]
            ];
    }

    public function deleteSubmit() {

        $author = $this->authentication->getUser();
      
        $joke = $this->jokesTable->findGeneric('id', $_POST['jokeid'])[0];

        $jokeArray = json_decode(json_encode($joke), true);
      
        if ($jokeArray['authorid'] != $author->id && !$author->hasPermission(AuthorEntity::DELETE_JOKE)) {
          return;
        }

        if ($jokeArray['id'] == $_POST['jokeid']) {
            $jokeArray['archived'] = 1;

            $dateTimePublishedStamp = strtotime($jokeArray['datetimepublished']);
            $jokeArray['datetimepublished'] = new \DateTime();
            $jokeArray['datetimepublished']->setTimestamp($dateTimePublishedStamp);

            $dateTimeJokeDate = strtotime($jokeArray['jokedate']);
            $jokeArray['jokedate'] = new \DateTime();
            $jokeArray['jokedate']->setTimestamp($dateTimeJokeDate);

            if (!is_null($jokeArray['datetimeapproved'])) {
                
                $dateTimeApproved = strtotime($jokeArray['datetimeapproved']);
                $jokeArray['datetimeapproved'] = new \DateTime();
                $jokeArray['datetimeapproved']->setTimestamp($dateTimeApproved);
            }

            if (!is_null($jokeArray['datetimearchived'])) {

                $dateTimeArchived = strtotime($jokeArray['datetimearchived']);
                $jokeArray['datetimearchived'] = new \DateTime();
                $jokeArray['datetimearchived']->setTimestamp($dateTimeArchived);
            }

            $author->addJoke($jokeArray);
        }

        header('location: /joke/list');
    }

    public function editSubmit() {
            // Get the currently logged in user as the $author to associate the joke with
            $author = $this->authentication->getUser();
            $categories = $this->categoriesTable->findAllGeneric();

            // Assume the data is valid to begin with
            $errors = [];
        
            $joke = $_POST['joke'];
            $tempJoke = null;

            $heading = 'Song Modification Page';

            if (is_null($author)) {
                # code...
                header('location: /login/login'); 
            }

            if (!empty($joke['id'])) {
              $tempJoke = $this->jokesTable->findGeneric('id', $joke['id'])[0];
        
              if ($tempJoke->authorid != $author->id && $author->permissions < AuthorEntity::APPROVE_JOKE) {
                return header('location: /joke/list');  
              }
              $heading = 'Editing Song No:'.$joke['id'];
            }
            
            $joke['approved'] = 0;

            if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $_POST['approved'] && !is_null($tempJoke)) {
                $joke['jokedate'] = $tempJoke->jokedate;
                $joke['approved'] = $_POST['approved'];
                $joke['datetimeapproved'] = new \DateTime();
                $joke['approvedby'] = $author->id;
            }else{
                $joke['jokedate'] = new \DateTime();
            }
            $tempDateTimePublished = $joke['datetimepublished'];
            //$timezone = new \DateTimeZone('CAT');
            //$joke['jokedate']->setTimezone($timezone);

            $joke['albumcover'] = ''; 
            $joke['song'] = '';


            if (empty(trim($joke['joketitle']))){
                    
                $errors[] = "Song Title Missing";
                
            } 
            
            if(empty(trim($joke['artistname']))){
                    
                $errors[] = "Artist Name Missing";
                
            } 
            
            if (empty(trim($joke['albumname']))){
                    
                $errors[] = "Album Name Missing";
                
            } 
            
            if($dateTimePublishedStamp = strtotime($joke['datetimepublished'])){
                $dateTimePublished = new \DateTime();
                $dateTimePublished->setTimestamp($dateTimePublishedStamp);
                
                if (!$dateTimePublished instanceof \DateTime || empty($joke['datetimepublished'])) {
                    
                    $errors[] = "Invalid Date/Time Published:";

                }else{
                    
                    $joke['datetimepublished'] = $dateTimePublished;
                    if ($joke['datetimepublished'] > $joke['jokedate']) {
                        $joke['datetimepublished'] = $joke['jokedate'];
                    }
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
                $allowedAudioExtensions = array("mp3", "wav", "m4a","3gp","webm","ogg","oga","mogg","swf");

                // Loop through every file
                for( $i=0 ; $i < $fileUploadNamesCount ; $i++ ) {

                        //The temp file path is obtained
                        $tmpFileUploadPath = $fileUploadArray['tmp_name'][$i];
                        $fileUploadedPath = $fileUploadArray['name'][$i];
                        $fileUploadedPathExtensionExtract = explode(".", $fileUploadedPath);
                        
                        $fileUploadedPathExtension = end($fileUploadedPathExtensionExtract);
                                    
                        //Setup our new file path
                        $uploadsDir = "../public/assets/music/uploads/";
                        $uploadsDirUnapproved = "../public/assets/music/uploads/Unapproved/";
                        $newFolderDir = $uploadsDir. $joke['artistname'] ."/". $joke['albumname'] ."/";
                        $newFolderDirUnapproved = $uploadsDirUnapproved. $joke['artistname'] ."/". $joke['albumname'] ."/";
                        $newFolderDirAlreadyExists = true;
                        $newFolderDirUnapprovedAlreadyExists = true;

                        if (!file_exists($newFolderDir) || !file_exists($newFolderDirUnapproved)){
                            $newFolderDirAlreadyExists = mkdir($newFolderDir, 0777, true);
                            $newFolderDirUnapprovedAlreadyExists = mkdir($newFolderDirUnapproved, 0777, true);
                        }

                        $newFileUploadPath = $newFolderDir . $fileUploadedPath;
                        $newFileUploadPathUnaproved = $newFolderDirUnapproved . $fileUploadedPath;

                        if(in_array($fileUploadedPathExtension, $allowedImageExtensions)){

                            if (filesize($tmpFileUploadPath) > 0 && filesize($tmpFileUploadPath) < 17000000) {
                                $joke['albumcover'] = str_replace("public/", '', $newFileUploadPath);
                                        
                            }else{
                                $errors[] = "Invalid Image File Size, Expected Max: 16MB";
                                $tmpFileUploadPath = null;
                                $i =+ $fileUploadNamesCount;
                            }

                        }elseif(in_array($fileUploadedPathExtension, $allowedAudioExtensions)){

                            if (filesize($tmpFileUploadPath) > 0 && filesize($tmpFileUploadPath) < 43000000) {
                                $joke['song'] = str_replace("public/", '', $newFileUploadPathUnaproved);

                                if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $_POST['approved'] && trim(strtolower($fileUploadedPathExtension)) == 'swf') {
                                    # code...
                                    $joke['song'] = str_replace("public/", '', $newFileUploadPath);
                                }
                                        
                            }else{
                                $errors[] = "Invalid Audio File Size, Expected Max: 42MB";
                                $tmpFileUploadPath = null;
                                $i =+ $fileUploadNamesCount;
                            }
                            
                        }else{
                            $errors[] = "Music/Image File Extension Not Supported, Expected Type: 'png', 'jpg', 'jpeg','mp3', 'wav', 'm4a','3gp','webm','ogg','oga','mogg'";
                            $tmpFileUploadPath = null;
                            $i =+ $fileUploadNamesCount;
                        }
                        
                        //A file path needs to be present
                       if (!empty($tmpFileUploadPath)) { 
                                        //File is uploaded to temp dir
                                        if($newFolderDirAlreadyExists && $newFolderDirUnapprovedAlreadyExists) {
                                            //File is uploaded to temp dir
                                            if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $_POST['approved']) {
                                                # code...
                                                if(!move_uploaded_file($tmpFileUploadPath, $newFileUploadPath)) {
                                                    $errors[] = "Failed To Upload Audio/Image File";
                                                    $i =+ $fileUploadNamesCount;
                                                }
                                            }else{
                                                if(!move_uploaded_file($tmpFileUploadPath, $newFileUploadPathUnaproved)) {
                                                    $errors[] = "Failed To Upload Audio/Image File";
                                                    $i =+ $fileUploadNamesCount;
                                                }
                                            }
                                            
                                        }else{
                                            $errors[] = "Failed to create Folder for Audio/Image File";
                                            $i =+ $fileUploadNamesCount;
                                        }
                        }
                }
            }

            if (empty(trim($joke['albumcover']))) {
                    
                $errors[] = "Image File Upload Failed";
                
            } 

            if (empty(trim($joke['song']))) {
                    
                $errors[] = "Audio File Upload Failed";
                
            } 
                

            if (!empty($errors)) {

                $joke['datetimepublished'] = $tempDateTimePublished;

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
                //var_dump($joke);
                //exit();
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
                    
                    if ($author->permissions < AuthorEntity::APPROVE_JOKE && $jokeEntity->authorid != $author->id) {
                        # code...
                        header('location: /joke/list');  
                    }
                
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