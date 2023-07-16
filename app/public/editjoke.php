<?php
try {
    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../includes/DatabaseFunctions.php';

    if (isset($_POST['joke'])) {
        $joke = $_POST['joke'];
        $joke['jokedate'] = new DateTime();
        $joke['authorId'] = 1;

        saveGeneric($pdo, 'joke', 'id', $joke);

        header('location: jokes.php');  
    } else {
        if (isset($_GET['id'])) {
            $joke = findGeneric($pdo, 'joke', 'id', $_GET['id'])[0] ?? null;
        }
        else {
          $joke = null;
        }

        $title = 'Edit joke';

        ob_start();

        include  __DIR__ . '/./templates/editjoke.html.php';

        $output = ob_get_clean();
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/./templates/layout.html.php';