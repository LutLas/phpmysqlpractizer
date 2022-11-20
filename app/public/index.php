<?php
// phpinfo();

$pdo = new PDO('mysql:dbname=practizer;host=mysql', 'practizer', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$query = $pdo->query('SHOW VARIABLES like "version"');

$row = $query->fetch();

echo 'MySQL version:' . $row['Value'];