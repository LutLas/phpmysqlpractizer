<?php
    $pdo = new PDO('mysql:host=mysql;dbname=practizer;charset=utf8mb4', 'practizer', 'secret');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
