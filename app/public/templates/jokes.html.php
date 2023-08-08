<div style="margin-top: 10px;">
    <?php foreach ($categories as $category): ?>
      <a class="navmaster2" href="/joke/list/<?=$category->id?>"><?=$category->name?></a>
    <?php endforeach; ?>
  <div>

<?php foreach ($jokes as $joke) : ?>
  <blockquote class="blockquoter">
      <?= (new \Generic\Markdown($joke->joketext))->toHtml();?>

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
                  <div style="margin-top: 10px;">
                      <a class="navmasterYellow" href="/joke/edit/<?= $joke->id?>">Edit</a>
                  </div>
            <?php endif; ?>
            <?php if ($user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::DELETE_JOKE)): ?>
              <div style="margin-top: 8px;padding-bottom: 8px;">
                  <form action="/joke/delete" method="post">
                    <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke->id ?>">
                    <input class="navmasterRed" type="submit" name="submit" value="Delete">
                  </form>
            </div>
            <?php endif; ?>
      <?php endif; ?>
  </blockquote>
<?php endforeach; ?>