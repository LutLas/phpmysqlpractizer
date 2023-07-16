<?php
    try {
        include __DIR__ . '/../includes/DatabaseConnection.php';

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