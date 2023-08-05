<?php
namespace Jokessite\Controllers;

use Generic\Authentication;
class Login {
    public function __construct(private Authentication $authentication) {

    }

    public function login() {
        return ['template' => 'login.html.php',
                'title' => 'Log in',
                'heading' => 'User Account Login',
               ];
    }

    public function loginSubmit() {
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

          if (empty($author['password'])) {
            $errors[] = 'Password is Required';
          }

          // If the $errors array is still empty, no fields were blank and the data can be Checked
          if (empty($errors)) {
            
            $success = $this->authentication->login($author['email'], $author['password']);

            if ($success) {
                return ['template' => 'loginsuccess.html.php',
                        'title' => 'Log In Successful',
                        'heading' => 'Account Login Success',
                        'alertText' => 'Welcome back! You are logged in as '.$author['email'],
                        'alertStyle' => 'noticep'
                    ];
            }
            else {
                return ['template' => 'login.html.php',
                        'title' => 'Log In',
                        'heading' => 'User Account Login',
                        'alertText' => 'Login was unsuccessful due to Invalid Credentials.',
                        'alertStyle' => 'noticef',
                        'variables' => [
                          'author' => $author
                        ]
                    ];
            }
          }
          else {
            // If the data is not valid, show the form again
            return [
                  'template' => 'login.html.php',
                  'title' => 'Log In',
                  'heading' => 'User Account Login',
                  'alertText' => 'Login was unsuccessful due to incomplete form submission.',
                  'alertStyle' => 'noticef',
                  'errors' => $errors,
                  'variables' => [
                    'author' => $author
                  ]
                ];
          }

    }

    public function logout() {
        $this->authentication->logout();
        header('location: /');
    }
}