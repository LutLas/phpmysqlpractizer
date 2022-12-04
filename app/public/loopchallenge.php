<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Loop Challenge</title>
    </head>
    <body>
        <p>Print All Odd numbers from 21 to 99.</p>
        <?php
            for ($i=21; $i <= 99; $i++) { 

            if ($i % 2 != 0) {
                  echo '<p>'.$i.'</p>';
                }
            }
            echo'<p>Print the nine times table up to 12 x 9.</p>';
            $nineTimes = 0;
            for ($n=1; $n <= 12; $n++) { 
                $nineTimes += 9;
                echo '<p>'.$nineTimes.'</p>';
            }
            echo'<p>Print the nine times table in requested format.</p>';
            $nineTimesFormated = 0;
            for ($n=1; $n <= 12; $n++) { 
                $nineTimesFormated = 9 * $n;
                echo'<p>'.$n.'x9 = '.$nineTimesFormated.'</p>';
            }
        ?>
    </body>
</html>