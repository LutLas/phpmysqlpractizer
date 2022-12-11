<?php
// phpinfo();

// $pdo = new PDO('mysql:dbname=practizer;host=mysql', 'practizer', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// $query = $pdo->query('SHOW VARIABLES like "version"');

// $row = $query->fetch();

// echo 'MySQL version:' . $row['Value'];

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