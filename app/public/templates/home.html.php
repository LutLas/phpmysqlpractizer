<?php if ($user): ?>

<div class="noticejoke">
  <div>
    <form action="/joke/search" method="post">
            <input type="hidden" name="pagequery" id="pagequery" value="home" REQUIRED>
            <input type="text" name="songquery" id="songquery" value="<?=$songquery ?? '' ?>" REQUIRED>
      <button class="navmasterJoke fas fa-search" type="submit" name="submit">
    </form>
  </div>
        <?=$jokesnav?>
</div>

<div style="margin-top: 10px;">
    <?php foreach ($categories as $category): ?>
      <a class="navmaster2" href="/joke/list/<?=$category->id?>"><?=$category->name?></a>
    <?php endforeach; ?>
<div>

<div class="centermaster larger">
<h3>Hello, <?=$user->name?></h3>
<p>You have access to the MasteredSite Music Database</p>
</div>


<div class="box-container">

  <?php foreach ($jokes as $joke) : ?>
    <?php if (!$joke->approved && !$joke->archived): ?>
      <div class="box">
        
        <div class="title"><?= htmlspecialchars($joke->joketitle, ENT_QUOTES,
              'UTF-8'); ?>(prod.<?= htmlspecialchars($joke->producername, ENT_QUOTES,
              'UTF-8'); ?>)</div>

          <?php if (!empty($joke->albumcover)): ?>
          <img src="<?= $joke->albumcover; ?>" alt="" class="album">
          <?php else: ?>
          <img src="../assets/images/disc.png" alt="" class="album">
          <?php endif; ?>
        
          <div class="name"><?= htmlspecialchars($joke->artistname, ENT_QUOTES,
              'UTF-8');?></div>
          <div class="artist"><?= htmlspecialchars($joke->albumname, ENT_QUOTES,
              'UTF-8'); ?></div>

          <?= (new \Generic\Markdown($joke->joketext))->toHtml();?>

          (by <a href="mailto:<?php
            echo htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
              'UTF-8'); ?>"><?php
            echo htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
              'UTF-8'); ?></a> on <?php
              $date = new DateTime($joke->jokedate);
              
              echo $date->format('jS F Y H:i:s');
              ?> UTC)
          <?php if ($user): ?>
                    <?php if (($user->id == $joke->authorid && $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)) || $user->hasPermission(\Jokessite\Entity\Author::APPROVE_JOKE)): ?>
                    <?php if ($user->permissions < \Jokessite\Entity\Author::APPROVE_JOKE): ?>
                          <div style="margin-top: 10px;margin-bottom: 10px;">
                    <?php else: ?>
                          <div style="margin-top: 10px;">
                    <?php endif; ?>
                            <a class="navmasterYellow" href="/joke/edit/<?= $joke->id?>">Edit</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($user->hasPermission(\Jokessite\Entity\Author::DELETE_JOKE)): ?>
                    <div style="margin-top: 8px;padding-bottom: 8px;">
                        <form action="/joke/delete" method="post">
                            <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke->id ?>">
                            <input class="navmasterRed" type="submit" name="submit" value="Delete">
                        </form>
                    </div>
                    <?php endif; ?>
                <?php if ($user->hasPermission(\Jokessite\Entity\Author::APPROVE_JOKE)): ?>
                    <div class="flex">
                        <a href="<?= $joke->song; ?>" download><i class="fas fa-download"></i><span>download</span></a>
                    </div>
                <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

</div>

<?php else: ?>

    <div class="centermaster large">
    <h3 style="margin-top: 8px;">Hello, There!</h3>
    <p>Welcome to the MasteredSite Music Database</p>
    </div>

<?php endif; ?>