<?php
    try {
        $pdo = new PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');

        $sql = 'DELETE FROM `joke` WHERE
            `id` LIKE :jokeid';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':jokeid', $_POST['jokeid']);

        $stmt->execute();

        header('location: jokes.php');
    } 
    catch (PDOException $e) {
        $message = '';
        $title = 'An error has occurred';

        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
include  __DIR__ . '/./templates/layout.html.php';