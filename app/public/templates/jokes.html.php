<?php foreach ($jokes as $joke) : ?>
  <blockquote style="align-items: center; justify-content: center; display: flex; border-bottom: 1px solid #ccc;">
    <p>
      <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>

      (by <a href="mailto:<?php
        echo htmlspecialchars($joke['email'], ENT_QUOTES,
          'UTF-8'); ?>"><?php
        echo htmlspecialchars($joke['name'], ENT_QUOTES,
          'UTF-8'); ?></a>)
    
      <form action="deletejoke.php" method="post">
        <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke['id'] ?>">
        <input style="margin-left: 8px;" type="submit" name="submit" value="Delete">
      </form>
    </p>
  </blockquote>
<?php endforeach; ?>