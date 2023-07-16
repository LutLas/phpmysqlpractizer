<?php
if (isset($_POST['joketext'])) {
    try {
        $pdo = new PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');

        $sql = 'INSERT INTO `joke` SET
            `joketext` = :joketext,
            `jokedate` = CURDATE()';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':joketext', $_POST['joketext']);

        $stmt->execute();

        header('location: jokes.php');

    } catch (PDOException $e) {
        $message = '';
        $title = 'An error has occurred';

        $output = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
    }
} else {
    $message = '';
    $title = 'Add a new joke';

    ob_start();

    include  __DIR__ . '/./templates/addjoke.html.php';

    $output = ob_get_clean();
}
include  __DIR__ . '/./templates/layout.html.php';