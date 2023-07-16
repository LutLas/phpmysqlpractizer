<?php
    try {
        include __DIR__ . '/../includes/DatabaseConnection.php';
        include __DIR__ . '/../includes/DatabaseFunctions.php';

        deleteGeneric($pdo,'joke','id', $_POST['jokeid']);

        header('location: jokes.php');
    } 
    catch (PDOException $e) {
        $title = 'An error has occurred';

        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
include  __DIR__ . '/./templates/layout.html.php';