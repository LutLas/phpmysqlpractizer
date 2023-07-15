<!doctype html>
<html lang="en">

  <head>
    <title>List of jokes</title>
    <link rel="stylesheet" href="form.css" />
    <meta charset="utf-8">
  </head>

  <body>
    <?php if (isset($error)) : ?>
      <p>
        <?=$error?>
      </p>
    <?php else : ?>
      <?php foreach ($jokes as $joke) : ?>
        <blockquote>
          <p>
            <?=htmlspecialchars($joke, ENT_QUOTES, 'UTF-8')?>
          </p>
        </blockquote>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>

</html>