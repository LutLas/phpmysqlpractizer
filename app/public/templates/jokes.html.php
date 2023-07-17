<p class="noticep"><?=$totalJokes?> jokes have been submitted to the Internet Joke Database.</p>
<?php foreach ($jokes as $joke) : ?>
  <blockquote style="align-items: center; justify-content: center; display: flex; border-bottom: 1px solid #ccc;">
    <p>
      <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>

      (by <a href="mailto:<?php
        echo htmlspecialchars($joke['email'], ENT_QUOTES,
          'UTF-8'); ?>"><?php
        echo htmlspecialchars($joke['name'], ENT_QUOTES,
          'UTF-8'); ?></a> on <?php
          $date = new DateTime($joke['jokedate']);
          
          echo $date->format('jS F Y');
          ?>)

        
      <div>
          <a style="margin-left: 12px;" href="index.php?action=edit&id=<?=$joke['id']?>">Edit</a>
      </div>

      <form action="index.php?action=delete" method="post">
        <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke['id'] ?>">
        <input style="margin-left: 8px;" type="submit" name="submit" value="Delete">
      </form>
    </p>
  </blockquote>
<?php endforeach; ?>