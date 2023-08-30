<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="The Ultimate Music Database To Be.">
    <meta name="author" content="MasteredSite and other contributors">

    <!-- Logo -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">

    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?= $title ?></title>

<!-- font awesome cdn link  -->
    <link rel="stylesheet" href="/assets/libs/font-awesome-5.14.0/css/all.min.css">
<!-- sweetalert2 cdn link  -->
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
    <script src="/assets/js/loading-script.js"></script>
</head>

<body>

    <header class="centermaster">
        <h2 style="padding:8px; "><?= $heading ?></h2>
    </header>
    <nav>
        <ul class="navmaster">
            <li><a class="customa" href="/">HOME</a></li>
            <li><a class="customa" href="/joke/list">MUSIC LIST</a></li>
            <li><a class="customa" href="/about/index">ABOUT</a></li>
            <li><a class="customa" href="/disclaimer/index">DISCLAIMER</a></li>

            <?php if ($loggedIn): ?>
            <li><a class="customa" href="/login/logout">LOG OUT</a></li>
            <?php else: ?>
            <li><a class="customa" href="/login/login">LOG IN</a></li>
            <?php endif; ?>

        </ul>
    </nav>

    <main class="playlist">
      <div class=<?=$alertStyle?>>
         <span><?=$alertText?></span>
         <i class="navmasterRed fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>

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
        <embed controls class="music"></embed>

    </div>
    


    <footer>
        &copy; MasteredSite <?= date('Y') ?>
    </footer>
    <script src="/assets/js/script.js"></script>
    <script src="/assets/libs/ruffle/ruffle.js"></script>
    <script src="/assets/libs/jquery-3.6.0/jquery.slim.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.3.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.3.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyDAvbkQOw8QeRO0_WgVnOYCJD6nybeAuvU",
            authDomain: "zambiansite.firebaseapp.com",
            databaseURL: "https://zambiansite.firebaseio.com",
            projectId: "zambiansite",
            storageBucket: "zambiansite.appspot.com",
            messagingSenderId: "807925102842",
            appId: "1:807925102842:web:acc54c25fcb66a348bf143",
            measurementId: "G-DSQV66HV8W"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7102371719293996"
     crossorigin="anonymous"></script>
</body>

</html>