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
          }elseif (strlen($author['password']) < 8 || strlen($author['password']) > 16 ) {
            $errors[] = 'Password Must Be 8-16 Characters';
          }elseif (ctype_alnum($author['password'])) {
            $errors[] = 'Password Must Contain One Non-Alphanumeric Character';
          }

          if (strcmp($author['password'], $author['passwordConfirm']) !== 0){
            $errors[] = 'Passwords Do Not Match';
          }else{
            unset($author['passwordConfirm']);
          }
        
          // If the $errors array is still empty, no fields were blank and the data can be added
          if (empty($errors)) {
            // Hash the password before saving it in the database
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
          
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
      
}