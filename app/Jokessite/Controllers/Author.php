<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
use Generic\Authentication;
//use Jokessite\Entity\Author as AuthorEntity;
class Author {
    public function __construct(private DatabaseTable $authorsTable, private Authentication $authentication) {
    }
    
    public function list() {
      $authors = $this->authorsTable->findAllGeneric();

      $user = $this->authentication->getUser();
    
      return ['template' => 'authorlist.html.php',
        'title' => 'Author List',
        'heading' => 'User List',
        'variables' => [
          'authors' => $authors,
          'user'=> $user
        ]
      ];
    }

    public function registrationForm() {
        return [
          'template' => 'register.html.php',
            'title' => 'Register An Account',
            'heading' => 'User Account Registration Form'
         ];
    }

    public function success() {
        return [
            'template' => 'registersuccess.html.php',
            'title' => 'Registration Successful',
            'heading' => 'User Account Registered',
            'alertText' => 'Success!',
            'alertStyle' => 'noticep'
        ];
    }

    public function registrationFormSubmit() {
        $author = $_POST['author'];

        // Assume the data is valid to begin with
        $errors = [];
      
        // But if any of the fields have been left blank, set $valid to false
        if (empty($author['email'])) {
            $errors[] = 'Email  Address is Required';
          }
          else if (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
            $errors[] = '"'.$author['email'].'" is An Invalid Email';
          }
          if (count($this->authorsTable->findGeneric('email', $author['email'])) > 0) {
            $errors[] = '"'.$author['email'].'" is Already Registered';
          }

        if (empty($author['name'])) {
          $errors[] = 'User Name is Required';
        }
      
        if (empty($author['password'])) {
            $errors[] = 'Password is Required';
          }elseif (strlen($author['password']) < 8 || strlen($author['password']) > 25 ) {
            $errors[] = 'Password Must Be 8-25 Characters';
          }elseif (ctype_alnum($author['password'])) {
            $errors[] = 'Password Must Contain One Non-Alphanumeric Character';
          }

          if (strcmp($author['password'], $author['passwordConfirm']) !== 0){
            $errors[] = 'Passwords Do Not Match';
          }else{
            unset($author['passwordConfirm']);
          }
          
          if (empty($author['acceptedprivacypolicy'])) {
            $errors[] = 'You Have Not Accepted Our Privacy Policy';
          }
        
          // If the $errors array is still empty, no fields were blank and the data can be added
          if (empty($errors)) {
            // Hash the password before saving it in the database
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
            
            $author['verified'] = 0;
            $author['acceptedprivacypolicy'] = 1;
            $author['acceptedeula'] = 0;
            $author['permissions'] = 0;
          
            // When submitted, the $author variable now contains a lowercase value for email
            // and a hashed password
            $this->authorsTable->saveGeneric($author);
        
            header('Location: /author/success');
          }
          else {
            // If the data is not valid, show the form again
            return ['template' => 'register.html.php',
                  'title' => 'Register an account',
                  'heading' => 'User Account Registration Form',
                  'alertText' => 'Registration was unsuccessful due to incomplete form submission.',
                  'alertStyle' => 'noticef',
                  'errors' => $errors,
                  'variables' => [
                    'author' => $author
                  ]
                ];
          }
    }

    public function permissions($id = null) {
      
      $authors = $this->authorsTable->findAllGeneric();
      $heading = 'Failed To Fetch Data';
      $author = null;

      foreach ($authors as $authorEntity) {
        # code...
        if ($authorEntity->id == $id) {
          # code...
          $author = $this->authorsTable->findGeneric('id', $id)[0];
          $heading = 'Permissions For '.$author->name;
        }
      }
    
      $reflected = new \ReflectionClass('\Jokessite\Entity\Author');
      $constants = $reflected->getConstants();
    
      return ['template' => 'permissions.html.php',
        'title' => 'Edit Permissions',
        'heading' => $heading,
        'variables' => [
          'author' => $author,
          'permissions' => $constants
        ]
      ];
    }


    public function permissionsSubmit() {
      
      $authors = $this->authorsTable->findAllGeneric();
      $heading = 'Failed To Fetch Data';
      $author = null;

      foreach ($authors as $authorEntity) {
        # code...
        if ($authorEntity->id == $_POST['authorid']) {

          $author = [
            'id' => $_POST['authorid'],
            'permissions' => array_sum($_POST['permissions'] ?? [])
          ];
        
          $this->authorsTable->saveGeneric($author);
        
          header('location: /author/list');
          exit;
        }
      }
    
        $reflected = new \ReflectionClass('\Jokessite\Entity\Author');
        $constants = $reflected->getConstants();
        $errors[] = 'Invalid Action/Request Detected';
      
        return [
          'template' => 'permissions.html.php',
          'title' => 'Edit Permissions',
          'heading' => $heading,
          'alertText' => 'Unsuccessful Due To Invalid Action.',
          'alertStyle' => 'noticef',
          'errors' => $errors,
          'variables' => [
            'author' => $author,
            'permissions' => $constants
          ]
        ];
    }
      
}