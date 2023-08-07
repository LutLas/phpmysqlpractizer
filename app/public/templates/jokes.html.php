
  <?php foreach ($categories as $category): ?>
    <a class="navmaster2" href="/joke/list/<?=$category->id?>"><?=$category->name?></a>
  <?php endforeach; ?>

<?php foreach ($jokes as $joke) : ?>
  <blockquote style="align-items: center; justify-content: center; display: flex; border-bottom: 1px solid #ccc;">
    <p>
      <?= htmlspecialchars($joke->joketext, ENT_QUOTES, 'UTF-8') ?>

      (by <a href="mailto:<?php
        echo htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
          'UTF-8'); ?>"><?php
        echo htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
          'UTF-8'); ?></a> on <?php
          $date = new DateTime($joke->jokedate);
          
          echo $date->format('jS F Y');
          ?>)
      <?php if ($user): ?>
            <?php if (empty($joke) || $user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)): ?>
                  <div>
                      <a style="margin-left: 12px;" href="/joke/edit/<?= $joke->id?>">Edit</a>
                  </div>
            <?php endif; ?>
            <?php if ($user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::DELETE_JOKE)): ?>

                  <form action="/joke/delete" method="post">
                    <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke->id ?>">
                    <input style="margin-left: 8px;" type="submit" name="submit" value="Delete">
                  </form>
            <?php endif; ?>
      <?php endif; ?>
    </p>
  </blockquote>
<?php endforeach; ?>