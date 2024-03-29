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

        $user = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAllGeneric();
        $jokes = $this->jokesTable->findAllGeneric('jokedate DESC', 10, $offset);
        $approvedJokes = [];

        $queryData = [
            'approved' => 1,
            'archived' => 0
        ];

        $totalJokes = $this->jokesTable->totalGeneric($queryData);

        foreach ($jokes as $key => $value) {
            # code...  
            $tempJoke = $value;
            $tempJokeTempAlbumCover = str_replace("../", '../public/', $tempJoke->albumcover);
            $tempJokeSongUrl = base64_encode($tempJoke->song);
            $tempJoke->song = $tempJokeSongUrl;

            if (!file_exists($tempJokeTempAlbumCover)) {
                # code...
                $tempJoke->albumcover = '';
            }
            
            $approvedJokes[] = $tempJoke;
        }


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

        $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';

        $msg = $totalJokes == 1 ? $totalJokes. ' song has' : $totalJokes. ' songs have';

        $jokesnav = $link.' '.$msg.' been submitted to the MasteredSite Music Database.';

        return ['template' => 'jokes.html.php', 
                'title' => 'Music List',
                'heading' => 'List of Songs',
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $approvedJokes,
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

        $categories = $this->categoriesTable->findAllGeneric();

        $title = 'MasteredSite Music Database';

        $jokes = $this->jokesTable->findAllGeneric('jokedate DESC', 0, 0);
        $unapprovedJokes = [];
        $unapprovedJokesForAdmin = [];

        if (!is_null($author)) {
            # code...
            $tempJoke = null;

            foreach ($jokes as $key => $value) {
                # code...
                $tempJoke = $value;
                $tempJokeTempAlbumCover = str_replace("../", '../public/', $tempJoke->albumcover);

                if (!file_exists($tempJokeTempAlbumCover)) {
                    # code...
                    $tempJoke->albumcover = '';
                }

                if ($value->authorid == $author->id && !$tempJoke->approved) {
                    $unapprovedJokes[] = $tempJoke;
                }

                if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && !$tempJoke->approved) {
                    unset($unapprovedJokes);
                    $unapprovedJokesForAdmin[] = $tempJoke;
                    $unapprovedJokes = $unapprovedJokesForAdmin;
                }
            }
        }
           
        $heading = 'The Ultimate Music Database';

        $totalJokes = count($unapprovedJokes);

        $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';

        $msg = $totalJokes == 1 ? $totalJokes. ' song ' : $totalJokes. ' songs';

        $jokesnav = $link.' You have '.$msg.' pending Approval.';

        return [
            'template' => 'home.html.php', 
            'title' => $title, 'heading' => $heading,
            'variables' => [
                'user' => $author ?? null,
                'jokes' => $unapprovedJokes,
                'categories' => $categories,
                'jokesnav' => $jokesnav
            ]
        ];
    }

    public function searchSubmit() {
        $categoryId = null; 
        $page = 0;
    
        $categories = $this->categoriesTable->findAllGeneric();

        $user = $this->authentication->getUser();

        $searchValue = $_POST['songquery'];
        $pageName = $_POST['pagequery'];

        if (trim(empty($pageName))) {
            return;
        }

        $searchValueArray = [
            'joketitle' => '%' . $searchValue . '%',
            'artistname' => '%' . $searchValue . '%',
            'albumname' => '%' . $searchValue . '%',
            'producername' => '%' . $searchValue . '%',
            'tracknumber' => '%' . $searchValue . '%',
            'joketext' => '%' . $searchValue . '%'
        ];

        if ($pageName == 'musiclist') {

            $queryData = [
                'approved' => 1,
                'archived' => 0
            ];

        }elseif ($pageName == 'home') {
            
            $queryData = [
                'approved' => 0,
                'archived' => 0
            ];
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

          if ($pageName == 'musiclist') {

            $totalJokes = $this->jokesTable->totalGeneric($queryData);
    
            $offset = ($page-1) * $totalJokes;
            $jokes = $this->jokesTable->searchGeneric($searchValueArray, $queryData, null, $totalJokes, $offset);
            
            if(trim($searchValue) == ""){
                $jokes = [];
            }

            $approvedJokes = [];
    
            $searchedJokesCount = count($jokes);

            foreach ($jokes as $key => $value) {
                # code...
                $tempJoke = $value;
                $tempJokeTempAlbumCover = str_replace("../", '../public/', $tempJoke->albumcover);
                $tempJokeSongUrl = base64_encode($tempJoke->song);
                $tempJoke->song = $tempJokeSongUrl;
    
                if (!file_exists($tempJokeTempAlbumCover)) {
                    # code...
                    $tempJoke->albumcover = '';
                }
                
                $approvedJokes[] = $tempJoke;
            }
    
            foreach ($categories as $categoryEntity) {
                if (!empty($categoryId) && $categoryEntity->id == $categoryId) {
                    $category = $this->categoriesTable->findGeneric('id', $categoryId)[0];
                    $jokes = $category->getJokes(10,$offset);
                    $totalJokes = $category->getNumJokes();
                }
            }

            $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';
    
            $msg = $totalJokes == 1 ? $totalJokes. ' song' : $totalJokes. ' songs';
    
            $jokesnav = $link.' '.$searchedJokesCount.' of '.$msg.' with the word "'.htmlspecialchars($searchValue, ENT_QUOTES,'UTF-8').'" found.';
    
            return ['template' => 'jokes.html.php', 
                    'title' => 'Music List',
                    'heading' => 'List of Songs',
                    'variables' => [
                        'totalJokes' => $totalJokes,
                        'jokes' => $approvedJokes,
                        'user' => $user,
                        'categories' => $categories,
                        'currentPage' => $page,
                        'categoryId' => $categoryId,
                        'jokesnav' => $jokesnav,
                        'songquery' => $searchValue
                    ]
                ];

          }elseif ($pageName == 'home') {
            
            $title = 'MasteredSite Music Database';

            $allJokes = $this->jokesTable->findAllGeneric('jokedate DESC', 0, 0);

            $jokes = $this->jokesTable->searchGeneric($searchValueArray, $queryData, null, 0, 0);
            
            if(trim($searchValue) == ""){
                $jokes = [];
            }

            $unapprovedJokes = [];
            $unapprovedJokesForAdmin = [];

            $allUnapprovedJokes = [];
            $allUnapprovedJokesForAdmin = [];
    
            if (!is_null($user)) {
                # code...
                $tempJoke = null;
                
                foreach ($allJokes as $key => $value) {
    
                    if ($value->authorid == $user->id && !$value->approved) {
                        $allUnapprovedJokes[] = $value;
                    }
    
                    if ($user->hasPermission(AuthorEntity::APPROVE_JOKE) && !$value->approved) {
                        unset($allUnapprovedJokes);
                        $allUnapprovedJokesForAdmin[] = $value;
                        $allUnapprovedJokes = $allUnapprovedJokesForAdmin;
                    }

                }
    
                foreach ($jokes as $key => $value) {
                    # code...
                    $tempJoke = $value;
                    $tempJokeTempAlbumCover = str_replace("../", '../public/', $tempJoke->albumcover);
    
                    if (!file_exists($tempJokeTempAlbumCover)) {
                        # code...
                        $tempJoke->albumcover = '';
                    }
    
                    if ($value->authorid == $user->id && !$tempJoke->approved) {
                        $unapprovedJokes[] = $tempJoke;
                    }
    
                    if ($user->hasPermission(AuthorEntity::APPROVE_JOKE) && !$tempJoke->approved) {
                        unset($unapprovedJokes);
                        $unapprovedJokesForAdmin[] = $tempJoke;
                        $unapprovedJokes = $unapprovedJokesForAdmin;
                    }
                }
            }
               
            $heading = 'The Ultimate Music Database';
    
            $totalJokes = count($allUnapprovedJokes);

            $searchedJokesCount = count($unapprovedJokes);
    
            $link = '<a class="navmasterJoke" href="/joke/edit">Add Song</a>';
    
            $msg = $totalJokes == 1 ? $totalJokes. ' song ' : $totalJokes. ' songs';
    
            $jokesnav = $link.' Showing '.$searchedJokesCount.' of '.$msg.' pending Approval, with the word "'.htmlspecialchars($searchValue, ENT_QUOTES,'UTF-8').'"';
    
            return [
                'template' => 'home.html.php', 
                'title' => $title, 'heading' => $heading,
                'variables' => [
                    'user' => $user ?? null,
                    'jokes' => $unapprovedJokes,
                    'categories' => $categories,
                    'jokesnav' => $jokesnav,
                    'songquery' => $searchValue
                ]
            ];
          }
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
        
              if ($tempJoke->authorid != $author->id && !$author->hasPermission(AuthorEntity::APPROVE_JOKE)) {
                return header('location: /joke/list');  
              }
              $heading = 'Editing Song No:'.$joke['id'];
            }

            if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && !empty($joke['approved'])) {
                $joke['approved'] = 1;
                $joke['datetimeapproved'] = new \DateTime();
                $joke['approvedby'] = $author->id;
            }else{
                $joke['jokedate'] = new \DateTime();
                $joke['approved'] = 0;
            }

            if (!is_null($tempJoke)) {
                # code...
                $joke['authorid'] = $tempJoke->authorid;
                $joke['jokedate'] = $tempJoke->jokedate;
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
                        
                        //Setup our new fileImage path
                        $uploadsDirImage = "../public/assets/images/Uploads/";
                        $uploadsDir = "../public/assets/music/uploads/Approved/";
                        $uploadsDirUnapproved = "../public/assets/music/uploads/Kulibiletu/";

                        $newFolderDirImage = $uploadsDirImage. $joke['artistname'] ."/". $joke['albumname'] ."/";
                        $newFolderDir = $uploadsDir. $joke['artistname'] ."/". $joke['albumname'] ."/";
                        $newFolderDirUnapproved = $uploadsDirUnapproved. $joke['artistname'] ."/". $joke['albumname'] ."/";

                        $newFolderDirImageAlreadyExists = true;
                        $newFolderDirAlreadyExists = true;
                        $newFolderDirUnapprovedAlreadyExists = true;

                        if (!file_exists($newFolderDirImage) || !file_exists($newFolderDir) || !file_exists($newFolderDirUnapproved )){
                            $newFolderDirImageAlreadyExists = mkdir($newFolderDirImage, 0777, true);
                            $newFolderDirAlreadyExists = mkdir($newFolderDir, 0777, true);
                            $newFolderDirUnapprovedAlreadyExists = mkdir($newFolderDirUnapproved, 0777, true);
                        }

                        $newFileUploadPathImage = $newFolderDirImage . $fileUploadedPath;
                        $newFileUploadPath = $newFolderDir . $fileUploadedPath;
                        $newFileUploadPathUnaproved = $newFolderDirUnapproved . $fileUploadedPath;

                        if(in_array($fileUploadedPathExtension, $allowedImageExtensions)){

                            if (filesize($tmpFileUploadPath) > 0 && filesize($tmpFileUploadPath) < 17000000) {
                                $joke['albumcover'] = str_replace("public/", '', $newFileUploadPathUnaproved);
                                
                                if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $joke['approved']) {
                                    # code...
                                    $joke['albumcover'] = str_replace("public/", '', $newFileUploadPathImage);
                                }
                                        
                            }else{
                                $errors[] = "Invalid Image File Size, Expected Max: 16MB";
                                $tmpFileUploadPath = null;
                                $i =+ $fileUploadNamesCount;
                            }

                        }elseif(in_array($fileUploadedPathExtension, $allowedAudioExtensions)){

                            if (filesize($tmpFileUploadPath) > 0 && filesize($tmpFileUploadPath) < 43000000) {
                                $joke['song'] = str_replace("public/", '', $newFileUploadPathUnaproved);

                                if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $joke['approved']) {
                                    # code...
                                    $joke['song'] = str_replace("public/", '', $newFileUploadPath);

                                    if ( trim(strtolower($fileUploadedPathExtension)) != 'mp3') {
                                        # code...
                                        $errors[] = "Expected MP3 Format";
                                        $tmpFileUploadPath = null;
                                        $i =+ $fileUploadNamesCount;
                                    }
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
                            if($newFolderDirAlreadyExists && $newFolderDirUnapprovedAlreadyExists && $newFolderDirImageAlreadyExists) {
                                //File is uploaded to temp dir
                                if ($author->hasPermission(AuthorEntity::APPROVE_JOKE) && $joke['approved']) {
                                    # code...
                                    
                                    if(in_array($fileUploadedPathExtension, $allowedImageExtensions)){
                                    
                                        if(!move_uploaded_file($tmpFileUploadPath, $newFileUploadPathImage)) {
                                            $errors[] = "Failed To Upload Image File";
                                            $i =+ $fileUploadNamesCount;
                                        }

                                    }elseif(in_array($fileUploadedPathExtension, $allowedAudioExtensions)){

                                        if(!move_uploaded_file($tmpFileUploadPath, $newFileUploadPath)) {
                                            $errors[] = "Failed To Upload Audio File";
                                            $i =+ $fileUploadNamesCount;
                                        }

                                    }else{
                                        $errors[] = "Music/Image File Extension Not Supported, Expected Type: 'png', 'jpg', 'jpeg','mp3', 'wav', 'm4a','3gp','webm','ogg','oga','mogg'";
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