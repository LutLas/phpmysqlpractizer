<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
class Author {
    public function __construct(private DatabaseTable $authorsTable) {
    }

    public function registrationForm() {
        return [
          'template' => 'register.html.php',
            'title' => 'Register an account',
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
        $valid = true;
        $missingFields = '';
      
        // But if any of the fields have been left blank, set $valid to false
        if (empty($author['email'])) {
          $valid = false;
          $missingFields .= "  Email Address Missing!  ";
        }

        if (empty($author['name'])) {
          $valid = false;
          $missingFields .= "  User Name Missing!  ";
        }
      
        if (empty($author['password'])) {
            $valid = false;
            $missingFields .= "  Password Missing!  ";
          }
        
          // If $valid is still true, no fields were blank and the data can be added
          if ($valid == true) {
            $this->authorsTable->saveGeneric($author);
        
            header('Location: /author/success');
          }
          else {
            // If the data is not valid, show the form again
            return ['template' => 'register.html.php',
                  'title' => 'Register an account',
                  'heading' => 'User Account Registration Form',
                  'alertText' => 'Registration was unsuccessful due to incomplete form submission.'.$missingFields,
                  'alertStyle' => 'noticef'
                ];
          }
      }
      
}