<?php
  try {
    $pdo = new PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');

    $sql = 'SELECT `joketext` FROM `joke`';
    $result = $pdo->query($sql);

    /* while ($row = $result->fetch()) {
      $jokes[] = $row['joketext'];
    } */

    foreach ($result as $row) {
        $jokes[] = $row['joketext'];
    }

  } catch (PDOException $e) {
      $error = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
  }

  include  __DIR__ . '/./templates/jokes.html.php';