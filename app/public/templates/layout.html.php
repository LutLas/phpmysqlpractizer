<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?= $title ?></title>

<!-- font awesome cdn link  -->
    <link rel="stylesheet" href="/assets/libs/font-awesome-5.14.0/css/all.min.css">
</head>

<body>

    <header class="centermaster">
        <h2 style="padding:8px; "><?= $heading ?></h2>
    </header>
    <nav>
        <ul class="navmaster">
            <li><a class="customa" href="/">HOME</a></li>
            <li><a class="customa" href="/joke/list">MUSIC LIST</a></li>

            <?php if ($loggedIn): ?>
            <li><a class="customa" href="/login/logout">LOG OUT</a></li>
            <?php else: ?>
            <li><a class="customa" href="/login/login">LOG IN</a></li>
            <?php endif; ?>

        </ul>
    </nav>

    <main class="playlist">
        <p class=<?=$alertStyle?>> <?=$alertText?></p>
        <?php if (!empty($errors)) : ?>
            <div>
                <ul class="errors">
                    <?php foreach ($errors as $error) :?>
                        <li class="errors centermaster"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?= $output ?>
    </main>
    <div class="music-player">

        <i class="fas fa-times" id="close"></i>
        <embed controls class="music"></audio>

    </div>
    


    <footer>
        &copy; LUTLAS <?= date('Y') ?>
    </footer>
    <script src="/assets/js/script.js"></script>
    <script src="/assets/libs/ruffle/ruffle.js"></script>
    <script src="/assets/libs/jquery-3.6.0/jquery.slim.min.js"></script>
</body>

</html>