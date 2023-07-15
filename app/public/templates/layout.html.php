<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/jokes.css">
    <title><?= $title ?></title>
</head>

<body>

    <header>
        <h2>INTERNET JOKE DATABASE</h2>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="jokes.php">JOKES LIST</a></li>
        </ul>
    </nav>

    <main>
        <?= $message ?>
        <?= $output ?>
    </main>

    <footer>
        &copy; LUTLAS <?= date('Y') ?>
    </footer>
    <script src="./assets/js/jokes.js"></script>
</body>

</html>