<?php
// phpinfo();

// $pdo = new PDO('mysql:dbname=practizer;host=mysql', 'practizer', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// $query = $pdo->query('SHOW VARIABLES like "version"');

// $row = $query->fetch();

// echo 'MySQL version:' . $row['Value'];
$pdo = null;

try {
    $pdo = new PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');
     $message = 'Database connection established.';
  }
  catch (PDOException $e) {
    $message = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
  }

  try {
    $sql = 'CREATE TABLE joke (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      joketext TEXT,
      jokedate DATE NOT NULL
    ) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
  
    $pdo->exec($sql);
  
    $message = 'Joke table successfully created.';
  }
  catch (PDOException $e) {
    $message = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
  }

  include  __DIR__ . '/./templates/output.html.php';

if (!isset($_POST['firstname'])) {
    include  __DIR__ . '/./templates/form.html.php';
} else {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];

    if ($firstName == 'Tom' && $lastName == 'Butler') {
        $output = 'Welcome, oh glorious leader!';
    } else {
        $output = 'Welcome to our website, ' .
      htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') . ' ' .
      htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8') . '!';
    }

    include  __DIR__ . '/./templates/welcome.html.php';
}