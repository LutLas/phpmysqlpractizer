<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Random Number</title>
    </head>
    <body>
        <?php
            $round = 1;
            for ($i=0; $i <= 100; $i++) { 
                $roll = rand(1, 6);
                $roll1 = rand(1, 6);

                echo '<strong>Round: '. $round .'</strong>';
                echo '<p>You rolled a ' . $roll . ' and ' . $roll1 . '</p>';

            if ($roll == 6 && $roll1 == 6) {
                  echo '<p>You win!</p>';
                  $i = 101; 
                }else{
                    echo '<p>Sorry, you didn\'t win, better luck next time!</p>';
                }
                $round++;
            }
                echo '<p>Thanks for playing</p>';
        ?>
    </body>
</html>