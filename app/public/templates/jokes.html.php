<?php foreach ($jokes as $joke) : ?>
  <blockquote style="align-items: center; justify-content: center; display: flex; border-bottom: 1px solid #ccc;">
    <p>
      <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
      <form action="deletejoke.php" method="post">
        <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke['id'] ?>">
        <input style="margin-left: 8px;" type="submit" name="submit" value="Delete">
      </form>
    </p>
  </blockquote>
<?php endforeach; ?>