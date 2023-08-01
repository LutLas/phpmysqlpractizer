<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/jokes.css">
    <title><?= $title ?></title>
</head>

<body>

    <header>
        <h2><?= $heading ?></h2>
    </header>
    <nav>
        <ul class="navmaster">
            <li><a class="customa" href="/">HOME</a></li>
            <li><a class="customa" href="/joke/list">JOKES LIST</a></li>
            <li><a class="customa" href="/joke/edit">ADD JOKE</a></li>
        </ul>
    </nav>

    <main>
        <p class=<?=$alertStyle?>> <?=$alertText?></p>
        <?php if (!empty($errors)) : ?>
            <div>
                <ul class="errors">
                    <?php foreach ($errors as $error) :?>
                        <li class="errors"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?= $output ?>
    </main>

    <footer>
        &copy; LUTLAS <?= date('Y') ?>
    </footer>
    <script src="/assets/js/jokes.js"></script>
</body>

</html>